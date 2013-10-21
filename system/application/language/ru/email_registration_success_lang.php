<?php
$site_name = str_replace('http://','',trim(base_url(),'/'));

$lang['reg_success_subject'] = "Registration at $site_name successfull.";       
                        
$lang['reg_success_message'] = '
<p>Dear %login%,<br />
Your registration was successfull and your account currently active. <br />
Please use the following information to access our site at <a href="'.base_url().'">'.$site_name.'</a>:</p>
<p>Username: %login%<br />
Password:   %password%</p>
<p>After initial login you can procceed to your account management page and 
change your password there.</p>
Hope to hear from you soon again, <br>
'.$site_name.' administration team</p>
';

?>