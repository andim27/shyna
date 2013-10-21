<?php
	/**
	 * Class Users
	 *
	 * general Users controller
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Users.class.php
	 * @created  Fri Sep 03 17:41:44 EEST 2010
	 */
	class Users extends Controller
	{
		/**
		 * Constructor of Users
		 *
		 * @access  public
		 */
		function __construct() 
		{
			parent::Controller();
		}

		function indes(){}
		
		function profile(){
			$user_id = $this->uri->segment(3);
			if (empty($user_id)) $user_id = $this->db_session->userdata('user_id');
			
			modules::run('user_mod/user_ctr/profile', $user_id);
		}
		
	}
?>