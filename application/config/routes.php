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
|	https://codeigniter.com/user_guide/general/routing.html
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


if($_SERVER['HTTP_HOST']=='admin.mmyvvonline.com'){

/* Admin Routes   */
$route['default_controller'] = 'admin/Admins';

$route[''] = 'admin/Admins/login';
$route['dashboard'] = 'admin/Admins/dashboard';
$route['login'] = 'admin/Admins/login';
$route['loginSub'] = 'admin/Admins/loginSub';
$route['Admin'] = 'admin/Admins';
$route['logout'] = 'admin/Admins/logout';
$route['enrollment/show_form/(:num)'] = 'admin/enrollment/show_form/$1';
$route['updateStudentData/(:num)'] = 'admin/updateStudentData/index/$1';
$route['remote_login/(:any)'] = 'admin/Admins/remote_login/$1';

}elseif($_SERVER['HTTP_HOST']=='center.mmyvvonline.com'){


$route[''] = 'center/center';
$route['profile'] = 'center/center/profile';
$route['change_password'] = 'center/center/change_password';
$route['login'] = 'center/center/login';
$route['logout'] = 'center/center/logout';
$route['dashboard'] = 'center/center/dashboard';
$route['instruction'] = 'center/center/instruction';
$route['admission_form'] = 'center/center/admission_form';
$route['all_student'] = 'center/center/all_student';
$route['show_form/(:any)'] = 'center/center/show_form/$1';
$route['show_fees/(:any)'] = 'center/center/show_fees/$1';
$route['student_list/(:any)'] = 'center/center/student_list/$1';
$route['admission_instruction'] = 'center/center/admission_instruction';
$route['loginAs/(:any)'] = 'center/center/loginAs/$1';
$route['not_approve_student_list'] = 'center/center/not_approve_student_list';
$route['remaining_documents']  = 'center/center/remaining_documents';
$route['Document/remainingDocument'] = 'center/Document/remainingDocument';
$route['form_edit_request']  = 'center/center/form_edit_request';
$route['get_request_detail'] = 'center/center/get_request_detail';
$route['payment_complaint'] = 'center/center/payment_complaint';

}








$route['student'] = 'student/Student';
$route['student/login'] = 'student/Student/login';
$route['student/loginSub'] = 'student/Student/loginSub';
$route['student/dashboard'] = 'student/Student/dashboard';
$route['student/logout'] = 'student/Student/logout';
$route['student/payment_detail'] = 'student/Student/view_payment_detail';
$route['student/show_form/(:num)'] = 'student/Student/show_form/$1';
$route['student/form'] = 'student/Student/form';
$route['student/admission/(:num)'] = 'student/Student/admission/$1';
$route['student/enquiry'] = 'student/Student/enquiry';
$route['student/new_admission'] = 'student/Student/new_admission';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
