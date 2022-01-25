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
$route['default_controller'] = 'Dashboard';


/* Admin Routes   */
$route['admin'] = 'admin/Admins/login';
$route['admin/dashboard'] = 'admin/Admins/dashboard';
$route['admin/login'] = 'admin/Admins/login';
$route['admin/loginSub'] = 'admin/Admins/loginSub';
$route['admin/Admin'] = 'admin/Admins';
$route['admin/logout'] = 'admin/Admins/logout';
$route['admin/enrollment/show_form/(:num)'] = 'admin/enrollment/show_form/$1';
$route['admin/updateStudentData/(:num)'] = 'admin/updateStudentData/index/$1';
$route['admin/remote_login/(:any)'] = 'admin/Admins/remote_login/$1';

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


$route['center'] = 'center/center';
$route['center/profile'] = 'center/center/profile';
$route['center/change_password'] = 'center/center/change_password';
$route['center/login'] = 'center/center/login';
$route['center/logout'] = 'center/center/logout';
$route['center/dashboard'] = 'center/center/dashboard';
$route['center/instruction'] = 'center/center/instruction';
$route['center/admission_form'] = 'center/center/admission_form';
$route['center/all_student'] = 'center/center/all_student';
$route['center/show_form/(:any)'] = 'center/center/show_form/$1';
$route['center/show_fees/(:any)'] = 'center/center/show_fees/$1';
$route['center/student_list/(:any)'] = 'center/center/student_list/$1';
$route['center/admission_instruction'] = 'center/center/admission_instruction';
$route['center/loginAs/(:any)'] = 'center/center/loginAs/$1';
$route['center/Document_uplode'] = 'center/center/Document_uplode';
 $route['center/center/madetoapproval']  = 'center/center/madetoapproval';
//$route['center/Document/nonapproveuploadDoc'] = 'center/Document/nonapproveuploadDoc';
$route['center/Document/remainingDocument'] = 'center/Document/remainingDocument';



$route['center/form_edit_request']  = 'center/center/form_edit_request';
$route['center/get_request_detail'] = 'center/center/get_request_detail';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
