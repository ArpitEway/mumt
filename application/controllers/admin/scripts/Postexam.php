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
	
    public function promote_student_class_list(){
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

      public function upload_old_marks()
      {
         
           $this->db->select('course_name,class_name,class_id, COUNT(student_id) as cnt');
           $this->db->where('exam_form', 'Y');
           $this->db->where('result_show', 'Y');
           $this->db->where('upload_result', 'N');
           $this->db->group_by('class_id'); 
         
           $data['courses'] = $this->db->get('student')->result();
        
           $this->load->view('header',array('title' => ''));
           $this->load->view('admin/script/upload_old_marks',$data);
           $this->load->view('footer');
          
      }

      public function upload_old_data_script($class_id="")
      {

         
          $check_grace_marks = false;
          $fail_count = 0;
          $whCount = 0;
          $fali_tot_marks = 0;
          $require_tot_marks = 0;
          $tot_std_marks = 0;
          $tot_marks = 0;
          $grace_result_count=0;
          $fail_result_count=0;
          
           $students = $this->Common_model->getRecordByWhere("student",array("class_id"=>$class_id , "exam_form"=>'Y',"result_show"=>'Y',"upload_result"=>'N'));
        
           foreach($students as $student)
           {

          

              $data = array(
                  'student_id' => $student->student_id ,
                  'center_id' => $student->institute_id ,
                  'center_code' => $student->institute_code ,
                  'course_group_id' =>$student->course_group_id ,
                  'course_name' => $student->course_name ,
                  'class_id' => $student->class_id ,
                  'enrollment_no' => $student->enrollment_no ,
                  'roll_no' => $student->roll_no ,
                  'name' => $student->name ,
                  'f_h_name' => $student->f_h_name ,
                  'mother_name' => $student->mother_name ,
                  'marksheet_no' =>$student->marksheet_no ,
                  
              );
            //  $old_exam_data_id = $this->Common_model->insertAll('old_exam_data',$data);
        $new_exam_form = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id'=>$student->student_id));

           foreach($new_exam_form  as $marks)
           {

                    $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$marks->class_id,'paper_code'=>$marks->paper_code));
                    echo "<pre>";
                    print_r($marks);
                    if($marks->paper_type=='theory'){
                        $tot_std_marks += $marks->theory_marks + $marks->int_marks;
                        $tot_marks += $paper_master[0]->reg_max_marks + $paper_master[0]->reg_max_int_marks;
                        if($marks->theory_marks==''){
                            $whCount++;
                        }else if($marks->theory_marks<$paper_master[0]->reg_min_marks){
                            $result = "fail";
                            $fail_count++;
                            $fali_tot_marks += $marks->theory_marks;
                            $require_tot_marks += $paper_master[0]->reg_min_marks;
                        }
                    }else if($paper=='practical'){
                        $tot_std_marks += $paper->p_marks;
                        $tot_marks += $paper->reg_max_marks;
                        if($paper->p_marks>=$paper->reg_min_marks){
                            $result = "pass";
                        }else if($paper->p_marks=='' && $paper->p_marks=='N'){
                            $whCount++;
                        }else if($paper->theory_marks<$paper->reg_min_marks){
                            $result = "fail";
                            $fail_count++;
                            $fali_tot_marks += $paper->p_marks;
                            $require_tot_marks += $paper->reg_min_marks;
                        }
                    }
           
           }     
           
          
           }
        
           die ;
          
      }

}

?>