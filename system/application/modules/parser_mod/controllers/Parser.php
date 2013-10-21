<?php
	/**
	 * Class Parser
	 *
	 * parser class
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Parser.class.php
	 * @created  Tue Sep 21 09:28:46 EEST 2010
	 */
	class Parser 
	{
		public $tablename_tmp = 'shyna_tmp';			
		protected $table_pricelists = 'pricelists';
		protected $table_prices = 'prices';
		protected $table_sheets = 'sheets';
		protected $table_vendor_functions = 'vendor_functions';		
		protected $ci;
		
		protected $vendor_id;
		
		/**
		 * Constructor of Parser
		 *
		 * @access  public
		 */
		function Parser($vendor_id = '') {
			$this->ci =& get_instance();
			$this->vendor_id = $vendor_id;
			$this->tablename_tmp .= $this->vendor_id;
		}
	
		/**
		 * set price in database
		 *
		 * @param	string 	$filepath
		 * @return 	int	$price_id
		 */
		function set_price_db($vendor_id, $file_ext) {
			try {
				if(empty($vendor_id))
				throw new Exception("Id of the vendor is empty!");

				//$query = "DELETE FROM ".$this->table_pricelists." WHERE vendor_id='".$vendor_id."'";
				//$result = $this->ci->db->query($query);
                $query = "SELECT list_id FROM ".$this->table_pricelists." WHERE vendor_id='".$vendor_id."'";
                $_result = $this->ci->db->query($query);
                if(!$_result)
				throw new Exception($this->ci->db->_error_message());

                if ($_result->num_rows() > 0) {
                    $row = $_result->row();
                    $query = "UPDATE ".$this->table_pricelists." set
                        price_date ='".date("Y-m-d H:i:s")."',
						price_status ='not parsed',
						file_ext = '".$file_ext."',
                        count_null = 0,
                        count_not_null = 0
                        WHERE list_id = ".$row->list_id;
                    $result = $this->ci->db->query($query);
	                if(!$result)
				    throw new Exception($this->ci->db->_error_message());
                    return $row->list_id;
                } else {
				    $query = "INSERT
					    INTO ".$this->table_pricelists." (
						    vendor_id,
						    price_date,
					    	price_status,
						    file_ext
					    ) VALUES (
						    '".$vendor_id."',
						    '".date("Y-m-d H:i:s")."',
						    'not parsed',
						    '".$file_ext."'
				    	) ";
				    $result = $this->ci->db->query($query);
	                if(!$result)
				    throw new Exception($this->ci->db->_error_message());
				    return $this->ci->db->insert_id();
                }
			} catch (Exception $e) {
				log_message('error', "[set_price_db]: ".$e->getMessage());
				echo $e->getMessage();
			}
		}
		
		protected function _check() {
			$query = " show tables like '{$this->tablename_tmp}'";
			$query = $this->ci->db->query($query);
			if(!$query)
				throw new Exception($this->ci->db->_error_message());
			return $query->row();
		}
		
		protected function _clear($general_columns) {			
			$query = "set names 'utf8';";			
			$query = "DROP TABLE IF EXISTS ".$this->tablename_tmp;
			$query = $this->ci->db->query($query);
            if(!$query) {
            	log_message("error", "[_clear]: query '".$query."' failed");
            	throw new Exception('query '.$query.' failed!');
            }
            if(empty($general_columns) || !is_numeric($general_columns)) {
            	throw new Exception('The column count parameter is empty or not numeric');
            }
            else {
            	$i = 1;
            	$columns_a = "";
            	while ($i <= $general_columns) {
            		$columns_a .= "`A" . $i . "` text, ";
            		$i++;
            	}
            	$query = "CREATE TABLE `".$this->tablename_tmp."` (
                   " . $columns_a . "
                  `row_id` bigint(11) NOT NULL auto_increment,
                  `brand_id` text,
				  `width_id` text,
				  `profile_id` text,
				  `diameter_id` text,
				  `model_id` text,
				  `load_index_id` text,
				  `speed_index_id` text,
				  `amount` text,
				  `price` text,
				  `price_type` text,
				  `comment` text,
                  `_temp` text,
				  PRIMARY KEY  (`row_id`)
				) DEFAULT CHARSET=utf8;";
	            $query = $this->ci->db->query($query);
	            if(!$query) {
	            	log_message('error', "[_clear]: query '".$query."' failed");
	            	throw new Exception('query '.$query.' failed!');
	            }
	            return TRUE;
            }
		}
		
		protected function _drop(){
			$query = "DROP TABLE IF EXISTS ".$this->tablename_tmp;
			$query = $this->ci->db->query($query);
            if(!$query) {
            	log_message("error", "[_clear]: query '".$query."' failed");
            	throw new Exception('query '.$query.' failed!');
            }
            return true;
		}
		
		protected function setError(Exception $e, $error_type="") {
			$message = $e->getMessage() .'\n file: '. $e->getFile() .'\n code:'. $e->getCode() . '\n line:' . $e->getLine();
			log_message('error', "[".$error_type."]: ". $message);
			echo $message;
			exit;
		}
		
		/**
		 * Destructor of Parser 
		 *
		 * @access  public
		 */
		function _Parser() {}		
	}
?>
