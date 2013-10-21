<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class jq_pagination {

	var $base_url			= ''; // The page we are linking to
	var $total_rows  		= ''; // Total number of items (database results)
	var $per_page	 		= 10; // Max number of items you want shown per page
	var $num_links			=  3; // Number of "digit" links to show before/after the currently viewed page
	var $cur_page	 		=  1; // The current page being viewed
	var $first_link   		= '&lsaquo;&lsaquo; ...';
	var $next_link			= 'Дальше&gt;';
	var $prev_link			= '&lt;Назад';
	var $last_link			= '... &rsaquo;&rsaquo;';
	var $uri_segment		= 3;
	var $full_tag_open		= '';
	var $full_tag_close		= '';
	var $first_tag_open		= '';
	var $first_tag_close	= '&nbsp;';
	var $last_tag_open		= '&nbsp;';
	var $last_tag_close		= '';
	var $cur_tag_open		= '&nbsp;<strong>';
	var $cur_tag_close		= '</strong>';
	var $next_tag_open		= '&nbsp;';
	var $next_tag_close		= '&nbsp;';
	var $prev_tag_open		= '&nbsp;';
	var $prev_tag_close		= '';
	var $num_tag_open		= '&nbsp;';
	var $num_tag_close		= '';
	var $page_query_string	= FALSE;
	var $query_string_segment = 'per_page';

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	function jq_pagination($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}

		log_message('debug', "Pagination Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	function create_links()
	{
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);
        // pr("\n\n total_rows=".$this->total_rows."  num_pages=".$num_pages);
		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{
			return '';
		}

		// Determine the current page number.
		$CI =& get_instance();



		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = 1;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->cur_page > $this->total_rows)
		{
			$this->cur_page = $num_pages;
		}

		$uri_page_number = $this->cur_page;
		//////$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed


  		// And here we go...
		$output = '';
        //pr("\n\n cur_page=".$this->cur_page."  num_links=".$this->num_links);
		// Render the "First" link
		if  ($this->cur_page > ($this->num_links + 1))
		{
			$output .= '<a class="pagLink" id="pg_first_id" href="javascript:pageShow(1)">'.$this->first_tag_open.$this->first_link.$this->first_tag_close.'</a>';

		}

		// Render the "previous" link
		if  ($this->cur_page != 1)
		{
			//$i = $uri_page_number - $this->per_page;
			$i = $this->cur_page-1;
			if ($i == 0) $i = 1;
			$output .= $this->prev_tag_open.'<a class="pagLink" id="pd_previous_id" href="javascript:pageShow('.$i.')">'.$this->prev_link.'</a>'.$this->prev_tag_close;
		}

		// Write the digit links
		for ($loop = $start; $loop <= $end; $loop++)
		{
			//$i = ($loop * $this->per_page) - $this->per_page;
           	$i = $loop;
            // pr("\n\n loop i=".$i."  loop=".$loop." cur_page=".$this->cur_page);
			if ($i >= 0)
			{
				if ($this->cur_page == $loop)
				{
					$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
				}
				else
				{
					$n = ($i == 0) ? '1' : $i;
					//--was--$output .= $this->num_tag_open.'<a  href="'.$this->base_url.$n.'">'.$loop.'</a>'.$this->num_tag_close;
					$output .= $this->num_tag_open.'<a class="pagLink" id="pg_cur_id_'.$n.'" href="javascript:pageShow('.$loop.')">'.$loop.'</a>'.$this->num_tag_close;
				}
			}
		}

		// Render the "next" link
		if ($this->cur_page < $num_pages)
		{
           	$i = $this->cur_page + 1;
            $output .= $this->next_tag_open.'<a class="pagLink" id="pg_next_id" href="javascript:pageShow('.$i.')">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if (($this->cur_page + $this->num_links) < $num_pages)
		{
			//$i = (($num_pages * $this->per_page) - $this->per_page);
           	$i = $num_pages;
		   	$output .= $this->last_tag_open.'<a class="pagLink" id="pg_last_id" href="javascript:pageShow('.$i.')">'.$this->last_link.'</a>'.$this->last_tag_close;
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		return $output;
	}
}
// END Pagination Class

/* End of file Pagination.php */
/* Location: ./system/libraries/Pagination.php */