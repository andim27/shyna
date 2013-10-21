<?php
  function logmes($msg, $var='#none', $filename='', $level = 'debug')
 {
  static $n = 0;

  if (empty($filename)) {
   static $ci;
      if (!is_object($ci)) $ci = &get_instance();
   $filename = 'log-'.str_replace('/','-',$ci->uri->uri_string);
  }
  $filepath = BASEPATH."logs/$filename-".date('Y-m-d').EXT; // $this->log_path
  $message  = '';

  if ( ! file_exists($filepath))
  {
   $message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
  }

  if ( ! $fp = @fopen($filepath, "a+")) /* @fopen($filepath, FOPEN_WRITE_CREATE) */
  {
   return FALSE;
  }

  if ($n++==0) $message .= "\n-------------------------------------------------------\n\n";
  if ($var!='#none') $msg .= var_export($var,true);
  $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date('y-m-d G:i'). ' --> '.$msg."\n";

  flock($fp, LOCK_EX);
  fwrite($fp, $message);
  flock($fp, LOCK_UN);
  fclose($fp);

  @chmod($filepath, FILE_WRITE_MODE);
  return TRUE;
 }
?>
