<?php
	/**
	 * Class Vendor_person_mdl
	 *
	 * class works with accounts of vendors
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Vendor_person_mdl.class.php
	 * @created  Fri Oct 15 16:31:58 EEST 2010
	 */
	class Vendor_person_mdl extends Model 
	{
		private $table_vendor_person = "vendor_person";
		
		/**
		 * Constructor of Vendor_person_mdl
		 *
		 * @access  public
		 */
		function Vendor_person_mdl() {
			parent::Model();
		}
		
		function get_vendor_person($person_id = null, $vendor_id = null, $person_name = '') {
			$query = "SELECT vp.* FROM {$this->table_vendor_person} vp INNER JOIN vendor v ON vp.vendor_id=v.id WHERE 1=1";
			if($person_id) $query .= " AND vp.id = ".clean($person_id);
			if($vendor_id) $query .= " AND vp.vendor_id = ".clean($vendor_id);
			if($person_name) $query .= " AND vp.name = ".clean(addslashes($person_name));
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->result();
		}
		
		function set_vendor_person($vendor_id, $person_data) {
			if(empty($vendor_id) || empty($person_data)) return FALSE;
			
			$query = "INSERT INTO {$this->table_vendor_person}
				(vendor_id, name, posada, phone, email) 
			VALUES
				('{$vendor_id}', '{$person_data['name']}', '{$person_data['posada']}', '{$person_data['phone']}', '{$person_data['email']}');";

			if(!$this->db->query($query)) {
        			throw new Exception($this->db->_error_message());
                    return false;
            }
			return $this->db->insert_id();
		}

		function update_vendor_person($person_id, $vendor_id, $person_data) {
			if(empty($person_id) || empty($vendor_id) || empty($person_data)) return FALSE;

			$this->db->where('id', $person_id);
			$this->db->where('vendor_id', $vendor_id);
			if(!$res = $this->db->update($this->table_vendor_person, $person_data))
			throw new Exception($this->db->_error_message());
			return true;
		}
        function delete_person($vendor_id, $person_id ) {
           	if(empty($person_id) || empty($vendor_id) ) return FALSE;
            $query = "DELETE FROM vendor_person WHERE vendor_id=".$vendor_id." AND id=".$person_id;
           	if(!$this->db->query($query)) {
 			  throw new Exception($this->db->_error_message());
              return FALSE;
            }
            return TRUE;
        }
	}
?>