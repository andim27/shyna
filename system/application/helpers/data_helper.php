<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Data manipulation Helpers
 *
 * @category	Helpers
 * @author		Warg
 */

// ------------------------------------------------------------------------

if (!function_exists('filter_data'))
{
	function filter_data($keys, $data)
	{
		if (is_array($keys) && is_array($data))
		{
		    $result = array();
            foreach($keys as $key) {
                if(isset($data[$key])) $result[$key] = $data[$key];
            }
            return $result;
		}
		else return false;
	}	
}

/* EOF */