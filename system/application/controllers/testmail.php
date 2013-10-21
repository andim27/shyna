<?php
    //  с кроном /usr/local/bin/php -f /usr/home/bideveloper/public_html/shyna/cron.php testmail/getfiles

    require_once(APPPATH.'libraries/Imap_pop.php');
	
    define("MAIL_SERVER", "bizone.com.ua:110"); // 62.149.0.14
    define("MAIL_USENAME", "shinok@bizone.com.ua");
    define("MAIL_PASSWORD", "tefbeFia");
    define("MAIL_SERVICE_FLAGS", "/pop3/notls");
	define("MAIL_MAILBOX", "INBOX");
    define("DS_DATA_BASE", FCPATH.'files/tmp/');
		
	/*
	define("MAIL_SERVER", "station.clickfuel.com:110");
	define("MAIL_USENAME", "nsdata");
	define("MAIL_PASSWORD", "4clickfue");
	define("MAIL_SERVICE_FLAGS", "/pop3/notls");
	define("MAIL_MAILBOX", "INBOX");
	*/
//	$priceMailBoxParser = new PriceMailBoxParser("{localhost:110/pop3/notls}INBOX", "prices@pharm-system.com", "f[evlec");

	class Testmail extends Controller {

		public function getFiles() {
//			$connection = imap_open('{62.149.0.14:110/pop3/notls}INBOX', MAIL_USENAME, MAIL_PASSWORD);
	        $filenames = $this->_grab_mails();
	        echo "<pre>";
	        	print_r($filenames);
	        echo "</pre>";exit;
	    }

	    public function _grab_mails($filters = null) {

	        $imap = new Imap_pop();

        	$config['server']	=		MAIL_SERVER;
        	$config['login'] 	=		MAIL_USENAME;
        	$config['pass'] 	=		MAIL_PASSWORD;
        	$config['service_flags'] =	MAIL_SERVICE_FLAGS;
        	$config['mailbox'] 	=		MAIL_MAILBOX;

        	$connected = $imap->connect_and_count($config);
            echo $imap->IMAP_state;

	        $filenames = array();
	        if($connected) {
	            if ($imap->get_message_count() > 0) {
	                $messages = $imap->get_message_list_overview();
	                if (is_array($messages)) {
	                    $msg_ids = array();
	                    foreach($messages as $message) {
	                        $accepted = true;
	                        if($filters) {
		                        foreach($filters as $key => $filter) {
		                            if(array_key_exists($key, $message)) if(strpos($message->$key, $filter) === false) $accepted = false;
		                        }
	                        }
	                        if($accepted) {
	                            $mail = $imap->grab_email_as_array($message->uid, false);
	                            foreach ($imap->parts_array as $part) {
	                                if (isset($part['attachment']) and $part['attachment']) {
	                                    foreach (array($part['attachment']) as $attach) {
	                                        $filename = $imap->decode_mime_text($attach['filename']);
	                                        if(strpos($filename, '.txt') === false) {
	                                            $fp=fopen(DS_DATA_BASE.$filename, "w+");
	                                            fwrite($fp, $attach['string']);
	                                            fclose($fp);
                                                @chmod(DS_DATA_BASE.$filename, 0777);
	                                            $filenames[] = DS_DATA_BASE.$filename;
	                                        }
	                                    }
	                                }
	                            }
	                            $msg_ids[] = $message->uid;
	                        }

	                    }
	                    $msg_ids = implode(',',$msg_ids);
	                    /*if(DS_KEEP_MAILS == false) {
	                        $imap->delete_and_expunge($msg_ids);
	                    } */
	                }
	            }
	            $imap->close();
	        }
	        else {
//	            $this->_log_error("[grab mails]: unable to connect to mail server");
	            unset($imap);
	            return false;
	        }
	        unset($imap);
	        return $filenames;
	    }
	}


//    $test = new Test;
//    $test -> getFiles();
?>
