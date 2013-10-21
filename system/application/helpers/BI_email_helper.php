<?php
	function send_email($email, $subject, $message, $config=array(),$attachments=array())
    {
    	static $ci;
	    if (!is_object($ci)) $ci = &get_instance();
    
        //$ci->load->library('email');
        if (isset($ci->load->_ci_classes['email']) && ($ci->load->_ci_classes['email'] == 'email')) {
			$ci->email->initialize($config);
		} else {
			$ci->load->library('email', $config);
		}
        //$ci->config->load('email');
        /*
        if ( ! empty($config)) 
        {
        	$ci->email->initialize($config);
        }
        */ 
        $ci->email->clear(TRUE);      
        $attachments = is_array($attachments) ? $attachments : array($attachments);
    	foreach ( $attachments as $att) 
        {
        	$ci->email->attach($att);
        }

        $ci->email->from($ci->config->item('admin_email'), $ci->config->item('site_name'));
		//$ci->email->from('Shyna');
        $ci->email->to($email);
        $ci->email->subject($subject);
        $ci->email->message($message);
        
        //return $this->email->print_debugger();
        return $ci->email->send();
    }
	function send_email_feedback($email, $subject, $message, $config=array(),$attachments=array())
    {
    	static $ci;
	    if (!is_object($ci)) $ci = &get_instance();
    
        if (isset($ci->load->_ci_classes['email']) && ($ci->load->_ci_classes['email'] == 'email')) {
			$ci->email->initialize($config);
		} else {
			$ci->load->library('email', $config);
		}
        $ci->email->clear(TRUE);      
        $attachments = is_array($attachments) ? $attachments : array($attachments);
    	foreach ( $attachments as $att) 
        {
        	$ci->email->attach($att);
        }
        
       
        $ci->email->from($email);
        $ci->email->to($ci->config->item('admin_email'));
        $ci->email->subject($subject);
        $ci->email->message($message);
        
        //return $this->email->print_debugger();
        return $ci->email->send();
    }
?>
