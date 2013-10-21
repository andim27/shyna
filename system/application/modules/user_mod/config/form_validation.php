<?php
$config = array(
           'users/register_full' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'username',
                                            'rules' => 'required|min_length[5]|callback_login_in_use_validation'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'email',
                                            'rules' => 'required|valid_email|callback_email_in_use_validation'
                                         ),
                                    array(
                                            'field' => 'pass1',
                                    		'label' => 'Пароль',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'pass2',
                                            'rules' => 'required|matches[pass1]'
                                         )
                                    ),
			'users/feedback_full' => array(
                                   array(
                                            'field' => 'email',
                                            'label' => 'email',
                                            'rules' => 'required|valid_email'
                                         )
                                    ),

			'users/register_step2' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'required|min_length[5]|callback_login_in_use_validation'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required|valid_email|callback_email_in_use_validation'
                                         )
                                    ),
           'users/authorize' => array(
                                    array(
                                            'field' => 'login',
                                            'label' => 'Login',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'required'
                                         )
                                    ),
            'users/profile_save' => array(
                                    array (
                                            'field' => 'username',
                                            'rules' => 'required|min_length[5]'
                                           ),
                                    array (
                                            'field' => 'email',
                                            'rules' => 'required|valid_email'
                                         )
                                    ),
            'users/profile_password' => array (
                                            'new_psw'     => "required",
                                            'confirm_psw' => "required"
                                    ),
			'users/profile_login' => array(
                                    array(
                                            'field' => 'user_login',
                                            'rules' => 'required|min_length[5]|callback_login_in_use_validation'
                                         )
                                    
                                    ),
               );

?>