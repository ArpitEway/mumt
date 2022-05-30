<?php
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
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
                                    // echo "<pre>";
                                    // print_r($student);
                                    // die ;
                                    // echo "<pre>";
                                    // print_r($marks);
                    $data = array(
                        'student_id' => $student->student_id ,
                        'center_id' => $student->center_id ,
                        'center_code' => $student->center_code ,
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
                    $old_exam_data_id = $this->Common_model->insertAll('old_exam_data',$data);
                    $new_exam_form = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id'=>$student->student_id));

                        foreach($new_exam_form  as $marks)
                        {

                                    $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$marks->class_id,'paper_code'=>$marks->paper_code));
                                 
                                            if($marks->paper_type=='theory'){
                                                $tot_std_marks += $marks->theory_marks + $marks->int_marks;
                                                $tot_marks += $paper_master[0]->max_theory_marks + $paper_master[0]->max_internal_marks;
                                                if($marks->theory_marks==''){
                                                    $whCount++;
                                                }else if($marks->theory_marks<$paper_master[0]->min_theory_marks){
                                                    $result = "fail";
                                                    $fail_count++;
                                                    $fali_tot_marks += $marks->theory_marks;
                                                    $require_tot_marks += $paper_master[0]->min_theory_marks;
                                                }
                                            }else if($marks->paper_type=='practical'){
                                                $tot_std_marks += $marks->p_marks;
                                                $tot_marks += $paper_master[0]->max_theory_marks;
                                                if($marks->p_marks>= $paper_master[0]->min_theory_marks){
                                                    $result = "pass";
                                                }else if($marks->p_marks=='' && $marks->p_marks=='N'){
                                                    $whCount++;
                                                }else if($marks->theory_marks<$paper_master[0]->min_theory_marks){
                                                    $result = "fail";
                                                    $fail_count++;
                                                    $fali_tot_marks += $marks->p_marks;
                                                    $require_tot_marks +=$paper_master[0]->min_theory_marks;
                                                }
                                            }
                        
                        }     
    
                            $aggregate_per =   ($tot_std_marks/$tot_marks) * 100;

                            $require_grace_marks = $require_tot_marks-$fali_tot_marks;
                            if ($fail_count<3 && $require_grace_marks<4 && $aggregate_per>36){
                                $check_grace_marks = true;
                            }
           }
        
          
          
                foreach($new_exam_form as $marks)
                {
                        $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$marks->class_id,'paper_code'=>$marks->paper_code));
                        $paper_name =$this->Common_model->getPaperNameById($marks->paper_id);
                        // echo "<pre>";
                        // print_r($paper_master);
                        if($marks->paper_type=="theory")
                        {
                            if($marks->theory_marks==''){
                                $result = "-";
                            }else if($marks->theory_marks>= $paper_master[0]->min_theory_marks){
                                $result = "PASS";
                            }else{
                                if($check_grace_marks){
                                    $result = "PASS BY GRACE";
                                    $grace_result_count++;
                                }else{
                                    $result = 'FAIL';
                                    $fail_result_count++;
                                }
                            }
                        }else if($marks->paper_type=="practical")
                        {
                            if($marks->p_marks==''){
                                $result = "-";
                            }else if($marks->p_marks>=$paper_master[0]->min_theory_marks){
                                $result = "PASS";
                            }else{
                                if($check_grace_marks){
                                    $result = "PASS BY GRACE";
                                    $grace_result_count++;
                                }else{
                                    $result = 'FAIL';
                                    $fail_result_count++;
                                }
                            }
                        }
           
                        $data = array(
                            'exam_data_id' =>$old_exam_data_id ,
                            'student_id' => $student->student_id ,
                            'course_group_id' =>$student->course_group_id ,
                            'class_id' => $student->class_id ,
                            'center_id' => $student->center_id ,
                            'paper_code'=>$paper_master[0]->paper_code ,
                            'type'=>$marks->paper_type ,
                            'max_theory_marks'=>$paper_master[0]->max_theory_marks ,
                            'max_int_marks'=>$paper_master[0]->max_internal_marks ,
                            'min_theory_marks'=>$paper_master[0]->min_theory_marks ,
                            'min_int_marks'=>$paper_master[0]->min_internal_marks ,
                            'theory_marks'=>$marks->theory_marks,
                            'p_marks'=>$marks->p_marks,
                            'int_marks'=>$marks->int_marks,
                            'paper_name'=>$paper_name,
                            'result' =>$result ,
                            'p_order'=>$marks->paper_order 
                        );
                           $insert = $this->Common_model->insertAll('old_result_data',$data);
                }
              
                        if($fail_result_count>0){
                            $final_result = "FAIL";
                        }elseif($grace_result_count>0)
                        {
                            $final_result = "PASS BY GRACE";
                        }else{
                            $final_result = "PASS";
                        }
                        $update_old_exam_data = $this->Common_model->updateRecordByConditions('old_exam_data',array('student_id'=>$student->student_id),array('exam_result'=>$final_result));
        
                        if($insert)
                        {
                            echo $old_exam_data_id;
                            die ;
                        }
      
      
 }

    public function general_promotion_class_list_paper_count(){
        $this->load->view('header',array('title' => 'General Promotion Students'));
        $this->db->select('count(paper_id) as cnt ,student.course_name ,student.class_id, student.class_name');
        $this->db->from('student');
        $this->db->join('new_exam_form', 'new_exam_form.student_id = student.student_id');
        $this->db->group_by('student.class_id');
        $this->db->where('student.new_exam_form','Y');
        $this->db->where('new_exam_form.paper_type','theory');
        $this->db->where('new_exam_form.theory_marks','');
        $data['courses'] = $this->db->get()->result();
        $this->load->view('admin/script/student_count_for_general_promotion',$data);
        $this->load->view('footer');
    }

    public function general_promotion_student_list($class_id=""){
        $this->load->view('header',array('title' => 'General Promotion Students Marks Details'));
        $this->db->select('*');
        $this->db->from('student');
        $this->db->join('new_exam_form', 'new_exam_form.student_id = student.student_id');
        $this->db->group_by('student.student_id');
        $this->db->where('student.new_exam_form','Y');
        $this->db->where('new_exam_form.class_id',$class_id);
        $this->db->where('new_exam_form.paper_type','theory');
        $this->db->limit(20);
        $data['students'] = $this->db->get()->result();
        $this->load->view('admin/script/general_promotion_student_view',$data);
        $this->load->view('footer');
    }
}

?>