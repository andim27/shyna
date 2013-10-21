<?php
	/**
	 * Class Models_mdl
	 *
	 * model of Models
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Models_mdl.class.php
	 * @created  Tue Oct 12 10:49:13 EEST 2010
	 */
	class Models_mdl extends Model 
	{
		private $table_model = "model";
		private $table_model_syn = "model_syn";
		private $symbols = array(' ', '/', '?', '.', ',', '>', '<', '=', '-', '_', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '|');		 
		
		/**
		 * Constructor of Models_mdl
		 *
		 * @access  public
		 */
		function Models_mdl() {
			parent::Model();
		}
	
		function delete_model_synonym($model_id, $model_parent_id = null) {
			if(empty( $model_id ) && empty( $model_parent_id )) return false;

			if( $model_parent_id ) {
				$query = "DELETE FROM {$this->table_model_syn} WHERE model_id='{$model_parent_id}'";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

				$query = "DELETE FROM {$this->table_model} WHERE id='{$model_parent_id}'";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );
			}
			elseif ( $model_id ) {
				$query = "DELETE FROM {$this->table_model_syn} WHERE id='{$model_id}'";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );
			}
			return true;
		}
		
		function get($brand_id = null, $model_id = null,  $model_name='') {
			$model_id = (intval($model_id)) ? $model_id : null;
			$brand_id = (intval($brand_id)) ? $brand_id : null;

			$query = "
				SELECT model.*
				FROM {$this->table_model} as model
				INNER JOIN brand ON brand.id=model.brand_id 
				WHERE 1=1";
			if($model_id) $query .= " and model.id=".clean($model_id);
			if($brand_id) $query .= " and model.brand_id=".clean($brand_id);
			if($model_name) $query .= " and model.name=".clean(addslashes($model_name));
			$query .= " ORDER BY model.name";
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			else return $query->result();
		}
		
		function get_synonyms($model_id = null, $synonym_name = '') {
			$model_id = (intval($model_id)) ? $model_id : null;
			
			$synonym_name = addslashes($synonym_name);
			
			$query = "SELECT * FROM {$this->table_model_syn} WHERE 1=1";
			if($model_id) $query .= " and model_id=".clean($model_id);
			if($synonym_name) $query .= " and name='{$synonym_name}'";
			$query .= " ORDER BY name";
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			else return $query->result();
		}
	   	function save_edit($tb_name, $type_id,$data_input) {

          $field_name="name";

		  $query = "UPDATE ".$tb_name." SET ".$field_name."='".$data_input."' WHERE id='".$type_id."'";
		  $query = $this->db->query($query);
          if ( ! $query) {return false;}

		  return true;
		}
		function add($data_input) {
			if(empty($data_input)) return false;
						
			$keyword = addslashes($data_input['keyword']);
			
			$query = "INSERT INTO {$this->table_model} (brand_id, name, season, car) VALUES ('{$data_input['brand_id']}','{$keyword}','{$data_input['season']}','{$data_input['car']}')";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

			return $this->db->insert_id();			
		}
		
		function add_synonym($model_id, $data_input) {
			if(empty($model_id) || empty($data_input)) return FALSE;

			$keyword = addslashes($data_input['keyword']);
			
			$synonym_id = null;
			$keyword = str_replace($this->symbols, '', $keyword);
			$synonym_isset = $this->get_synonyms($model_id, $keyword);
			if(!$synonym_isset) {
				$query = "INSERT INTO {$this->table_model_syn} (model_id, name) VALUES ('{$model_id}', '{$keyword}')";
				$query = $this->db->query($query);
				if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

				$synonym_id = $this->db->insert_id();

			} else {
				if(!empty($synonym_isset) && is_array($synonym_isset)) $synonym_isset = $synonym_isset[0];
				$synonym_id = $synonym_isset->id;
			}
			return $synonym_id;
		}
		
		function delete_type($type_id) {
			$query = "DELETE FROM {$this->table_model_syn} WHERE model_id='{$type_id}'";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );

			$query = "DELETE FROM {$this->table_model} WHERE id='{$type_id}'";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );
			
			return true;
		}
		
		function delete_synonym($type_id) {
			$query = "DELETE FROM {$this->table_model} WHERE id='{$type_id}'";
			$query = $this->db->query($query);
			if ( ! $query) throw new Exception( $this->ci->db->_error_message() );
			
			return true;
		}
		
		/**
		 * Destructor of Models_mdl 
		 *
		 * @access  public
		 */
		function _Models_mdl() {
		 	
		 }
		
	}
?>
