<?php
// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

 // use chriskacerguis\RestServer\REST_Controller;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept"); 

class WebSite extends REST_Controller {
/**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() { 
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        // Load the user model
        $this->load->model('Common_model');
    }

    public function insertEnquiry_post() {

        $name = html_escape($this->input->post("name"));
        $email = html_escape($this->input->post("email"));
        $department    = html_escape($this->input->post("department"));
        $program    = html_escape($this->input->post("program"));
        $mobile    = html_escape($this->input->post("mobile"));
        $city = html_escape($this->input->post("city"));
        $data = array('name'=>$name, 'email' =>$email,'department'=>$department,'program'=>$program,'mobile'=>$mobile,'city'=>$city);
        $insert = $this->Common_model->insertAll('enquiry',$data);
        if($insert){
            $results['msg'] = 'Enquiry Submitted Successfully';
        }else{
            $results['msg']= "An Error Occurred";
        }
            return $this->response($results, REST_Controller::HTTP_OK);
    }

    public function getDepartment_get()
    {
        $results = $this->Common_model->get_record('department','*');

        return $this->response($results, REST_Controller::HTTP_OK);
    }

    public function GetPrograms_get()
    {
       $department_id = $this->input->get("department_id");
       $results = $this->Common_model->getRecordByWhere('program',array('department_id' => $department_id));
        return $this->response($results, REST_Controller::HTTP_OK);
    }

    public function GetDepartmentWisePrograms_get()
    {
        $this->db->order_by('orders');
        $departments = $this->Common_model->get_record('department','*');
        $i=0;
        while ( $i < count($departments) ) { 
            $where = array('department_id' => $departments[$i]['id']);
            $this->db->order_by('course_type_order,p_order');
            $departments[$i]['programs'] = $this->Common_model->get_record('program','*',$where);
            $i++;
        }
        return $this->response($departments, REST_Controller::HTTP_OK);
    }

    public function getCourseTypeWiseCourse_get()
    {
        $where = array('course_type !=' => '');
        $this->db->order_by('course_type_order,p_order');
        $course_type = $this->Common_model->get_record('program','DISTINCT(course_type) as course_type',$where);
        $i = 0;
        $response = array();
        foreach ($course_type as $course) {
            $response[$i]['course_type'] = $course['course_type'];
            $response[$i]['program'] = $this->Common_model->get_record('program','*',array('course_type' => $course['course_type']));
            $i++;
        }
        return $this->response($response, REST_Controller::HTTP_OK);
    }

    public function programDetailsById_post()
    {
        $program_id = html_escape($this->input->post("program_id"));
        $where = array('id' => $program_id);
        $response['program'] =  $this->Common_model->get_record('program','*',$where);
        $response['department_name'] = $this->Common_model->getSinglefield('department','department_name','id='.$response['program'][0]['department_id']);
        return $this->response($response, REST_Controller::HTTP_OK);
    }

    public function getEligibility_list_get()
    {
        $eligibility_list = $this->Common_model->get_record('course_group','DISTINCT (eligibility)');
        
        
        return $this->response($eligibility_list, REST_Controller::HTTP_OK);
    }

    public function getCourseByEligibility_post()
	{
		$eligibility = html_escape($this->input->post('eligibility'));
        //$eligibility ="GRADUATION";
		$session ="July 2023";
		$mode = 'REG';
		$myString =$eligibility;
		
		 
		// if($this->session->has_userdata('center_id')){
		// $center_id =  $this->session->center_id;
		
		// $centerdata = $this->Common_model->getRecordById('center','id',$center_id);
		// $this->db->group_start();
		// $this->db->where('course_group_id in ('.$centerdata->allot_course_group_id.')');
		// $this->db->group_end();
		// }
		 $where['eligibility'] = $eligibility;
		
		
		$this->db->select('course_group.id,course.course_name');
		$this->db->from('course');
		$this->db->join('course_group', 'course_group.id = course.course_group_id'); 
		$this->db->group_start();
		$this->db->where('eligibility',$eligibility);
		$this->db->where('course.session',$session);
		if($mode=='REG' || $mode=='regular'){
			$where['admission_permission_regular'] = 'Y';
			$this->db->where('admission_permission_regular','Y');
		  }
		  $this->db->group_end();
		//   if($center_id == 11 || $center_id == 13 || $center_id == 2115 || $center_id == 1707 ){
		// 	$this->db->or_group_start();
		//   $this->db->or_where_in('course_group.id',array(33,45));
		//   $this->db->where(array('eligibility' => $eligibility ,'course.session'=>$session));
		//   $this->db->group_end();
		 
		//   }
		$query = $this->db->get();
		$course_group_list= $query->result_array();
		
		$data = array('course_group_list'=>$course_group_list);
        
        return $this->response($data, REST_Controller::HTTP_OK);
	}
    public function checkDuplicateMobileNo_post()
	{
		$p_mobile_no = $this->input->post('p_mobile_no');
       
		$count = $this->db->query("select * from student_data as d join student as s on s.student_id=d.student_id where s.course_complete='N' and s.new_admission_permission='N' and d.p_mobile_no = '".$p_mobile_no."' limit 1")->num_rows();
		if($count>0){
			$data= "Duplicate Mobile No";
		}else{
            $data="";
        }
        return $this->response($data, REST_Controller::HTTP_OK);
	}
    public function checkDuplicateAadhaarNo_post()
	{
		$adhar_no = $this->input->post('adhar_no');
		$where = array('adhar_no'=>$adhar_no,'course_complete'=>'N','new_admission_permission'=>'N');
		$count = $this->Common_model->getCountByWhere('student',$where);
		if($count>0){
			$data= "Duplicate Aadhaar Card Number";
		}else{
            $data="";
        }
        return $this->response($data, REST_Controller::HTTP_OK);
	}

    public function checkDuplicateEmail_post()
	{
		$p_email = $this->input->post('p_email');
       
		$count = $this->db->query("select * from student_data as d join student as s on s.student_id=d.student_id where s.course_complete='N' and s.new_admission_permission='N' and d.p_email = '".$p_email."' limit 1")->num_rows();
		if($count>0){
			$data= "Duplicate Email";
		}else{
            $data="";
        }
        return $this->response($data, REST_Controller::HTTP_OK);
	}
}
