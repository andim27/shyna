<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['upload_path'] = dirname(BASEPATH).'/files/';
$config['allowed_types'] = 'doc|docx|xls|xlsx|csv|txt|zip|rar';
$config['max_size'] = 20480;  // KB
$config['remove_spaces'] = TRUE;
$config['overwrite'] = false;
$config['rename'] = false;
$config['price_types'] = 'xls|xlsx'; //BI
$config['upload_url'] = 'files/';  //BI

/* End of file */ 
