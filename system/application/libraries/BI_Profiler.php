<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BI_Profiler extends CI_Profiler
{
    function BI_profiler()
    {
        parent::CI_Profiler();
    }
    
	function _log_message($msg, $logname='profiler', $level = 'debug')
	{	
		$filepath = BASEPATH.'logs/'.$logname.'-log-'.date('Y-m-d').EXT; // $this->log_path
		$message  = '';
		
		if ( ! file_exists($filepath))
		{
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}
			
		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
		{
			return FALSE;
		}
	
		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date('y-m-d G:i'). ' --> '.$msg."\n";
		
		flock($fp, LOCK_EX);	
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);
	
		@chmod($filepath, FILE_WRITE_MODE); 		
		return TRUE;
	}
	
	function _html2txt($data)
	{
		$data = strip_tags(html_entity_decode($data)); 
		$data = preg_replace('/([\r\n]){3,}/',"\n\n",$data);
        $data = preg_replace('/[^\r\n\S]+/',' ',$data);
		return $data;
	}

    function run()
    {
          /* $output = <<<ENDJS
<script type="text/javascript" language="javascript" charset="utf-8">
// < ![CDATA[
    $(document).ready(function() {
        var html = $('#codeigniter_profiler').clone();
        $('#codeigniter_profiler').remove();
        $('#debug').hide().empty().append(html).fadeIn('slow');
    });
// ]]>
</script>
ENDJS;
            $output .= "<div id='codeigniter_profiler' style='font-size: 0.7em; clear:both;background-color:#fff;padding:10px;'>";
            $output .= $this->_compile_uri_string();
            $output .= $this->_compile_controller_info();
            $output .= $this->_compile_memory_usage();
            $output .= $this->_compile_benchmarks();
            $output .= $this->_compile_get();
            $output .= $this->_compile_post();
            $output .= $this->_compile_queries();
            $output .= '</div>';
            return $output; */
			
          	$logname = 'profiler-'.str_replace('/','-',$this->CI->uri->uri_string);
			$this->_log_message('', $logname);
			$this->_log_message("<<<<<<<<<-----------------------", $logname);
            $output = $this->_compile_uri_string();
            $output .= $this->_compile_controller_info();
            $output .= $this->_compile_memory_usage();
            $output .= $this->_compile_benchmarks();
            $output .= $this->_compile_get();
            $output .= $this->_compile_post();
			
            $output = $this->_html2txt($output);
            $with_sessions_queries = TRUE;
            $output .= $this->_compile_queries($with_sessions_queries);
			
			$this->_log_message($output, $logname);
            $this->_log_message("------------------------>>>>>>>>>", $logname);
            
    }
    
	function _compile_queries($with_sessions_queries=FALSE)
	{
		$output = '';
		//$output = "\n\n QUERIES ".count($this->CI->db->queries)."\n\n";
		/*
            $output .= " ################# ";
			$output .= var_export($this->CI->db->queries,TRUE);
            $output .= " ################# ";
            $output .= var_export($this->CI->db->query_times,TRUE);
            */
		$sess_n = 0;
		foreach ($this->CI->db->queries as $i=>$query)
		{
			if (strstr($this->CI->db->queries[$i],'ci_sessions')) 
			{
				$sess_n++;
				if ( ! $with_sessions_queries) continue;
			}
			$q = "[".($i+1)."]\t";
			$q .= $this->CI->db->query_times[$i];
			$q .= "\n";
			$q .= $this->CI->db->queries[$i];
			$q .= "\n\n";
			$output .= $q;
			if (ceil($this->CI->db->query_times[$i])>3) 
			{
				$q = "\n\n".$this->_html2txt($this->_compile_controller_info())."\t".$q;
				$this->_log_message($q, 'slow-queries-3');
			}
			elseif (ceil($this->CI->db->query_times[$i])>2) 
			{
				$q = "\n\n".$this->_html2txt($this->_compile_controller_info())."\t".$q;
				$this->_log_message($q, 'slow-queries-2');
			}
			elseif (ceil($this->CI->db->query_times[$i])>1) 
			{
				$q = "\n\n".$this->_html2txt($this->_compile_controller_info())."\t".$q;
				$this->_log_message($q, 'slow-queries-1');
			}
		}
		$output = "\n\n QUERIES ".(count($this->CI->db->queries)-$sess_n)." + ".$sess_n." to ci_sessions\n\n
				  ".$output;
		return $output;
	}
	
	function _compile_queries_CI()
	{
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}
					
		if (count($dbs) == 0)
		{
			$output  = "\n\n";
			$output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
			$output .= "\n";
			$output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').'&nbsp;&nbsp;</legend>';
			$output .= "\n";		
			$output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";
			$output .="<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>".$this->CI->lang->line('profiler_no_db')."</td></tr>\n";
			$output .= "</table>\n";
			$output .= "</fieldset>";
			
			return $output;
		}
		
		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

		$output  = "\n\n";
			
		foreach ($dbs as $db)
		{
			$output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
			$output .= "\n";
			$output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_database').':&nbsp; '.$db->database.'&nbsp;&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').': '.count($this->CI->db->queries).'&nbsp;&nbsp;&nbsp;</legend>';
			$output .= "\n";		
			$output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";
		
			if (count($db->queries) == 0)
			{
				$output .= "<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>".$this->CI->lang->line('profiler_no_queries')."</td></tr>\n";
			}
			else
			{				
				foreach ($db->queries as $key => $val)
				//foreach ($this->CI->db->queries as $key => $val)
				{					
					$time = number_format($db->query_times[$key], 4);

					$val = highlight_code($val, ENT_QUOTES);
	
					foreach ($highlight as $bold)
					{
						$val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);	
					}
					
					$output .= "<tr><td width='1%' valign='top' style='color:#990000;font-weight:normal;background-color:#ddd;'>".$time."&nbsp;&nbsp;</td><td style='color:#000;font-weight:normal;background-color:#ddd;'>".$val."</td></tr>\n";
				}
			}
			
			$output .= "</table>\n";
			$output .= "</fieldset>";
			
		}
		
		return $output;
	}
    
}

/* End of file BI_Profiler.php */
/* Location:  */