<?php
	/**
	 * Class Price_mdl
	 *
	 * price model
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Price_mdl.class.php
	 * @created  Wed Sep 22 09:50:03 EEST 2010
	 */
	class Price_mdl extends Model 
	{
		private $tablename = 'prices';
		
		/**
		 * Constructor of Price_mdl
		 *
		 * @access  public
		 */
		function Price_mdl() {
			parent::Model();
		}
		
		function get_price($price_list_id=null) {
			if( empty($price_list_id) ) return FALSE;
			try {
				$query = "SELECT * FROM ".$this->tablename." WHERE 1=1";
				if($price_list_id) $query .= " AND list_id=".$this->db->escape($price_list_id);
				
				if(!$this->db->query($query)) 
				throw new Exception($this->db->_error_message());
				
				return true;
				
			} catch (Exception $e) {
				$this->setError($e);
			}
		}
		
		function set_price($price_list_id) { //заливает цены для выбранного $price_list_id
			$duplicate = $this->get_price($price_list_id);
			if(!empty($duplicate)) {
				if(is_array($duplicate)) $duplicate = array_shift($duplicate);
				$this->delete_price($price_list_id);
			}
			try {
				$query = "INSERT INTO ".$this->tablename." (
						list_id, width_id, profile_id, diameter_id, model_id, load_id, speed_id, extra, amount, price) 
					VALUES (
						'".$price_list_id."', 0, 0, 0, 0, 0 ,0 ,'', '', 0 
					)";
				if(!$this->db->query($query)) 
				throw new Exception($this->db->_error_message());
				
				return true;
				
			} catch (Exception $e) {
				$this->setError($e);
			}
		}
		
		function delete_price($price_list_id) {
			if(empty($price_list_id)) return FALSE;
			try {
				$query = "DELETE FROM ".$this->tablename." WHERE list_id=".$this->db->escape($price_list_id);
				if(!$this->db->query($query)) 
				throw new Exception($this->db->_error_message());
				
				return true;
				
			} catch (Exception $e) {
				$this->setError($e);
			}
		}
		
		private function setError(Exception $e) {
			log_message('error',$e->getMessage()."\n". "file: ".$e->getFile()."\n". "code: ".$e->getCode()."\n". "line: ".$e->getLine());
				echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	
		/**
		 * Destructor of Price_mdl 
		 *
		 * @access  public
		 */
		 function _Price_mdl() {
		 	
		 }
		
	}
?>