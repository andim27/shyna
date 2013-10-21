<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "main";
$route['scaffolding_trigger'] = "";

$route['admin'] = "admin/admin";
$route['profile/(:num)/(:num)']="profile/view/$1/$2";
$route['register']="main/register";
$route['contacts']="main/contacts";
$route['logout']  ="main/logout";
$route['mailbot']  ="mailbot/work";
//$route['search/action_0']  ="search/action_0";
//$route['search/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)']  ="search/details/$1/$2/$3/$4/$5/$6";
//$route['search/(:any)/(:any)/(:any)']  ="search";

/*$route['admin/(.*)'] = "admin/admin/$1";
$route['page/(.*)'] = "main/view/page/$1";

$route['help'] = "pages/view/help";
$route['faq'] = "pages/view/faq";
$route['contacts'] = "pages/view/contacts";
$route['agreement'] = "pages/view/agreement";

$route['jury2'] = "pages/view/jury2";
$route['jury3'] = "pages/view/jury3";
$route['conditions'] = "pages/view/conditions";

$route['bibb/(.*)'] = "bibb/view/$1";*/


//$route['admin/(.*)'] = "admin/home/$1";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */