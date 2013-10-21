<?php
	/**
	 * Class Vendor_account_mdl
	 *
	 * class works with accounts of vendors
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Vendor_account_mdl.class.php
	 * @created  Fri Oct 15 16:31:58 EEST 2010
	 */
	class Vendor_account_mdl extends Model 
	{
		private $table_vendor_accounts = "vendor_accounts";
		
		/**
		 * Constructor of Vendor_account_mdl
		 *
		 * @access  public
		 */
		function Vendor_account_mdl() {
			parent::Model();
		}
		
		function get_vendor_accounts($account_id = null, $vendor_id = null, $account_email = null) {
			$query = "SELECT va.* FROM {$this->table_vendor_accounts} va INNER JOIN vendor v ON va.vendor_id=v.id WHERE 1=1";
			if($account_id) $query .= " AND va.account_id = ".clean($account_id);
			if($vendor_id) $query .= " AND va.vendor_id = ".clean($vendor_id);
			if($account_email) $query .= " AND va.account_email = ".clean($account_email);
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->result();
		}
		
		function set_vendor_account($account_data) {
			if(empty($account_data)) return FALSE;
			
			$query = "INSERT INTO {$this->table_vendor_accounts} 
				(account_email, vendor_id) 
			VALUES
				('{$account_data['account_email']}', '{$account_data['vendor_id']}');";
			
			if(!$this->db->query($query)) 
			throw new Exception($this->db->_error_message());
			return $this->db->insert_id();
		}
		
		function update_vendor_account($account_id, $vendor_id, $account_data) {
			if(empty($account_id) || empty($vendor_id) || empty($account_data)) return FALSE;

			$this->db->where('account_id', $account_id);
			$this->db->where('vendor_id', $vendor_id);
			if(!$res = $this->db->update($this->table_vendor_accounts, $account_data))
			throw new Exception($this->db->_error_message());
			return true;
		}

		function check_account_email($account_email) {
			$query = "SELECT * FROM {$this->table_vendor_accounts} WHERE account_email='{$account_email}'";
			if(!$this->db->query($query)) 
			throw new Exception($this->db->_error_message());
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->result();
		}			
	}
?>