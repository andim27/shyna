<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BI
 *
 * functions for upload files processing
 *
 * @package		CodeIgniter
 * @author		Michael
 * @copyright	Copyright (c) 2010
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

if (!function_exists('get_file_ext'))
{
     function get_file_ext($filename) {
        $x = explode('.', $filename);
		return $extention = '.'.end($x);
     }

}

if (!function_exists('valid_ext'))
{
     function valid_ext($ext,$ext_type="all"){
	    static $ci;
        if (!is_object($ci)) $ci = &get_instance();


        if ($ext_type == "price") {
            $arr=explode('|', $ci->config->item('price_types'));
        } else {
            $arr=explode('|', $ci->config->item('allowed_types'));
        }
        if (in_array($ext,$arr)) {
            return true;
        }else {
            return false;
        }
     }
}

if (!function_exists('unarchive'))
{
//process zip, rar archives. return valid file (.xls, .xlsx) in dirpath or false

    function unarchive($arcpath, $dirpath, $type){
		log_message('error', "upload helper:  unarchive ");
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
                                $file_ext = get_file_ext($file_in_arc);
                                if (valid_ext($file_ext,"price") == false){
                                   	logmes("upload helper: unarchive: Bad file in zip arc");
                                    return false;
                                }
                                $fp = fopen($dirpath.$file_in_arc, "w+");
								$buf = zip_entry_read($entry, zip_entry_filesize($entry));
								zip_entry_close($entry);
								fwrite($fp, $buf);
								fclose($fp);
                            	logmes("upload helper: unarchive: zip file=".$file_in_arc." $arcpath=".$arcpath );
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
				logmes("upload helper: unarchive: rar_open_error" . $arcpath);
					return FALSE;
				}
				logmes("upload helper: unarchive: rar_open_ok" . $arcpath);
				$entries_list = rar_list($rar_file);
				foreach ($entries_list as $entry) {
					logmes("upload helper: unarchive: entry number mb empty");
					if (!empty($entry->crc)) {
                        $file_in_arc =$dirpath.basename($entry->name);
                        //$this->file_ext=$this->get_file_ext($file_in_arc);
                        $file_ext = get_file_ext($file_in_arc);
                        if (valid_ext($file_ext,"price") == false){
                            logmes("upload helper: unarchive: Bad file in rar arc");
                            return false;
                        }
                        logmes("upload helper: unarchive: rar file_in_arc =" . $file_in_arc );
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
}


