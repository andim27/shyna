<?php
	require_once(MODBASE.modules::path()."/controllers/Parser.php");
	
	define("MAIL_SERVER", "62.149.0.14:110"); // 62.149.0.14
	define("MAIL_USENAME", "shinok@bizone.com.ua");
	define("MAIL_PASSWORD", "tefbeFia");
	define("MAIL_SERVICE_FLAGS", "/pop3/notls");
	define("MAIL_MAILBOX", "INBOX");
	
	define("GET_BY_EMAIL", 'email');
	
	/**
	 * Class priceAccount
	 *
	 * This class grabbers of mails, gets attaches and saves to file dirrectory
	 *
	 * @author   Popov
	 * @access   public
	 * @package  priceAccount.class.php
	 * @created  Fri Oct 15 14:58:00 EEST 2010
	 */
	class priceAccount extends Parser {
		
		private $statistics = array();
		private $ext_allowed = array('.xls', '.xlsx', '.cvs');
		private $account_email = MAIL_USENAME;
		private $account_password = MAIL_PASSWORD;
		
		/**
		 * Constructor of priceAccount
		 *
		 * @access  public
		 */
		function priceAccount() {
			parent::Parser();
		}
	
		public function parseAccounts() {
			try {
				$config = array(
					'login' => $this->account_email,
					'pass' => $this->account_password
				);
				$this->grab_mails($config);
				
				return true;				
			} catch (Exception $e) {
				$this->setError($e, __FUNCTION__);
			}
		}
		
		public function getStatistics() {
			return $this->statistics;
		}
		
		private function grab_mails($account_config) {
			$imap = new Imap_pop();
			
			$config = $this->ci->load->config('upload');
			
			if($account_config != null) {
	        	$account_config['server'] 			= MAIL_SERVER;
	        	$account_config['service_flags'] 	= MAIL_SERVICE_FLAGS;
	        	$account_config['mailbox'] 			= MAIL_MAILBOX;
	        }	        
        	$connected = $imap->connect_and_count($account_config);
			if($connected) {
				if ($imap->get_message_count() > 0) {
					$messages = $imap->get_message_list_overview();					
					if (is_array($messages)) {
						$msg_ids = array();
						foreach($messages as $index_mess=>$message) {
							$account = $imap->decode_mime_text($message->from);
							$account = str_replace(array('"', "'", " "), "", $account);
							$account = strstr($account, "&lt;");
							$subject = str_replace(array('&lt;', "&gt", ";"), "", $account);
							$isset_subject = $this->get_account_email($subject);
							if($isset_subject) {
								$vendor_id = $isset_subject->vendor_id;
								$mail = $imap->grab_email_as_array($message->uid, false);
								foreach ($imap->parts_array as $part) {
									if (isset($part['attachment']) and !empty($part['attachment'])) {
										foreach (array($part['attachment']) as $index=>$attach) {
											$filename = $imap->decode_mime_text($attach['filename']);
											$x = explode('.', $filename);
											$extention = '.'.end($x);
											if(in_array($extention, $this->ext_allowed) === true) {
												$fp = fopen($config['upload_path'].$filename, "w+");
												fwrite($fp, $attach['string']);
												fclose($fp);
		
												rename($config['upload_path'].$filename, $config['upload_path'].$vendor_id.$extention);
		
												$statistics = (Object)array(
													'vendor_id' => $vendor_id,
													'file_ext' => $extention,
													'type_getting' => GET_BY_EMAIL
												);
												if(!in_array($statistics, $this->statistics)) {
													$this->setStatistics($statistics);
												}
											}
										}
									}
								}
								$msg_ids[] = $message->uid;
							}							
						}
						$msg_ids = implode(',',$msg_ids);
					}
				}
			}	             
		}
		
		private function grab_mails_old($vendor_id, $account_config) {
        
	        $imap = new Imap_pop();
	        
			$config = $this->ci->load->config('upload');
	        
	        if($account_config != null) {
	        	$account_config['server'] 			= MAIL_SERVER;
	        	$account_config['service_flags'] 	= MAIL_SERVICE_FLAGS;
	        	$account_config['mailbox'] 			= MAIL_MAILBOX;
	        }	        
        	$connected = $imap->connect_and_count($account_config);
	        $filenames = array();
	        if($connected) {
	            if ($imap->get_message_count() > 0) {
	                $messages = $imap->get_message_list_overview();
	                if (is_array($messages)) {
	                    $msg_ids = array();
	                    foreach($messages as $index_mess=>$message) {
//	                        $accepted = true;
	                        /*if($filters) {
		                        foreach($filters as $key => $filter) {
		                            if(array_key_exists($key, $message)) if(strpos($message->$key, $filter) === false) $accepted = false;
		                        }
	                        }*/
//	                        if($accepted) {
	                            $mail = $imap->grab_email_as_array($message->uid, false);
	                            foreach ($imap->parts_array as $part) {
	                                if (isset($part['attachment']) and !empty($part['attachment'])) {
	                                    foreach (array($part['attachment']) as $index=>$attach) {	                                    	
	                                        $filename = $imap->decode_mime_text($attach['filename']);
	                                        $x = explode('.', $filename);
	                                        $extention = '.'.end($x);
	                                        if(in_array($extention, $this->ext_allowed) === true) {	                                        
	                                            $fp = fopen($config['upload_path'].$filename, "w+");
	                                            fwrite($fp, $attach['string']);
	                                            fclose($fp);
	                                            	                                            
	                                            rename($config['upload_path'].$filename, $config['upload_path'].$vendor_id.$extention);	                                            
	                                            
	                                            $statistics = (Object)array(
	                                            	'vendor_id' => $vendor_id,
	                                            	'file_ext' => $extention,
	                                            	'type_getting' => GET_BY_EMAIL
	                                            );
	                                            if(!in_array($statistics, $this->statistics)) {
	                                            	$this->setStatistics($statistics);
	                                            }
	                                        }
	                                    }
	                                }
	                            }
	                            $msg_ids[] = $message->uid;
//	                        }
	                        
	                    }
	                    $msg_ids = implode(',',$msg_ids);
	                    /*if(DS_KEEP_MAILS == false) {
	                        $imap->delete_and_expunge($msg_ids);
	                    }*/
	                }
	            }
	            $imap->close();
	        }
	        else {
	        	$this->setError("unable to connect to mail server", __FUNCTION__);
	            unset($imap);
	            return false;
	        }
	        unset($imap);
	        return true; 
	    }

	    function get_account_email($account_email) {
			$query = "SELECT * FROM vendor_accounts WHERE account_email='{$account_email}'";
			if(!$this->ci->db->query($query)) 
			throw new Exception($this->ci->db->_error_message());
			
			$query = $this->ci->db->query($query);
			if ( ! $query) return FALSE;
			return $query->row();
		}
	    
	    private function setStatistics($statistics) {
	    	$this->statistics[] = $statistics;
	    }	    
	}

//End of priceAccount.php 
