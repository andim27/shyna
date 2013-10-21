<?php
	/**
	 * Class Attachment
	 *
	 * class for attachment
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Attachment.class.php
	 * @created  Sun Jan 25 15:52:12 EET 2009
	 */
	class Currency_mdl extends Model {
	
		private $table_currency = 'currency';
		
		function Currency_mdl() {
			parent::Model();
		}
		
		function get_currency($currency_id=null, $currency_value=null){
			$query = "select * from {$this->table_currency} where 1";
			if($currency_id) $query .= " and currency_id=".clean($currency_id);
			if($currency_value) $query .= " and currency_value=".clean(strtoupper($currency_value));
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			else return $query->result();
		}
		
		function add_currency($currency_value){
			try {
				if(empty($currency_value))
					throw new Exception("currency value is empty");
				
				$values = array("currency_value" => $currency_value);
				if(!$this->db->insert('currency', $values))
					throw new Exception($this->db->_error_message());			
				return $this->db->insert_id();
				
			} catch(Exception $e){
				log_message('error', $e->getMessage().'\n'.
									$e->getFile().'\n'.
									$e->getCode());
			}
			return false;
		}
		
		function delete_currency(){
			
		}
	}
?>