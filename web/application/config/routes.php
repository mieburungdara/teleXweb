<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['miniapp'] = 'MiniApp/index';
$route['miniapp/login/(:num)'] = 'MiniApp/index/$1';
$route['miniapp/admin'] = 'MiniApp/admin_panel';
$route['miniapp/dashboard'] = 'MiniApp/dashboard';
$route['miniapp/unauthorized'] = 'MiniApp/unauthorized';

// Admin Routes for Bot Management
$route['admin'] = 'Admin/index';
$route['admin/form'] = 'Admin/form';
$route['admin/form/(:num)'] = 'Admin/form/$1';
$route['admin/save'] = 'Admin/save';
$route['admin/delete/(:num)'] = 'Admin/delete/$1';

// Admin Routes for User Management
$route['admin/users'] = 'Admin/users';
$route['admin/edit_user_role/(:num)'] = 'Admin/edit_user_role/$1';
$route['admin/update_user_role'] = 'Admin/update_user_role';

// Main file view
$route['files'] = 'Files/index';
$route['files/gallery'] = 'Files/gallery';
$route['files/details/(:num)'] = 'Files/details/$1';

// API Routes
$route['api/upload'] = 'api/Upload/index';
$route['api/send_message'] = 'api/Upload/send_message';
$route['api/get_recent_files'] = 'api/Upload/get_recent_files';
$route['api/search_files'] = 'api/Upload/search_files';
$route['api/toggle_favorite'] = 'api/Upload/toggle_favorite';
$route['api/update_file'] = 'api/Upload/update_file';


// Folder Management Routes
$route['folders'] = 'Folders/index';
$route['folders/index/(:num)'] = 'Folders/index/$1';
$route['folders/save'] = 'Folders/save';
$route['folders/edit/(:num)'] = 'Folders/edit/$1';
$route['folders/delete/(:num)'] = 'Folders/delete/$1';

// Folder View & Review Routes
$route['folders/view/(:num)'] = 'Folders/view/$1';
$route['folders/submit_review'] = 'Folders/submit_review';

// Folder Sharing Routes
$route['folders/share/(:num)'] = 'Folders/share/$1';
$route['folders/view_shared/(:any)'] = 'Folders/view_shared/$1';

// Folder Like/Star Route
$route['folders/toggle_like/(:num)'] = 'Folders/toggle_like/$1';


// Folder Quick Actions
$route['folders/toggle_favorite/(:num)'] = 'Folders/toggle_favorite/$1';









$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
