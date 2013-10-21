<?php
define('CSV_EOL', "\r\n");
define('CSV_DELEMITER', ";");
define('CSV_ENCLOSURE', "\"");

require_once(MODBASE.modules::path().'/libraries/PHPExcel/PHPExcel.php');
require_once(MODBASE.modules::path()."/controllers/Parser.php");

/**
 * Class priceLoader
 *
 * process class
 *
 * @author   Popov
 * @access   public
 * @package  priceLoader.class.php
 * @created  Thu Sep 16 11:50:03 EEST 2010
 */
class priceLoader extends Parser {

	public $general_columns = 12;
	public $general_rows = 1;
    public $sheets = array();

	private $upload_data = NULL;

	/**
	 * Constructor of priceLoader
	 *
	 * @access  public
	 */
	function __construct($vendor_id) {
		parent::Parser($vendor_id);
		
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod);	
	}
	
	/**
	 * check vendor price file [vendor_id].ext
	 * parse file
	 * create txt-file with parsed data
	 * load txt-file to DB
	 * delete txt-file
	 *
	 * @param string $file_ext
	 * @return int	$price_id
	 */
	function loading($file_ext, $sheet_num) {
		$this->get_vendor_price($file_ext);
		$objWorksheet = $this->loadFile($this->upload_data['full_path'], $sheet_num);
		$sheet_str = $this->parseFile($objWorksheet);	
		$result = $this->save_txt($sheet_str);
		$this->load_data_infile();	
		return $this->set_column_count();
	}
	
	
	function get_vendor_price($file_ext) {
		static $ci;
		$this->ci =& get_instance();
		
		$config = $this->ci->load->config('upload');
		
		try {
	     //$file_price=$config['upload_path'].$this->vendor_id.$file_ext;
	     $file_price=dirname(BASEPATH).'/files/'.$this->vendor_id.$file_ext;
            if(!file_exists($file_price)) {
                log_message('error', "get_vendor_price  file_price:".$file_price);
                log_message('error', "get_vendor_price  config:".var_export($config,true));
				throw new Exception("The vendor's price file does not exists!".$file_price);
			} else {
				$this->upload_data['file_path'] = dirname(BASEPATH).'/files/';//$config['upload_path'];
				$this->upload_data['raw_name'] = $this->vendor_id;
				$this->upload_data['file_name'] = $this->vendor_id.$file_ext;
				$this->upload_data['full_path'] = $this->upload_data['file_path'] . $this->upload_data['file_name'];
			}
			return true;
		} catch (Exception $e) {
			$this->setError($e, __FUNCTION__);
		}
		return false;
	}
	
	private function loadFile($full_path, $sheet_num) {
	    	$objReader = PHPExcel_IOFactory::createReader('Excel5');
		//$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($full_path);
		$objPHPExcel->setActiveSheetIndex($sheet_num);
		return $objPHPExcel->getActiveSheet();

	}
	
	private function parseFile(PHPExcel_Worksheet $objWorksheet) {
		$sheet_str = '';
		$row_start = empty($row_start) ? 1 : ++$row_start;
		$empty_colls = 0;
		$empty_rows = 0;

		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$this->general_columns = PHPExcel_Cell::columnIndexFromString($highestColumn);		
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn)+1;
		
		for ($row = $row_start; $row <= $highestRow; ++$row) {			
			$row_values_str = ";";  //field for autoincrement row_id
			for ($col = 0; $col < $highestColumnIndex; ++$col) {
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
				if(empty($value)) {
					$empty_colls++;
					$value = $value = str_replace(" ", "", $value);
				} else {
					$value = mb_convert_encoding($value,"UTF-8", mb_detect_encoding($value));
					$symbols = array('`','~','!','@','#','$','%','^','&','*','(', ')','-','=',';',':','"',"'",'<','>','?');
					$value = str_replace($symbols, '', $value);
				}
				$row_values_str .= '"'.$value.'";';
			}
			if($empty_colls != $highestColumnIndex) $sheet_str .= $row_values_str."\r\n";
			$empty_colls = 0;
			
			if($empty_rows == 5) break;
		}
		return $sheet_str;
	}
	
	private function save_txt($sheet_str) {
		if(file_exists($this->upload_data['file_path'].$this->upload_data['raw_name'].".txt")) {
			unlink($this->upload_data['file_path'].$this->upload_data['raw_name'].".txt");
		}
		
		$rs = fopen($this->upload_data['file_path'].$this->upload_data['raw_name'].".txt", "w");
		fwrite($rs, $sheet_str);
		fclose($rs);

		if(!file_exists($this->upload_data['file_path'].$this->upload_data['raw_name'].".txt")) {
			throw new Exception('File '.$this->upload_data['file_path'].$this->upload_data['raw_name'].".txt does not exists!");
		}
		return TRUE;
	}
	
	private function load_data_infile() {
		try {
			if( empty($this->upload_data) ) return FALSE;

			$load_file = realpath($this->upload_data['file_path'].$this->upload_data['raw_name'].".txt");
			if( ! @file_exists($load_file) ) {
				throw new Exception('File '.$this->upload_data['file_path'].$this->upload_data['raw_name'].'txt does not exist!');
			}

			chmod($load_file, 0777);
			$load_file = str_replace("/","//",$load_file);

		$this->_clear($this->general_columns);

			$qstr = "LOAD DATA LOCAL INFILE '".addslashes($load_file)."'
				INTO TABLE ".$this->tablename_tmp."
	            FIELDS TERMINATED BY '".CSV_DELEMITER."'
	            OPTIONALLY ENCLOSED BY '".CSV_ENCLOSURE."' 
	            LINES TERMINATED BY '".CSV_EOL."' IGNORE 0 LINES";

			$result = $this->ci->db->query($qstr);
				if(!$result)
			throw new Exception($this->ci->db->_error_message());

			unlink($this->upload_data['file_path'].$this->upload_data['raw_name'].".txt");

			return TRUE;

		} catch (Exception $e) {
			$this->setError($e, __FUNCTION__);
		}
	}
	
	private function set_column_count() {
		$query = "SELECT * FROM " . $this->table_vendor_functions . " WHERE vendor_id = '{$this->vendor_id}'";
		$query = $this->ci->db->query($query);
		if(!$query) {
				//throw new Exception($this->ci->db->_error_message());
			log_message('error', $this->ci->db->_error_message());
			return false;
		}
		$vendor_functions = $query->row();
		if(empty($vendor_functions)) {
			$query="SELECT count(sh.sheet_id) FROM {$this->table_sheets} sh LEFT JOIN {$this->table_} pl on sh.list_id=pl.list_id WHERE pl.vendor_id=".$this->vendor_id;
			$result_count_sh = $this->ci->db->query($query);
			for ($i=0;$i<$result_count_sh;$i++)
			{
			    $query = "INSERT INTO {$this->table_vendor_functions} (vendor_id) VALUES ('{$this->vendor_id}')";
			    $result = $this->ci->db->query($query);
			    if(!$result) {
				    //throw new Exception($this->ci->db->_error_message());
				log_message('error', $this->ci->db->_error_message());
				return false;
			    } else $ins_id=mysql_insert_id();
			    if ($ins_id!=0) $query = "UPDATE sh SET sh.function_id=$ins_id FROM {$this->table_sheets} sh LEFT JOIN pricelists pl on sh.list_id=pl.list_id WHERE pl.vendor_id=".$this->vendor_id." AND sh.sheet_id=".$i;
			}
		}
		$query = "UPDATE " . $this->table_vendor_functions . " SET columns_count = '{$this->general_columns}' WHERE vendor_id = '{$this->vendor_id}'";
		$result = $this->ci->db->query($query);
        if(!$result) {
    		 //throw new Exception($this->ci->db->_error_message());
             log_message('error', $this->ci->db->_error_message());
             return false;
        }

		return true;
	}

	public function get_upload_data() {
		return $this->upload_data;
	}

	public function upload_price($filename){


		if(empty($filename) || empty($this->vendor_id)) return FALSE;

		$this->ci->load->library('upload');
		$config = $this->ci->load->config('upload');

		if(!is_dir($config['upload_path'])){
			mkdir($config['upload_path'], 0755);
		}

		if (isset($this->ci->load->_ci_classes['upload']) && ($this->ci->load->_ci_classes['upload'] == 'upload')) {
			$this->ci->upload->initialize($config);
		} else {
			$this->ci->load->library('upload', $config);
		}

		try {
			if ( ! $this->ci->upload->do_upload($filename))
			throw new Exception($this->ci->upload->display_errors());

			$this->upload_data = $this->ci->upload->data();
			if(empty($this->upload_data)) return FALSE;

			// rename file
			$old_name_path = $this->upload_data['full_path'];
			$new_name_path = $this->upload_data['file_path'] . $this->vendor_id . $this->upload_data['file_ext'];


			if(file_exists($new_name_path)) unlink($new_name_path);
			$result = rename($old_name_path, $new_name_path);
			if(!$result)
                throw new Exception('the price file did not rename');
            else
                if ($this->get_sheets_info($new_name_path))
                    $this->add_sheets_info();

			return TRUE;



		} catch (Exception $e) {
			$this->setError($e, __FUNCTION__);
		}

		return FALSE;
	}

    public function get_sheets_info($full_filename){

	    $objReader = PHPExcel_IOFactory::createReader('Excel5');
	    $objPHPExcel = $objReader->load($full_filename);
	    // Fetch sheets
	    $objPHPExcelall= array();
		$objPHPExcelall = $objPHPExcel->getAllSheets();

        if (!empty($objPHPExcelall))  {
		    foreach ($objPHPExcelall as $sheet) {
                $i = $sheet->getParent()->getIndex($sheet);
			    $this->sheets["name"][$i] = $sheet->getTitle();
            }
            return TRUE;
		} else return FALSE;
    }


    public function add_sheets_info(){

		$query = "SELECT list_id FROM " .$this->table_pricelists. " WHERE vendor_id='{$this->vendor_id}'";
		$query = $this->ci->db->query($query);
		$result=$query->row();
		if ( !$result) {
		    throw new Exception($this->ci->db->_error_message());
		} else {
		//adding to table sheets, if data exists do nothing
		    $query = "SELECT * FROM " .$this->table_sheets." WHERE list_id=".$result->list_id;
			$query = $this->ci->db->query($query);
			$row=$query->row();

			if (!$row) {
				foreach ($this->sheets["name"] as $index=>$name){
					$query = "INSERT INTO " . $this->table_sheets. " (list_id, sheet_id, sheet_name,parsed,active)
					VALUES ('".$result->list_id."', '".$index."','".$name."','0','0')";
					$query = $this->ci->db->query($query);
				}
		    } else {
				//Update
			}
		}
    }
	/**
	 * Destructor of priceLoader
	 *
	 * @access  public
	 */
	function __destruct() {}
}
?>
