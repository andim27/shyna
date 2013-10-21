<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['pages'] = "Pages: ";

$lang['search'] = "search";

$lang['mod_state_new'] = 'New';
$lang['mod_state_approved'] = 'Approved';
$lang['mod_state_featured'] = 'Featured';
$lang['mod_state_declined'] = 'Declined'; 

$lang['registration'] = "Registration";
$lang['reg_step_1'] = "Step 1: Pick your username and supply email address";
$lang['username_field'] = "Pick your preffered username:";
$lang['email_field'] = "Email address:";
$lang['reg_step_2'] = "Step 2: Activation email sent";
$lang['activation_sent'] = "Please check your email for activation email we sent to you, and use supplied link to confirm your registration.";
$lang['reg_step_3'] = "Step 3: Activation successfull";
$lang['start_using_account'] = "Your account now active and you can start using it.
  Please check your email for access information we sent to you. 
  Don't forget to keep your access information safe from the others.";
$lang['collect_information_for_profile'] = "Now, we want to collect some additional information for your profile on our site.";

$lang['error_login_empty']  = "Login or password empty!";
$lang['error_login_wrong']  = "Wrong login or password!";
$lang['error_login_used']  = "This username already taken. Please pick another one";

$lang['login_input']  = "Enter username";
$lang['email_input']  = "Enter email";
$lang['error_email_format']  = "Email format invalid";
$lang['error_email_used']  = "Email already used. May be you <a href='/forgot-password'>forgot your password?</a>";
$lang['banned']  = "This account banned!";
$lang['not_activated']  = "Account not activated yet! Please follow the link from the activation email first.";
$lang['confirm_reg'] = "Confirm your registration at $site_title";
$lang['error_data_saving'] = "Problem occured with your data saving. Please try again latter or contact site admin at $admin_email";
$lang['error_activation_link'] = "Invalid activation link. Please use activation link from activation email sent to you";
$lang['error_activation_info'] = "Wrong activation info. If you have already activated your account check your email for password sent to you, or contact site admin at $admin_email";
$lang['reg_success'] = "Registration at $site_title successfull";

$lang['error_activation_process'] = "There were errors during your activation process. Please contact site admin at $admin_email";

$lang['email_changed_adm'] = "Your email was changed by administrator";
$lang['email_changed'] = "Email changed";
$lang['email_change_requested'] = "You have requested email change";
$lang['confirm_email_sent'] = "Confirmation email was sent to your new address. Please use supplied link to confirm change.";
$lang['error_confirmation_link'] = "Invalid confirmation link. Please use confirmation link from confirmation email sent to you";
$lang['error_confirmation_info'] = "Wrong confirmation info. May be you have confirmed your change already?";
$lang['confirm_passw'] = "Please confirm password";
$lang['error_passw_mismatch'] = "Passwords mismatch";
$lang['passw_changed'] = "Password changed";
$lang['access_info_changed'] = "Access information changed";
$lang['error_user_not_found'] = "User with this email not found";
$lang['error_security_question'] = "You haven't assigned security question for password reset. Please contact site admin at $admin_email";
$lang['error_answer'] = "Answer incorrect";
$lang['passw_reset'] = "Your password was reset. Please check email and use new password for access";

$lang['login'] = 'Login: ';
$lang['password'] = 'Password: ';
$lang['error_auth']  = "Wrong login or password!";

$lang['forgot_password'] = 'Forgot your password?';
$lang['register'] = 'Register';

$lang['male'] = "Male";
$lang['female'] = "Female";

$lang['name'] = 'Name';
$lang['description'] = 'Brief Description';

$lang['category_tags'] = 'Category Keywords';

$lang['to_delete'] = "to delete";
$lang['to_edit'] = "to edit";
$lang['to_add'] = "to add";
$lang['date'] = "Date";
$lang['rate'] = "Rate";


/*
$security_question[1] = "What is your 1st pet's name?";
$security_question[2] = "What is your favourite movie?";
$security_question[3] = "What is your favourite band?";
*/
?>
