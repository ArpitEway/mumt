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
    
   

     


       public function generate_marksheet_no(){
        /*
            212 - Dec 2021
            221 - June 2022
        */
            $data['students'] = $this->Common_model->getRecordByWhere('student', array('new_exam_form'=>'Y' ,'roll_no!='=>0 ,'marksheet_no'=>''));
            $starting_no = 10001 ;
            foreach($data['students']  as $key =>  $student){
                $f_l_center_code = substr($student->center_code, 0, 1);
                $l_l_center_code =  substr($student->center_code,-4);           
                $marksheet_no = $f_l_center_code.$starting_no.'221'.$l_l_center_code ;
                
                // $data['students'][$key]->marksheet_no = $marksheet_no;
                $updateData  = array('marksheet_no'=>$marksheet_no);
                $where = array('student_id'=>$student->student_id);
                $this->Common_model->updateRecordByConditions('student',$where,$updateData); 
                // $this->Common_model->updateRecordByConditions('old_exam_data',$where,$updateData); 
                $starting_no++ ;
           }

           $data['students'] = $this->Common_model->getRecordByWhere('student', array('new_exam_form' => 'Y' ,'roll_no!=' => 0 ,'marksheet_no!='=>''));
            $this->load->view('admin/script/header');
            $this->load->view('admin/script/student_marksheet_no',$data);
            $this->load->view('admin/script/footer');
       }


       public function upload_old_marks()
       {
            $this->db->select('course_name,student.class_name,class_id, COUNT(student_id) as cnt');
            $this->db->join('class_master', 'student.class_id = class_master.id');
           // $this->db->where('last_class', 'L');
            $this->db->where('exam_form', 'Y');
            $this->db->where('mode', 'Semester');
            $this->db->where('result_permission', 'Y');
            // $this->db->where('final_result_permission', 'Y');
            // $this->db->where('result_show', 'Y');
            $this->db->where('upload_result', 'N');
            $this->db->group_by('class_id');          
            $data['courses'] = $this->db->get('student')->result();
            $this->load->view('header',array('title' => ''));
            $this->load->view('admin/script/upload_old_marks',$data);
            $this->load->view('footer');
       }

    public function upload_old_data_script($class_id=""){
        $classData = $this->Common_model->getRecordById('class_master','id',$class_id);
        $this->db->limit(500);
        $students = $this->Common_model->getRecordByWhere("student",array("class_id"=>$class_id, "exam_form"=>'Y', "upload_result"=>'N'));
     // $this->db->where_in('course_group.course_type',array('Diploma','PGDiploma'));
     // $course_type = $this->Common_model->getRecordByWhere("course_group",array('id'=> $students[0]->course_group_id));

        foreach($students as $student)
        {
            $check_grace_marks = false;
            $fail_count = 0;
            $abs_count = 0;
            $whCount = 0;
            $fali_tot_marks = 0;
            $require_tot_marks = 0;
            $tot_std_marks = 0;
            $tot_marks = 0;
            $grace_result_count=0;
            $fail_result_count=0;
            $final_result = '';
            $p_fail_count = 0;
             $paper_count = 0;

            $examData = array(
                'student_id' => $student->student_id,
                'session' => $student->session,
                'class_order' => $classData->class_order,
                'center_id' => $student->center_id,
                'center_code' => $student->center_code,
                'course_group_id' =>$student->course_group_id,
                'course_name' => $student->course_name,
                'class_id' => $student->class_id,
                'enrollment_no' => $student->enrollment_no,
                'roll_no' => $student->roll_number,
                'name' => $student->name,
                'exam_year' => 'Aug 2022',
                'f_h_name' => $student->f_h_name,
                'mother_name' => $student->mother_name,
                'marksheet_no' =>$student->marksheet_no,
            );
            $new_exam_form = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id' => $student->student_id,'class_id'=>$class_id));
            foreach($new_exam_form  as $marks)
            {
                $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$marks->class_id,'paper_code'=>$marks->paper_code));
                if($marks->paper_type=='theory'){
                    if($student->university_mode=="REG")
                        {
                            $tot_std_marks += $marks->theory_marks + $marks->int_marks;
                            $tot_marks += $paper_master[0]->max_theory_marks + $paper_master[0]->max_internal_marks;
                            if($marks->theory_marks<$paper_master[0]->min_theory_marks){
                                $fail_count++;
                                $fali_tot_marks += $marks->theory_marks;
                                $require_tot_marks += $paper_master[0]->min_theory_marks;
                            }
                            
                        } 
                    else{
                            
                            $tot_std_marks += $marks->theory_marks ;
                            $tot_marks += $paper_master[0]->private_max_theory_marks ;
                            if($marks->theory_marks<$paper_master[0]->private_min_theory_marks){
                                $fail_count++;
                                $fali_tot_marks += $marks->theory_marks;
                                $require_tot_marks += $paper_master[0]->private_min_theory_marks;
                            }
                        }
                         
                    

                    if($marks->theory_marks==''){
                        $whCount++;

                    }
                    if ($marks->theory_marks=='ABS') {
                        $abs_count++;
                        $fail_count++;
                    }
                    if ($marks->int_marks=='ABS') {
                        $abs_count++;
                        $fail_count++;
                    }
                   
                }else{ //if($marks->paper_type=='Practical'){
                    if ($classData->practical_internal_marks=='Y') {
                        $tot_std_marks += $marks->p_marks+$marks->int_marks;
                        $tot_marks += $paper_master[0]->max_theory_marks + $paper_master[0]->max_internal_marks;
                    }else{
                        $tot_std_marks += $marks->p_marks;
                        $tot_marks += $paper_master[0]->max_theory_marks;
                    }
                    if($marks->p_marks=='' && $marks->p_marks=='N'){
                        $whCount++;
                    }
                    if($marks->p_marks=='ABS'){
                        $abs_count++;
                        $fail_count++;
                    }
                    if($marks->p_marks<$paper_master[0]->min_theory_marks){
                        $p_fail_count++;
                        $fali_tot_marks += $marks->p_marks;
                        $require_tot_marks +=$paper_master[0]->min_theory_marks;
                    }
                }
            }
           
            $aggregate_per = round(  ($tot_std_marks/$tot_marks) * 100,2);
            $require_grace_marks = $require_tot_marks-$fali_tot_marks;
            //$aggregate_per>36 &&
            if($fail_count<2 && $fail_count!=0 && $abs_count==0 && $require_grace_marks<4 &&  $p_fail_count==0){
                $check_grace_marks = true;
            }
            if($fail_count>0) {
                $final_result = ($check_grace_marks) ? 'PASS BY GRACE' : 'FAIL';
            }else{
                $final_result = 'PASS';   
            }
            if($final_result=='FAIL' || $whCount!=0) {
                 //  && count($course_type)==0 && $student->course_group_id!=76 && $student->course_group_id!=77
                continue;
            }
            $examData['university_mode'] = $student->university_mode;
            $examData['photo'] = $student->photo;
            $examData['total_marks'] = $tot_marks;
            $examData['obtain_marks'] = $tot_std_marks;
            $examData['percentage'] = $aggregate_per;
            $examData['update_date'] = date('Y-m-d');
            $examData['exam_status'] = 'R';
            $examData['exam_result'] = $final_result;

           $old_exam_data_id = $this->Common_model->insertAll('old_exam_data',$examData);
            echo $this->db->last_query().'<br>';
            if($old_exam_data_id){
                foreach($new_exam_form as $marks)
                {
                    $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$marks->class_id,'paper_code'=>$marks->paper_code));
                    if($marks->paper_type=="theory"){
                        $result = "PASS";  
                        if($marks->theory_marks=='' || $marks->theory_marks=='ABS' ){
                            $result = "FAIL";
                        }
                        if(($marks->theory_marks<$paper_master[0]->min_theory_marks && $student->university_mode=="REG") || ($marks->theory_marks<$paper_master[0]->private_min_theory_marks && $student->university_mode=="PVT") ){
                            if($check_grace_marks){
                                $result = "PASS BY GRACE";          
                            }else{
                                $result = 'FAIL';
                            }
                        }
                       $paper_count++;    
                    }else{ //  if($marks->paper_type=="Practical"){
                        $result = "PASS";
                        if($marks->p_marks=='' || $marks->p_marks=='N' || $marks->p_marks=='ABS'){
                            $result = "FAIL";
                        }
                        if($marks->p_marks<$paper_master[0]->min_theory_marks){
                                $result = 'FAIL';
                        }
                    }
                    if($student->university_mode=="REG"){
                        $max_theory_marks= $paper_master[0]->max_theory_marks;
                        $max_int_marks= $paper_master[0]->max_internal_marks;
                        $min_theory_marks= $paper_master[0]->min_theory_marks;
                        $min_int_marks= $paper_master[0]->min_internal_marks;
                    }
                    else{
                        $max_theory_marks= $paper_master[0]->private_max_theory_marks;
                        $max_int_marks= 0;
                        $min_theory_marks= $paper_master[0]->private_min_theory_marks;
                        $min_int_marks= 0;
                    }
                    $ResultData = array(
                        'exam_data_id' =>  $old_exam_data_id ,
                        'student_id' =>  $student->student_id ,
                        'course_group_id' => $student->course_group_id ,
                        'class_id' =>  $student->class_id ,
                        'paper_code'=> $paper_master[0]->paper_code ,
                        'type'=> $marks->paper_type ,
                        'max_theory_marks'=> $max_theory_marks,
                        'max_int_marks'=> $max_int_marks,
                        'min_theory_marks'=> $min_theory_marks,
                        'min_int_marks'=> $min_int_marks,
                        'theory_marks'=> $marks->theory_marks,
                        'p_marks'=> $marks->p_marks,
                        'int_marks'=> $marks->int_marks,
                        'paper_name'=>  $this->Common_model->getPaperNameById($marks->paper_id),
                        'result' => $result ,
                        'p_order'=> $marks->paper_order 
                    );
                   $insert = $this->Common_model->insertAll('old_result_data',$ResultData);
                    echo $this->db->last_query().'<br>';
                } 

            }
            $studentData = array('upload_result'=>'Y');
            // if($paper_count==$abs_count && count($course_type)!=0){  
            //     $studentData['demo'] = 'Y';
            //     $studentData['new_exam_form'] = 'N';
            // }else
            if(($fail_count>1 && $student->course_group_id==76) || ($paper_count==$abs_count)){
                $studentData['promote'] = 'D';    
            }else{
                $studentData['promote'] = 'N';
            }
           $this->Common_model->updateRecordByConditions('student',array('student_id'=>$student->student_id),$studentData);     
            if($insert){
                echo $old_exam_data_id;
                 echo '<hr style="margin:20px; 0px;">';
               // die;
            } 
        }
     
    
    }

    public function promote_student_list(){
        $this->db->select('count(*) as cnt ,student.course_name ,student.class_id, student.course_group_id, student.class_name');
        $this->db->from('student');
        $this->db->join('class_master', 'class_master.id = student.class_id');
        $this->db->group_by('student.class_id');
        $this->db->where('student.exam_form','Y');
        // $this->db->where('student.result_show','Y');
        $this->db->where('student.promote','N');
        $this->db->where('student.course_complete','N');
        $this->db->where('class_master.last_class is NULL');
        $data['courses'] = $this->db->get()->result();
      //  echo $this->db->last_query();
        $this->load->view('header',array('title' => 'Promote Students'));
        $this->load->view('admin/script/class_wise_student_count_for_promote_student',$data);
        $this->load->view('footer');
    }
    
      public function promote_student($class_id="" ,$course_group_id=""){
        $data = array(
            'name_csrf' => $this->security->get_csrf_token_name(),
            'hash_csrf' => $this->security->get_csrf_hash(),
        );//'result_show'=>'Y'
        $this->db->limit(1000);
          $data['students']= $this->Common_model->getRecordByWhere('student',array('class_id' => $class_id, 'exam_form'=>'Y' , 'promote'=>'N' ,'course_complete'=>'N' ));
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
                'new_exam_form' => 'N',
                'promote' => 'Y'
                
            );
            $where = array('student_id'=>$student_id,'new_exam_form'=>'D');
            $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
        }
        if($update){
            redirect(base_url('admin/scripts/Postexam/promote_student_list'));
        }
    }



    
    public function check_demo_backlog_student()
      {
        $this->db->select('DISTINCT(id)');
        $this->db->from('class_master');
        $this->db->where('backlog_exam_form_permission','Y');
        $classes = $this->db->get()->result();
       $class_id = array_column($classes,'id');
       if($classes){

           $this->db->select('course_name,class_id, COUNT(student_id) as cnt');
           $this->db->where('exam_year', 'Aug 2022');
           $this->db->where('exam_result', 'FAIL');
           $this->db->where('exam_status', 'R');
           $this->db->where_in('class_id',$class_id );
           $this->db->group_by('class_id');         
           $data['courses'] = $this->db->get('old_exam_data')->result();
       }else{
        $data['courses'] = "No Data Found";
       }
        //    $this->Common_model->last_query();
           $this->load->view('header',array('title' => 'Check Backlog Student'));
           $this->load->view('admin/script/check_demo_backlog_student',$data);
           $this->load->view('footer');
      }

    public function check_demo_backlog_student_script($class_id)
    {
        $this->load->view('header',array('title' => 'Backlog Students'));
        $this->db->select('*');
        $this->db->from('old_exam_data');
        $this->db->where('exam_year', 'Aug 2022');
        $this->db->where('exam_result', 'FAIL');
        $this->db->where('exam_status', 'R');
        $this->db->where('old_exam_data.class_id',$class_id);
        $data['students'] = $this->db->get()->result();
        $this->load->view('admin/script/check_demo_backlog_student_script',$data);
        $this->load->view('footer');
      }


    public function set_demo($student_id,$class_id)
    {
        $where = array('student_id'=>$student_id,'new_exam_form'=>'D');
        $data = array('demo'=>'Y','new_exam_form'=>'N');
        $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
        if($update){
        redirect(base_url('admin/scripts/Postexam/check_demo_backlog_student_script/'.$class_id));
        $this->session->set_flashdata('ajax_flash_message','Your Query Submited Successfully'); 
        }    
    }   

    public function backlog_marks_update_scripts($student_id,$class_id='')
    {
        $students = $this->Common_model->getRecordByWhere("old_exam_data",array("class_id"=>$class_id,'student_id'=>$student_id));
        $whereResult = array("class_id"=>$students[0]->class_id ,"student_id"=>$students[0]->student_id, 'exam_data_id' => $students[0]->id);
        $old_result_datas = $this->Common_model->getRecordByWhere("old_result_data",$whereResult );
            $data = array(
                'student_id' => $students[0]->student_id,
                'course_group_id' =>$students[0]->course_group_id,
                'class_id' => $students[0]->class_id,
                'roll_no' => 0,
                'session' => $students[0]->session,
                'mode'=>$students[0]->university_mode,
                'exam_year'=>'Aug 2022',
                'exam_form' => 'D',
                'enrollment_no' => $students[0]->enrollment_no,
                'center_id' => $students[0]->center_id,
                'center_code' => $students[0]->center_code,
                'attempt_no' => 1,
                'exam_center_id' => 0,
                'exam_center_code'=>'',
                'back_marksheet_no' => '',
                'upload_result' =>  'N',
                'result_permission' => 'N',
               );
              $duplicate =  $this->Common_model->getRecordByWhere('backlog_student',array('student_id'=>$students[0]->student_id,'class_id'=>$students[0]->class_id,'exam_year'=>'Aug 2022'));
              if( $duplicate){
                redirect(base_url('admin/scripts/Postexam/check_demo_backlog_student_script/'.$class_id));
                $this->session->set_flashdata('ajax_flash_message','Already Submited'); 
              }else{

              
            $backlog_student_id = $this->Common_model->insertAll('backlog_student',$data);
            echo $this->db->last_query().'<br>';
            foreach($old_result_datas as $old_result_data)
            {
                $examData = array(
                    'student_id' => $old_result_data->student_id ,
                    'backlog_student_id' => $backlog_student_id,
                    'course_group_id' =>$old_result_data->course_group_id,
                    'class_id' => $old_result_data->class_id,
                    'paper_code' => $old_result_data->paper_code,
                    'paper_type' => $old_result_data->type,
                    'group_id' => '',
                    'paper_order' => $old_result_data->p_order,
                    'theory_marks' =>$old_result_data->theory_marks,
                    'int_marks' =>$old_result_data->int_marks,
                    'p_marks' => $old_result_data->p_marks,
                    'status' => 'C',
                 );
                if ($old_result_data->result=='FAIL'){
                    $examData['status'] = 'B';
                    $examData['theory_marks'] = '';
                }
                $backlog_exam_form_june = $this->Common_model->insertAll('backlog_exam_form',$examData);
                echo $this->db->last_query().'<br>';
            }
         } 
     }
     public function course_complete_status()
     {
          $this->db->select('course_name,student.class_name,student.course_group_id,class_id, COUNT(student_id) as cnt');
          $this->db->join('class_master', 'student.class_id = class_master.id');
          $this->db->where('last_class', 'L');
          $this->db->where('new_exam_form', 'Y');
          $this->db->where('result_permission', 'Y');
          $this->db->where('course_complete', 'N');
          $this->db->where('upload_result', 'Y');
          $this->db->group_by('class_id');          
          $data['courses'] = $this->db->get('student')->result();
          $this->load->view('header',array('title' => ''));
          $this->load->view('admin/script/course_complete_status',$data);
          $this->load->view('footer');
     }
     public function update_course_complete_status($course_group_id="",$class_id=""){
            $classData = $this->Common_model->getRecordById('class_master','id',$class_id);
            $this->db->limit(500);
            $students = $this->Common_model->getRecordByWhere("student",array("class_id"=>$class_id, "new_exam_form"=>'Y', "upload_result"=>'Y','course_complete'=>'N'));
            $courseClassData = $this->Common_model->getRecordByWhere("class_master",array("course_group_id"=>$course_group_id,"mode"=>$classData->mode));
    
            $i=1;
            echo "<br>&nbsp;&nbsp; # &nbsp;&nbsp; Form Number  &nbsp;&nbsp; Enrollment Number";
            foreach($students as $student)
            {
                $passCounter=0;
                foreach($courseClassData as $courseClass)
                 {
                    $courseCompleteStudentData = $this->Common_model->getRecordByWhere("old_exam_data",array("student_id"=>$student->student_id,"exam_result!="=>"FAIL","class_id"=>$courseClass->id));
                    if($courseCompleteStudentData) $passCounter++;
                }
                 if(count($courseClassData)==$passCounter){
                    $data = array(
                        'course_complete' => 'Y',
                        'new_admission_permission'=>'Y',
                    );
                    $where = array('student_id'=>$student->student_id);
                    $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
                        echo "<br>&nbsp;&nbsp;".$i++."  &nbsp;&nbsp;&nbsp;  ".$student->student_id. "   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   ".$student->enrollment_no;
                }
               
            }
        }     






        //for open book only Start ****************/
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
        $this->db->where('new_exam_form.theory_marks','');
        $this->db->limit(1000);
        $data['students'] = $this->db->get()->result();
        $this->load->view('admin/script/general_promotion_student_view',$data);
        $this->load->view('footer');
    }

    public function update_teacher_upload_exam_ans_sheet(){
        echo "<pre>";
        
        $this->db->select('*');
        $this->db->from('upload_exam_ans_sheet');
        $this->db->where('file_exist','Y');
        $this->db->where('total_marks!=',0);
        $this->db->where('teacher_id','');
        $rows=$this->db->get()->result();
      
       $a="";
       
        foreach($rows as $row){
        
            $this->db->select('*');
            $this->db->from('assign_answersheet');
            $this->db->where('class_id',$row->class_id);
            $this->db->where('paper_code',$row->paper_code);
            $data=$this->db->get()->result();
            
           // $this->db->last_query();
         
            $student_master = $this->Common_model->getRecordByWhere('student',array('student_id'=>$row->student_id));
            $a.= "<br>Student Center ID ".$student_master[0]->center_id ."<br>";
            
            foreach($data as $record){
                $arr=explode(',',$record->center_id);
                if(in_array($student_master[0]->center_id,$arr)){
                  
                    $data  = array('teacher_id'=>$record->teacher_id,);
                    $where = array('id'=>$row->id ,);
                    $update =$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
                    $a.=$this->db->last_query();
                }
                
            }
        }
        if(!empty($a))
            echo $a;
        else
            echo "No Record Found!";    
    }

    public function update_teacher_upload_exam_ans_sheet_to_new_exam_form($startlimit=1){
       // echo "Hello <pre>";
        
        $this->db->select('*');
        $this->db->from('upload_exam_ans_sheet');
        $this->db->where('file_exist','Y');
        $this->db->where('total_marks!=',0);
        $this->db->where('teacher_id !=','');
        $start=0;
		$start=($startlimit-1)*10000;
		$this->db->limit(10000,$start);
        $rows=$this->db->get()->result();
        foreach($rows as $row){
            $this->db->select('*');
            $this->db->from('new_exam_form');
            $this->db->where('class_id',$row->class_id);
            $this->db->where('student_id',$row->student_id);
            $this->db->where('paper_code',$row->paper_code);
            $this->db->where('teacher_id','');
            $data=$this->db->get()->result();
          //  echo $this->db->last_query();
           // print_r($data);
           foreach($data as $record){
           // print_r($row);
            $data  = array('teacher_id'=>$row->teacher_id ,);
            $where = array('id'=>$record->id,);
            $update =$this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
            echo $this->db->last_query();
            echo "<br>Data <br>";
           // print_r($record); die;
           }
           
        }
       $a="";
    }


    public function update_teacher_id_to_new_exam_form(){
       // echo "Hello <pre>";
        $this->db->select('*');
        $this->db->from('student');
        $this->db->join('new_exam_form', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.old_class_id');
        $this->db->where('student.exam_form','Y');
        $this->db->where('teacher_id','');
        $this->db->where('new_exam_form.paper_type','theory');
        $ignore = array(75, 76,77);
        $this->db->where_not_in('student.course_group_id', $ignore);
        $this->db->limit(10000);
        $rows=$this->db->get()->result();
        foreach($rows as $row){
            $this->db->select('*');
            $this->db->from('upload_exam_ans_sheet');
            $this->db->where('file_exist','Y');
            $this->db->where('total_marks!=',0);
            $this->db->where('teacher_id !=','');
            $this->db->where('class_id',$row->class_id);
            $this->db->where('student_id',$row->student_id);
            $this->db->where('paper_code',$row->paper_code);
            $data=$this->db->get()->result();
            $teacher_id=$data[0]->teacher_id;
            if($teacher_id=='')  {
                $this->db->select('*');
                $this->db->from('assign_answersheet');
                $this->db->where('class_id',$row->class_id);
                $this->db->where('paper_code',$row->paper_code);
                $data=$this->db->get()->result();
                foreach($data as $record){
                    $arr=explode(',',$record->center_id);
                    if(in_array($row->center_id,$arr)){ 
                        $teacher_id=$record->teacher_id;
                    }
                }
            }
            $data  = array('teacher_id'=> $teacher_id);
            $where = array('id'=>$row->id);
            $update =$this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
            echo $this->db->last_query();
            echo "<br>";      
        }  
    }
 
    
//for open book only End****************/
}
