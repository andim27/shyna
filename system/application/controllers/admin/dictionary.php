<?php
	/**
	 * Class Dictionary
	 *
	 * class works with dictionary of sinonyms
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Dictionary.class.php
	 * @created  Mon Oct 11 18:38:25 EEST 2010
	 */
	class Dictionary extends Controller
	{
		/**
		 * Constructor of Dictionary
		 *
		 * @access  public
		 */
		function Dictionary() {
			parent::Controller();
		}
		
		function index() {
		
        if ($this->db_session->userdata('group_id') == 2) {
        	$this->get_dictionary_form();   	
        }else {
        	redirect(base_url()."admin");     
        }
			
		}
	
		private function get_dictionary_form() {
			$models = array('brands_mdl' => 'brands', 'models_mdl' => 'models');
			$values = array();
			foreach ($models as $model_name => $model_slug) {
				$this->load->model($model_name, $model_slug);
				$values[$model_slug] = $this->{$model_slug}->get();
				$values[$model_slug."_syn"] = null;
			}
			
			$this->load->view('admin/dictionary', $values);
		}
		
		function ajax_actions() {
			$action = $this->input->post('action');
			
			$this->load->model('brands_mdl', 'brands');
			$this->load->model('models_mdl', 'models');

			$type = $this->input->post("type");
			$keyword = $this->input->post("keyword");
			$type_id = $this->input->post("type_id");
			// if adding a model
			$brand_id = $this->input->post("brand_id");
			$model_season = $this->input->post("model_season");
			$model_car = $this->input->post("model_car");

			$data_input = array();
			$data_input['keyword'] = $keyword;
			$data_input['brand_id'] = $brand_id;
			$data_input['season'] = $model_season;
			$data_input['car'] = $model_car;
	
			$data = '';			
			$exists = false;
			switch ($action) {
			  	case "save_edit":
                   $n_name = $this->input->post("n_name");
                   if ($type == 'brands'){
                        $res=$this->brands->save_edit('brands',$type_id,$n_name);
                   }
                   if ($type == 'brands_syn'){
                        $res=$this->brands->save_edit('brand_syn',$type_id,$n_name);
                   }
                   if ($type == 'models'){
                        $res=$this->models->save_edit('model',$type_id,$n_name);
                   }
                   if ($type == 'models_syn'){
                        $res=$this->models->save_edit('model_syn',$type_id,$n_name);
                   }
                   if ($res==false) {
                      $mes_res="Ошибка сохранения данных";
                   } else {
                      $mes_res="Данные сохранены";
                   }
                   $data = (Object)array('type_id'=>$type_id, 'res'=>$res, 'mes'=>$mes_res);
				   $data = json_encode($data);
                break;
				case "get_dicts":
					$type1 = $this->input->post("type1");
					$type2 = $this->input->post("type2");

                    if ($type1 == 'brands') {
					    $type1_obj = $this->{$type1}->get($type_id);
					    $type2_obj = $this->{$type2}->get($type_id);
                    } else if ($type1 == 'models'){
   					    $type1_obj = $this->{$type1}->get(null,$type_id);
					    $type2_obj = $this->{$type2}->get(null,$type_id);
                    }
					$type1_synonyms = $this->{$type1}->get_synonyms($type_id);
					$type2_synonyms = null;
					if(!empty($type2_obj)) {						
						$type2_synonyms = $this->{$type2}->get_synonyms($type2_obj[0]->id);
					}
					
					$data = (Object)array('type1_syns' => $type1_synonyms, 'type2_syns' => $type2_synonyms, 'type1_obj' => $type1_obj, 'type2_obj' => $type2_obj);
					$data = json_encode($data);
					
				break;
				case "add_type":
					$type_exists = $this->{$type}->get($data_input['brand_id'], null, $data_input['keyword']);
					if($type_exists) {
						if(is_array($type_exists)) $type_exists = $type_exists[0];
						$type_id = $type_exists->id;
						$exists = true;
					} else {
						$type_id = $this->{$type}->add($data_input);						
					}
					$synonym_exists = $this->{$type}->get_synonyms(null, $data_input['keyword']);
					if($synonym_exists) {
						if(is_array($synonym_exists)) $synonym_exists = $synonym_exists[0];
						$type_syn_id = $synonym_exists->id;
					} else {
						$type_syn_id = $this->{$type}->add_synonym($type_id, $data_input);
						$exists = false;
					}
					
					$data = (Object)array('type_id' => $type_id, 'type_syn_id' => $type_syn_id, 'type_exists' => $exists);
					$data = json_encode($data);
				break;
				case "add_synonym":
					$synonym_exists = $this->{$type}->get_synonyms($type_id, $data_input['keyword']);
					if($synonym_exists) {
						if(is_array($synonym_exists)) $synonym_exists = $synonym_exists[0];
						$type_syn_id = $synonym_exists->id;
						$exists = true;
					} else {
						$type_syn_id = $this->{$type}->add_synonym($type_id, $data_input);
					}
					
					$data = (Object)array('type_syn_id' => $type_syn_id, 'type_exists' => $exists);
					$data = json_encode($data);
				break;
				case "delete_type":
					$synonyms = $this->{$type}->get_synonyms($type_id);
					$this->{$type}->delete_type($type_id);
					
					$data = (Object)array('synonyms' => $synonyms);
					$data = json_encode($data);
				break;
				case "delete_synonym":
					$this->{$type}->delete_synonym($type_id);
				break;
			}
			$this->output->set_output($data);
		}
	}
?>
