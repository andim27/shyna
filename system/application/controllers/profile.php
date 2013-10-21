<?php
class Profile extends Controller {
var $data;
	function Profile()
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
    function view($type=null,$sub_type=null)
	{
        $this->data["page"]="profile";
        $this->data["type"]=$type;
        $this->data["sub_type"]=$sub_type;
        $this->data["user_data"]=modules::run('user_mod/user_ctr/get_user_data');
        $this->data["pf_date"]=modules::run('user_mod/user_ctr/get_price_file_date',$this->data["user_id"]);
        $this->load->view('profile_page',$this->data);
	}
    function save()
    {
    echo modules::run('user_mod/user_ctr/profile_save');

    }
    function save_psw()
    {
      echo modules::run('user_mod/user_ctr/profile_save_psw');

    }
    function upload_price()
    {
      modules::run('user_mod/user_ctr/profile_upload_price');
    }
}
/* end of file */
