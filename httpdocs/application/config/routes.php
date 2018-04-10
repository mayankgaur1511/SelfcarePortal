<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Authentication';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
 

# Custom routes
$route['reset-password'] = 'Authentication/resetPassword';
$route['reset-password-request'] = 'Authentication/resetPasswordLink';
$route['change-password'] = 'Authentication/changePassword';
$route['signin'] = 'Authentication/index';
$route['signout'] = 'Authentication/signout';
$route['orders'] = 'OrderTracking/index';

$route['case'] = 'CaseManagement/index';
$route['case/(:any)'] = 'CaseManagement/details/$1';


# Settings
$route['settings/bridge'] = 'Bridge';
$route['settings/bridge/add'] = 'Bridge/add';
$route['settings/bridge/update/(:any)'] = 'Bridge/update/$1';

$route['settings/subsidiary'] = 'Subsidiary';
$route['settings/subsidiary/add'] = 'Subsidiary/add';
$route['settings/subsidiary/update/(:any)'] = 'Subsidiary/update/$1';

$route['settings/translation'] = 'Translation';
$route['settings/translation/add'] = 'Translation/add';
$route['settings/translation/update/(:any)'] = 'Translation/update/$1';

$route['settings/language'] = 'Language';
$route['settings/language/add'] = 'Language/add';
$route['settings/language/update/(:any)'] = 'Language/update/$1';

$route['settings/user'] = 'User';
$route['settings/user/add'] = 'User/add';
$route['settings/user/update/(:any)'] = 'User/update/$1';

