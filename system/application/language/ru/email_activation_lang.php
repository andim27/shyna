<?php
$site_name = str_replace('http://','',trim(base_url(),'/'));

$lang['activation_subject'] = "Confirm your registration at $site_name";        
                        
$lang['activation_message'] = '
<p>Hello, %login%.</p>
<p>Thanks for your registration at '.$site_name.'.<br>
To finish it and activate your account please use the link below:<br>
%activation_url%<br>
Hope to hear from you soon again, <br>
'.$site_name.' administration team</p>
';
?>