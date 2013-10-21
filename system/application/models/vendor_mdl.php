<?php
	/**
	 * Class Vendor_mdl
	 *
	 * Vendor class
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Vendor_mdl.class.php
	 * @created  Fri Sep 17 17:36:43 EEST 2010
	 */
	class Vendor_mdl extends Model 
	{
		public $table_vendor = 'vendor';
		public $table_prices = 'prices';
		public $table_sheets = 'sheets';
		public $table_pricelists = 'pricelists';
		public $table_vendor_functions = 'vendor_functions';
		public $table_vendor_person = 'vendor_person';
		
		/**
		 * Constructor of Vendor_mdl
		 *
		 * @access  public
		 */
		function __construct() {
			parent::Model();
		}
		
		function set_vendor($user_id, $vendor_data=null) {
			if(empty($user_id)) return FALSE;
			
			$query = "SELECT * FROM user_account WHERE user_id = '{$user_id}'";
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			$user = $query->row();
			
		//	if((!empty($user))&&($this->db_session->userdata('group_name')!= "admin")) {			
				if(!empty($user)) {
				if(isset($vendor_data['user_id'])) unset($vendor_data['user_id']);
				
				$duplicate = $this->get_vendor_by_user_id($user->user_id);
				if(empty($duplicate)) {
					$vendor_data['id']=$vendor_data['user_id'] = $user->user_id;
					$vendor_data['name'] = addslashes($user->company);
					$vendor_data['city'] = addslashes($user->city);
					$vendor_data['phone'] = $user->tel;
					$vendor_data['email'] = $user->email;
					
					if(!$this->db->insert('vendor', $vendor_data))
					throw new Exception($this->db->_error_message());			
					return $this->db->insert_id();
					
				} else {
					if(is_array($duplicate)) $duplicate = array_shift($duplicate);
					return $duplicate->id;
				}
			}
			return false;
		}
		
		function update_vendor($vendor_id, $data) {
			if ( empty($vendor_id) || ! is_array($data) ) return FALSE;

			$vendor_fields = array(
									'user_id',
									'name',
									'short_name',
									'city',
									'short_city',
									'phone',
									'fax',
									'email',
									'www');
			$vendor_data = array();
			foreach($data as $key=>$value) {
				if (in_array($key, $vendor_fields)) $vendor_data[$key] = $value;
			}
			if (!empty($vendor_data)) {
				$this->db->where('id', $vendor_id);
				$res = $this->db->update('vendor', $vendor_data);
			}
			return $res;
		}
		
		function get_vendors($vendor_id=null, $vendor_name=null, $per_page=0, $page=1, $with_count = FALSE) {
			$page = empty($page)?1:$page;
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
			$query = "SELECT 
				SQL_CALC_FOUND_ROWS 
				v.id as vendor_id,
				v.name as vendor_name,
				v.short_name,
				v.city as vendor_city,
				v.short_city,
				v.phone as vendor_phone,
				v.fax as vendor_fax,
				v.email as vendor_email,
                v.www as vendor_www,				
				pl.price_date,
				pl.count_null,
				pl.count_not_null,
				pl.price_status,
				pl.file_ext
			FROM 
				".$this->table_vendor." v 
			LEFT JOIN ".$this->table_pricelists." pl ON pl.vendor_id=v.id
			WHERE 1=1 ";
			if($vendor_id) $query .= " AND v.id='".$vendor_id."'";
			if($vendor_name) $query .= " AND v.name='".$vendor_name."'";
			
			$query .= " GROUP BY v.id";
			
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
		function get_vendor_sheets($vendor_id) {
			$query = "SELECT 
			    SQL_CALC_FOUND_ROWS
			    sh.id,
				sh.list_id,
				sh.sheet_id  as sheet_id,
				sh.sheet_name  as sheet_name,
				sh.active as sheet_active,
				sh.parsed as sheet_parsed
			FROM 
				".$this->table_vendor." v 
			LEFT JOIN ".$this->table_pricelists." pl ON pl.vendor_id=v.id
			LEFT JOIN ".$this->table_sheets." sh ON sh.list_id=pl.list_id 
			WHERE 1=1 ";
			if($vendor_id) $query .= " AND v.id='".$vendor_id."'";
						
					
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			$result = $query->result();
	

			return $result;
		}
		function get_account_email($account_email) {
			$query = "SELECT  va.*,v.name as vendor_name FROM vendor_accounts as va,vendor as v WHERE va.account_email='{$account_email}' and va.vendor_id=v.id";
            if(!$this->db->query($query))
			throw new Exception($this->ci->db->_error_message());

			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->row();
		}
		function get_vendor_by_user_id($user_id) {
			$query = "SELECT id FROM vendor WHERE user_id = '{$user_id}'";
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->result();
		}
		function get_vendor_name_by_vendor_id($vendor_id) {
			$query = "SELECT distinct v.name FROM vendor_accounts as va INNER JOIN vendor v ON va.vendor_id=v.id WHERE va.vendor_id='{$vendor_id}'";
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->result();
		}
		function get_vendor_price($vendor_id=null, $vendor_name=null, $per_page=0, $page=1, $with_count = FALSE) {
			$page = empty($page)?1:$page;
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
			$query = "SELECT 
				SQL_CALC_FOUND_ROWS 
				v.*, 
				p.*,
				pl.price_date,
				pl.price_status
			FROM 
				".$this->table_vendor." v 
			INNER JOIN ".$this->table_pricelists." pl ON pl.vendor_id=v.id 
			LEFT JOIN ".$this->table_prices." p ON p.list_id=pl.list_id
			WHERE 1=1 ";
			if($vendor_id) $query .= " AND v.id='".$vendor_id."' AND pl.vendor_id='".$vendor_id."'";
			if($vendor_name) $query .= " AND v.vendor_name='".$vendor_name."'";
					
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
	
		function set_vendor_price($vendor_id, $price_id='', $file_ext='.xls') {
			if(empty($vendor_id)) return FALSE;
			try {
				
				$duplicate = $this->get_vendors($vendor_id);
				
				if(!empty($duplicate)) {				
					foreach ($duplicate as $price) {
						
						if(!empty($price->price_id)) {
							
							$query = "DELETE FROM ".$this->table_prices." WHERE list_id='".$price->price_id."'";
							
							if(!$this->db->query($query)) 
							throw new Exception($this->db->_error_message());
							
							$query = "DELETE FROM ".$this->table_pricelists." WHERE vendor_id='".$vendor_id."'";
							
							if(!$this->db->query($query)) 
							throw new Exception($this->db->_error_message());
						}
					}
				}

				$query = "INSERT INTO ".$this->table_pricelists." (list_id, vendor_id, price_date, price_status, file_ext)
					VALUES(".clean($price_id).", ".clean($vendor_id).", '".date("Y-m-d")."', 'not parsed', '".$file_ext."')";
				
				if(!$this->db->query($query)) 
				throw new Exception($this->db->_error_message());
				
				return TRUE;
				
			} catch (Exception $e) {
				log_message('error',$e->getMessage()."\n". "file: ".$e->getFile()."\n". "code: ".$e->getCode()."\n". "line: ".$e->getLine());
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
		
		function set_vendor_function($vendor_id, $function_data=null) {
			if(empty($vendor_id)) return FALSE;
			if(isset($function_data['vendor_id'])) unset($function_data['vendor_id']);
			
			$function_data['vendor_id'] = $vendor_id;
			
			if(!$this->db->insert('vendor_functions', $function_data))
			throw new Exception($this->db->_error_message());
			
			return true;
		}
		
		function get_vendor_functions($vendor_id, $sheet_num=0) {
			if(empty($vendor_id)) return FALSE;
			
			$query = 'SELECT 
						REPLACE(vf.brand_func, \'"\', \'\') as brand_func,
						REPLACE(vf.width_func, \'"\', \'\') as width_func,
						REPLACE(vf.profile_func, \'"\', \'\') as profile_func,
						REPLACE(vf.diameter_func, \'"\', \'\') as diameter_func,
						REPLACE(vf.model_func, \'"\', \'\') as model_func,
						REPLACE(vf.load_index_func, \'"\', \'\') as load_index_func,
						REPLACE(vf.speed_index_func, \'"\', \'\') as speed_index_func,
						REPLACE(vf.amount_func, \'"\', \'\') as amount_func,
						REPLACE(vf.price_func, \'"\', \'\') as price_func,
						REPLACE(vf.price_type_func, \'"\', \'\') as price_type_func,
						vf.brand_column,
						vf.width_column,
						vf.profile_column,
						vf.diameter_column,
						vf.model_column,
						vf.load_index_column,
						vf.speed_index_column,
						vf.amount_column,
						vf.price_column,
						vf.comment_column,
						vf.comment_func,
						vf.price_type_column,
						rows_ignore,
						columns_count,
						delete_tmp_table
			FROM vendor_functions vf
			    LEFT JOIN pricelists pl on pl.vendor_id=vf.vendor_id
			    LEFT JOIN sheets sh on sh.list_id=pl.list_id
			WHERE sh.function_id=vf.function_id AND vf.vendor_id='.$vendor_id.' AND sh.sheet_id='.$sheet_num;
/*old where:
 * 	FROM vendor_functions vf
			WHERE vf.vendor_id='.$vendor_id;
*/
/* where for a function_id for each sheet :
 *			'FROM vendor_functions vf
			    LEFT JOIN pricelists pl on pl.vendor_id=vf.vendor_id
			    LEFT JOIN sheets sh on sh.list_id=pl.list_id
			WHERE sh.function_id=vf.function_id;
*/
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->row();
		}
		
		function update_vendor_functions($vendor_id, $vendor_functions, $sheet_num) {
			if ( empty($vendor_id) || ! is_array($vendor_functions) ) return FALSE;
			$vendor_fields = array(
									'brand_func',
									'brand_column',
									'width_func',
									'width_column',
									'profile_func',
									'profile_column',
									'diameter_func',
									'diameter_column',
									'model_func',
									'model_column',
									'load_index_func',
									'load_index_column',
									'speed_index_func',
									'speed_index_column',
									'amount_func',
									'amount_column',
									'price_func',
									'price_column',
									'price_type_func',
									'price_type_column',
									'comment_func',
									'comment_column',									
									'rows_ignore',
									'columns_count',
									);
			$vendor_data = array();
			$key_value_str='';
			foreach($vendor_functions as $key=>$value) {
				if (in_array($key, $vendor_fields)) //$vendor_data[$key] = $value;
				    $key_value_str.='vf.'.$key.'='.($value==''? '\'\'':$value ).', ';
		        }
			$key_value_str=rtrim($key_value_str, ' ,');
			if (!empty($key_value_str)) {
				//$this->db->where('vendor_id', $vendor_id);
				$query="UPDATE vendor_functions vf LEFT JOIN pricelists pl on pl.vendor_id=vf.vendor_id LEFT JOIN sheets sh on vf.function_id=sh.function_id SET $key_value_str WHERE vf.vendor_id=$vendor_id AND sh.sheet_id=$sheet_num";
				$res = $this->db->query($query);
				//$res = $this->db->update('vendor_functions', $vendor_data);
			}
			return $res;
		}
		
		function get_vendor_person($person_id = null, $vendor_id = null, $person_name = '') {
			$query = "SELECT * FROM {$this->table_vendor_person} WHERE 1=1";
			if($person_id) $query .= " AND id='{$person_id}'";
			if($vendor_id) $query .= " AND vendor_id='{$vendor_id}'";
			if($person_name) $query .= " AND name=".clean(addslashes($person_name));
			
			$query = $this->db->query($query);
			return $query->result();
		}
		
		function update_vendor_person($person_id, $vendor_id, $data) {
			if ( empty($vendor_id) || ! is_array($data) ) return FALSE;

			$person_fields = array(
									'name',
									'posada',
									'phone',
									'email');
			$person_data = array();
			foreach($data as $key=>$value) {
				if (in_array($key, $person_fields)) $person_data[$key] = $value;
			}
			$res = false;
			if (!empty($person_data)) {
				$this->db->where('id', $person_id);
				$this->db->where('vendor_id', $vendor_id);
				$res = $this->db->update($this->table_vendor_person, $person_data);
			}
			return $res;
		}
		function update_sheet($id, $data) {
			if ( empty($id) || ! is_array($data) ) return FALSE;

			$sheet_fields = array('active');
			$sheet_data = array();
			foreach($data as $key=>$value) {
				if (in_array($key, $sheet_fields)) $sheet_data[$key] = $value;
			}
			if (!empty($sheet_data)) {
				$this->db->where('id', $id);
			$this->db->update('sheets', $sheet_data);
		   }
			
		}
		/**
		 * Destructor of Vendor_mdl 
		 *
		 * @access  public
		 */
		function __destruct() {}		
	}
?>
