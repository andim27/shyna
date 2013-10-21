<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * dateHuman_str converts date to human string
 *
 * @package		CodeIgniter
 * @author		AndMak
 * @copyright	Copyright (c) 2010
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
if ( ! function_exists('dateHuman_str'))
{
    function dateHuman_str($d){
      $CI =& get_instance();
      $dt_arr = getdate(strtotime($d));
      $day    = $dt_arr['mday'];
      $month  = $dt_arr['mon'];
      $year   = $dt_arr['year'];
      $month_str="<span id='month'>".lang("month_".$month)."</span>";
      $out_str=" ".$day." ".$month_str." ".$year;
    return $out_str;
}
}
function getValueById($items,$id) {
  foreach ($items as $item) {
    if ($item->id == $id) {return $item->value;}
  }
  return "";
}
function getKeyByValue($items,$value) {
  foreach ($items as $key => $val) {
    if ($val == $value) {return $key;}
  }
  return "";
}
function getIdByValue($items,$value) {
  foreach ($items as $item) {
    if ($item->value == $value) {return $item->id;}
  }
  return "";
}
function getPriceByCuorse($p_in,$course_val,$cur_in=1,$cur_out=1) {
  //number_format($item->price/(empty($item->course)?1:$item->course), 2, '.', '')
  $out_str="";
  $dg=2;//for rus
/* now price comes already recalculated
  if ($cur_in == 1) {
     $out_str=number_format($p_in/(empty($course_val)?1:$course_val),  $dg, '.', '');
  } else {
     $out_str=number_format($p_in*(empty($course_val)?1:$course_val),  $dg, '.', '');
  }
*/
  $out_str=number_format($p_in,  $dg, '.', '');
  $out_str=intval($out_str);
return strval($out_str);
}
function cache_clear() {
      Header("Expires: Mon, 26 Jul 1990 05:00:00 GMT"); //Дата в прошлом
      Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
      Header("Pragma: no-cache"); // HTTP/1.1
      Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
}
function pr($v){
     $f=@fopen("d:/mylog.txt","a+");
     @fwrite($f,$v);
     @fclose($f);
}


