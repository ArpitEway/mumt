<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Postexam extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
		$this->load->model('users/User_model');
		if(!$this->session->has_userdata('loged_in')){
			exit;
		}
	}
	
    public function class_wise_student_count_for_promote_student(){
        $this->db->select('count(*) as cnt ,student.course_name ,student.class_id, student.course_group_id, student.class_name');
        $this->db->from('student');
        $this->db->join('class_master', 'class_master.id = student.class_id');
        $this->db->group_by('student.class_id');
        $this->db->where('student.exam_form','Y');
        $this->db->where('student.result_show','Y');
        $this->db->where('student.promote','N');
        $this->db->where('student.course_complete','N');
        $this->db->where('class_master.last_class!=','Y');
        $data['courses'] = $this->db->get()->result();
            $this->load->view('header',array('title' => 'Promote Students'));
            $this->load->view('admin/script/class_wise_student_count_for_promote_student',$data);
            $this->load->view('footer');
      
      }
    
      public function promote_student($class_id="" ,$course_group_id=""){
        $data = array(
            'name_csrf' => $this->security->get_csrf_token_name(),
            'hash_csrf' => $this->security->get_csrf_hash(),
        );
          $data['students']= $this->Common_model->getRecordByWhere('student',array('class_id' => $class_id , 'result_show'=>'Y' , 'exam_form'=>'Y' , 'promote'=>'N' ,'course_complete'=>'N' ));
          $data['course_name']= $this->Common_model->getCourseNameByCourseId($course_group_id);
          $data['class_name']= $this->Common_model->getClassNameByClassId($class_id);
          $data['class_id'] =$class_id ;
        
          $data['course_group_id'] =$course_group_id ;
          $this->load->view('header',array('title' => 'Promote Students'));
          $this->load->view('admin/script/promote_student_view',$data);
          $this->load->view('footer');
      }
    
      public function promote_student_submit(){
  
        foreach($_POST['student_id'] as $student_id){
         
            $student= $this->Common_model->getRecordByWhere('student',array('student_id'=>$student_id));
            $data = array(
                'class_id' => $_POST['new_class_id'],
                'class_name' =>$_POST['class_name'],
                'old_class_id' =>$_POST['old_class_id'],
                'temp_exam_form' => 'N',
                'promote' => 'Y'
            );
            $where = array(
                'student_id'=>$student_id ,
            );
            $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
          }
           if($update){
                redirect(base_url('admin/scripts/Postexam/class_wise_student_count_for_promote_student'));
           }
      }

}

?>