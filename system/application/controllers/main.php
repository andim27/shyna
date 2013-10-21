<?php
class Main extends Controller {
var $data;
var $user_id;
    function Main()
	{
		parent::Controller();
        $this->user_id=$this->db_session->userdata('user_id');
        $this->user_login=$this->db_session->userdata('user_login');
        $this->load->model("shyna");
        $this->data = array();
        $this->data["user_id"]=$this->user_id;
        $this->data["user_login"]=$this->user_login;
        $this->data["width_items"]   =$this->shyna->getWidths();
        $this->data["profil_items"]  =$this->shyna->getProfils();
        $this->data["diameter_items"]=$this->shyna->getDiameters();
        $this->data["brand_items"]   =$this->shyna->getBrands();
	}
    function index()
	{
      $this->view();
	}
    function view()
	{
        $this->data["page"]   ="main";
        $this->load->view('main',$this->data);
	}
    function register()
	{
        $this->data["page"]     ="main";
        $this->data["page_sub"] ="register";
        $this->load->view('main',$this->data);
	}
    function contacts()
    {

      $this->data["page"]="contacts";
      $captcha_result = "";
      $this->data["cap_img"] = $this ->_make_captcha();
      $this->load->view('contacts',$this->data);
    }
    function _make_captcha()
    {
      $this->load->plugin( 'captcha' );
      $vals = array(
        'img_path' => './images/captcha/', // PATH for captcha ( *Must mkdir (htdocs)/captcha )
        'img_url'  => base_url()."images/captcha/", // URL for captcha img
        'img_width'  => 130, // width
        'img_height' => 60, // height
        // ‘font_path’    => ‘../system/fonts/2.ttf’,
        // ‘expiration’ => 7200 ,
        );
      // Create captcha
      $cap = create_captcha( $vals );
      // Write to DB
      if ( $cap ) {
        $data = array(
          'captcha_id' => '',
          'captcha_time' => $cap['time'],
          'ip_address' => $this -> input -> ip_address(),
          'word' => $cap['word'] ,
          );
        $query = $this -> db -> insert_string( 'captcha', $data );
        $this -> db -> query( $query );
      }else {
        return "Umm captcha not work" ;
      }
      return $cap['image'] ;
    }

    function ajax_action()
    {
      $action=$this->input->post("action");
      if ($action == "register") {
          $out_str= modules::run('user_mod/user_ctr/register');//"[{'status' : '-1', 'login_err':'Ошибка регистрации!'}]";
          $this->output->set_output($out_str);
      }
      if ($action == "fogot") {
          $out_str= modules::run('user_mod/user_ctr/fogot');//"[{'status' : '-1', 'mes':'Ошибка регистрации!'}]";
          $this->output->set_output($out_str);
      }
      if ($action == "feedback") {
          $out_str= modules::run('user_mod/user_ctr/feedback');
          $this->output->set_output($out_str);
      }
    }
    function enter()
    {
       $action=$this->input->post("action");
       if ($action == "enter") {
          $out_str=modules::run('user_mod/user_ctr/enter');//"[{'status' : '-1', 'login_mes':'Ошибка входа!'}]";
          $this->output->set_output($out_str);
      }
    }
    function logout()
    {
      modules::run('user_mod/user_ctr/logout');
    }
}
/* end of file */
