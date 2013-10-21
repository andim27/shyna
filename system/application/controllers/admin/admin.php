<?php
class Admin extends Controller {

	function Admin() {
		parent::Controller();  
        $this->load->language('admin');
	}
	
	function index() {
	    $user_id = $this->db_session->userdata('user_id');
        $group_id = $this->db_session->userdata('group_id');
        if (empty($user_id) || empty($group_id)) {
        	$this->login();        	
        } else if ($group_id==2) {
        	$this->load->view("admin/main");
        }
	}

	function prices() {
		$values = array();				
		$values['parser_mod_result'] = modules::run('parser_mod/parser_ctr/main');		
		$this->load->view("admin/prices", $values);
	}
	
    function login() {        
        $data['error'] = '';
        
        $login = $this->input->post('login');
        $passwd = $this->input->post('passwd');
                
        if(!empty($login) && !empty($passwd)) {
            $result = modules::run('user_mod/user_ctr/login', $login, $passwd);
            if(!$result) {
                $data['error'] = lang('admin.login.error');
                $this->load->view("admin/_admin_login", $data);
            }
            else {
                $this->load->view("admin/main");
            }
        }
        else {
            $this->load->view("admin/_admin_login", $data);
        }
    }


    function logout() {
	   modules::run('user_mod/user_ctr/logout');
    }

    function usersview() {	
		$this->load->view('admin/usersview');
	}	
}

/* EOF */
