<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name');

//$lang[''] = "";  //for copying

$lang['admin_area'] = "$site_title Admin Area";
$lang['catalog'] = "Catalog";
$lang['delete_category'] = "Delete";
$lang['select_files'] = "Select Files to Upload:";

/* End of file admin_lang.php */
