<?php
	/**
	 * Class Parser_mdl
	 *
	 * Price parser model class
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Parser_mdl.class.php
	 * @created  Thu Sep 09 17:49:58 EEST 2010
	 */
	class Parser_mdl extends Model 
	{
		private $tmp_table = 'shyna_tmp';
		private $tmp_compare_table = 'shyna_tmp_compare';
		
		/**
		 * Constructor of Price_mdl
		 *
		 * @access  public
		 */
		function Parser_mdl() {
			parent::Model();
		}
		
		function get_price_tmp($vendor_id=null, $per_page=0, $page=1, $with_count=true) {
			$page = empty($page)?1:$page;
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
			$this->tmp_table .= $vendor_id;
			
			$query = $this->db->query("SHOW TABLES");
			if(!$query) return FALSE;
			$result = $query->result();
			
			$table_exists = false;
			$db_name = $this->db->database;
			$db_name_prefix = "Tables_in_".$db_name;
			
			foreach ($result as $table) {
				if($table->{$db_name_prefix} == $this->tmp_table) {
					$table_exists = true;
					break;
				}
			}
			
			if ($table_exists) {
				$query = "
				SELECT SQL_CALC_FOUND_ROWS
					t.*,
					b.value as brand,
					w.value	as width,
					p.value	as profile,
					d.value	as diameter,
					m.name as model_name,
					li.ind as load_index,
					si.ind as speed_index
				FROM 
					".$this->tmp_table." t
                LEFT JOIN brand_syn bs on bs.id=t.brand_id
                LEFT JOIN brand b ON bs.brand_id=b.id
                LEFT JOIN model_syn ms ON ms.id=t.model_id
                LEFT JOIN model m ON ms.model_id=m.id
				LEFT JOIN width w ON w.id=t.width_id				
				LEFT JOIN profile p ON p.id=t.profile_id
				LEFT JOIN diameter d ON d.id=t.diameter_id				
				LEFT JOIN load_index li ON li.id=t.load_index_id
				LEFT JOIN speed_index si ON si.id=t.speed_index_id
				GROUP BY t.row_id";
				
				$query .= $limit;
				
				$query = $this->db->query($query);
				if ( ! $query) return FALSE;
				$result = $query->result();
				
				if($with_count) {
					$query = $this->db->query("select found_rows() as count");
					if ( ! $query) return FALSE;
					$result['count'] = $query->row()->count;
				}
				return $result;
			}
			else
				return FALSE;
		}
		
		/**
		 * Destructor of Parser_mdl 
		 *
		 * @access  public
		 */
		function _Price_mdl() {
		 	
		}
		
	}
?>
