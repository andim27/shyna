<?php
	/**
	 * Class Users
	 *
	 * admin side of users
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Users.class.php
	 * @created  Mon Oct 18 10:56:38 EEST 2010
	 */
	class Users extends Controller 
	{
		private $user_id;
		
		/**
		 * Constructor of Users
		 *
		 * @access  public
		 */
		function Users() {
			parent::Controller();
			
			$this->user_id = $this->uri->segment(4);
			if(!is_numeric($this->user_id)) $this->user_id = NULL;
		}
		
		function index() {
		
		if ($this->db_session->userdata('group_id') == 2) {
        	$users = $this->get_users();
			$values = array();
			$values['users'] = $users;
			$this->load->view('admin/users', $values);
    	
        } else {
        	redirect(base_url()."admin");  
        }
			
		}
		
		function profile() {
		    if ($this->db_session->userdata('group_id') == 2) {
			$user = $this->get_users($this->user_id, 'object');
			
			$values = array();
			$values['user'] = $user;
			$values['upload_form'] = $this->load->view("admin/price_upload_form", $values, true);
	
			$config = $this->load->config('upload');
			$file_exist = false;
			if(!empty($vendor->file_ext)) {
				if(file_exists($config['upload_path'].$this->user_id.$vendor->file_ext)) $file_exist = true;
			}
			$values['file_exist'] = $file_exist;
	
			$this->load->view('admin/user_profile', $values);
			}
			else {
        	redirect(base_url()."admin");  
        }
			
		}
		
		private function get_users($user_id=null, $returyn_type='array', $user_login='') {
			$this->load->model('user_mdl','user');
	
			$users = $this->user->get_users($user_id,$user_login);	
			if($returyn_type == 'object') {
				if($users && is_array($users)) $users = $users[0];
			}
			return $users;
		}
		
		
		function ajax_actions() {
			$action = $this->input->post('action');
	        $error = $result = false;
            $mes="";
			$data = '';
			switch ($action) {
				case "set_person":
					$vendor_id = $this->input->post("vendor_id");
					$person_name = $this->input->post("person_name");
					$person_posada = $this->input->post("person_posada");
					$person_phone = $this->input->post("person_phone");
					$person_email = $this->input->post("person_email");
					
					$person_data = array(
						'name' => $person_name,
						'posada' => $person_posada,
						'phone'	=> $person_phone,
						'email' => $person_email
					);
					
					$error_person = $result = false;
					$person_exists = $this->get_vendor_person(null, null, $person_name);
					if(is_array($person_exists)) $person_exists = array_shift($person_exists);
					if(empty($person_exists)) {					
						$result = $this->set_vendor_person($vendor_id, $person_data);					
					}
					else {
						$error_person = true;
					}
					$data = (Object)array('result'=>$result, 'error'=>$error_person);
					$data = json_encode($data);					
				    break;
				case "get_person":
					$person_id = $this->input->post("person_id");
					$vendor_id = $this->input->post("vendor_id");
					$person = $this->get_vendor_person($person_id, $vendor_id);
					if(!empty($person)) {
						$values = array();
						$values['persons'] = $person;
						$data = $this->load->view('admin/_vendor_person_tpl', $values, true);
					} else {
						$data = 0;
					}
				break;
                case "admin_block_user":
                     $user_id=$this->input->post('user_id');
                      if (!empty($user_id)){
                       $this->load->model('user_mdl', 'user');
                       $result=$this->user->admin_block_user($user_id);
                       if ($result ==false){
                           $error=false;
                           $mes="Ошибка блокировки";
                       }
                       $data = (Object)array('error' => $error, 'result' =>"".$result."",'mes'=>$mes);
				       $data = json_encode($data);
                   }
                break;
                case "admin_unblock_user":
                     $user_id=$this->input->post('user_id');
                      if (!empty($user_id)){
                       $this->load->model('user_mdl', 'user');
                       $result=$this->user->admin_unblock_user($user_id);
                       if ($result ==false){
                           $error=false;
                           $mes="Ошибка разблокировки";
                       }
                       $data = (Object)array('error' => $error, 'result' =>"".$result."",'mes'=>$mes);
				       $data = json_encode($data);
                   }
                break;
                case "admin_del_user":
                   $del_user_id=$this->input->post('user_id');
                   if (!empty($del_user_id)){
                       $this->load->model('user_mdl', 'user');
                       $result=$this->user->admin_del_user($del_user_id);
                       if ($result ==false){
                           $error=false;
                           $mes="Ошибка удаления";
                       }
                       try{
                            $this->config->load('upload');
                            $filename = $this->config->item("upload_path")."/".$del_user_id.".xls";
                            unlink($filename);

                       } catch (Exception $e) {
                            log_message('error',"delete file for user_id ".$del_user_id." error");
                       }
                       $data = (Object)array('error' => $error, 'result' =>"".$result."",'mes'=>$mes);
				       $data = json_encode($data);
                   }
                break;
                case "admin_add_new_user":

                    $data_user['login'] = $this->input->post('user_name');
					$data_user['psw']   = $this->input->post('user_psw');
					$data_user['email'] = $this->input->post('user_email');
                    $this->load->model('user_mdl', 'user');
                    $u =$this->user->get_user_by_login($data_user['login'] );
                    if ((!empty($u)) && ($u->login == $data_user['login'])) {
                           $error=true;
                           $mes="Пользователь с таким именем уже существует!";
                    } else {
                           $result=$this->user->admin_add_user($data_user);
                    }
                    $data = (Object)array('error' => $error, 'result' =>"".$result."",'mes'=>$mes);
				    $data = json_encode($data);
                break;
				case "update_user":
					$user_id = $this->input->post('user_id');
					$user_login = $this->input->post('user_login');
					$user_email = $this->input->post('user_email');
					$user_city = $this->input->post('user_city');
					$user_tel = $this->input->post('user_tel');
					$user_company = $this->input->post('user_company');
					$user_fio = $this->input->post('user_fio');
					$user_aedate = $this->input->post('user_aedate');
					$user_group = $this->input->post('user_group');
					if($user_group=='admin')  $group_id='2';
					if($user_group=='user')   $group_id='1';
					if($user_group=='active') $group_id='3'; 
					if($user_group=='guest')  $group_id='0';
					
					

					$this->load->model('user_mdl', 'user');
				     
					$regs=array();
					$result = false;
					$error = false;
					$error_aedata = false;
					$user_exists = $this->get_users(null,'object',$user_login);
					if(empty($user_exists) || ($user_exists->user_id == $user_id)) {
                    	if(!preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/",$user_aedate,$regs)){
								$error_aedata = true;
						}
						else{
							$user_data = array(
							'login' => $user_login,
							'email' => $user_email,
							'city' => $user_city,
							'company' => $user_company,
							'fio' => $user_fio,
							'tel' => $user_tel,
							'active_end_date' => $user_aedate,
							'group_id' => $group_id							

						);
						$result = $this->user->update_user($user_id, $user_data);
                        $this->user->set_user_activity($user_id);
						}
					} else {
					$error = true;
				    }

				$data = (Object)array('error' => $error, 'result' => $result, 'error_aedata' => $error_aedata,);
				$data = json_encode($data);
				
			break; 

			}
			$this->output->set_output($data);
		}
		
		/**
		 * Destructor of Users 
		 *
		 * @access  public
		 */
		function _Users(){}	
	}
?>
