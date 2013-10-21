<?php

	class User_mdl extends Model
	{
		/**
		 * Constructor of User_mdl
		 *
		 * @access  public
		 */
		function User_mdl() {
			parent::Model();
		}
		
		function get_users($user_id=null,$user_login=null, $per_page=0, $page=1, $with_count = FALSE) {
			$query = "SELECT SQL_CALC_FOUND_ROWS
						ua.*,ug.group_name
					FROM user_account ua
					LEFT JOIN user_group ug ON ug.group_id=ua.group_id
					WHERE 1=1 ";
			if($user_id) $query .= " AND ua.user_id = ".clean($user_id);
			if($user_login) $query .= " AND ua.login='".$user_login."'";

			$query .= " GROUP BY ua.user_id";
		
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			$result = $query->result();
	
			if($with_count) {
				$query = $this->db->query("select found_rows() as count");
				if ( ! $query) return FALSE;
				$result['count'] = $query->row()->count;
			}
			return $result;
		}
       function get_user_by_login($login = null)
        {
          $query = "SELECT * FROM user_account WHERE login=".clean($login)."";
          $query = $this->db->query($query);
          return $query->row();
       }
       function admin_block_user($user_id) {
          $query = "UPDATE user_account SET active=-1 WHERE user_id=".clean($user_id)."";
          $query = $this->db->query($query);
          if (!$query) return FALSE;
          //--SELECT list_id to delete
           $query = "SELECT list_id FROM pricelists WHERE vendor_id in (SELECT id FROM vendor WHERE user_id=".$user_id.")";
           $query = $this->db->query($query);
           $list_arr = $query->result_array();
           //pr("\nadmin_block_user  list_arr=".var_export($list_arr,true));
           if (!empty($list_arr)) {
                $del_str="";
                $L=count($list_arr)-1;
                for ($i=0;$i<=$L;$i++) {
                    if ($i >=$L) {
                       $del_str.=$list_arr[$i]['list_id'];
                    } else {
                       $del_str.=$list_arr[$i]['list_id'].",";
                    }

                }
                //--delete price list
                $query = "DELETE FROM pricelists WHERE list_id IN (".$del_str.")";
                $query = $this->db->query($query);
                //----delete prices-----------------
                $query = "DELETE FROM prices WHERE list_id IN (".$del_str.")";
                $query = $this->db->query($query);
           }
          return true;
       }
       function admin_unblock_user($user_id) {
          $query = "UPDATE user_account SET active=1 WHERE user_id=".clean($user_id)."";
          $query = $this->db->query($query);
          if ( ! $query) return FALSE;
          return true;
       }
       function admin_del_user($user_id) {
         try {
          $this->load->helper("log_helper");
          $query = "DELETE  FROM user_account WHERE user_id=".clean($user_id)."";
          $query = $this->db->query($query);

          //---vendor delete---
          //--SELECT vendor_id to delete
          $query = "SELECT id FROM vendor WHERE user_id =".clean($user_id)."";
          $query = $this->db->query($query);
          $vendor_arr = $query->result_array();

          $query = "DELETE  FROM vendor WHERE user_id=".clean($user_id)."";
          $query = $this->db->query($query);
          //--vendor person delete
          if (!empty($vendor_arr)) {
                $del_str="";
                $L=count($vendor_arr)-1;
                for ($i=0;$i<=$L;$i++) {
                    if ($i >=$L) {
                       $del_str.=$vendor_arr[$i]['id'];
                    } else {
                       $del_str.=$vendor_arr[$i]['id'].",";
                    }

                }
                $query = "SELECT list_id FROM pricelists WHERE vendor_id in (".$del_str.")";
                $query = $this->db->query($query);
                $list_id_arr = $query->result_array();
                if (! empty($list_id_arr)) {
                     $list_del_str="";
                     $L=count($list_id_arr)-1;
                     for ($i=0;$i<=$L;$i++) {
                        if ($i >=$L) {
                            $list_del_str.=$list_id_arr[$i]['list_id'];
                        } else {
                            $list_del_str.=$list_id_arr[$i]['list_id'].",";
                     }

                    }
                }
                //--delete sheets
                $query = "DELETE FROM sheets WHERE list_id IN (".$list_del_str.")";
                $query = $this->db->query($query);
                //--delete vendor_person
                $query = "DELETE FROM vendor_person WHERE vendor_id IN (".$del_str.")";
                $query = $this->db->query($query);

                //--delete price list and prices
                $query = "DELETE FROM pricelists WHERE (pricelists.list_id IN (".$list_del_str."))";
                //$query = "DELETE FROM pricelists LEFT JOIN prices ON pricelists.list_id=prices.list_id WHERE (pricelists.vendor_id IN (".$del_str."))";
                $query = $this->db->query($query);
                //----delete prices-----------------
                $query = "DELETE FROM prices WHERE list_id IN (".$list_del_str.")";
                $query = $this->db->query($query);
                //------------delete vendor  functions------
                $query = "DELETE FROM vendor_functions WHERE vendor_id IN (".$del_str.")";
                $query = $this->db->query($query);
           }
           } catch (Exception $e) {
                $err_str=$e->getMessage()."\n". "file: ".$e->getFile()."\n". "code: ".$e->getCode()."\n". "line: ".$e->getLine();
                log_message('error',$err_str);
                logmes("\n<br>DELETE USER ERROR ".$err_str);
           }
          return true;
       }
       function admin_add_user($data_user=null) {
			if(empty($data)) return -1;
            $login=$data_user['login'];
            $email=$data_user['email'];
            $passwd=$data_user['psw'];
           	$registration_ip = $_SERVER['REMOTE_ADDR'];
			$fio='NULL';
            $tel='NULL';
            $city='NULL';
            $company='NULL';
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
            $this->db->query($query);
            $user_id=$this->db->insert_id();
			return $user_id;
		}
        function update_user($user_id, $user_data) {
			if(empty($user_id)  || empty($user_data)) return FALSE;

			$this->db->where('user_id', $user_id);
			if(!$res = $this->db->update('user_account', $user_data))
			throw new Exception($this->db->_error_message());

			return true;
		}

        function set_user_activity($user_id) {
			if(empty($user_id)) return FALSE;

            $query = "UPDATE user_account
                      SET group_id=if(active_end_date >= CURDATE(),3,1)
                      where user_id = ".$user_id;

            if(!$this->db->query($query))
			    throw new Exception($this->db->_error_message());
 		    return true;
		}
		/**
		 * Destructor of User_mdl 
		 *
		 * @access  public
		 */
		 function _User_mdl() {
		 	
		 }
		
	}
?>
