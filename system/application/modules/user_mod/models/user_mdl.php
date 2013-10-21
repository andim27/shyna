<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_mdl extends Model {

	function User_mdl()
	{
		parent::Model();
	}
    function registration ($login, $email, $passwd,$fio, $tel, $city, $company) {
		try {
			$registration_ip = $_SERVER['REMOTE_ADDR'];

			$fio  = empty($fio) ? 'NULL' : clean($fio);
			$tel  = empty($tel) ? 'NULL' : clean($tel);
			$city = empty($city)? 'NULL' : clean($city);
			$company = empty($company) ? 'NULL' : clean($company);
            $registration_date=date("Y-m-d");
            $last_login_date=$registration_date;
			$query = 'INSERT INTO user_account (login, email, passwd,
            registration_date,last_login_date, registration_ip ,
            last_login_ip, fio,tel,city,
            company,group_id,active)
			VALUES ('.clean($login).', '.clean($email).', md5('.clean($passwd).'),
             \''.$registration_date.'\', "'.$last_login_date.'","'.$registration_ip.'", "'.$registration_ip.'",'.
             $fio.','.$tel.','.$city.','.
             $company.',1,1 )';
            //pr("\nreg q=".$query);
            $query = $this->db->query($query);
            $inserted_id=$this->db->insert_id();
            //-----для групового пользователя.Оставлено для совместимости с админкой
            //$query ="INSERT INTO user_group_map (user_id,group_id) VALUES (".$inserted_id.",1)";
            $query = $this->db->query($query);
			return $inserted_id;

		} catch (Exception $e) {
    		log_message('error',$e->getMessage()."\n".
    							"file: ".$e->getFile()."\n".
    							"code: ".$e->getCode()."\n".
    							"line: ".$e->getLine());
    	}
	}
	function update_login_info($table, $id, $date, $ip)
	{
		if(empty($table) || empty($id) || empty($date) || empty($id)) return FALSE;

		try {
			$query = "UPDATE ".$table."_account SET last_login_date = ".clean($date).", last_login_ip = ".clean($ip)." WHERE user_id=".clean($id);
			$query = $this->db->query($query);
			
			if(!$query)
			throw new Exception($this->db->_error_message());

			return true;

		} catch (Exception $e) {
			log_message('error', $e->getMessage().'\n'. $e->getFile().'\n'. $e->getCode());
		}
		return false;
	}

	/**
     * login
     * gets user_id, group_id id user data
     *
     * @param string $table
     * @param string $login
     * @param string $passwd
     * @return array
     * @todo to change these queries to one query
     */
	function login($table, $login, $passwd)
	{
		if(empty($table) || empty($login) || empty($passwd)) return false;

		try {
			$query = "SELECT a.user_id,a.login,a.group_id,a.active
				FROM
					".$table."_account a
				WHERE
					a.login = ".clean($login)." AND a.passwd = '".md5($passwd)."'
				   ";
            $query = $this->db->query($query);
			if(!$query)
			throw new Exception($this->db->_error_message());

			return $query->row();

		} catch (Exception $e) {
			log_message('error', $e->getMessage().'\n'. $e->getFile().'\n'. $e->getCode());
		}
		return false;
	}
    function get_user_by_id($user_id = null)
    {
      if ($user_id == null){return false;}
      $query = "SELECT * FROM user_account WHERE user_id=".clean($user_id);
      $query = $this->db->query($query);

      return $query->row();
    }
    function get_user_by_login($login = null)
    {
      $query = "SELECT * FROM user_account WHERE login=".clean($login)."";
      $query = $this->db->query($query);
      return $query->row();
    }
    function get_user_by_email($email){
      if ($email == null){return false;}
      $query = 'SELECT * FROM user_account WHERE email="'.$email.'"';
      $query = $this->db->query($query);
      if ( ! $query) return FALSE;
      return $query->row();
    }
	function get_users($user_id = null, $login=null, $password=null)
	{
    	$query = "SELECT * FROM user_account ";
    	if($user_id) $query .= ' WHERE user_id='.clean($user_id);
    	
		$query = $this->db->query($query);
	
		if ( ! $query) return FALSE;
		else return $query->result();
    }
    function set_new_psw($user_id,$psw){
      if ($user_id == null){return false;}
      if ($psw == null)    {return false;}
      $query = 'UPDATE user_account SET passwd=md5("'.$psw.'") WHERE user_id='.$user_id;
      $query = $this->db->query($query);
      if ( ! $query) return FALSE;
      return true;
    }
    function profile_save($user_id = null)
    {
        if ($user_id == null){return false;}
        $fio=clean($this->input->post("fio"));
        $tel=clean($this->input->post("tel"));
        $city=clean($this->input->post("city"));
        $email=clean($this->input->post("email"));

        $ip= $_SERVER['REMOTE_ADDR'];
        $query = "UPDATE user_account SET  last_login_ip = ".clean($ip).",fio=".$fio.",tel=".$tel.",city=".$city.",email=".$email." WHERE user_id=".$user_id;
        //pr("\n profile_save query=".$query);
        $query = $this->db->query($query);
       	if(!$query)
		throw new Exception($this->db->_error_message());

		return true;
    }
    function profile_save_psw($user_id = null)
    {
        if ($user_id == null){return false;}
        $pass1=$this->input->post("pass1");
        $pass2=$this->input->post("pass2");
        if (($pass1 ||$pass2) =="" ) {
            return 3;
        }
        if ($pass1 !=$pass2) {
            return 2;
        }
        $query = "UPDATE user_account SET  passwd = '".md5($pass1)."' WHERE user_id=".$user_id;
        $query = $this->db->query($query);
       	if(!$query)
		throw new Exception($this->db->_error_message());

		return 1;
    }
	
}

/* EOF */