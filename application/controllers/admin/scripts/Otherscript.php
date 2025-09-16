<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Otherscript extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
		$this->load->model('users/User_model');
		if(!$this->session->has_userdata('loged_in')){
			exit;
		}
	}
	
	// Update Exam fields in Paper Master from paper_paper_master_sub table
	public function move_student_document(){
		echo "Move Student Document  ";
		$this->db->select('*');
       	$this->db->from('student');
        $this->db->join('admission_document', 'admission_document.student_id = student.student_id');   
        $this->db->where('approved ="Y" and document_uploaded ="Y" and enrolled = "Y" and move="N"');   
        $start=0;
		$this->db->limit(1000,$start);
		$rows=$this->db->get()->result();
		$i=1;
		foreach($rows as $row){

		 	echo "<br> ".$i." ".$row->student_id ." ". $row->firstname ." ". $row->session;
		
	
			$source=FCPATH."/assets/documents/".$row->document_image;
		
			$destination=FCPATH."/assets/enrolled_documents/".$row->session."/".$row->document_image;
		
			$dirname = FCPATH."/assets/enrolled_documents/".$row->session;

			if(!is_dir($dirname)){
				mkdir( $dirname, 0777);
				echo "The directory $dirname was successfully created.";
			
			} 

			if( rename( $source , $destination )){
				echo '<br>moved!'.$destination;
				$data  = array('move'=>'Y' );
				$where = array('student_id'=>$row->student_id,'id'=> $row->id);
				
				$update =$this->Common_model->updateRecordByConditions('admission_document',$where,$data);
			} 

		 	$i++;
		}
	}
	
	public function update_extra_papers_in_new_exam()
	{
		// add_paper
		//$where = " paper_code in ('2RMAEDU5', '2RMAGEO6', '2RMAGEO7', '2RMAGEO8', '2RMAPSY5', '2RMSCC7', '2RMSCC8', '2RMSCM7', '2RMSW7')";
		// $where = " paper_code in ('2RBED6')";
		//$where = " paper_code in ('1RMLIS10','1RMLIS11')";
		//$where="`paper_code` in ('1RCMSCCH7','1RCMSCCH8','1RCMSCCH9','1RCMSCBT7','1RCMSCBT8','1RCMSCC7','1RCMSCM7','3RMSCC6','3RMSCC7')";
		$where="`id` in (0)";
		// 1491,1501,1579 // 1945,1946
		// 1829,1830,1831 // 382,392,292 // 947,985,1054
		//in (947,1799,985,1800,1054,1801) (1818,1819,1820,1821)";
		// $where="`id` = 292";
		$papers = $this->Common_model->get_record('paper_master','*',$where);

		foreach ($papers as $paper) {
			$where = ' class_id = "'.$paper['class_id'].'" and temp_exam_form="Y"  and university_mode="REG"  and  exam_pattern="GRADE" ';
			// and university_mode="PVT"  and  exam_pattern="GRADE" 
			
			//student_id not in (703394,703395,683403,685044,685047,686312,689208,702853,723869,724934)
			$students = $this->Common_model->get_record('student','*',$where);

			$studentData = array(
				'course_group_id' => $paper['course_group_id'],
				'class_id' => $paper['class_id'],
				'paper_id' => $paper['id'],
				'paper_code' => $paper['paper_code'],
				'paper_type' => $paper['type'],
                'sub_group_id' => $paper['sub_group_id'],
				'paper_order' => $paper['paper_no'],
			);
			foreach ($students as $student) {
				$where = ' class_id = "'.$paper['class_id'].'" and student_id=  "'.$student['student_id'].'" and  paper_id="'.$paper['id'].'"  ';
				$examData = $this->Common_model->get_record('new_exam_form','*',$where);
				if(!$examData){
					echo	$studentData['student_id'] = $student['student_id'];
				    echo "<br>";
				    $this->Common_model->insertAll('new_exam_form',$studentData);
				    echo $this->db->last_query().'<br>';
				}
				
			}
		}	
	}
	 
	public function move_practical_marks(){
		
		$this->db->select('exam_form.*');
		$this->db->from('exam_form');
		
		$this->db->join('new_exam_form', 'exam_form.student_id=new_exam_form.student_id and exam_form.p_marks != new_exam_form.p_marks and exam_form.class_id = new_exam_form.class_id and exam_form.paper_code = new_exam_form.paper_code');
		$this->db->join('student', 'exam_form.student_id=student.student_id');
		$where = array('exam_form.paper_type !='=>'theory','new_exam_form.p_marks !='=>'N','student.new_exam_form'=>'Y','student.p_marks_sub'=>'Y','demo'=>'N');
		$this->db->where($where);
		$data = $this->db->get()->result();
		
        	foreach($data as $dt){
			$pmark  = array('p_marks'=>$dt->p_marks);
			$where = array('id'=> $dt->id);
			//$update =$this->Common_model->updateRecordByConditions('new_exam_form',$where,$pmark);
		}
		
	}

	public function student_photo(){
	
		$this->db->select('student_id,name,photo,session');
		$this->db->from('student');
		
		$start=0;
		$this->db->limit(30,$start);
		$result = $this->db->get()->result();
		
		foreach ($result as $row){
			$dir = './assets/student_image/'.$row->session;
		
			$files = glob( './assets/student_image/'.$row->session.'/'.$row->photo.'');

			foreach($files as $key => $value){
				if($value){
					$std .=  $row->student_id.'/';
				}

			
			} 
		}

		$de = explode('/',$std);
			
		$this->db->select('student_id');
		$this->db->from('student');
		$this->db->where_not_in('student_id',$de);
	
		$this->db->limit(10);
		$rs = $this->db->get()->result();
		// echo $this->db->last_query().'<br>';
		foreach ($rs as  $val) {
			echo $val->student_id.",";
		}
				
			
	}
	public function update_old_result_show(){
		
		$result = $this->Common_model->getRecordByWhere("student",array("exam_form"=>'Y'));
		
		foreach($result as $row){
			
			    $data  = array('old_result_show'=>$row->result_show);
				$where = array('exam_form'=>'Y','student_id'=>$row->student_id);
				
				// $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}
	}
	

	public function update_int_marks($cls_id =0)
	{
		//$marks = array('18','17','16','18','17','16','15','15');
		// $marks = array('09','08','07','09','08','07','06','06');
		   $marks = array('27','26','25','27','26','25','24','24','23'); 
		$cls_id=133;
		
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and int_marks_sub='N' and roll_no!=0 and university_mode='REG' limit 100";
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			
			// $new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and int_marks in ('N') and class_id='".$cls_id."' and paper_code not in ('1RBA1','1RBA2','1RBA3','1RBA4','1RMOM1') and sub_group_id!='1' and paper_type='Theory' ORDER by rand()";

			// and paper_type='Theory'

			// Practical with internal	
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type!='Project' and int_marks in ('N') and class_id='".$cls_id."' and paper_code not in ('1RBA1','1RBA2','1RBA3','1RBA4','1RMOM1') and sub_group_id!='1' ORDER by rand()";
			// Practical with internal	

			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$i=0;
			foreach ($new_exam_rs as $new_exam_data) {
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data['paper_code'].' Marks=>'.$marks[$i].'<br>';

				$update_marks = "update new_exam_form set int_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				$i++;
				$this->db->query($update_marks);
			}
			$update_student = "update student set int_marks_sub='Y' where student_id='".$student['student_id']."' and class_id='".$cls_id."'";

			$this->db->query($update_student);
		}
		
	}

	public function update_practical_marks($cls_id =0)
	{
		//$marks = array('43','42','41','40'); 
		// $marks = array('85','84','83','82'); 
		// $marks = array('68','67','66','65');
		  $marks = array('60','59','58','57');
		$cls_id=109;
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and p_marks_sub='N' and roll_no!=0 and university_mode='PVT' order by roll_no limit 100";
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type='Practical' and p_marks in ('N') and class_id='".$cls_id."' ORDER by rand()";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$i=0;
			foreach ($new_exam_rs as $new_exam_data) {
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data['paper_code'].' Marks=>'.$marks[$i].'<br>';

				$update_marks = "update new_exam_form set p_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				$i++;
				$this->db->query($update_marks);
				shuffle($marks);
			}
			   $update_student = "update student set p_marks_sub='Y' where student_id='".$student['student_id']."' and class_id='".$cls_id."'";

			  $this->db->query($update_student);
		}
	}

	public function update_project_marks($cls_id =0)
	{
		//$marks = array('166','168','170','165'); 
		$marks = array('85','84','83','82'); 
		$cls_id=109;
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and p_marks_sub='N' and  university_mode='REG' and  roll_no!=0 order by roll_no  limit 100";
		// and exam_pattern= 'GRADE'
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type='Project' and p_marks in ('N') and class_id='".$cls_id."' ORDER by rand()";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$i=0;
			foreach ($new_exam_rs as $new_exam_data) {
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data['paper_code'].' Marks=>'.$marks[$i].'<br>';

				$update_marks = "update new_exam_form set p_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				//$update_marks_exam_form = "update exam_form set p_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				$i++;
				$this->db->query($update_marks);
				//$this->db->query($update_marks_exam_form);
				shuffle($marks);
			}
			$update_student = "update student set p_marks_sub='Y' where student_id='".$student['student_id']."' and class_id='".$cls_id."'";

			$this->db->query($update_student);
		
		}
	}
	public function update_oldexam_marks($cls_id =0)
	{

		$cls_id=227;
		$old_totoal_marks=600;
		$paper_master_total_marks=700;

		$sql = "SELECT * FROM `old_exam_data` WHERE `class_id`='".$cls_id."'  AND total_marks='".$old_totoal_marks."' AND exam_year='March 2023'  limit 250";
		$rs = $this->db->query($sql)->result_array();
		$i=0;
		foreach ($rs as $student) {
			$new_exam_sql = "SELECT SUM(p_marks) + SUM(int_marks)  as tot FROM `old_result_data` WHERE `exam_data_id`='".$student['id']."' and `type`!='Theory'";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$obtain_marks=$student['obtain_marks']+$new_exam_rs[0]['tot'];
			echo "<br> $i=".$student['enrollment_no']."<br>";
			$percentage=round(($obtain_marks/$paper_master_total_marks)*100,2);
				// $percentage=($obtain_marks/$total_marks)*100;
			echo $update_marks = "update old_exam_data set total_marks='".$paper_master_total_marks."',obtain_marks='".$obtain_marks."',percentage='".$percentage."' where id=".$student['id'];
			$i++;

			// $this->db->query($update_marks);
		}
	}

	public function remaining_failed_student_marks($class_id=0){
		$class_id=104;
		$university_mode='PVT';
		$this->db->select('count(*) as num,student.*');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.class_id');
		$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
		$this->db->order_by('new_exam_form.course_group_id,new_exam_form.class_id','asc');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('university_mode',$university_mode);
		$this->db->where('new_exam_form.theory_marks!=','');
		$this->db->where('exam_form','Y');
		if ($university_mode=='REG') {
			$this->db->where('new_exam_form.theory_marks < paper_master.min_theory_marks');
		}else{
			$this->db->where('new_exam_form.theory_marks < paper_master.private_min_theory_marks');
		}
		$this->db->where('new_exam_form.class_id',$class_id);
		// //$this->db->where_in('roll_number',array());
		$this->db->where('paper_type','theory');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->having('num',1);
		$data['students'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		
		$this->load->view('header',array('title' => 'Student Failed Remaining Marks List'));
		$this->load->view('admin/remaining_student_average_marks_offline',$data);
		$this->load->view('footer');
	}

	public function update_student_remaining_marks($student_id,$nefId,$marks)
	{
		// $this->Common_model->updateRecordByConditions('new_exam_form',array('id'=>$nefId,'student_id'=>$student_id),array('theory_marks'=>$marks));
		echo "<title>MMYVVONLINE</title>";
		echo $this->db->last_query();
	}

	public function update_exam_fields_of_paper_master_table(){
		echo "Update Exam Data in Paper Master";
		$this->db->select('*');
       		$this->db->from('paper_testid_relation');
		$rows=$this->db->get()->result();
		$i=1;
		foreach($rows as $row){

			echo "<br> ".$i." ". $row->paper_code ." ". $row->test_id;
			$data  = array('test_id'=>$row->test_id);
            $where = array('test_id'=>'0','paper_code'=> $row->paper_code,'id'=> $row->paper_id);
            $update =$this->Common_model->updateRecordByConditions('paper_master',$where,$data);
			 echo  $this->db->last_query(); //die;
			$i++;
		
		// echo $this->db->last_query().'<br>';
		}
	}
	public function remaining_failed_student_list(){
		$university_mode='REG';
		$this->db->select('count(*) as num,student.*');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.old_class_id');
		$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('university_mode',$university_mode);
		$this->db->where('exam_form','Y');
		if ($university_mode=='REG') {
			$this->db->where('new_exam_form.theory_marks < paper_master.min_theory_marks');
		}else{
			$this->db->where('new_exam_form.theory_marks < paper_master.private_min_theory_marks');
		}
		// $this->db->where('new_exam_form.class_id',$class_id);
		$this->db->where_in('old_class_id',array(162,165,180,183,170,173,134,116,101,169,110,125,128,131));
		$this->db->where('roll_number !=','');
		$this->db->where('theory_marks','');
		$this->db->where('paper_type','theory');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->order_by('student.course_group_id,student.old_class_id,student.roll_number','asc');
		$data['students'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		
		$this->load->view('header',array('title' => 'Student Failed Remaining Marks List'));
		$this->load->view('admin/remaining_student_average_marks_offline',$data);
		$this->load->view('footer');
	}

	public function remaining_withheld_student_list(){
		$class_id=104;
		$university_mode='PVT';
		if ($university_mode=='REG') {
		$this->db->select('count(*) as num,student.*,new_exam_form.paper_code,paper_master.min_theory_marks as min_marks, paper_master.max_theory_marks as max_marks');
		}else{
			$this->db->select('count(*) as num,student.*,new_exam_form.paper_code,paper_master.private_min_theory_marks as min_marks,private_max_theory_marks as max_marks');
		}
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.class_id');
		$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
		$this->db->join('student_av_check', 'student_av_check.enrollment_no_check = student.enrollment_no and student_av_check.paper_code_check=new_exam_form.paper_code');
		$this->db->order_by('new_exam_form.course_group_id,new_exam_form.class_id','asc');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('university_mode',$university_mode);
		$this->db->where('new_exam_form.theory_marks','');
		$this->db->where('exam_form','Y');
		if ($university_mode=='REG') {
			$this->db->where('new_exam_form.theory_marks < paper_master.min_theory_marks');
		}else{
			$this->db->where('new_exam_form.theory_marks < paper_master.private_min_theory_marks');
		}
		$this->db->where('new_exam_form.class_id',$class_id);
		$this->db->where('roll_number !=','');
		$this->db->where('paper_type','theory');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->having('num<',3);
		$data['students'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		$this->load->view('header',array('title' => 'Student Failed Remaining Marks List'));
		$this->load->view('admin/remaining_withheld_student_list',$data);
		$this->load->view('footer');
	}
	public function update_withheld_student_remaining_marks($student_id,$paper,$marks)
	{
		// $this->Common_model->updateRecordByConditions('new_exam_form',array('paper_code'=>$paper,'student_id'=>$student_id,'theory_marks'=>''),array('theory_marks'=>$marks));
		echo "<title>MMYVVONLINE</title>";
		echo $this->db->last_query();
	}

	public function update_group_id_in_new_exam_form(){
		// $this->db->limit(10);
		//$students = $this->Common_model->getRecordByWhere('student',array('group_id !='=>''));
		$sql="SELECT DISTINCT(s.student_id),s.group_id,s.class_id FROM `new_exam_form` as e join student as s on s.student_id=e.student_id and s.class_id=e.class_id WHERE e.group_id='' and s.group_id!='' order by s.class_id";
		$students = $this->db->query($sql)->result_array();

		foreach($students as $student){
		$where = array(
			'student_id'=>$student['student_id'],
			'class_id'=>$student['class_id'],
		);
		$data = array(
			'group_id'=>$student['group_id']
		);

		$this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
		echo $this->db->last_query().'<br>';//die;
	}
}

public function update_sub_group_id_in_new_exam_form(){
	// $this->db->limit(10);
	 $this->db->where_in('class_id',array(134,107,116,119,110,125,128,131));
	//$this->db->where_in('class_id',array(110,119,125,128,131));
	// if($class_id == 101 || $class_id == 104){
	// 	$this->db->where(array('sub_group_id'=>1));
	// }
	$this->db->limit(100);
	$papers = $this->Common_model->getRecordByWhere('paper_master');
	// echo '<pre>';
	// print_r($papers);die;
	foreach($papers as $paper){
	$where = array(
		'paper_code'=>$paper->paper_code,
		'class_id'=>$paper->class_id,
		'sub_group_id'=>0,
	);
	$data = array(
		'sub_group_id'=>$paper->sub_group_id
	);
	

	$this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
	echo $this->db->last_query().'<br>';
}
}

public function update_sub_group_id_in_new_exam_form_sub(){
	// $this->db->limit(10);
	$this->db->where_in('class_id',array(101,104));
	$this->db->where(array('sub_group_id'=>1));
	
	$papers = $this->Common_model->getRecordByWhere('paper_master');
	echo '<pre>';
	print_r($papers);die;
	foreach($papers as $paper){
	$where = array(
		'paper_code'=>$paper->paper_code,
		'class_id'=>$paper->class_id,
		'sub_group_id'=>0,
	);
	$data = array(
		'sub_group_id'=>$paper->sub_group_id
	);
	

	// $this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
	echo $this->db->last_query().'<br>';
}
}

public function update_sub_group_id_in_backlog_exam_form(){
    // $this->db->limit(300);
    $this->db->where_in('class_id',array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,121));
    $students = $this->Common_model->getRecordByWhere('backlog_student',array('exam_year'=>'June 2025',
    'exam_form'=>'Y'));
    // $this->Common_model->last_query();
    // ,'mode'=>'REG'
    echo count($students);
    foreach($students as $student){
     $papers =   $this->Common_model->getRecordByWhere('old_result_data',array('student_id'=>$student->student_id,'class_id'=>$student->class_id));
    //  $this->Common_model->last_query();
    //  echo $papers[0]->student_id;die;
    //  echo '<pre>';
    //  print_r($papers);die;
     foreach ($papers as $paper){
        $where = array(
             	'paper_code'=>$paper->paper_code,
             	'class_id'=>$paper->class_id,
                'student_id'=>$paper->student_id,
            	'sub_group_id'=>0,
             );
            $data = array(
            	'sub_group_id'=>$paper->sub_group_id
            );
        $this->Common_model->updateRecordByConditions('backlog_exam_form',$where,$data);
	        echo $this->db->last_query().'<br>';
     }
    }

}

public function update_group_id_in_backlog_exam_form(){
    // $this->db->limit(300);
    $this->db->where_in('class_id',array(101,104,105));
    $students = $this->Common_model->getRecordByWhere('backlog_student',array('exam_year'=>'June 2024',
    'exam_form'=>'Y'));
    // ,'mode'=>'REG'
    // $this->Common_model->last_query();
    echo count($students);
    foreach($students as $student){
     $papers =   $this->Common_model->getRecordByWhere('old_result_data',array('student_id'=>$student->student_id,'class_id'=>$student->class_id));
    //  $this->Common_model->last_query();
    //  echo $papers[0]->student_id;die;
    //  echo '<pre>';
    //  print_r($papers);die;
     foreach ($papers as $paper){
        $where = array(
             	'paper_code'=>$paper->paper_code,
             	'class_id'=>$paper->class_id,
                'student_id'=>$paper->student_id,
            	'group_id'=>'',
             );
            $data = array(
            	'group_id'=>$paper->group_id
            );
        $this->Common_model->updateRecordByConditions('backlog_exam_form',$where,$data);
	        echo $this->db->last_query().'<br>';
     }
    }

}
// public function update_sub_group_id_in_new_exam_form_group_paper(){
// 	// $this->db->limit(10);
// 	// $this->db->where_in('class_id',array(101,104));
// 	$this->db->where_not_in('sub_group_id',array(0,1));
// 	$papers = $this->Common_model->getRecordByWhere('group_paper');
// 	echo '<pre>';
// 	print_r($papers);die;
// 	foreach($papers as $paper){
// 	$where = array(
// 		'paper_code'=>$paper->paper_code,
// 		'group_id'=>$paper->group_id,
// 		'sub_group_id'=>0,
// 	);
// 	$data = array(
// 		'sub_group_id'=>$paper->sub_group_id
// 	);
	

// 	// $this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
// 	echo $this->db->last_query().'<br>';
// }
// }

public function update_roll_no_old_data(){
	
	$sql = "SELECT o.*,s.roll_no FROM `old_exam_data` as o join student as s on s.student_id=o.student_id WHERE `exam_year`='January 2025' and s.old_class_id=o.class_id and exam_status='R' and s.roll_no!=o.roll_no  limit 500";
	$students = $this->db->query($sql)->result_array();
	
	foreach($students as $student){
		$where = array('student_id'=>$student['student_id'],'exam_year'=>'January 2025','id'=>$student['id'],'roll_no'=>'0');
		$data = array ('roll_no'=>$student['roll_no']);
		$this->Common_model->updateRecordByConditions('old_exam_data',$where,$data);
		echo $this->db->last_query().'<br>';
	}

}
	public function center_wise_student_count_list(){
		$data['courses'] = $this->Common_model->getRecordByWhere('course_group');
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('header',array('title' => 'Center Wise Student Count List'));
		$this->load->view('admin/center_wise_student_count_list',$data);
		$this->load->view('footer');
	}

	public function get_student_count_data(){
		$data['course_group_id'] = $this->input->post('course_group_id');
		$this->db->select('count(*) as num,center_id,center_code,examcentercode,exam_center_id');
		$this->db->from('student');
		$this->db->where('course_group_id',$this->input->post('course_group_id'));
		$this->db->where('session','July 2022');
		$this->db->group_by('center_id');
		$data['students'] = $this->db->get()->result();
		$dt = $this->load->view('admin/getStudentCountData',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}

	public function update_sub_group_id(){
	
		$this->db->select('e.paper_code,e.sub_group_id,e.class_id,e.group_id,e.student_id');
		$this->db->from('new_exam_form as e');
		$this->db->join('old_result_data as r', 'r.student_id = e.student_id AND r.paper_code=e.paper_code');
		$this->db->where_in('e.class_id',array(104,105,106,107,108,109,134,135,136));
		$this->db->where('r.sub_group_id',0);
		//$this->db->where_in('class_id',array(101,104,107,110,116,119,125,128,131,134));
		 $this->db->limit(25000);
		$papers = $this->db->get()->result();
		//echo $this->db->last_query();
		foreach($papers as $paper){
			
			$where = array('paper_code'=>$paper->paper_code,'class_id'=>$paper->class_id,'student_id'=>$paper->student_id,'sub_group_id'=>0);
			$data = array ('sub_group_id'=>$paper->sub_group_id,'group_id'=>$paper->group_id);
			$this->Common_model->updateRecordByConditions('old_result_data',$where,$data);
			echo $this->db->last_query();
		}
	}

	public function add_extra_papers_in_old_result_data()
	{
		
		//$class_id=253;
		 $class_id=269;
		$num_of_papers=4;
		
		$sql="SELECT count(*)as num,e.* FROM `old_result_data` as e join `old_exam_data` as s on s.id=e.`exam_data_id` WHERE s.`class_id`=".$class_id." AND exam_year='May 2023' and exam_status='R' group by s.student_id HAVING num='".$num_of_papers."'";
		$sql_result = $this->db->query($sql);
        $students = $sql_result->result_array();
		foreach ($students as $student) {
			 	$query="SELECT * FROM `new_exam_form` WHERE `student_id`='".$student['student_id']."' and class_id='".$class_id."' and paper_type!='Theory'";
			$query_result = $this->db->query($query);
			$student_practical_records = $query_result->result_array();
			foreach ($student_practical_records as $row) {
				$where="`id` = '".$row['paper_id']."'"  ;
				$papers = $this->Common_model->get_record('paper_master','*',$where);
				//print_r($papers);
				$ResultData = array(
					'exam_data_id' =>  $student['exam_data_id'] ,
					'student_id' =>  $row['student_id'] ,
					'course_group_id' => $row['course_group_id'] ,
					'class_id' => $row['class_id'] ,
					'paper_code'=> $row['paper_code'] ,
					'type'=> $row['paper_type'] ,
					'max_theory_marks'=> $papers[0]['max_theory_marks'],
					'max_int_marks'=> $papers[0]['max_internal_marks'],
					'min_theory_marks'=> $papers[0]['min_theory_marks'],
					'min_int_marks'=> $papers[0]['min_internal_marks'],
					'theory_marks'=> $row['theory_marks'],
					'p_marks'=> $row['p_marks'],
					'int_marks'=> $row['int_marks'],
					'paper_name'=>  $papers[0]['paper_name'],
					'result' => 'PASS',
					'group_id' => $row['group_id'],
					'sub_group_id' => $row['sub_group_id'],
					'p_order'=> $row['paper_order'] 
				);
				
				echo "<pre>";
				
				//print_r($ResultData);

			   $insert = $this->Common_model->insertAll('old_result_data',$ResultData);
				echo $this->db->last_query().'<br>';
				
			}
			
			
		}	
		
	}
	public function update_int_marks_new_exam_form_to_old_result_data()
	{
		
		$sql = "SELECT * FROM `old_result_data` WHERE `int_marks`='-' and type!='theory' order by student_id limit 1000";
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			
			$new_exam_sql = "SELECT * FROM `new_exam_form` WHERE `student_id`='".$student['student_id']."' and class_id= '".$student['class_id']."' and paper_type!='Theory' and paper_code='".$student['paper_code']."' ";
			$new_exam_data =	$this->db->query($new_exam_sql)->result_array();
			//print_r($new_exam_data);
			$i=0;
			
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data[0]['paper_code'].' Marks=>'.$new_exam_data[0]['int_marks'].'<br>';

			echo	$update_marks = "update old_result_data set int_marks='".$new_exam_data[0]['int_marks']."' where id=".$student['id']." and int_marks='-'";
			echo '<br>';
				$i++;
				$this->db->query($update_marks);
		
		
		}
	}

	public function grade_pattern_papers_list(){
		
		$this->db->select('pm.*,cm.class_name');
		$this->db->from('paper_master as pm');
		$this->db->join('class_master as cm','cm.id = pm.class_id');
		$this->db->where('cbcs','Y');
		$this->db->where('cbcs_paper','Y');
		$this->db->order_by('cm.id');
		$papers_pg = $this->db->get()->result();
	
		$this->db->select('pm.*,cm.class_name');
		$this->db->from('paper_master as pm');
		$this->db->join('class_master as cm','cm.id = pm.class_id');
		$this->db->where_in('cm.course_group_id',array(11,12,13,14,16,17,18,19,20,21));
		$this->db->where_in('cm.class_name',array('I Year','II Year'));
		$papers_ug = $this->db->get()->result();
 		$data['papers'] = array_merge($papers_pg,$papers_ug);
		$this->load->view('header',array('title' => 'Grade Pattern Papers'));
		$this->load->view('admin/grade_pattern_papers',$data);
		$this->load->view('footer');
	
	}

	public function check_div_form_to_old_result_data()
	{
		echo "<h3>Check Div of final and Avg % of all Sem</h3>";
		$sql = "SELECT o.* FROM `old_exam_data`as o join student as s on s.student_id= o.student_id and s.class_id=o.class_id and s.course_complete='Y' WHERE exam_year='March 2023' and o.class_id in (155,182)   ";//order by o.student_id limit 1000";
		//and o.enrollment_no='AH/22100348'
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			$result= "<br> ".$s_no." ".$student['class_id']." ".$student['student_id']."  ".$student['enrollment_no'];
			$percent=$student['percentage'];
			if($percent>=60){
				$div = "First";
			  }elseif($percent<60 && $percent>=40){
				$div  = "Second";
			  }else{
				$div = "Third";
			  }
			  $result.= " Division  ". $div;
			$query = "SELECT * FROM `old_exam_data` WHERE student_id ='".$student['student_id']."' and exam_result!='FAIL'";
			$rset = $this->db->query($query)->result_array();
			$per_total=0;
			foreach ($rset as $all_class_result) {
				//echo "<br>".	$all_class_result["percentage"];
				$per_total+=$all_class_result["percentage"];
			}
		
			//echo "<br>".$per_total/2;
			$per_total=$per_total/2;
			$percentage = round($per_total,2);
			if($percentage>=60){
			  $division = "First";
			}elseif($percentage<60 && $percentage>=40){
			  $division  = "Second";
			}else{
			  $division = "Third";
			}
			$result.= " Correct  ". $division;
			if($division!=$div){
			echo $result;
			$s_no++;
			}
		}
	}
	public function get_below_fifty_per_marks()
	{
		echo "<h3>Get BA I Sem PVT Student LIst of below 50% Marks in Subjects</h3>";
		 $sql = "SELECT * FROM `paper_master` WHERE `class_id`=104 AND type='theory' AND id in(367,368,371,381) ORDER BY `paper_master`.`paper_no` ASC  ";
	
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo "<table>";
		foreach ($rs as $paper) {
		echo	$result= "<tr><td> ".$s_no."</td><td> ".$paper['id']." </td><td>".$paper['paper_code']." </td><td>".$paper['paper_name']." </td><td> ".$paper['private_max_theory_marks']."</td><td>";
		if($paper['sub_group_id']==1){
			$th_marks=25;
		}
		else{
			$th_marks=50;
		}
		 $studentsql = "SELECT count(*) as total FROM `new_exam_form_backup_ba_pvt` WHERE `class_id`=104 AND paper_code= '".$paper['paper_code']."' AND theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and update_marks_status='N' AND student_id in (SELECT `student_id` FROM `student` WHERE `class_id`=104 and exam_form='Y' and `university_mode`='PVT'); ";
	
		$exam_papers = $this->db->query($studentsql)->result_array();
		
		//print_r($exam_papers);
		echo "<b><a href='get_new_marks/".$paper['id']."'>". $exam_papers[0]['total']."</a></b></td></tr>";
		$s_no++;
		}
		echo "</table>";
	}
	public function get_new_marks($paperID)
	{
		$sql = "SELECT * FROM `paper_master` WHERE `class_id`=104 AND type='theory' AND id ='".$paperID."'";
	//30 Jan 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo "<table>";
		foreach ($rs as $paper) {
		echo	$result= "<tr><td> ".$s_no."</td><td> ".$paper['id']." </td><td>".$paper['paper_code']." </td><td>".$paper['paper_name']." </td><td> ".$paper['private_max_theory_marks']."</td><td></tr></table>";
		if($paper['sub_group_id']==1){
			$th_marks=25;
		}
		else{
			$th_marks=50;
		}
		 // $studentsql = "SELECT e.*,s.name,s.enrollment_no,s.roll_number FROM `new_exam_form` as e join student as s on s.student_id=e.student_id  WHERE e.`class_id`=104 AND e.paper_id= '".$paperID."' AND e.theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and s.`class_id`=104 and s.exam_form='Y' and s.`university_mode`='PVT' ";
		 $studentsql = "SELECT e.*,s.name,s.enrollment_no,s.roll_number FROM `new_exam_form_backup_ba_pvt` as e join student as s on s.student_id=e.student_id  WHERE e.`class_id`=104 AND e.paper_id= '".$paperID."' AND e.theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and s.`class_id`=104 and s.exam_form='Y' and s.`university_mode`='PVT' and update_marks_status='N' limit 1000";
	
		$student_papers = $this->db->query($studentsql)->result_array();
		echo "<table>";
		$i=1;
		foreach ($student_papers as $stud) {
			$a=(int)$stud['theory_marks']/3;
			$new_marks=$stud['theory_marks']+$a;
			$new_marks=round($new_marks,0);
			echo "<tr><td>".$i."</td><td>".$stud['student_id']."</td><td>".$stud['name']."</td><td>".$stud['enrollment_no']."</td><td>".$stud['theory_marks']."</td><td><b>".$new_marks."</b></td></tr>";
			/*$updateSQL="update `new_exam_form` set theory_marks='".$new_marks."' WHERE `class_id`=104 AND paper_id= '".$paperID."' AND student_id='".$stud['student_id']."' AND id='".$stud['id']."'";
			 $this->db->query($updateSQL);
			$updateStatus="update new_exam_form_backup_ba_pvt set update_marks_status='Y' where id='".$stud['id']."'";
			 $this->db->query($updateStatus);
			*/
			$i++;
		}
		echo "</table>";
		
		$s_no++;
		}
		

	}
	public function get_single_subject_fail_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code,p.private_max_theory_marks FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and p.private_min_theory_marks>e.theory_marks and e.theory_marks not in ('','00','ABS') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code,e.theory_marks FROM `new_exam_form` as e  join student as s on s.student_id =e.student_id WHERE   e.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'  and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' and e.theory_marks  in ('','00','ABS') having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				
				$avgsql="SELECT sum(e.theory_marks) as obtain , sum(p.private_max_theory_marks) as obtainfrom FROM `paper_master` as p  join `new_exam_form` as e on p.id=e.paper_id WHERE p.paper_code=e.paper_code and e.class_id=104 and p.class_id=104 and  `student_id`='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."'";
				$avg = $this->db->query($avgsql)->result_array();
				$average=($avg[0]['obtain']*100)/$avg[0]['obtainfrom'];
				$average=number_format((float)$average, 2, '.', '');
				$avg_marks = round($student['private_max_theory_marks']*$average/100);
				echo " , ".$avg[0]['obtain']." , ".$avg[0]['obtainfrom']." , ".$average." , ".$student['private_max_theory_marks']." , ".$avg_marks;
				$updateSql="Update new_exam_form set theory_marks='".$avg_marks."' WHERE paper_code='".$student['paper_code']."' AND student_id='".$student['student_id']."' ";
				//Set AVG Marks in remaining paper
				//$this->db->query($updateSql);
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " total_fail_count ".$total_fail_count;
	
		

	}
	public function get_single_subject_zero_marks_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code,p.private_max_theory_marks FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and e.theory_marks='00' and e.theory_marks not in ('','ABS') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'   and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' AND (p.private_min_theory_marks>e.theory_marks OR e.theory_marks  in ('','ABS')) having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				$avgsql="SELECT sum(e.theory_marks) as obtain , sum(p.private_max_theory_marks) as obtainfrom FROM `paper_master` as p  join `new_exam_form` as e on p.id=e.paper_id WHERE p.paper_code=e.paper_code and e.class_id=104 and p.class_id=104 and  `student_id`='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."'";
				$avg = $this->db->query($avgsql)->result_array();
				$average=($avg[0]['obtain']*100)/$avg[0]['obtainfrom'];
				$average=number_format((float)$average, 2, '.', '');
				$avg_marks = round($student['private_max_theory_marks']*$average/100);
				echo " , ".$avg[0]['obtain']." , ".$avg[0]['obtainfrom']." , ".$average." , ".$student['private_max_theory_marks']." , ".$avg_marks;
				$updateSql="Update new_exam_form set theory_marks='".$avg_marks."' WHERE paper_code='".$student['paper_code']."' AND student_id='".$student['student_id']."' ";
				//Set AVG Marks in remaining paper
			//	$this->db->query($updateSql);
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " total_zero_count ".$total_fail_count;
	
		

	}
	public function get_single_subject_blank_marks_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code,p.private_max_theory_marks FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and e.theory_marks='' and e.theory_marks not in ('00','ABS') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'   and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' AND (p.private_min_theory_marks>e.theory_marks OR e.theory_marks  in ('00','ABS')) having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				 $avgsql="SELECT sum(e.theory_marks) as obtain , sum(p.private_max_theory_marks) as obtainfrom FROM `paper_master` as p  join `new_exam_form` as e on p.id=e.paper_id WHERE p.paper_code=e.paper_code and e.class_id=104 and p.class_id=104 and  `student_id`='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."'";
				$avg = $this->db->query($avgsql)->result_array();
				$average=($avg[0]['obtain']*100)/$avg[0]['obtainfrom'];
				$average=number_format((float)$average, 2, '.', '');
				$avg_marks = round($student['private_max_theory_marks']*$average/100);
				echo " , ".$avg[0]['obtain']." , ".$avg[0]['obtainfrom']." , ".$average." , ".$student['private_max_theory_marks']." , ".$avg_marks;
				 $updateSql="Update new_exam_form set theory_marks='".$avg_marks."' WHERE paper_code='".$student['paper_code']."' AND student_id='".$student['student_id']."' ";
				//Set AVG Marks in remaining paper
				//$this->db->query($updateSql);
				
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " total_zero_count ".$total_fail_count;
	
		

	}
	public function get_single_subject_abs_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and e.theory_marks='ABS' and e.theory_marks not in ('','00') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'   and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' AND (p.private_min_theory_marks>e.theory_marks OR e.theory_marks  in ('','00')) having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				
				//echo "<pre>";
			   // print_r($failrs);
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " Total ABS count ".$total_fail_count;
	
		

	}

    public function check_old_percentage(){
        // $this->db->limit(1);
        // $this->db->where('id',37339);
        $old_datas = $this->Common_model->getRecordByWhere('old_exam_data', array('exam_year'=>'July 2023'));
       $student = [];
        foreach($old_datas as $data){
           
            $old_result_datas = $this->Common_model->getRecordByWhere('old_result_data', array('exam_data_id'=>$data->id));
            $class_data = $this->Common_model->getRecordById('class_master', 'id', $data->class_id);
            $total_marks= 0;
            $total_obtained_marks=0;
            foreach($old_result_datas as $old){
                if($old->type == 'theory'){
                    if($class_data->internal == 'Y' && $data->university_mode !='PVT'){
                        
                        if($old->theory_marks == 'ABS' || $old->int_marks == 'ABS'){
                            if($old->theory_marks == 'ABS' && $old->int_marks == 'ABS'){
                                $total_obtained_marks +=0;
                            }elseif($old->int_marks == 'ABS'){
                                $total_obtained_marks += $old->theory_marks; 
                            }elseif($old->theory_marks == 'ABS'){
                                $total_obtained_marks +=$old->int_marks;
                            }
                            
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;
                        }else{
                            $total_obtained_marks += $old->theory_marks + $old->int_marks;
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;
                        }
                       
                    }else{
                        if($old->theory_marks == 'ABS'){
                            $total_obtained_marks += 0;
                            $total_marks += $old->max_theory_marks;
                        }else{
                            $total_obtained_marks += $old->theory_marks;
                            $total_marks += $old->max_theory_marks;
                        }
                       
                    }
                }else if($old->type == 'Sessional'){
                    $total_obtained_marks += $old->int_marks;
                    $total_marks += $old->max_int_marks;
                }else{
                    if($class_data->practical_internal_marks == 'Y' && $data->university_mode !='PVT'){
                        if($class_data->id == 206 && $data->marks_pattern == 'MARKS'){
                            $total_obtained_marks += $old->p_marks;
                            $total_marks += $old->max_theory_marks;
                        }
                        elseif($old->p_marks == 'ABS' || $old->int_marks == 'ABS'){
                            if($old->p_marks == 'ABS' && $old->int_marks == 'ABS'){
                                $total_obtained_marks += 0;
                            }elseif($old->int_marks == 'ABS'){
                                $total_obtained_marks += $old->theory_marks; 
                            }elseif($old->p_marks == 'ABS'){
                                $total_obtained_marks +=$old->int_marks;
                            }
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;;
                        }else{
                            $total_obtained_marks += $old->p_marks + $old->int_marks;
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;
                        }
                       
                    }else{
                        if($old->p_marks == 'ABS'){
                            $total_obtained_marks += 0;
                            $total_marks += $old->max_theory_marks;
                        }else{
                            $total_obtained_marks += $old->p_marks;
                            $total_marks += $old->max_theory_marks;
                        }
                       
                    }
                }
            }
           
            if($total_obtained_marks == 0 || $total_marks ==0){
                $percentage = 0;
            }else{
                $percentage = round(($total_obtained_marks/$total_marks) *100,2);
            }
           
           
            if($total_marks != $data->total_marks || $total_obtained_marks != $data->obtain_marks || $percentage != $data->percentage){
                echo 'Total :'.$total_marks.'Obtain :'.$total_obtained_marks.'Percentage :'.$percentage .'<br>';
                // echo $data->percentage .'actual'.$percentage ;
                $student[] =  $data->id;
                echo 'Exam Id :'. $data->id.'<br>';
            }
        }
       
         echo 'Count :' .count($student);
    }

	public function roll_number_correction(){

		//$sql = "SELECT o.id,o.student_id,o.class_id ,s.roll_number FROM `old_exam_data` as o join student as s on o.student_id=s.student_id WHERE o.class_id=s.old_class_id and o.`roll_no`!=s.roll_number and exam_year='July 2023' and exam_status='R'";
		echo $sql="SELECT * FROM `old_exam_data` as o JOIN student as s on o.student_id=s.student_id WHERE o.marks_pattern!=s.exam_pattern";
		//7 March 24
		$this->db->limit(1);
		$rs = $this->db->query($sql)->result_array();
		echo "<br>";
		echo count($rs);
		echo "<br>";
		$i=1;
		foreach ($rs as $student) {
			echo "<br>".$i."    ";
			echo $student['id']." ".$student['student_id']." ".$student['class_id']." ".$student['roll_number'] ." OLD ".$student['marks_pattern']." STUD ".$student['exam_pattern'];

			//$data  = array('roll_no'=>$student['roll_number'] );
			$data  = array('marks_pattern'=>$student['exam_pattern'] );
			$where = array('student_id'=>$student['student_id'],'id'=> $student['id']);
			//$update =$this->Common_model->updateRecordByConditions('old_exam_data',$where,$data);
			$i++;
			
			//break;
		}
	}

	public function update_pvt_grade_marks(){

		$class_id=107;
		 $sql="SELECT id,student_id,paper_code,theory_marks,(theory_marks+2) as new_marks FROM `new_exam_form` WHERE class_id='".$class_id."' and theory_marks in (30,31) and sub_group_id!=1  and `student_id` in (SELECT student_id FROM `student` WHERE  `roll_number` in (210710091,210710263,210710295,210710297,210710410,210710460,210710501,210710533,210710572,210710581,210710691,210710766,210711020,210711062,210711065,210711095,210711203,210711380,210711392,210711422,210711776,210711822,210711834,210711851,210712018,210712061,210712130,210712195,210712245,210712285,210712379,210712429,210712545,210712587) and exam_form='Y' )  ";
		//210410083,210410113,210410114,210410116,210410213,210410218,210410371,210410482,210410523,210410537,210410599,210410719,210410730,210410732,210410841,210410981,210411117,210411118,210411119,210411149,210411295,210411375,210411435,210411437,210411614,210411667,210411678,210411679,210411756,210411794,210411843,210412033,210412148,210412551,210412646,210413194,210413373,210413381,210413507,210413525,210413526,210413528,210413645,210413650,210413722,210413904,210413958,210414060,210414157,210414162,210414169,210414178,210414179,210414184,210414190,210414200,210414203,210414299,210414306,210414335,210414363,210414371,210414381,210414571,210414679,210414743,210414777,210414823,210414861,210415012,210415105,210415134,210415242,210415307,210415383,210415385,210415400,210415410,210415569,210415580,210415597,210415665,210415777,210415856,210416241,210416277,210416299,210416551,210416728,210416767,210416825,210416831,210416845,210416866,210416907,210416913,210416965,210416986,210416993,210417006,210417008,210417010,210417028,210417033,210417247,210417350,210417612,210417841,210417945,210417951,210417957,210417964,210417965,210417969,210418073,210418111,210418183,210418298,210418411,210418427,210418441,210418445,210418470,210418513,210418620,210418722,210418765,210418964,210419025,210419204,210419206,210419213,210419226,210419227,210419442,210419510,210420144,210420197,210420302,210420379,210420388,210420433,210420482,210420500,210420619,210420690,210420706,210420730,210420753,210420764,210420829,210420836,210420886,210420941,210420985,210421042,210421230,210421246,210421286,210421302,210421305,210421384,210421469,210421505,210421537,210421696,210421701,210421737,210421753,210421776,210421844,210422058,210422065,210422333,210422380,210422480,210422607,210422672,210422724,210423014,210423145,210423171,210423185,210423226,210423311,210423465,210423590,210423681,210423806,210423917,210423936,210423984,210424021,210424077,210424083,210424109,210424392,210424499,210424611,210424759,210424784,210424881,210425039,210425144,210425264,210425496,210425592,210425653,210425757,210425784,210426060,210426100,210426351,210426368
		//$this->db->limit(1);
		$rs = $this->db->query($sql)->result_array();
		echo "<br>";
		echo count($rs);
		echo "<br>";
		$i=1;
		foreach ($rs as $student) {
			echo "<br>".$i."    ";
			echo $student['id']." ".$student['student_id']." ".$student['paper_code']." ";
			echo "<br>";
			//$data  = array('roll_no'=>$student['roll_number'] );
				$sql1="update new_exam_form set theory_marks= ".$student['new_marks']." where id='".$student['id']."'";
				$sql2="update old_result_data set theory_marks= ".$student['new_marks']." where student_id='".$student['student_id']."' AND paper_code='".$student['paper_code']."' ";
				$old_datas = $this->Common_model->getRecordByWhere('old_exam_data', array('exam_year'=>'July 2023','class_id'=>$class_id,'student_id'=>$student['student_id']));
			
		
				 $obtain_marks= $old_datas[0]->obtain_marks+2;
				 $percentage=round(($obtain_marks/$old_datas[0]->total_marks)*100,2);
				
			 $sql3="update old_exam_data set obtain_marks='".$obtain_marks."',percentage='".$percentage."' where student_id='".$student['student_id']."' AND  class_id='".$class_id."'AND exam_year='July 2023'" ;
			$i++;

			 $this->db->query($sql1);
			 $this->db->query($sql2);
			 $this->db->query($sql3);
			
		//	break;
		}
	}
	

	public function update_AGPA_CGPA_class_list(){
		
		$this->db->select('count(class_id) as total, class_id');
		$this->db->from('old_exam_data');
		$this->db->where('university_mode="REG" and marks_pattern ="GRADE"  and exam_status = "R"');  
		$this->db->where_in('id',array(35181,35208,35226,35238,36848,36849,137034,36851,35289,35292,35306,35316,35342,35344,35378,35381,35395,35412,35413,35419,35428,35481,35497,35582,35583,35611,35612,35640,35651,36944,35668,35680,35726,35742,35795,36281,36215,36225,36256,36273));
		$this->db->group_by('class_id');
		$class_list = $this->db->get()->result();
	
		
 		$data['class_list'] = $class_list;
	
		$this->load->view('header',array('title' => 'Grade Pattern Class List'));
		$this->load->view('admin/update_AGPA_CGPA_class_list',$data);
		$this->load->view('footer');
	
	}
	public function update_AGPA_CGPA($class_id,$cbcs){
		$this->db->select('*');
		$this->db->from('old_exam_data');
		$this->db->where('university_mode="REG" and exam_status="R" and marks_pattern ="GRADE" and class_id="'.$class_id.'"');  
		// $this->db->where('enrollment_no' ,'AI/22210465');
		// $this->db->where('student_id',380243);
		$this->db->where_in('id',array(35181,35208,35226,35238,36848,36849,137034,36851,35289,35292,35306,35316,35342,35344,35378,35381,35395,35412,35413,35419,35428,35481,35497,35582,35583,35611,35612,35640,35651,36944,35668,35680,35726,35742,35795,36281,36215,36225,36256,36273));
        //  $this->db->where_in('id',array(157037,137380,137387,137394,137481,137544,137546,137567,137580,137611,137855,137863,137906,137915,137916,137937,137950,137961,138027,138042,138078,138134,138159,138222,138225,138248,138326,138352,138364,138456,138551,138585,138656,138691,138777,138805,138812,138876,138891,138914,138921,138935,138961,139084,139096,139104,139112,139115,139121,139143,139291,139352,139353,139360,139390,139434,139441,139445,139557,139802,139847,139882,139963,140058,140126,140162,140171,140181,140263,140269,140318,140336,140338,140341,140447,140487,140513,140527,140624,140684,140687,140715,140736,140766,140813,140823,140831,140837,140943,141047,141085,141175,141306,141335,141374,141448,141469,141472,141478,141486,141497,141526,141551,141572,141663,141666,141699,141791,141793,141795,141909,141914,141940,141984,142010,142017,142023,142043,142084,142095,142101,142241,142251,142288,142338,142437,142445,142499,142509,142607,142623,142762,142788,142795,142801,142824,142896,142897,142928,142940,142942,142966,143006,143081,143118,143119,143187,143188,143200,143246,143262,143274,143285,143347,143392,143421,143487,143528,143537,143551,143562,143580,143595,143615,143694,143713,143820,143836,143865,143871,143891,144022,144041,144103,144117,144148,144186,144192,144208,144259,144271,144346,144356,144361,144362,144374,144389,144407,144458,144487,144492,144511,144563,144565,144606,144608,144630,144638,144661,144721,144732,144746,144784,144826,144835,144916,144939,144946,144952,144960,145017,145045,145056,145120,145163,145211,145284,145361,145414,145452,145472,145504,145528,145589,145594,145619,145621,145643,145677,145697,145765,145905,145944,145986,146117,146137,146213,146221,146291,146297,146307,146314,146331,146368,146380,146400,146435,146471,146512,146571,146576,146584,146595,146606,146622,146641,146701,146738,146745,146752,146775,146835,146862,146868,146890,146940,146943,146952,147047,147065,147080,147117,147229,147244,147252,147291,147339,147367,147393,147443,147450,147463,147483,147548,147561,147607,147628,147711,147741,147749,147779,147792,147919,147934,147935,147988,147994,147996,148033,148096,148147,148163,148201,148264,148383,148533,148552,148576,148696,148749,148753,148789,148845,148856,148876,148916,148945,148953,148962,148986,149003,149016,149024,149054,149150,149274,149292,149381,149444,149460,149530,149674,149682,149690,149720,149796,149829,149846,149915,149924,150014,150019,150055,150095,150129,150220,150241,150287,150321,150362,150442,150447,150480,150538,150540,150556,150604,150640,150735,150742,150764,150895,150984,151048,151223,151241,151259,151293,151315,151336,151343,151489,151530,151567,151582,151585,151596,151626,151653,151792,151807,151816,151833,151940,152076,152138,152171,152184,152190,152211,152222,152247,152292,152343,152375,152387,152419,152525,152574,152575,152591,152605,152694,152747,152771,152883,152932,152933,153001,153005,153013,153069,153099,153117,153131,153134,153172,153175,153266,153319,153326,153335,153365,153367,153412,153446,153448,153456,153524,153590,153600,153738,153749,153833,153857,153899,153938,153941,153967,154013,154024,154060,154073,154091,154115,154161,154227,154252,154266,154336,154341,154405,154440,154445,154451,154456,154544,154625,154658,154839,154856,154860,154954,154995,155009,155021,155076,155099,155118,155138,155249,155252,155257,155360,155413,155431,155490,155531,155545,155548,155550,155598,155656,155721,155766,155817,155821,155828,155855,155924,155966,156004,156050,156074,156162,156215,156220,156226,156275,156292,156356,156369,156424,156454,156476,156502,156508,156526,156571,156581,156726,156752,156831,156883,156931,156999,157009,157086,157094,157114,157133,157146,157186,157216,157218,157228,157240,157332,157363,157376,157388,157402,157421,157431,157462,157507,157511,157535,157553,157561,157633,173503,95599,172312,172318,172369,172370,172389,172402,172414,172424,172438,172439,172446,172447,172454,172466,172499,172500,172527,172543,172571,172644,172657,172658,172659,172678,172714,172719,172741,172744,172751,172754,172780,172788,172804,172825,172854,172858,172883,172898,172950,172953,172975,172992,173040,173078,173086,173094,173171,173176,173196,173251,173311,173313,173369,173370,173461,173471,173476,173480,173508,173510,173529,173556,173597,173602,173605,173610,173639,173646,173649,173674,173683,173731,173741,173745,173756,173758,173796,173820,173916,173937,174003,174004,174028,174041,174133,174161,174163,174165,174238,174246,174263,174283,174313,174320,174341,174359,174381,174382,174468,174495,174508,174514,174522,174529,174543,174544,174592,174640,174644,174651,174674,174681,174737,174742,174824,174875,174876,174878,174897,178239,178316,146564,147307,147619,147690,149988,142410,143284,137734,137739,57910,140751,53222));
		// $this->db->limit(1000);
		$student_list = $this->db->get()->result();
        if($cbcs == "Y" && $class_id!=101){
			$this->load->model('GradeSheet_old_model_pg');
		}
		else{
			echo 'sdfsdf';
			$this->load->model('Gradesheet_old_model');
		}
		
		foreach($student_list as $student){
		echo "<br>". $student->student_id;
		if($cbcs == 'Y' && $class_id!=101){

			$gradeData = $this->GradeSheet_old_model_pg->view_old_results($student->student_id,$student->course_group_id,$class_id,$student->university_mode,$student->id);
			// print_r($gradeData);
		}else{
			$gradeData = $this->Gradesheet_old_model->view_old_results($student->student_id,$student->course_group_id,$class_id,$student->university_mode,  $student->id,$student->exam_status);
		}
			
			//print_r($gradeData);
			
			echo "<br> AGPA ".$gradeData['agpa'].' ';
			echo $agpa=number_format((float)$gradeData['agpa'], 2, '.', '');
		    echo 	$update_marks = "update old_exam_data set agpa_sgpa='".$agpa."' where id=".$student->id;
			
			$this->db->query($update_marks);

            // echo $this->db->last_query();
			//die;
		}
	
	}

	public function remove_old_class_paper(){
		

		$sql="SELECT e.id,e.student_id FROM `student_report` as s JOIN new_exam_form_report as e on s.student_id=e.student_id WHERE s.class_id!=e.class_id limit 50000";
		$students = $this->db->query($sql)->result_array();
		
		foreach($students as $student)
        {
			$where=array("student_id"=>$student['student_id'],"id"=> $student['id']);
		    $response = $this->Common_model->deleteByWhere('new_exam_form_report',$where);
			echo $this->db->last_query();echo "<br>";

		}
	}	

    public function update_old_exam_data_result_date_main(){
        $exam_year = 'January 2024';
        $this->db->select('DISTINCT(class_id),university_mode');
        $this->db->from('old_exam_data');
        $this->db->limit(20);
        $this->db->order_by('class_id','university_mode');
        $this->db->where(array('exam_year'=>$exam_year,'exam_status'=>'R','marksheet_date'=>""));
        $classes = $this->db->get()->result();
        
        foreach($classes as $class){
            $marksheet_varible = $this->Common_model->getRecordById('marksheet_variables','class_id', $class->class_id);
           $date = ($class->university_mode == 'REG')?$marksheet_varible->result_date:$marksheet_varible->pvt_result_date;
           $date = str_replace('/', '-', $date);
           $data = array('marksheet_date'=>date('Y-m-d',strtotime($date)));
            $update =$this->Common_model->updateRecordByConditions('old_exam_data',array('exam_year'=>$exam_year,'exam_status'=>'R', 'class_id'=>$class->class_id,'university_mode'=>$class->university_mode,'marksheet_date'=>""),$data);
            echo $this->db->last_query() . '<br>';
        } 
    }

    public function update_old_exam_data_result_date_backlog(){
        $exam_year = 'January 2024';
        $this->db->select('DISTINCT(class_id),university_mode');
        $this->db->from('old_exam_data');
        $this->db->order_by('class_id','university_mode');
        $this->db->limit(20);
        $this->db->where(array('exam_year'=>$exam_year,'exam_status'=>'B','marksheet_date'=>""));
        $classes = $this->db->get()->result();
      
        foreach($classes as $class){
            $marksheet_varible = $this->Common_model->getRecordById('marksheet_variables','class_id', $class->class_id);
            $date = ($class->university_mode == 'REG')?$marksheet_varible->backlog_result_date:$marksheet_varible->backlog_pvt_result_date;
            $date = str_replace('/', '-', $date);
            $data = array('marksheet_date'=>date('Y-m-d',strtotime($date)));
            $update =$this->Common_model->updateRecordByConditions('old_exam_data',array('exam_year'=>$exam_year,'exam_status'=>'B', 'class_id'=>$class->class_id,'university_mode'=>$class->university_mode,'marksheet_date'=>""),$data);
            echo $this->db->last_query() . '<br>';
        } 
    }

    public function update_roll_no_in_old(){
        $this->db->select('student_id, id, class_id');
        $this->db->from('old_exam_data');
        $this->db->where(array('exam_year'=>"June 2024", 'class_id'=>175));
        $results =$this->db->get()->result();
      
    foreach($results as $res){
        $response = $this->Common_model->getRecordByWhere('student', array('student_id'=>$res->student_id));
        $this->Common_model->updateRecordByConditions('old_exam_data',array('exam_year'=>'june 2024','id'=>$res->id, 'class_id'=>$res->class_id,'student_id'=>$res->student_id,), array('roll_no'=>$response[0]->roll_no));
        echo $this->db->last_query().'<br>';
       
    }
   
     
    }
    public function final_class_merit_list(){
   
        $year = 'January 2024';
        $this->db->select('cm.id,cm.class_name, cm.course_group_id,cg.course_name');
        $this->db->from('class_master as cm');
        $this->db->join('course_group as cg','cg.id =cm.course_group_id');
        $this->db->join('old_exam_data as od','cm.id =od.class_id');
        $this->db->where_in('od.exam_result', array('PASS', 'PASS BY GRACE'));
        $this->db->where(array('cm.last_class'=>'L','od.exam_year'=>$year,'od.university_mode'=>'REG','od.exam_status'=>'R'));
        $this->db->group_by('cg.course_name');
        $data['classes'] = $this->db->get()->result();
      
        $this->load->view('header');
		$this->load->view('admin/final_class_course_list',$data);
		$this->load->view('footer');
       
    }

    public function view_final_class_merit_list($mode,$id){
      
        // $dept_ids = array(10,11,12,13,20,21,22,23,24,25,26,27,28,29,30);
        // $class_cbcs = array(216,232,236,238,240,246,248,250,252,254,218,278,282);
        // $class_ids=array(103,106,109,112,118,121,127,130,133,136);
        // $data['classData'] = $this->Common_model->getRecordById('class_master','id',$id);
        // if(in_array($id, $class_cbcs) && $mode == 'REG'){
          
        //     $this->load->model('GradeSheet_old_model_pg');
        //     $this->load->model('Gradesheet_tr_model_pg');
        //     // $this->db->where('enrollment_no', 'AI/22210332');
        //     $data['students'] = $this->Common_model->getRecordByWhere('student',array('exam_pattern'=>'GRADE', 'class_id'=>$id,'new_exam_form'=>'Y','result_show'=>'Y'));
        //     $this->load->view('header',array('title'=>$data['students'][0]->course_name));
        //     $this->load->view('admin/final_class_merit_list_pg',$data);
        //     $this->load->view('footer');
        // }else if(in_array($id, $class_ids) && $mode == 'REG'){
            
        //     $this->load->model('Gradesheet_model');
        //     $this->db->where_not_in('center_id',$dept_ids);
        //     // $this->db->where('enrollment_no', "AG/21207398");
        //     $data['students'] = $this->Common_model->getRecordByWhere('student',array('exam_pattern'=>'GRADE', 'class_id'=>$id,'new_exam_form'=>'Y','result_show'=>'Y'));
        //     $this->load->view('header',array('title'=>$data['students'][0]->course_name));
        //     $this->load->view('admin/final_class_merit_list_ug',$data);
        //     $this->load->view('footer');
        // }else{
            $year = 'January 2024';
            $data['classData'] = $this->Common_model->getRecordById('class_master','id',$id);
          $this->db->select('s.*,sd.p_mobile_no');
          $this->db->from('student as s');
          $this->db->join('old_exam_data as od', 'od.student_id=s.student_id and od.class_id=s.class_id');
          $this->db->join('student_data as sd', 'sd.student_id=s.student_id');
          $this->db->where_in('od.exam_result', array('PASS', 'PASS BY GRACE'));
          $this->db->where(array('od.exam_year'=>$year,'s.class_id'=>$id,'exam_pattern'=>'MARKS','course_complete'=>'Y','od.university_mode'=>'REG','od.exam_status'=>'R'));
          $data['students'] = $this->db->get()->result();
        //   $this->Common_model->last_query();
            // $data['students'] = $this->Common_model->getRecordByWhere('student',array('exam_pattern'=>'MARKS', 'class_id'=>$id,'new_exam_form'=>'Y','result_show'=>'Y'));
          
            $this->load->view('header',array('title'=>$data['students'][0]->course_name.' - '.$year));
            $this->load->view('admin/final_class_merit_non_grade',$data);
            $this->load->view('footer');
        // }
    }

    public function copy_student_image(){
        $this->db->where_in('course_group_id', array(76,77,80));
        $this->db->order_by('session','course_group_id','class_id','name');
        $students = $this->Common_model->getRecordByWhere('student', array('enrolled'=>'Y', 'enrollment_no !='=>'-', 'center_id'=>12,'student_id !='=>188079));

    foreach($students as $student){
        $filename="assets/student_image/".$student->session."/".$student->photo;
          

        if (file_exists($filename)) {
    
            //session
        
            $session_name=ucfirst(strtolower(str_replace(" ","_",$student->session)));
            $folder_name="assets/student_image/copy_image/";
            $check_dir=$folder_name.$session_name;
            if(!is_dir($check_dir)) {
                mkdir($folder_name.$session_name,0777,true);
                chmod($folder_name.$session_name,0777);
            }
            // course
            $dir_name=ucfirst(strtolower(str_replace(" ","_",$student->course_name)));
            $folder_name="assets/student_image/copy_image/".$session_name.'/';
            $check_dir=$folder_name.$dir_name;
            if(!is_dir($check_dir)) {
                mkdir($folder_name.$dir_name,0777,true);
                chmod($folder_name.$dir_name,0777);
            }
            $class_name=$student->class_name;
            $folder_name="assets/student_image/copy_image/".$session_name.'/'.$dir_name.'/';
            $check_dir=$folder_name.$class_name;
            if(!is_dir($check_dir)) {
                mkdir($folder_name.$class_name,0777,true);
                chmod($folder_name.$class_name,0777);
            }  
            $i++;
        
            $ext=explode(".",$student->photo);	
        
            $new_name=$student->name.".".$ext[1];
            $newFilePath = $check_dir."/".$new_name;

            if(file_exists($newFilePath)){
                $new_name =	$student->name.' '.$student->f_h_name.".".$ext[1];
                $newFilePath = $check_dir.'/'.$new_name;
            }
            if(copy($filename, $newFilePath)){
                echo 'Success! '; 
            }  
        
        }
            
    }
       
            echo "<hr>";  
    }

    public function update_credit_point_in_old_result_data(){
        $datas = $this->db->query('SELECT DISTINCT(p.paper_code),p.sub_group_id,p.credit_point FROM `old_result_data` as r join paper_master as p on p.paper_code=r.paper_code join old_exam_data as o on o.id=r.exam_data_id and o.marks_pattern ="GRADE" WHERE p.credit_point!=0 and p.sub_group_id!=1 and r.class_id not in (215,101,102,103,104,105) and r.credit_point=0 limit 100')->result_array();

            foreach($datas as $data){
                $this->db->query('UPDATE `old_result_data` SET credit_point ='.$data["credit_point"].' WHERE paper_code="'.$data['paper_code'].'" AND sub_group_id='.$data['sub_group_id'].' AND exam_data_id in (SELECT id FROM `old_exam_data` WHERE marks_pattern ="GRADE") ');
                echo $this->db->last_query().'<br>';
               
            }
    }

    public function update_credit_point_in_old_result_data_for_group(){
       $this->db->select('gp.credit_point,gp.paper_code,gp.sub_group_id, gp.group_id,od.class_id,od.student_id,od.id');
       $this->db->from('group_paper as gp');
       $this->db->join('old_result_data as od','od.paper_code=gp.paper_code and od.sub_group_id=gp.sub_group_id and od.group_id=gp.group_id');
       $this->db->where('gp.sub_group_id !=',1);
       $this->db->where('od.credit_point',0);
       $this->db->where_in('od.class_id',array(215,101,102,103,104,105,106));
       $this->db->limit(25000);
       $papers = $this->db->get()->result();
       // $this->Common_model->last_query();
       foreach ($papers as $paper) {
        $data  = array('credit_point'=> $paper->credit_point);
        $where = array('student_id'=>$paper->student_id,'id'=> $paper->id, 'paper_code'=>$paper->paper_code,'sub_group_id'=>$paper->sub_group_id,'group_id'=>$paper->group_id,'class_id'=>$paper->class_id);
        
        $update =$this->Common_model->updateRecordByConditions('old_result_data',$where,$data);
       }
      echo  $this->db->last_query().'<br>';
    }

    public function check_student_course_duration(){
        $students = $this->Common_model->getRecordByWhere('student',array('new_exam_form !='=>'D'));
        $current_exam_month ='6';
        $current_exam_year ='2025';
        $student_data = [];
        foreach($students as $student){
            $course_d = $this->Common_model->getRecordById('course','course_group_id',$student->course_group_id);
            $due = explode(" ",$student->session);
             if($due[0]=="July"){
              $month = "6";
              $year = $due[1]+$course_d->max_duration;
             }else{
              $month = "12";
              $year = $due[1]+$course_d->max_duration-1;
             }
             $course_d->max_duration." Years".'('.$month." ".$year.')';

            if($year == $current_exam_year && $month < $current_exam_month){
                array_push($student_data,$student);
            }elseif($year < $current_exam_year){
              array_push($student_data,$student);
            }else{
                continue;
            }
          
        }

        $data['students'] = $student_data;
        $this->load->view('header',array('title'=>'Student Course Duration'));
        $this->load->view('admin/check_student_course_duration',$data);
        $this->load->view('footer');
    }

	public function check_backlog_student_course_duration(){
        $students = $this->Common_model->getRecordByWhere('backlog_student',array('exam_form !='=>'D','exam_year'=>'June 2025' ));
        $current_exam_month ='6';
        $current_exam_year ='2025';
        $student_data = [];
        foreach($students as $student){
			$course_d = $this->Common_model->getRecordById('course','course_group_id',$student->course_group_id);
			$student_d = $this->Common_model->getRecordById('student','student_id',$student->student_id);
            $due = explode(" ",$student_d->session);
             if($due[0]=="July"){
              $month = "6";
              $year = $due[1]+$course_d->max_duration;
             }else{
              $month = "12";
              $year = $due[1]+$course_d->max_duration-1;
             }
             $course_d->max_duration." Years".'('.$month." ".$year.')';

            if($year == $current_exam_year && $month < $current_exam_month){
                array_push($student_data,$student);
            }elseif($year < $current_exam_year){
              array_push($student_data,$student);
            }else{
                continue;
            }
          
        }

        $data['students'] = $student_data;
        $this->load->view('header',array('title'=>'Backlog Student Course Duration'));
        $this->load->view('admin/check_backlog_student_course_duration',$data);
        $this->load->view('footer');
    }

	public function set_abc_in_student(){
		$this->db->select('*');
       	$this->db->from('abc');
          
        $this->db->where('status ="N"');   
        $this->db->where('enrollment_id !="-"');   
		$start=0;
		$this->db->limit(10,$start);
		$rows=$this->db->get()->result();
		$i=1;
		foreach($rows as $row){
			echo "<pre>";
			print_r($row);
			//echo $row->enrollment_id;
			
			$data['abc_id']=$row->abc_id;
			$where = array('enrollment_no'=>$row->enrollment_id,'abc_id'=>0);
			$update =$this->Common_model->updateRecordByConditions('student',$where,$data);
			$where_abc = array('EnrollmentNo'=>$row->enrollment_id,'abc_id'=>0);
			$update =$this->Common_model->updateRecordByConditions('student_abc_id',$where_abc,$data);


			$abcdata['status']='Y';
			$where = array('enrollment_id'=>$row->enrollment_id);
			$abcupdate =$this->Common_model->updateRecordByConditions('abc',$where,$abcdata);
		}


	}

	public function dg_locker_data($startlimit=0){
		$start=0;
		if($startlimit==!0){
			$start=($startlimit-1)*5000;
			//$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}

		$exam_year="January 2025";
		//and enrollment_no in ('AG/21204765','AG/21204615')
		$this->load->view('header',array('title' => 'Student Data For DIGI LOCKER '.$exam_year));
		//$this->db->where_in('enrollment_no',array('AG/21204765','AG/21204615'));
		//$sql="SELECT * FROM `old_exam_data` WHERE class_id in (131,125,119,116,110,101,134,107,104) and exam_year in ('August 2022','Aug 2022') and center_id in (21,22,23,24,25,26,27,28)   and university_mode='REG'";
		
		$class_ids='101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136';
		 $sql="SELECT * FROM `old_exam_data` WHERE class_id in ($class_ids) and exam_year in ('".$exam_year."') and exam_result !='FAIL' and marks_pattern='GRADE' and  exam_status='R' and university_mode='PVT' order by course_name,class_id,roll_no limit ".$start.",5000 ";
		$rs = $this->db->query($sql)->result_array();
		$i=1;
		//$this->Common_model->last_query();
		$this->load->model('Gradesheet_old_model');
		$data['rs']=$rs;
		
		$this->load->view('admin/digi_locker',$data);
		$this->load->view('footer');
	}

	public function dg_locker_data_pg(){
		//and enrollment_no in ('AG/21204765','AG/21204615')
		$exam_year='January 2025';
		$this->load->view('header',array('title' => 'Student Data For DIGI LOCKER '.$exam_year));
		// $this->db->where_in('enrollment_no',array('AI/22207463'));
		$class_cbcs = '193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210,243,267';
		//193,197,201,203,205,209,211,213,221,223,225,227,302,275,279
		
		$sql="SELECT * FROM `old_exam_data` WHERE class_id in (".$class_cbcs.") and exam_year in ('".$exam_year."')  and exam_result !='FAIL' and marks_pattern='GRADE'  and  exam_status='R' and university_mode='REG' order by course_name,class_id,roll_no";
        //and enrollment_no='AI/22206868'and center_id in (21,22,23,24,25,26,27,28)
		//$this->db->limit(1);
		$rs = $this->db->query($sql)->result_array();
        //  $this->Common_model->last_query();
        // print_r(count($rs));die;
		$i=1;
		
		$this->load->model('GradeSheet_old_model_pg');
		$data['rs']=$rs;
		
		$this->load->view('admin/digi_locker_pg',$data);
		$this->load->view('footer');
	}

	public function check_agpa_sgpa($startlimit = 0){
		$start=0;
		if($startlimit==!0){
			$start=($startlimit-1)*5000;
			$this->db->limit(5000,$start);
			$pagetitle=$startlimit;
		}
       
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,111,117,120,126,129,132,103,106,109,112,118,121,127,130,133,136);
       
		$this->load->model('Gradesheet_old_model');
		$this->db->where_in('class_id', $class_ids);
		// $this->db->where('exam_year !=','June 2024');
        $old_datas = $this->Common_model->getRecordByWhere('old_exam_data', array('exam_status'=>'R','marks_pattern'=>'GRADE','university_mode'=>'PVT'));
		
       $student = [];
        foreach($old_datas as $data){
           
          $gradeData = $this->Gradesheet_old_model->check_agpa($data->student_id,$data->course_group_id,$data->class_id,$data->university_mode, $data->id);
		
		  if(number_format((float)$gradeData['agpa'], 2, '.', '') != number_format((float)$data->agpa_sgpa, 2, '.', '')){
			$student [] =  $data->id;	
			}
				
        }

		foreach($student as $std){
			echo $std.',';
		}
       
         echo 'Count :' .count($student);
    }

	public function check_agpa_sgpa_pg($exam_status = 'R',$startlimit = 0){
		$start=0;
		if($startlimit==!0){
			$start=($startlimit-1)*5000;
			$this->db->limit(5000,$start);
			$pagetitle=$startlimit;
		}
       
		  $class_ids = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210,243,267);
       
		$this->load->model('GradeSheet_old_model_pg');
		$this->db->where_in('class_id', $class_ids);
		// $this->db->where('enrollment_no','AK/23200725');
        $old_datas = $this->Common_model->getRecordByWhere('old_exam_data', array('exam_status'=>$exam_status,'marks_pattern'=>'GRADE','university_mode'=>'REG'));
		
       $student = [];
        foreach($old_datas as $data){

          $gradeData = $this->GradeSheet_old_model_pg->check_agpa($data->student_id,$data->course_group_id,$data->class_id,$data->university_mode, $data->id,$data->exam_status);

		  if(number_format((float)$gradeData['agpa'], 2, '.', '') != number_format((float)$data->agpa_sgpa, 2, '.', '')){
			// echo $data->agpa_sgpa.' -----'. number_format((float)$gradeData['agpa'], 2, '.', '');
			$student [] =  $data->id;	
			}
				
        }

		foreach($student as $std){
			echo $std.',';
		}
       
         echo 'Count :' .count($student);
    }

	public function dg_locker_data_non_grading_class_list(){

		$exam_year = 'January 2025';
		$this->db->select('course_name,course_group_id, COUNT(DISTINCT(student_id)) as total_students');
		$this->db->from('old_exam_data');
		$this->db->where('exam_year', $exam_year);
		$this->db->where('exam_result !=', 'FAIL');
		$this->db->where('marks_pattern', 'MARKS');
		$this->db->where('exam_status', 'R');
		$this->db->where('university_mode', 'REG');
		// $this->db->where('university_mode', 'PVT');
		$this->db->group_by('course_group_id');
		$this->db->order_by('class_id');
		$data['courses'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$this->load->view('header',array('title' => 'Non Grading Dg Locker Data Class List '.$exam_year));
		$this->load->view('admin/digi_locker_class_list',$data);
		$this->load->view('footer');
	
	}

	public function dg_locker_data_non_grading($course_group_id,$startlimit =0){
		//and enrollment_no in ('AG/21204765','AG/21204615')

		$start = 0;

		if ($startlimit==!0) {
			$start = ($startlimit - 1) * 4000;
			$pagetitle = $startlimit;
			$this->db->limit(4000, $start);
		}
		// $this->db->limit(1, 0);
		$exam_year = 'January 2025';
		$this->load->view('header', ['title' => 'Student Data For DIGI LOCKER' . $exam_year]);

		$this->db->select('*');
		$this->db->from('old_exam_data');
		$this->db->where('exam_year', $exam_year);
		$this->db->where('exam_result !=', 'FAIL');
		$this->db->where('marks_pattern', 'MARKS');
		$this->db->where('exam_status', 'R');
		$this->db->where('university_mode', 'REG');
		// $this->db->where('university_mode', 'PVT');
		$this->db->where('course_group_id', $course_group_id);
		// $this->db->where('enrollment_no', 'PA/21209363');
		$this->db->order_by('course_name');
		$this->db->order_by('class_id');
		$this->db->order_by('roll_no');
		$data['rs'] = $this->db->get()->result_array();
		$data['title'] = "DIGI LOCKER DATA Of ".$this->Common_model->getCourseNameByCourseId($course_group_id)." ".$exam_year;

		$this->load->view('admin/digi_locker_data_non_grading',$data);
		$this->load->view('footer');
	}
}
?>
