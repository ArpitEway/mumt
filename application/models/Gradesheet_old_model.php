<?php 

/**
 * 
 */
class Gradesheet_old_model extends CI_Model
{
	protected $paper;
	protected $percent;
	protected $grade_point;
	protected $credit_point;
	protected $obt_marks;
	protected $total_marks;
	protected $agpa;
	protected $result;
	protected $fail_count = 0;
	protected $tot_credit_point = 0;
	protected $tot_credit = 0;
	protected $foundation_paper = array();
	protected $classCount = 0;
	protected $allclass;
	protected $classData;
	protected $obt_tot_credit;
	protected $check_grace_marks = false;
	protected $withheld = false;
	protected $fail_tot_marks;
	protected $fail_obt_marks;
	protected $fail_min_marks;
	protected $result_array = array();
	protected $paper_array = array();
	protected $total_grade_point;
	function __construct()
	{
		
	}

	public function view_result($student_id,$course_group_id,$class_id,$mode,$exam_data_id='')
	{
		
        // $table = $this->Common_model->getMaster('exam_form_table');
        $this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$student = $this->Common_model->getRecordById('student','student_id',$student_id);
		$session = explode(' ',$student->session);
		// echo $std[0]->sub_group_id;die;
		if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
            // print_r($papers);
		}
		if($this->classData->class_group == 'Y' || (in_array($session[1],array(2021,2022)) && in_array($class_id,array(101,102)))){
		$papers_list = $this->Common_model->get_all_old_group_papers($student_id,$class_id,$exam_data_id,$course_group_id);
		}
		// get_all_group_papers
		//  print_r($papers);die;
		
		// print_r($this->allclass);die;
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		
		foreach ($papers as $paper) {
			$this->paper = $paper;
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
		
			$this->_row();
			
		}
		foreach ($papers_list as $paper) {
			$this->paper = $paper;
			if(@$this->paper["group_name"]){
				$group = explode('(', $this->paper["group_name"]);
				 $group_name = explode(',',$group[1]);
				 $this->paper['group_name_array']=$group_name;
			
			 }
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
			$this->_row();
		}
		
		// var_dump($this->result_array);
		$this->echo_result(); 
		$this->total();
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		if($this->mode=='REG'){
			// $this->result_head();
			$this->set_result();
			// $this->AGPA();
		}else{
			// $this->result_head_pvt();
			$this->set_result();
			// $this->AGPA_pvt();
		}
		return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}

	public function view_result_grade($student_id,$course_group_id,$class_id,$mode,$exam_data_id='')
	{
		// $table = $this->Common_model->getMaster('exam_form_table');
		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$student = $this->Common_model->getRecordById('student','student_id',$student_id);
		$session = explode(' ',$student->session);
		
		if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
		}
		// echo count($papers);die;
		if($this->classData->class_group == 'Y' || (in_array($session[1],array(2021,2022)) && ($class_id == 101 || $class_id == 102))){
		$papers_list = $this->Common_model->get_all_old_group_papers($student_id,$class_id,$exam_data_id,$course_group_id);
		}
		// get_all_group_papers
		// print_r($papers);die;
		
		// print_r($this->allclass);die;
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->paper_array = array();
		$this->tot_credit_point = 0;
		$this->percent = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->obt_marks = 0;
		$this->total_marks=0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		
		foreach ($papers as $paper) {
			$this->paper = $paper;
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
		
			$this->_row();
			
		}
		foreach ($papers_list as $paper) {
			$this->paper = $paper;
			if(@$this->paper["group_name"]){
				$group = explode('(', $this->paper["group_name"]);
				 $group_name = explode(',',$group[1]);
				 $this->paper['group_name_array']=$group_name;
			
			 }
			
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
			$this->_row();
		}
		
		// var_dump($this->result_array);
		
		$this->echo_result_grade(); 
		
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		 $this->set_result();
		$this->total_grade();
		
		return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}

	public function check_agpa($student_id,$course_group_id,$class_id,$mode,$exam_data_id='')
	{
		// $table = $this->Common_model->getMaster('exam_form_table');
		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$student = $this->Common_model->getRecordById('student','student_id',$student_id);
		$session = explode(' ',$student->session);
		
		if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
		}
		// echo count($papers);die;
		if($this->classData->class_group == 'Y' || (in_array($session[1],array(2021,2022)) && $class_id == 101)){
		$papers_list = $this->Common_model->get_all_old_group_papers($student_id,$class_id,$exam_data_id,$course_group_id);
		}
		// get_all_group_papers
		// print_r($papers);die;
		
		// print_r($this->allclass);die;
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->paper_array = array();
		$this->tot_credit_point = 0;
		$this->percent = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->obt_marks = 0;
		$this->total_marks=0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		
		foreach ($papers as $paper) {
			$this->paper = $paper;
			
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
		
			$this->_row();
			
		}
		foreach ($papers_list as $paper) {
			$this->paper = $paper;
			if(@$this->paper["group_name"]){
				$group = explode('(', $this->paper["group_name"]);
				 $group_name = explode(',',$group[1]);
				 $this->paper['group_name_array']=$group_name;
			
			 }
			
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
			$this->_row();
		}
		
		// var_dump($this->result_array);
		
		$this->check_agpa_grade(); 
		
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		 $this->set_result();
		// $this->total_grade();
		
		return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}


	public function _row($forDG="")
	{
		

		// print_r($this->foundation_paper[$this->paper['sub_group_id']]);die;
		if($this->paper['sub_group_id']=='1'){
			 $this->obt_marks += $this->paper["theory_marks"];
		 	 $this->total_marks += $this->paper["max_theory_marks"];
			if (isset($this->foundation_paper[$this->paper['group_paper_name']])) {
				if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
				if($this->paper['theory_marks'] === 'ABS'){
					$this->foundation_paper[$this->paper['group_paper_name']]['obt'] = 'ABS';
				}
				$this->foundation_paper[$this->paper['group_paper_name']]['type'] = $this->paper['type'];
				$this->foundation_paper[$this->paper['group_paper_name']]['sub_group'] = $this->paper['sub_group_id'];
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] += $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] += $this->paper['credit_point'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] += $this->paper['max_theory_marks'];
				
				$this->_echo_row_foudation($this->paper['group_paper_name'],$forDG);
			}else{
				if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
				if($this->paper['theory_marks'] === 'ABS'){
					$this->foundation_paper[$this->paper['group_paper_name']]['obt'] = 'ABS';
				}
				$this->foundation_paper[$this->paper['group_paper_name']]['type'] = $this->paper['type'];
				$this->foundation_paper[$this->paper['group_paper_name']]['sub_group'] = $this->paper['sub_group_id'];
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] = $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] = $this->paper['max_theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['paper_code'] = $this->paper['paper_code'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] = $this->paper['credit_point'];
				// $paper_name_post_fix = ($this->paper['group_paper_name']=='FC1') ? 'I' : 'II';
				if(empty($forDG)){
					$this->foundation_paper[$this->paper['group_paper_name']]['paper_name'] = 'A) '.$this->paper['paper_name'].' ';
				}
				else{
					$this->foundation_paper[$this->paper['group_paper_name']]['paper_name'] = $this->paper['paper_name'].' ';
					
				}
				
			}
			
		}else{
			
			$this->_echo_row($forDG);
		}
	}

	private function _echo_row($forDG)
	{
		
		$this->paper_code();
		$this->paper_name($forDG);
		$this->min_max_no();
		$this->credit();
		$this->grade();
		$this->grade_point();
		$this->credit_point();
	}

	private function _echo_row_foudation($sub_group_id,$forDG="")
	{
		$this->paper_code_foudation($sub_group_id);
		$this->paper_name_foudation($sub_group_id,$forDG);
		$this->foudation_min_max_no($sub_group_id);
		$this->credit_foudation($sub_group_id);
		$this->grade_foudation($sub_group_id);
		$this->grade_point($sub_group_id);
		$this->credit_point($sub_group_id);
	}

	private function paper_code()
	{
		$this->result_array[$this->paper['paper_code']] = array();
		array_push($this->result_array[$this->paper['paper_code']], $this->paper["paper_code"]);
	
	}

	private function paper_name($forDG="")
	{
		
		$this->result_array[$this->paper['paper_code']]["type"] = $this->paper["type"];
		$this->result_array[$this->paper['paper_code']]['sub_group'] = $this->paper['sub_group_id'];
		if(empty($forDG)){

			if($this->paper["course_group_id"]==12){
				if($this->paper['sub_group_id']==2){
					$group_paper_name=' - '.$this->paper['group_name_array'][0];
				}
				if($this->paper['sub_group_id']==3){
					$group_paper_name=' - '.$this->paper['group_name_array'][1];
				}
				if($this->paper['sub_group_id']==4){
					$group_paper_name=' - '.substr($this->paper['group_name_array'][2],0,-1);
				}
				if($this->paper['sub_group_id']==5){
					$group_paper_name=' - '.'Vocational Subject';
				}
				if($this->paper['sub_group_id']==6){
					$group_paper_name=' - '.'Field Work';
				}
				$this->result_array[$this->paper['paper_code']]["paper_name"] =''. $this->paper["group_paper_name"].$group_paper_name.'#'.$this->paper["paper_name"];
			}
			else{
				$this->result_array[$this->paper['paper_code']]["paper_name"] ='['. $this->paper["group_paper_name"].']#'.$this->paper["paper_name"];
			}
		}
		else{
			$this->result_array[$this->paper['paper_code']]["paper_name"] =$this->paper["group_paper_name"].'-'.$this->paper["paper_name"];
			
		}
	}

	private function credit()
	{
		$this->credit_point = $this->paper["credit_point"];
		$this->tot_credit += $this->paper["credit_point"];
		$this->result_array[$this->paper['paper_code']]["credit"] = $this->paper["credit_point"];
	}

	private function grade()
	{
		if ($this->paper["type"]=='theory') {
			$this->obt_marks += $this->paper["theory_marks"] + $this->paper["int_marks"];
		 	$this->total_marks += $this->paper["max_theory_marks"]+ $this->paper["max_internal_marks"];
		
			if($this->mode=='REG'){
				if ($this->paper['theory_marks']=='' || ($this->paper["int_marks"]=='' || $this->paper["int_marks"]=='N')) {
					$this->withheld = true;
				}
				$check_fail_marks = $this->paper["theory_marks"] + $this->paper["int_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"]+$this->paper["min_internal_marks"];
				$check_fail_tot_marks = $this->paper["max_theory_marks"]+ $this->paper["max_internal_marks"];
				$tot_obt_marks = $this->paper["theory_marks"] + $this->paper["int_marks"];
				 $tot_marks = $this->paper["max_theory_marks"] + $this->paper["max_internal_marks"];
				$min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
			}else{
				if ($this->paper['theory_marks']==''){
					$this->withheld = true;
				}
				$check_fail_marks = $this->paper["theory_marks"];
				// $check_fail_min_marks = $this->paper["private_min_theory_marks"];
                $check_fail_min_marks = $this->paper["min_theory_marks"]+$this->paper["min_internal_marks"];
				$check_fail_tot_marks = $this->paper["private_max_theory_marks"];
				$tot_obt_marks = $this->paper["theory_marks"];
				$tot_marks = $this->paper["private_max_theory_marks"];
				// $min_marks = $this->paper["private_min_theory_marks"];
                $min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
			}
		}else{
			$this->obt_marks += $this->paper["p_marks"]+$this->paper["int_marks"];
		 $this->total_marks += $this->paper["max_theory_marks"]+  $this->paper["max_internal_marks"];
		
			if ($this->paper['p_marks']==''){
				$this->withheld = true;
			}
			$check_fail_marks = $this->paper["p_marks"]+$this->paper["int_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"]+$this->paper["min_internal_marks"];
				$check_fail_tot_marks = $this->paper["max_theory_marks"]+ $this->paper["max_internal_marks"];
			$tot_obt_marks = $this->paper["p_marks"]+$this->paper["int_marks"];
			$tot_marks = $this->paper["max_theory_marks"]+$this->paper["max_internal_marks"];
			$min_marks = $this->paper["min_theory_marks"]+$this->paper["min_internal_marks"];
		}
		$persent = $tot_obt_marks*100/$tot_marks;
		
		//  $tot_marks ;die;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade',$where);
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			array_push($this->paper_array, $this->paper["paper_code"]);
			$this->fail_count++;
			$this->fail_obt_marks += $check_fail_marks;
			$this->fail_tot_marks += $check_fail_tot_marks;
			$this->fail_min_marks += $check_fail_min_marks;
			$this->result_array[$this->paper['paper_code']]['obt_credit'] = 0;
		}else{
			$this->obt_tot_credit += $this->paper['credit_point'];
			$this->result_array[$this->paper['paper_code']]['obt_credit'] = $this->paper['credit_point'];
		}
		// var_dump($this->check_grace_marks);
		// echo $student_id;
		// echo $this->classData->final_result_permission;
		// echo $this->fail_count;die;
		
		$this->grade_point = $gradeData[0]->grade_point;
		$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
	}

	private function grade_point()
	{
		$this->result_array[$this->paper['paper_code']]['grade_point'] = $this->grade_point;
	}

	private function credit_point()

	{
		
		$this->tot_credit_point += $this->grade_point*$this->credit_point;
		$this->result_array[$this->paper['paper_code']]['credit_point'] = $this->grade_point*$this->credit_point;
	}

	private function paper_code_foudation($sub_group_id){
		$this->result_array[$this->paper['paper_code']] = array();
		
		array_push($this->result_array[$this->paper['paper_code']], $this->foundation_paper[$sub_group_id]['paper_code']);
		
	}

	private function paper_name_foudation($sub_group_id,$forDG=""){
		if(empty($forDG)){
			if($this->paper["course_group_id"]==12){
			
				if($this->paper["group_paper_name"]=="FC1")
					$foundation_fc=' - Foundation Course 1 ';
				else
					$foundation_fc=' - Foundation Course 2 ';
				$data = ''.$this->paper["group_paper_name"].$foundation_fc.'#'.$this->foundation_paper[$sub_group_id]["paper_name"].'<br><br>'.'B) '.$this->paper["paper_name"];
			}
			else

			$data = '['.$this->paper["group_paper_name"].']#'.$this->foundation_paper[$sub_group_id]["paper_name"].'<br><br>'.'B) '.$this->paper["paper_name"];


		}else{
			$data = $this->paper["group_paper_name"].'-'.$this->foundation_paper[$sub_group_id]["paper_name"].'/'.$this->paper["paper_name"];
		}
		// print_r($this->paper["paper_name"]);
		$this->result_array[$this->paper['paper_code']]['paper_name'] = $data;
		$this->result_array[$this->paper['paper_code']]['type'] = $this->foundation_paper[$sub_group_id]["type"];
		$this->result_array[$this->paper['paper_code']]['sub_group'] = $this->foundation_paper[$sub_group_id]['sub_group'];
		$this->result_array[$this->paper['paper_code']]['carry_theory'] = $this->paper['carry_theory'];
	}

	private function credit_foudation($sub_group_id){
		$this->credit_point = $this->foundation_paper[$sub_group_id]["credit_point"];
		$this->tot_credit += $this->foundation_paper[$sub_group_id]["credit_point"];
		$this->result_array[$this->paper['paper_code']]['credit'] = $this->foundation_paper[$sub_group_id]["credit_point"];
	}

	private function grade_foudation($sub_group_id){
		
		$tot_obt_marks = $this->foundation_paper[$sub_group_id]["tot_marks"];
		$tot_marks = $this->foundation_paper[$sub_group_id]["max_theory_marks"];
		$persent = $tot_obt_marks*100/$tot_marks;
		
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade',$where);
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			array_push($this->paper_array, $sub_group_id);
			$this->fail_count++;
			$this->fail_obt_marks += $tot_obt_marks;
			$this->fail_tot_marks += $tot_marks;
			$this->fail_min_marks += 35;
			$this->result_array[$this->paper['paper_code']]['obt_credit'] = 0;
		}else{
			$this->obt_tot_credit += $this->foundation_paper[$sub_group_id]['credit_point'];
		$this->result_array[$this->paper['paper_code']]['obt_credit'] = $this->foundation_paper[$sub_group_id]['credit_point'];
		}
		$this->grade_point = $gradeData[0]->grade_point;
		$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
	}

	public function set_result()
	{
		// var_dump($this->withheld);die;
		if ($this->withheld==true) {
			$this->result = 'WITHHELD';
		}
		else if ($this->fail_count!=0 && $this->agpa>=4) {
			if ($this->check_grace_marks) {
				$this->result = 'PASS BY GRACE';
			}else{
				$this->result = 'FAIL';
			}
		}else if($this->agpa<4){
			$this->result = 'FAIL';
		}else{
			$this->result = 'PASS';
		}
	}

	public function result($forDG="")
	{
		
		 $this->percent = $this->obt_marks*100/$this->total_marks;
		 $data = array(
				'tot_credit' => $this->tot_credit,
				'obt_credit' => $this->obt_tot_credit,
				'credit_point' => $this->tot_credit_point,
				'agpa' => $this->agpa,
				'result' => $this->result,
				'equivalent' => ($this->agpa*10),

				
			);
			if($forDG == 'old_data'){
				$data['total_grade_point']=$this->total_grade_point;
			}
			if(!empty($forDG)){
				$data['html']=$this->html;
				$data['papercount']=$this->papercount;
				$data['total_grade_point']=$this->total_grade_point;
			}
			return $data;
	}

	public function min_max_no()
	{
		if ($this->mode=='PVT') {
			$this->result_array[$this->paper['paper_code']]['max_marks'] = $this->paper['private_max_theory_marks'];
			if ($this->paper['type']=='theory') {
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['private_min_theory_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks']= $this->paper['theory_marks'];
				$this->result_array[$this->paper['paper_code']]['carry_theory'] = $this->paper['carry_theory'];
			}else{
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['p_marks'];
				$this->result_array[$this->paper['paper_code']]['carry_int'] = $this->paper['carry_int'];
			}
		}else{
			$this->result_array[$this->paper['paper_code']]['max_marks'] = $this->paper['max_theory_marks'];
			if ($this->paper['type']=='theory') {
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'];
				$this->result_array[$this->paper['paper_code']]['int_max_marks'] = $this->paper['max_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['int_min_marks'] = $this->paper['min_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['theory_marks'];
				$this->result_array[$this->paper['paper_code']]['int_obt_marks'] = $this->paper['int_marks'];
				$this->result_array[$this->paper['paper_code']]['carry_theory'] = $this->paper['carry_theory'];
				$this->result_array[$this->paper['paper_code']]['carry_int'] = $this->paper['carry_int'];
			}else{
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'];
				$this->result_array[$this->paper['paper_code']]['int_max_marks'] = '-';
				$this->result_array[$this->paper['paper_code']]['int_min_marks'] = '-';
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['p_marks'];
				$this->result_array[$this->paper['paper_code']]['int_obt_marks'] = '-';
				$this->result_array[$this->paper['paper_code']]['carry_theory'] = $this->paper['carry_theory'];
			}
		}
		
		
	}

	public function foudation_min_max_no($sub_group_id)
	{
		if($this->mode=='PVT'){
			$this->result_array[$this->paper['paper_code']]['max_marks'] = $this->foundation_paper[$sub_group_id]['max_theory_marks'];
			$this->result_array[$this->paper['paper_code']]['min_marks'] = 35;
			$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->foundation_paper[$sub_group_id]['tot_marks'];
			$this->result_array[$this->paper['paper_code']]['f_abs'] = $this->foundation_paper[$sub_group_id]['obt'];
			
		}else{
			$this->result_array[$this->paper['paper_code']]['max_marks'] = $this->foundation_paper[$sub_group_id]['max_theory_marks'];
			$this->result_array[$this->paper['paper_code']]['min_marks'] = '35';
			$this->result_array[$this->paper['paper_code']]['int_max_marks']  = '-';
			$this->result_array[$this->paper['paper_code']]['int_min_marks'] = '-';
			$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->foundation_paper[$sub_group_id]['tot_marks'];
			$this->result_array[$this->paper['paper_code']]['int_obt_marks'] = '-';
			$this->result_array[$this->paper['paper_code']]['f_abs'] = $this->foundation_paper[$sub_group_id]['obt'];
		
			
		}
	
	}

	public function echo_result()
	{
		$this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		foreach ($this->result_array as $key => $result) {
			$paper = explode('#',$result['paper_name']);
			echo "<tr>";
			echo "<th>".$key."</th>";
			if($this->paper["course_group_id"]==12){
				echo "<td><table style='border:0px solid black'><tr style='font-family:Arial, Helvetica, sans-serif; font-size:12px;' align='left' valign='center'><td width='100%' style='border:0px solid black'><strong>".$paper[0]."</strong><br><strong>".$paper[1]."</strong></td></tr></table></td>
				";
				}else{
				echo "<td><table style='border:0px solid black'><tr style='font-family:Arial, Helvetica, sans-serif; font-size:12px;' align='left' valign='center'><td width='100px' style='border:0px solid black'><strong>".$paper[0]."</strong></td><td style='border:0px solid black'></td><td style='border:0px solid black'><strong>".$paper[1]."</strong></td></tr></table></td>";
				}
			//echo "<td><table style='border:0px solid black'><tr style='font-family:Arial, Helvetica, sans-serif; font-size:12px;' align='left' valign='center'><td width='100px' style='border:0px solid black'><strong>".$paper[0]."</strong></td><td style='border:0px solid black'></td><td style='border:0px solid black'><strong>".$paper[1]."</strong></td></tr></table></td>";
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
				$this->check_grace_marks = true;
			//	$this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				//$credit_point = $result['credit']*4;

				if($result['sub_group']==1 && $result['f_abs']=='ABS'){
					$obt_credit=$result['credit']/2;
					$this->obt_tot_credit += $obt_credit;
					$credit_point = $result['credit']*2;
				
					
				}
				else{
					$obt_credit=$result['credit'];
					$this->obt_tot_credit += $result['credit'];
					$credit_point = $result['credit']*4;

				}

				$this->result_array[$key]['credit_point']=$credit_point;
				$this->tot_credit_point += $credit_point;
				echo "<th class='text-center'>".$obt_credit."</th>";
				echo "<th class='text-center'>P-G</th>";
				echo "<th class='text-center'>4</th>";
				echo "<th class='text-center'>".$credit_point."</th>";
			}else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			if(($result['f_abs'] === 'ABS' && $result['obt_marks'] != '0')){
				$result['obt_credit'] = 2;
				$this->obt_tot_credit +=2; 
				$credit_point = $result['obt_credit']*$result['grade_point'];
			$result['credit_point']=$credit_point;
			$this->tot_credit_point -= $credit_point;
				
			}
				echo "<th class='text-center'>".$result['obt_credit']." ".$result["carry_theory"]."</th>";
				echo "<th class='text-center'>".$result['letter_grade']."</th>";				
				echo "<th class='text-center'>".$result['grade_point']."</th>";
				echo "<th class='text-center'>".$result['credit_point']."</th>";
			}
			echo "</tr>";
		}
	}

	public function echo_result_grade($forBacklog="")
	{
		$this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		
		foreach ($this->result_array as $key => $result) {
			$paper = explode('#',$result['paper_name']);
			
		
			
			echo '<tr style="padding:4px;font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="center">';
			echo '<td style="margin-top:2px;" align="center"><strong>'.$key.'</strong></td>';
			if($this->paper["course_group_id"]==12){
				echo "<td align='left'><table border='0'><tr style='font-family:Arial, Helvetica, sans-serif; font-size:12px;' align='left' valign='center'><td ><strong>".$paper[0]."</strong><br><br><strong>".$paper[1]."</strong></td></tr></table></td>";
				}else{
				echo "<td align='left'><table border='0'><tr style='font-family:Arial, Helvetica, sans-serif; font-size:12px;' align='left' valign='center'><td width='50px'><strong>".$paper[0]."</strong></td><td></td><td><strong>".$paper[1]."</strong></td></tr></table></td>";
				}
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
				$this->check_grace_marks = true;
				
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				if($result['sub_group']==1 && $result['f_abs']=='ABS'){
					$obt_credit=$result['credit']/2;
					$this->obt_tot_credit += $obt_credit;
					$credit_point = $result['credit']*2;
				}
				else{
					$obt_credit=$result['credit'];
					$credit_point = $result['credit']*4;
					$this->obt_tot_credit += $result['credit'];
				}
				
				$this->result_array[$key]['credit_point']=$credit_point;
				$this->tot_credit_point += $credit_point;
				echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
				echo "<td align='center' colspan='3'><span class='style4'>".$obt_credit."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>4</span></td>";
				echo "<td align='center' colspan='2''><span class='style4'>".$credit_point."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".'P-G'."</span></td>";
				
			}else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			if(($result['f_abs'] == 'ABS' && $result['obt_marks'] != '0'  && $result['sub_group'] == 1 && $this->fail_count == 0) || ($result['f_abs'] == 'ABS' && $result['obt_marks'] >= '35'  && $result['sub_group'] == 1 && $this->fail_count > 0) ){
				$result['obt_credit'] = 2;
				$this->obt_tot_credit -=2;
				$credit_point = $result['obt_credit']*$result['grade_point'];
				$result['credit_point']=$credit_point;
				$this->tot_credit_point -= $credit_point;
				
			}
				echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
				echo "<td align='center' colspan='3'><span class='style4'>".$result['obt_credit']." ".$result["carry_theory"]."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['grade_point']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['credit_point']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['letter_grade']."</span></td>";
			}
			echo "</tr>";
		}
	}

	public function check_agpa_grade(){
		$this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		
		foreach ($this->result_array as $key => $result) {
			$paper = explode('#',$result['paper_name']);
			
		
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
				$this->check_grace_marks = true;
				
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				if($result['sub_group']==1 && $result['f_abs']=='ABS'){
					$obt_credit=$result['credit']/2;
					$this->obt_tot_credit += $obt_credit;
					$credit_point = $result['credit']*2;
				}
				else{
					$obt_credit=$result['credit'];
					$credit_point = $result['credit']*4;
					$this->obt_tot_credit += $result['credit'];
				}
				
				$this->result_array[$key]['credit_point']=$credit_point;
				$this->tot_credit_point += $credit_point;
				
				
			}else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			if(($result['f_abs'] == 'ABS' && $result['obt_marks'] != '0'  && $result['sub_group'] == 1 && $this->fail_count == 0) || ($result['f_abs'] == 'ABS' && $result['obt_marks'] >= '35'  && $result['sub_group'] == 1 && $this->fail_count > 0) ){
				$result['obt_credit'] = 2;
				$this->obt_tot_credit -=2;
				$credit_point = $result['obt_credit']*$result['grade_point'];
				$result['credit_point']=$credit_point;
				$this->tot_credit_point -= $credit_point;
				
			}
			
			}
		
		}
	}

	private function total()
	{
		echo '<tr>';
			echo '<td></td>';
			echo '<td class="text-right font-weight-bold" style="padding-right: 3rem!important;">Total</td>';
			echo '<td class="text-center font-weight-bold">'.$this->obt_tot_credit.'</td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td class="text-center font-weight-bold">'.$this->tot_credit_point.'</td>';
		echo '</tr>';
	}
	private function total_grade()
	{
		
		$papers = implode(', ',$this->paper_array);
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$agpa = ($this->result == 'FAIL')?'0.00':number_format((float)$this->agpa, 2, '.', '');
		$result = ($this->result == 'FAIL' && $this->agpa>=4 )?'SUPP IN '. $papers: $this->result;
		echo '<tr  style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="middle">';
		
		
		echo '<th colspan="2" style="font-size:14px;">Total</th>';
			echo '<th colspan="3" style="font-size:14px;"> '.$this->tot_credit.'</th>';
			echo '<th colspan="3" style="font-size:14px;">'.$this->obt_tot_credit.'</th>';
			echo '<th colspan="2"></th>';
			echo '<th style="font-size:14px;" colspan="3">'.$this->tot_credit_point.'</th>';
			echo '<th colspan=""></th>';
			
			
		echo '</tr>';
		echo '<tr  style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="middle">';
		echo '<th colspan="14" style="font-size:13px;" >Result - '.$result.'</th>';
		echo '</tr>';
	}

	private function result_head_pvt()
	{
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;">सत्रार्द्ध/ वर्ष</td>';
		foreach ($this->allclass as $key => $class) {
			echo '<td style="width: 80px;">'.$class->class_name.'</td>';	
		}
		echo '<td class="text-center">महायोगः</td>';
		echo '<td class="text-center">परिणामः	</td>';
		echo '<td class="text-center">श्रेणी</td>';
		echo '<td></td>';
		echo '</tr>';
	}

	private function AGPA_pvt()
	{
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;">AGPA</td>';
		echo '<td>'.$this->agpa.'</td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td class="text-center"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;">Result</td>';
		echo '<td>'.$this->result.'</td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td class="text-center"></td>';
		echo '</tr>';
	}

	private function result_head()
	{
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;" >सत्रार्द्ध/ वर्ष</td>';
		foreach ($this->allclass as $key => $class) {
			echo '<td colspan="2" >'.$class->class_name.'</td>';	
		}
		echo '<td class="text-center">महायोगः</td>';
		echo '<td class="text-center">परिणामः	</td>';
		echo '<td class="text-center">श्रेणी</td>';
		echo '<td></td>';
		echo '</tr>';
	}

	private function AGPA()
	{
		// echo  $this->tot_credit_point.' ppp'.$this->tot_credit;
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;">AGPA</td>';
		echo '<td colspan="2">'.$this->agpa.'</td>';
		echo '<td colspan="2"></td>';
		echo '<td colspan="2"></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;">Result</td>';
		echo '<td colspan="2" style="width: 80px;">'.$this->result.'</td>';
		echo '<td colspan="2"></td>';
		echo '<td colspan="2"></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '</tr>';
	}

	public function app_result($student_id,$course_group_id,$class_id,$mode)
	{
		$papers = $this->Common_model->get_all_papers($student_id,$class_id);
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
		$result_data = $this->student_result_data(); 
		// $this->total();
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		if($this->mode=='REG'){
			$this->set_result();
		}else{
			$this->set_result();
		}
		$returndata['paper_details'] = $result_data;
		$returndata['final_result'] = $this->result();
		return $returndata;
	}


	public function student_result_data()
	{
		$this->fail_count;
		if ($this->fail_count>0) {
			$require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		$result_data = array();
		$key  = 0;
		foreach ($this->result_array as $result) {
			$result_data[$key] = array();
			$result_data[$key]['paper_name'] = $result['paper_name'];
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F') {
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
				$result_data[$key]['credit'] = $result['credit'];
				$result_data[$key]['letter_grade'] = "P-G";
			}else{
				$result_data[$key]['credit'] = ($result['letter_grade']=='F' || $result['letter_grade']=='ABS') ? 0 : $result['credit'];
				$result_data[$key]['letter_grade'] = $result['letter_grade'];		
				
			}
			$key++;
		}
		return $result_data;
	}

	public function echo_result_marksheet()
	{
		$this->fail_count;
		if ($this->fail_count>0) {
			$require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		foreach ($this->result_array as $key => $result) {
			echo "<tr>";
			echo "<td class='text-center'>".$key."</td>";
			echo "<td>".$result['paper_name']."</td>";
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F') {
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				$credit_point = $result['credit']*4;
				echo "<td class='text-center'>".$result['credit']."</td>";
				echo "<td class='text-center'>".$result['obt_credit']."</td>";
				echo "<td class='text-center'>P-G</td>";
				echo "<td class='text-center'>4</td>";
				echo "<td class='text-center'>".$credit_point."</td>";
			}else{
				echo "<td class='text-center'>".$result['credit']."</td>";
				echo "<td class='text-center'>".$result['obt_credit']."</td>";
				echo "<td class='text-center'>".$result['letter_grade']."</td>";				
				echo "<td class='text-center'>".$result['grade_point']."</td>";
				echo "<td class='text-center'>".$result['credit_point']."</td>";
			}
			echo "</tr>";
		}
	}

	private function total_marksheet()
	{
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;">कुल योग</td>';
		echo '<td class="text-center">'.$this->tot_credit.'</td>';
		echo '<td class="text-center">'.$this->obt_tot_credit.'</td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td class="text-center">'.$this->tot_credit_point.'</td>';
		echo '</tr>';
	}

	public function view_result_marksheet($student_id,$course_group_id,$class_id,$mode)
	{
		$papers = $this->Common_model->get_all_papers($student_id,$class_id);
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
		// var_dump($this->result_array);
		$this->echo_result_marksheet(); 
		$this->total_marksheet();
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		if($this->mode=='REG'){
			// $this->result_head();
			$this->set_result();
			// $this->AGPA();
		}else{
			// $this->result_head_pvt();
			$this->set_result();
			// $this->AGPA_pvt();
		}
		return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}
//DG Locker
	public function view_result_grade_for_dg_locker($student_id,$course_group_id,$class_id,$mode,$id)
	{
		// $table = $this->Common_model->getMaster('exam_form_table');
		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id,'exam_data_id'=>$id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
		
		if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id ,$id);
		}
		if($this->classData->class_group == 'Y'){
		$papers_list = $this->Common_model->get_all_old_group_papers($student_id,$class_id,$id);
		}
		// get_all_group_papers
		// echo count($papers);
		// echo "<br><pre>";
		//  print_r($papers);die;
		
		// print_r($this->allclass);die;
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->percent = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->obt_marks = 0;
		$this->total_marks=0;
		$this->total_grade_point=0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		$this->html = "";
		$this->papercount=count($papers)+count($papers_list);
	//echo count($papers_list);
	//die;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			
			
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
		
			$this->_row(Y);
			
		}
		foreach ($papers_list as $paper) {
			$this->paper = $paper;
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
			$this->_row(Y);
		}
		
		// var_dump($this->result_array);
		
		//$this->echo_result_grade(); 
		$this->echo_result_digi();
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		// $this->set_result();
		//$this->total_grade();
		
		return $this->result(y);
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}
	private function echo_result_digi()
	{
		
		$this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		
		foreach ($this->result_array as $key => $result) {
			$paper = explode('#',$result['paper_name']);
			//print_r($result);
			$this->html.= "<td >".$paper[0]." ".$paper[1]."</td>";
			$this->html.= "<td >".$result[0]."</td>";
//break;
			//echo '<tr style="padding:4px;font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="center">';
		//	echo '<td style="margin-top:2px;" align="center"><strong>'.$key.'</strong></td>';
			
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				$credit_point = $result['credit']*4;
				$this->result_array[$key]['credit_point']=$credit_point;
				$this->tot_credit_point += $credit_point;
				$result['grade_point']=4;
			//	echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
			//	echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
			//	echo "<td align='center' colspan='2'><span class='style4'>4</span></td>";
			//	echo "<td align='center' colspan='2''><span class='style4'>".$credit_point."</span></td>";
			//	echo "<td align='center' colspan='2'><span class='style4'>".'P-G'."</span></td>";
				$this->html.= "<td >".'P-G'."</td>";
			//	$this->html.= "<td >4</td>";
				$this->html.= "<td >".$result['credit']."</td>";
				$this->html.= "<td >".$credit_point."</td>";
				$this->html.= "<td >4</td>";
				
			}else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			if(($result['f_abs'] === 'ABS' && $result['obt_marks'] != '0')){
				$result['obt_credit'] = 2;
				$this->obt_tot_credit -=2; 
				$credit_point = $result['obt_credit']*$result['grade_point'];
				$result['credit_point']=$credit_point;
				$this->tot_credit_point -= $credit_point;
				
			}
			/*	echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
				echo "<td align='center' colspan='3'><span class='style4'>".$result['obt_credit']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['grade_point']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['credit_point']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['letter_grade']."</span></td>";*/
				$this->html.= "<td >".$result['letter_grade']."</td>";
			//	$this->html.= "<td >".$result['credit']."</td>";
				$this->html.= "<td >".$result['obt_credit']."</td>";
				$this->html.= "<td >".$result['credit_point']."</td>";
				$this->html.= "<td >".$result['grade_point']."</td>";
				
			}
			$this->total_grade_point+=$result['grade_point']; 
			//echo "</tr>";
		}
	}

	public function view_old_results($student_id,$course_group_id,$class_id,$mode,$id="", $exam_status="")
	{
        // $papers = $this->Common_model->get_all_old_papers($student_id,$class_id);
        $this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id, 'exam_data_id'=>$id));
		

		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$student = $this->Common_model->getRecordById('student','student_id',$student_id);
		$session = explode(' ',$student->session);
		// echo $std[0]->sub_group_id;die;
        // echo $this->classData->class_group;die;
		if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$id);
            // print_r($papers);
		}
		if($this->classData->class_group == 'Y' || (in_array($session[1],array(2021,2022)) && ($class_id == 101 || $class_id == 102)){
		$papers_list = $this->Common_model->get_all_old_group_papers($student_id,$class_id,$id);
		}
		
	
		// get_all_group_papers
		//  print_r($papers);die;
		
		// print_r($this->allclass);die;
		$this->classCount = count($this->allclass);
	//	$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->total_grade_point=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
            $this->_row();
			
		}

        foreach ($papers_list as $paper) {
			//echo "<pre>".print_r( $paper)." </pre>";
			$this->paper = $paper;
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			// if ($this->fail_count>0 && !$this->check_grace_marks && $this->classData->final_result_permission!='Y' ) {  
			// 	echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
			// 	'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
			// 	 '<h3 class="text-center">'.'WH'.'</h3>'.
			//    '</div>';
			//    return $this->result();
		   
			//    die;
			// }
			$this->_row();
		}
		
		// var_dump($this->result_array);
		
		// $this->total();
        if($exam_status !="B"){
            $this->check_grace_for_old();
        }

        foreach ($this->result_array  as $key => $result) {
			$this->total_grade_point+=$result['grade_point'];
            if($result['sub_group'] == 1){ 
                if(($result['f_abs'] === 'ABS' && $result['obt_marks'] != '0')){
                        $result['obt_credit'] = 2;
                        $this->obt_tot_credit -=2; 
                        $credit_point = $result['obt_credit']*$result['grade_point'];
                        $result['credit_point']=$credit_point;
                        $this->tot_credit_point -= $credit_point;
			        }
            }
        }
      // echo $this->tot_credit_point;die;
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		if($this->mode=='REG'){
			// $this->result_head();
			$this->set_result();
			// $this->AGPA();
		}else{
			// $this->result_head_pvt();
			$this->set_result();
			// $this->AGPA_pvt();
		}
		return $this->result('old_data');

    }
	public function check_grace_for_old(){
        $this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
        foreach ($this->result_array as $key => $result) {
            if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
				
                $this->check_grace_marks = true;
				
				 $req_marks = $result['min_marks']-$result['obt_marks'];
				 $obt_marks = $result['obt_marks']+$req_marks;
				if($result['sub_group']==1 && $result['f_abs']=='ABS'){
					 $obt_credit=$result['credit']/2;
					 $this->obt_tot_credit += $result['credit'];
					 $credit_point = $result['credit']*$obt_credit;	
				}
				else{
					$this->obt_tot_credit += $result['credit'];
					$credit_point = $result['credit']*4;
				}
				$this->result_array[$key]['credit_point']=$credit_point;
				$this->tot_credit_point += $credit_point; 
				$this->result_array[$key]['grade_point']= 4;
            }
        }
    }


	public function view_result_grade_backlog($student_id,$course_group_id,$class_id,$mode,$exam_data_id)
	{
		
		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$student = $this->Common_model->getRecordById('student','student_id',$student_id);
		$session = explode(' ',$student->session);
	
		if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
		}
		if($this->classData->class_group == 'Y' || (in_array($session[1],array(2021,2022)) && in_array($class_id,array(101,102)))){
		$papers_list = $this->Common_model->get_all_old_group_papers($student_id,$class_id,$exam_data_id,$course_group_id);
		}
		// get_all_group_papers
	
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->paper_array = array();
		$this->tot_credit_point = 0;
		$this->percent = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->obt_marks = 0;
		$this->total_marks=0;
		$this->check_grace_marks = false;
		$this->withheld = false;
		
		foreach ($papers as $paper) {
			$this->paper = $paper;
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			
		
			$this->_row();
			
		}
		foreach ($papers_list as $paper) {
			$this->paper = $paper;
			if(@$this->paper["group_name"]){
				$group = explode('(', $this->paper["group_name"]);
				 $group_name = explode(',',$group[1]);
				 $this->paper['group_name_array']=$group_name;
			
			 }
			
			if($this->withheld){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
		
			$this->_row();
		}
	
		$this->echo_result_grade('backlog'); 
		
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		 $this->set_result();
		$this->total_grade();
		
		return $this->result();
		
	}

}
