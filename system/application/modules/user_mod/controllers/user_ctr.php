<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_ctr extends Controller {
 var $language;
    function User_ctr()
    {
         parent::Controller();
         $this->load->model('user_mdl','user');
         $this->language="ru";
         $this->user_id = $this->db_session->userdata('user_id');
    }
    
    function login($login, $passwd)
    {
    	if(empty($login) || empty($passwd)) return FALSE;
    	
		$this->load->language("user", $this->language);

		try {

			$result = $this->user->login("user", $login, $passwd);
            
			if(!empty($result)) {
				if($result->user_id != -1) {
                    if ($result->active <= -1) {return -1;}
					$user_id = $result->user_id;

					$this->db_session->set_userdata('user_id', $result->user_id);
                    $this->db_session->set_userdata('user_login', $result->login);
					try {
		           		$this->user->update_login_info("user", $result->user_id, date("Y-m-d H:i:s"), $this->input->ip_address());
		           		
		            } catch (Exception $e) {
			    		log_message('error',
			    						$e->getMessage().'\n'. 
			    						$e->getFile().'\n'. 
			    						$e->getCode());
			    		return false;
			    	}			            
		            if($result->group_id != -1)
		            	$this->db_session->set_userdata('group_id', $result->group_id);
		            if($result->group_name != -1)
		            	$this->db_session->set_userdata('group_name', $result->group_name);
				}
				return true;				
				
			} else {
				throw new Exception("The result of login is empty.");
			}
			
		} catch (Exception $e) {
    		log_message('error', $e->getMessage().'\n'. $e->getFile().'\n'. $e->getCode());
    	}    	
    	return false;
    }
    function enter()
    {
      $login = $this->input->post('login_name');
      $psw = $this->input->post('login_psw');
      $json_data = array ('status'=>-1,'login_mes'=>'');
      $res=$this->login($login, $psw);

      if ($res == 1) {

            $json_data['status']="1";
            $json_data['login_mes']="Вы вошли как ".$login;
      } else {
             if ($res == -1) {
                 $json_data['status']   ="-1";
                 $json_data['login_mes']="Доступ закрыт,<br>обратитесь к администратору";
             } else {
                 $json_data['status']   ="-1";
                 $json_data['login_mes']="Ошибка входа,<br>повторите ввод";
             }

      }
      $data = "[".json_encode($json_data)."]";
      return $data;
    }
    function fogot() {
      $email     = $this->input->post('email');
      $json_data = array ('status'=>'-1','mes'=>'');
      $user = $this->user->get_user_by_email($email);
      if (empty($user)) {
         $json_data['status']   ="-1";
         $json_data['mes']="Указанный адрес<br>отсутствует в списке зарегистрированных";
      } else {
          $this->load->library('email');
		  $this->load->helper('email');
          //$this->load->helper('BI_email');
          $new_psw=$this->create_random_password();
          if ($this->user->set_new_psw($user->user_id,$new_psw)) {
              $subject="Сайт: ".base_url()." восстановление пароля";
              $message="Восстановление пароля<br>";
              $message.="--------------------<br>";
              $message.="Ваш логин: ".$user->login."<br>";
              $message.="Новый пароль: ".$new_psw."<br>";
              $message.="--------------------<br>";
              $message.="Вы можете заменить данный пароль<br>";
              $message.="Зайдя после входа в <a href='".base_url()."profile/'>личный кабинет</a> <br>";

              if (send_email($email, $subject, $message)) {
                    $json_data['status']   ="1";
                    $json_data['mes']="На Ваш email выслан новый пароль!";
    		  } else {
                    $json_data['status']   ="-1";
                    $json_data['mes']="Ошибка отправки почты!!";
    		  }
          } else {
              $json_data['status']   ="-1";
              $json_data['mes']="Ошибка восстановления пароля!!";
          }

      }
      $data = "[".json_encode($json_data)."]";
      return $data;
    }
    function _check_capthca()
    {
      // Delete old data ( 2hours)
      $expiration = time()-7200 ;
      $sql = " DELETE FROM captcha WHERE captcha_time < ? ";
      $binds = array($expiration);
      $query = $this->db->query($sql, $binds);
      //checking input
      $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
      $binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
      $query = $this->db->query($sql, $binds);
      $row = $query->row();
      if ( $row -> count > 0 )
      {
        return true;
      }
      return false;
    }
    function feedback() {
      $user_name=$this->input->post('username');
      $email    =$this->input->post('email');
      $tel      =$this->input->post('tel');
      $user_message  = $this->input->post('message');
      $captcha       = $this->input->post('captcha');
	  $json_data = array ('status'=>'-1','mes'=>'');
      if (!empty($captcha)) {
        if ( $this -> _check_capthca() ) {
           $captcha_result = 'GOOD';
           $json_data['status']="1";
		   $json_data['mes']="Текст совпадает!";
        }else {
           $json_data['status']   ="-1";
           $json_data['mes']="Текст картинки не совпадает с введеным!";
           $data = "[".json_encode($json_data)."]";
           return $data;
        }
      }
      // $data = "[".json_encode($json_data)."]";
      //return $data;
	  $this->load->library('form_validation');
	  $config = $this->load->config('form_validation');
      $cfg = $this->config->item('users/feedback_full');
	  foreach ($cfg as &$c) {
			if($c['field'] == 'email') $c['label'] = "&quot;".$email."&quot;";
	  }
	  $this->form_validation->set_rules($cfg);
      if ($this->form_validation->run() == TRUE)
	    {
				$this->load->library('email');
				$this->load->helper('email');
				//$this->load->helper('BI_email');
				$subject="Сайт: ".base_url()." обратная связь";
				$message="Администратор, вам пришло сообщение от пользователя - ".$user_name."<br>";
				$message.="--------------------<br>";
				$message.="Контактный телефон пользователя: ".$tel."<br>";
				$message.="Email: ".$email."<br>";
				$message.="Текст сообщения:<br>".$user_message."<br>";
				$message.="--------------------<br>";
				if (send_email_feedback($email, $subject, $message)) {
						$json_data['status']   ="1";
						$json_data['mes']="Ваше сообщение отправлено!";
				} else {
                        $json_data['status']   ="-1";
                        $json_data['mes']="Возникла ошибка отправки сообщения!";
				}
			} 
			else
			{   //form validation failed
				$this->form_validation->set_error_delimiters("", "");
				$json_data['status']=-1;				
				$json_data['email_err']=$this->form_validation->error('email');
		}
	  
      $data = "[".json_encode($json_data)."]";
      return $data;
    }
    function register()
    {
      $json_data = array ('status'=>-1,'login_err'=>'','email_err'=>'', 'pass1_err'=>'', 'pass2_err'=>'' );
      $login = $this->input->post('username');
      $email = $this->input->post('email');
      $pass1 = $this->input->post('pass1');
      $pass2 = $this->input->post('pass1');
      $fio   = $this->input->post('fio');
      $tel   = $this->input->post('tel');
      $city  = $this->input->post('city');
      $company = $this->input->post('company');
      $this->load->library('form_validation');
	  $config = $this->load->config('form_validation');
      $cfg = $this->config->item('users/register_full');
	  foreach ($cfg as &$c) {
			if($c['field'] == 'username') $c['label'] = "&quot;".$login."&quot;";
			elseif($c['field'] == 'email') $c['label'] = "&quot;".$email."&quot;";
	  }
	  $this->form_validation->set_rules($cfg);
      if ($this->form_validation->run() == TRUE)
	    {
              //---check the same email---
             $user = $this->user->get_user_by_email($email);
             if ($user->email == $email) {
                   $json_data['status']="-1";
                   $json_data['login_err']="Пользователь с таким email уже существует!";
             } else {
             //---check the same login---
             $user = $this->user->get_user_by_login($login);
             if ($user->login == $login) {
                   $json_data['status']="-1";
                   $json_data['login_err']="Пользователь с таким именем уже существует!";
             } else {
                   $user_id = $this->user->registration ($login, $email, $pass1,$fio, $tel, $city, $company);
                   if ($user_id == 0  )  {
                        $json_data['login_err'] = "Ошибка сохранения данных";
                   } else {
						//$json_data['status']="1";
						//{Отправка данных на почту
							$this->load->library('email');
							$this->load->helper('email');
							//$this->load->helper('BI_email');
							$subject="ШинОК";
							$message="Добро пожаловать на сайт: ШинОК <br><br>";
							$message.="Пожалуйста, сохраните это сообщение.<br>";
							$message.="Параметры вашей учетной записи на ШинОК - ".base_url()." таковы:<br><br>";
							$message.="--------------------<br>";
							$message.="Имя пользователя: ".$login."<br>";
							$message.="Пароль: ".$pass1."<br>";
							$message.="--------------------<br><br>";
							$message.="Если вы удалите это сообщение и забудете ваш пароль, вы сможете запросить новый.<br>";
							$message.="Вы можете заменить данный пароль на странице <a href='".base_url()."profile/'>личный кабинет</a>. <br><br>";
							
							$message.="Спасибо за регистрацию.<br><br>";
							$message.="--<br>";
							$message.="С уважением,<br>";
                               $message.="Администрация сайта<br>";
							if (send_email($email, $subject, $message)) {
								$json_data['status']   ="1";
								//$json_data['mes']="На Ваш email выслана информация о регистрации!";
							} else {
								$json_data['status']   ="-1";
								$json_data['mes']="Возникла ошибка отправки сообщения!";
							}  
						//}
                   }
                   $this->db_session->set_userdata('user_id', $user_id);
		           $this->db_session->set_userdata('user_login',$login);
		           $this->db_session->set_userdata('user_group',$group_id);
               }
             }
	    }
      else
		{   //form validation failed
		    $this->form_validation->set_error_delimiters("", "");
            $json_data['status']=-1;
            $json_data['login_err']=$this->form_validation->error('username');
            $json_data['email_err']=$this->form_validation->error('email');
            $json_data['pass1_err']=$this->form_validation->error('pass1');
            $json_data['pass2_err']=$this->form_validation->error('pass2');

         /*   $data = "{'status' : '-1',
    					  'login_err':'".$this->form_validation->error('username')."',
    					  'email_err':'".$this->form_validation->error('email')."',
                          'pass1_err':'".$this->form_validation->error('pass1')."',
                          'pass2_err':'".$this->form_validation->error('pass2')."'
    					  }";*/
		}
      $data = "[".json_encode($json_data)."]";
      return $data;
    }

    function logout()
    {
        $this->db_session->unset_userdata('user_id');
        $this->db_session->unset_userdata('user_login');
        $this->db_session->unset_userdata('group_id');
        $this->db_session->unset_userdata('group_name');
        redirect(base_url());
    }
   function get_user_data($user_id=null)
   {
    if ($user_id == null){$user_id =$this->user_id;}
    return $this->user->get_user_by_id($user_id);
   }
   function get_user_by_login($login=null) {
     if (empty($login)) return false;
     $this->load->model('user_mdl','user_l');
     return $this->user_l->get_user_by_login($login);
   }
   function profile_save()
   {
     $out_str="";
     $user_id=$this->db_session->userdata('user_id');
     $this->load->model('user_mdl','user');
     $res=$this->user->profile_save($user_id);
     if (!empty($res)){
         $mes="Данные сохранены !";
     } else {
         $mes="Ошибка сохранения данных !";
     }
     $out_str="{'user_id':'".$user_id."','mes':'".$mes."'}";
     $out_str="[".$out_str."]";
     return $out_str;
   }
   function profile_save_psw()
   {
     $out_str="";
     $user_id=$this->db_session->userdata('user_id');
     $res=$this->user->profile_save_psw($user_id);
     if ($res == 1){
         $mes="Пароль изменен !";
     } else {
         if ($res == 2){
                 $mes="Ошибка подтверждения пароля !";
         } else {$mes="Ошибка замены пароля !";}
     }
     $out_str="{'user_id':'".$user_id."','mes':'".$mes."'}";
     $out_str="[".$out_str."]";
     return $out_str;
   }
   	function create_random_password() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '';
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
    function profile_upload_price()
    {
      $user_id = $this->input->post('user_id');
      if (empty($user_id)){
        show_error('Ошибка загрузки файла. Нет юзера');
        return;
      }

      $this->load->library('upload');
 //     $config = $this->load->config('upload');
//      $this->upload->initialize($config);
      if ( ! $this->upload->do_upload()) {
        show_error('Ошибка загрузки файла');
        redirect(base_url()."profile/2/1");
      }
      $upload_data = $this->upload->data();
      $file_ext = $upload_data["file_ext"];
      $uploadedfile = $upload_data["full_path"];
      if (($file_ext == 'zip') || ($file_ext == 'rar')) {
        $this->load->helper('upload');
        $uploadedfile=unarchive($upload_data["full_path"], $upload_data["file_path"], $file_ext);
	  }
      if ($uploadedfile === FALSE) {
        show_error('Неправильный файл');
        redirect(base_url()."profile/2/1");
      }
      $file_ext = ".".end(explode(".", $uploadedfile));
      $uploadfile = $upload_data["file_path"] . $user_id . $file_ext;
      if (file_exists($uploadfile)) {unlink($uploadfile);}
      rename($uploadedfile, $uploadfile);
      //
	  $this->load->model('user_mdl','user');
      $this->load->model('vendor_mdl','vendor');
	  $this->vendor->set_vendor($user_id);
	  $this->vendor->set_vendor_price($user_id, '', $file_ext);

/*      require_once(PARSER_MODULE_PATH."controllers/priceLoader.php");
      $price_id = modules::run('parser_mod/parser_ctr/upload_price', $this->vendor_id);
      $loader = new priceLoader($user_id);
      if ($loader->get_sheets_info($uploadfile))
         $loader->add_sheets_info();
*/
      //отправка почты
      $this->load->library('email');
	  $this->load->helper('email');
      $this->load->helper('log');
	  $user = $this->user->get_user_by_id($user_id);
      $this->config->load('upload');

	  $email=$this->config->item('admin_email');
	  $subject="Сайт ШинОК: загрузка прайса";
	  $message="--------------------<br>";
	  $message.="Администратор, ".date("d.m.Y H:i:s")."  пользователем  - <b>".$user->login."</b> был загружен прайс.<br>";
	  $message.="Вы можете загрузить файл по этой ссылке <a href='".$this->config->item('base_url').$this->config->item('upload_url').$user->user_id.$file_ext."'>".$user->user_id.$file_ext."</a> <br>";
	  $message.="--------------------<br>";
	  if (send_email_feedback($email, $subject, $message)){
        redirect(base_url()."profile/2/0");
	  } else {
        redirect(base_url()."profile/2/1");
      }

   }

    function get_price_file_date($user_id=null){
      if (empty($user_id)){return "";}
       $config = $this->load->config('upload');
       $uploaddir = $config['upload_path'];
       $fileprice = $uploaddir . $user_id.".xls";
       if (file_exists( $fileprice)) {
           return date("d.m.Y", filectime($fileprice));
       } else {
           return "";
       }
    }

    function profile($user_id){
    	if(empty($user_id)) return false;

    	$user = $this->user->get_users($user_id);

    	$data = array();
		$data['user'] = $user;
		$this->load->view('profile', $data);
    }
}

/* EOF */
