<?php
	/**
	 * Class Brands_mdl
	 *
	 * model of brands
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Brands_mdl.class.php
	 * @created  Tue Oct 12 10:48:52 EEST 2010
	 */
	class Brands_mdl extends Model 
	{
		private $table_brand = "brand";
		private $table_brand_syn = "brand_syn";		
		private $symbols = array(' ', '/', '?', '.', ',', '>', '<', '=', '-', '_', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '|');
		
		/**
		 * Constructor of Brands_mdl
		 *
		 * @access  public
		 */
		function Brands_mdl() {
			parent::Model();
		}
		
		function delete_brand_synonym($brand_id, $brand_parent_id = null) {
			if(empty( $brand_id ) && empty( $brand_parent_id )) return false;

			if( $brand_parent_id ) {
				$query = "DELETE FROM {$this->table_brand_syn} WHERE brand_id='{$brand_parent_id}'";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

				$query = "DELETE FROM {$this->table_brand} WHERE id='{$brand_parent_id}'";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );
			}
			elseif ( $brand_id ) {
				$query = "DELETE FROM {$this->table_brand_syn} WHERE id='{$brand_id}'";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );
			}
			return true;
		}
	
		function get($brand_id = null, $model_id = null, $brand_name = '') {
			$brand_id = (intval($brand_id)) ? $brand_id : null;
			$model_id = (intval($model_id)) ? $model_id : null;
			$brand_name = (!empty($brand_name)) ? addslashes($brand_name) : null;
			
			$query = "SELECT brand.id, brand.value as name FROM brand LEFT JOIN model ON model.brand_id = brand.id WHERE 1=1";			
			if($brand_id) $query .= " AND brand.id=".clean($brand_id);
			if($model_id) $query .= " AND model.id = ".clean($model_id);
			if($brand_name) $query .= " AND brand.value=".clean(addslashes($brand_name));
			$query .= " GROUP BY brand.id ORDER BY brand.value";
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			else return $query->result();
		}
		
		function get_synonyms($brand_id = null, $synonym_name = '') {
			$brand_id = (intval($brand_id)) ? $brand_id : null;
			
			$synonym_name = addslashes($synonym_name);
			
			$query = "SELECT * FROM {$this->table_brand_syn} WHERE 1=1";
			if($brand_id) $query .= " and brand_id=".clean($brand_id);
			if($synonym_name) $query .= " and name='{$synonym_name}'";
			$query .= " ORDER BY name";
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			else return $query->result();
		}
		
		function add($data_input) {
			if(empty($data_input)) return false;
			
			$keyword = addslashes($data_input['keyword']);
			
			$query = "INSERT INTO {$this->table_brand} (value) VALUES ('{$keyword}')";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

			return $this->db->insert_id();			
		}
		
		function add_synonym($brand_id, $data_input) {
			if(empty($brand_id) || empty($data_input)) return FALSE;

			$keyword = addslashes($data_input['keyword']);
			
			$synonym_id = null;
			$keyword = str_replace($this->symbols, '', $keyword);
			$synonym_isset = $this->get_synonyms($brand_id, $keyword);
			if(!$synonym_isset) {
				$query = "INSERT INTO {$this->table_brand_syn} (brand_id, name) VALUES ('{$brand_id}', '{$keyword}')";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

				$synonym_id = $this->db->insert_id();

			} else {
				if(!empty($synonym_isset) && is_array($synonym_isset)) $synonym_isset = $synonym_isset[0];
				$synonym_id = $synonym_isset->id;
			}
			return $synonym_id;
		}
		function save_edit($tb_name, $type_id,$data_input) {
          if ($tb_name == "brands") {
              $field_name="value";
              $tb_name="brand";
          }
          if ($tb_name == "brand_syn") {
              $field_name="name";
              $tb_name="brand_syn";
          }
		  $query = "UPDATE ".$tb_name." SET ".$field_name."='".$data_input."' WHERE id='".$type_id."'";
          $query = $this->db->query($query);
          if ( ! $query) {return false;}

		  return true;
		}
		function delete_type($type_id) {
			$query = "DELETE FROM {$this->table_brand_syn} WHERE brand_id='{$type_id}'";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

			$query = "DELETE FROM {$this->table_brand} WHERE id='{$type_id}'";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

			return true;
		}
		
		function delete_synonym($type_id) {
			$query = "DELETE FROM {$this->table_brand_syn} WHERE id='{$type_id}'";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );
			
			return true;
		}
		
		/**
		 * Destructor of Brands_mdl 
		 *
		 * @access  public
		 */
		function _Brands_mdl() {
		 	
		 }
		
	}
?>