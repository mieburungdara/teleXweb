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
$route['miniapp/auth'] = 'MiniApp/auth'; // Explicitly define auth route
$route['miniapp/(:num)'] = 'MiniApp/index/$1'; // Route for miniapp with bot_id

// Admin Routes for Bot Management
$route['admin'] = 'Admin/dashboard'; // Default admin route to dashboard
$route['admin/dashboard'] = 'Admin/dashboard';
$route['admin/form'] = 'Admin/form';
$route['admin/form/(:num)'] = 'Admin/form/$1';
$route['admin/save'] = 'Admin/save';
$route['admin/delete/(:num)'] = 'Admin/delete/$1';

// Admin Routes for User Management
$route['admin/users'] = 'Admin/users';
$route['admin/edit_user_role/(:num)'] = 'Admin/edit_user_role/$1';
$route['admin/update_user_role'] = 'Admin/update_user_role';

// Admin Routes for Role Management
$route['admin/roles'] = 'Admin/roles';
$route['admin/edit_role_permissions/(:num)'] = 'Admin/edit_role_permissions/$1';
$route['admin/update_role_permissions'] = 'Admin/update_role_permissions';

// Admin Routes for Tag Management
$route['admin/tagmanagement'] = 'TagManagement/index';
$route['admin/tagmanagement/find_duplicates'] = 'TagManagement/find_duplicates';
$route['admin/tagmanagement/merge'] = 'TagManagement/merge';

// Main file view
$route['files'] = 'Files/index';
$route['files/gallery'] = 'Files/gallery';
$route['files/details/(:num)'] = 'Files/details/$1';
$route['files/timeline'] = 'Files/timeline';


// API Routes
$route['api/upload'] = 'api/Upload/index';
$route['api/send_message'] = 'api/Upload/send_message';
$route['api/get_recent_files'] = 'api/Upload/get_recent_files';
$route['api/search_files'] = 'api/Upload/search_files';
$route['api/toggle_favorite'] = 'api/Upload/toggle_favorite';
$route['api/update_file'] = 'api/Upload/update_file';
$route['api/file_preview_data/(:num)'] = 'api/Upload/file_preview_data/$1';
$route['api/bulk_action'] = 'api/Upload/bulk_action';
$route['api/tag_suggestions'] = 'api/Upload/tag_suggestions';





// Folder Management Routes
$route['folders'] = 'Folders/index';
$route['folders/index/(:num)'] = 'Folders/index/$1';
$route['folders/save'] = 'Folders/save';
$route['folders/edit/(:num)'] = 'Folders/edit/$1';
$route['folders/delete/(:num)'] = 'Folders/delete/$1';

// Folder View & Review Routes
$route['folders/view/(:num)'] = 'Folders/view/$1';
$route['folders/submit_review'] = 'Folders/submit_review';
$route['folders/submit_comment'] = 'Folders/submit_comment';

// Folder Sharing Routes
$route['folders/share/(:num)'] = 'Folders/share/$1';
$route['folders/view_shared/(:any)'] = 'Folders/view_shared/$1';

// Folder Like/Star Route
$route['folders/toggle_like/(:num)'] = 'Folders/toggle_like/$1';


// Folder Quick Actions
$route['folders/toggle_favorite/(:num)'] = 'Folders/toggle_favorite/$1';

// Smart Collections Routes
$route['smartcollections'] = 'SmartCollections/index';
$route['smartcollections/create_edit'] = 'SmartCollections/create_edit';
$route['smartcollections/create_edit/(:num)'] = 'SmartCollections/create_edit/$1';
$route['smartcollections/save'] = 'SmartCollections/save';
$route['smartcollections/delete/(:num)'] = 'SmartCollections/delete/$1';
$route['smartcollections/view_collection/(:num)'] = 'SmartCollections/view_collection/$1';

// Public Collections Routes
$route['publiccollections'] = 'PublicCollections/index';
$route['publiccollections/create_edit'] = 'PublicCollections/create_edit';
$route['publiccollections/create_edit/(:num)'] = 'PublicCollections/create_edit/$1';
$route['publiccollections/save'] = 'PublicCollections/save';
$route['publiccollections/delete/(:num)'] = 'PublicCollections/delete/$1';
$route['publiccollections/view_public/(:any)'] = 'PublicCollections/view_public/$1';

// User Profile Routes
$route['users/profile'] = 'Users/profile';
$route['users/edit_profile'] = 'Users/edit_profile';
$route['users/update_profile'] = 'Users/update_profile';

// Monetization Routes
$route['monetization/balance'] = 'Monetization/balance';
$route['monetization/add_funds'] = 'Monetization/add_funds';









$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
