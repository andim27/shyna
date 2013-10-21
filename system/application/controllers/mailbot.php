<?php
    //  с кроном /usr/local/bin/php -f /usr/home/bideveloper/public_html/shyna/cron.php mailbot/work

    require_once(APPPATH.'libraries/Imap_pop.php');

    define("MAIL_SERVER", "62.149.0.14:110"); // 62.149.0.14    bizone.com.ua:110
    define("MAIL_USENAME", "shinok@bizone.com.ua");
    define("MAIL_PASSWORD", "tefbeFia");
    define("MAIL_SERVICE_FLAGS", "/pop3/notls");
	define("MAIL_MAILBOX", "INBOX");
    define("DS_DATA_TMP", FCPATH.'files/tmp/');
    define("DS_DATA_ARC",  FCPATH.'files/arc/');
   	define("GET_BY_EMAIL", 'email');


	class mailBot extends Controller {
	  	private $stat_arr = array();
	  	private $letter_arr = array();
        private $valid_all_arr=array("zip","rar","xls","xlsx");
        private $valid_price_arr=array("xls","xlsx");
        private $vendor_id;
        private $file_ext;
        protected $cii;
        protected $config_mail;
        protected $statistics;

        function mailBot() {
           	parent::Controller();
            $this->load->model("vendor_mdl");
            $this->load->helper("log_helper");
            $this->config_mail['server']    	= MAIL_SERVER;
        	$this->config_mail['login']      	= MAIL_USENAME;
        	$this->config_mail['pass'] 	        = MAIL_PASSWORD;
        	$this->config_mail['service_flags'] = MAIL_SERVICE_FLAGS;
        	$this->config_mail['mailbox'] 	    = MAIL_MAILBOX;
        }
        function index() {
            $this->work();
        }
        public function stat() {
            $this->config->load('upload');
            $conf =& get_config();
            $ci = &get_instance();
            $log_path = ($conf['log_path'] != '') ? $conf['log_path'] : BASEPATH.'logs/';
            $filename = 'log--mailbot';
            $filepath = BASEPATH."logs/$filename-".date('Y-m-d').EXT;

            $imap = new Imap_pop();
        	$connected = $imap->connect_and_count($this->config_mail);
            $messages=null;
            if($connected) {
              $messages = $imap->get_message_list_overview();
            }
            $file_list = scandir(FCPATH.'/files');
            echo "<br> Mail state:".$imap->IMAP_state;
            echo "<br>Mails in mailbox:".$imap->get_message_count();
            echo "<br>Messages in mailbox:".var_export($messages,true);
            echo "<br>Files in files:".var_export($file_list,true);
            echo "<br>Files path:".$this->config->item('upload_path');
            echo "<br>admin_email=".$conf['admin_email']."<br>";
            echo "<br>------------------Log----------------------<br>";
            echo "<br>Log file=".$filepath."<br>";
            logmes("\n<br>MAIL_BOT  STAT-----------------");
            echo implode('\n', file($filepath));
            unset($imap);
        }
        public function work() {
            $config = $this->config->load('upload');
            $imap = new Imap_pop();
        	$connected = $imap->connect_and_count($this->config_mail);
            if($connected) {
              $filenames = array();
              $messages = $imap->get_message_list_overview();
              if ($imap->get_message_count() > 0) {
                  foreach($messages as $index_mess=>$message) {
                    $account = $imap->decode_mime_text($message->from);
					$account = str_replace(array('"', "'", " "), "", $account);
					$account = strstr($account, "&lt;");
					$subject = str_replace(array('&lt;', "&gt", ";"), "", $account);
				    //	$isset_subject = $this->get_account_email($subject);
                    //logmes("MAIL_BOT  work subject=".var_export($subject,true));
					$isset_subject = $this->vendor_mdl->get_account_email($subject);
                    if($isset_subject) {
                            $vendor_id = $isset_subject->vendor_id;
                            $this->vendor_id=$vendor_id;
                            $vendor_name=$this->vendor_mdl->get_vendor_name_by_vendor_id($vendor_id);
                            logmes("\n<br>MAIL_BOT  vendor_name=",$vendor_name);
                            $vendor_name=$vendor_name[0]->name;
                            //$messages = $imap->get_message_list_overview();
                            //$msg_ids = array();
	                        $cur_mail_id=$message->uid;
                            //logmes("MAIL_BOT  work cur_mail_id=".$cur_mail_id);
                            $file_price=$this->write_price($imap,$message);
                            $pars_res =$this->pars_file($file_price);
                            if (! empty($pars_res)) {
                                array_push($this->stat_arr,array(
													'vendor_id' => $vendor_id,
													'vendor_name' => $vendor_name,
													'file_ext' => $this->file_ext,
													'all_rec' => (empty($pars_res->count_all))?" ? ":$pars_res->count_all,
													'bad_rec' => (!isset($pars_res->count_not_null))?" ? ":$pars_res->count_not_null,
													'type_getting' => GET_BY_EMAIL
												));

                                array_push($this->letter_arr,$cur_mail_id);
                            }

                    } else {
                       continue;//unset($imap);
                       }
              }//--for
              if (! empty($pars_res)) {
                 $this->send_report($imap,$cur_mail_id);
              }
            } else {  unset($imap); }

        }
      }
     //-------------------------------------------------------------------------
     function get_file_ext($filename) {
        $x = explode('.', $filename);
		return $extention = '.'.end($x);
     }
     function valid_ext($ext,$ext_type="all"){
        if ($ext_type == "price") {
            $arr=$this->valid_price_arr;
        } else {
            $arr=$this->valid_all_arr;
        }
        if (in_array($ext,$arr)) {
            return true;
        }else {
            return false;
        }
     }
     //-------------------------------------------------------------------------
     function write_price($imap,$message) {
              $mail = $imap->grab_email_as_array($message->uid, false);
              //logmes("MAIL_BOT  write_price ".var_export($mail,true));
              $this->config->load('upload');
              //logmes("MAIL_BOT  config->load('upload') ");
              foreach ($imap->parts_array as $part) {
                  if (isset($part['attachment']) and $part['attachment']) {
	                  foreach (array($part['attachment']) as $attach) {
	                       $filename = $imap->decode_mime_text($attach['filename']);
                           $filename = DS_DATA_TMP.$filename;
                           $this->file_ext=$this->get_file_ext($filename);//$extention;
                           logmes("MAIL_BOT  write_price   filename=".$filename);
                           if( $this->valid_ext($this->file_ext,"all") === true) {
                              $type =$this->file_ext;
                              if (($type == 'zip') || ($type == 'rar')) {
                                  $filename=$this->unArhive($filename,DS_DATA_TMP,$type);
	                          }
                              if (! empty($filename )) {
                                  $fp=fopen($filename, "w+");
	                              fwrite($fp, $attach['string']);
	                              fclose($fp);
                                  @chmod($filename, 0777);
	                              $filenames[] = $filename;
                                  $file_name_new=$this->config->item('upload_path').$this->vendor_id.$this->file_ext;
                                  if (file_exists($file_name_new)) {unlink($file_name_new);}
                                  rename(DS_DATA_TMP.$filename, $file_name_new);
                                  logmes("MAIL_BOT  written to   file_name_new=".$file_name_new);
                              }
                           }
	                  }
	              }
	          }//-for
	          //$msg_ids[] = $message->uid;
   }
   //------------------------------------------------
   function pars_file($file_price) {
     //logmes("MAIL_BOT  start pars file ".$file_price);
   	 try {
       $res = modules::run('parser_mod/parser_ctr/parse_price', $this->vendor_id,$this->file_ext);
       if (! empty($res)) {
            $stat= modules::run('parser_mod/parser_ctr/getPriceStat',$this->vendor_id);
            logmes("MAIL_BOT  stat= ".var_export($stat,true));
            return $stat;
       }
       //logmes("MAIL_BOT  END pars file ".$res);
     }catch (Exception $e) {
        logmes("MAIL_BOT ERROR pars file ".$file_price);
     }
     return $res;
   }
   function send_report($imap,$cur_mail_id) {
       $data['items']=$this->stat_arr;
       $mes=$this->load->view("prices_parsed",$data,true);
       $this->load->helper('email');
	   //$this->load->helper('bi_email_helper');
       $subject="Произведен разбор прайсов";
       $email=$this->config->item('admin_email');
       if (! send_email($email, $subject, $mes)) {
           logmes("MAIL_BOT  email do not send");
       } else {
          //$imap->delete_and_expunge($cur_mail_id);
          logmes("MAIL_BOT deleted ".count($this->letter_arr));
          foreach ($this->letter_arr as $letter_id) {
             $imap->delete_and_expunge($letter_id);
          }
       }
   }

    function unArhive($arcpath, $dirpath, $type){
		log_message('error', " MAIL_BOT unArhive");
		if (!file_exists($arcpath) || !is_file($arcpath)) {
			return FALSE;
		}
		//if (file_exists($dirpath) && is_dir($dirpath)) {
		//	removeDirRec($dirpath);
		//}
		//mkdir($dirpath, 0777);
		switch($type) {
			case 'zip':
				$zip_file = zip_open($arcpath);
                if ($zip_file) {
					while ($entry = zip_read($zip_file)) {
						if (zip_entry_filesize($entry) > 0) {
							if (zip_entry_open($zip_file, $entry, "r")) {
								$file_in_arc = basename(zip_entry_name($entry));
                                $file_ext=$this->get_file_ext($file_in_arc);
                                if ($this->valid_ext($file_ext,"price") == false){
                                   	logmes("MAIL_BOT Bad file in zip arc");
                                    return false;
                                }
                                $fp = fopen($dirpath.$file_in_arc, "w+");
								$buf = zip_entry_read($entry, zip_entry_filesize($entry));
								zip_entry_close($entry);
								fwrite($fp, $buf);
								fclose($fp);
                            	logmes("MAIL_BOT zip file=".$file_in_arc." $arcpath=".$arcpath );
                                return $file_in_arc;
							}
						}
					}
				} else {
					return FALSE;
				}
				break;
			case 'rar':
                /*
                $file_from_arc=$arcpath;
                $file_to=$dirpath.$this->vendor_id.".".$this->file_ext
                exec("/usr/local/bin/unrar p -inul ".$file_from_arc." > ". $file_to);//,$aout,$rout); print_r($aout); echo($rout."\n");
				log_message('error', " MAIL_BOT rar file".$arcpath." file_to=".$file_to);
                */

                $rar_file = rar_open($arcpath);
				if ($rar_file === FALSE) {
				    logmes("MAIL_BOT  rar_open_error" . $arcpath);
					return FALSE;
				}
				logmes(" rar_open_ok" . $arcpath);
				$entries_list = rar_list($rar_file);
				foreach ($entries_list as $entry) {
					logmes("MAIL_BOT  entry number mb empty");
					if (!empty($entry->crc)) {
                        $file_in_arc =$dirpath.basename($entry->name);
                        //$this->file_ext=$this->get_file_ext($file_in_arc);
                        $file_ext=$this->get_file_ext($file_in_arc);
                        if ($this->valid_ext($file_ext,"price") == false){
                            logmes("MAIL_BOT Bad file in zip arc");
                            return false;
                        }
                        logmes(" MAIL BOT rar file_in_arc =" . $file_in_arc );
                        $entry->extract(realpath('.'), $file_in_arc);
                        return $file_in_arc;
					}
				}

				break;
			default:
				return FALSE;
				break;
		}
		return TRUE;
	}
        //-----------------------------------------------------------------------
   /*     function get_account_email($account_email) {
			$query = "SELECT  va.*,v.name as vendor_name FROM vendor_accounts as va,vendor as v WHERE va.account_email='{$account_email}' and va.vendor_id=v.id";
            if(!$this->db->query($query))
			throw new Exception($this->ci->db->_error_message());

			$query = $this->cii->db->query($query);
			if ( ! $query) return FALSE;
			return $query->row();
		}
*/



}//---class

?>
