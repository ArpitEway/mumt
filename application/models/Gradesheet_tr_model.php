<?php 

/**
 * 
 */
class Gradesheet_tr_model extends CI_Model
{
	protected $paper;
	protected $grade_point;
	protected $credit_point;
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
	protected $grade_tot_point;
	protected $check_grace_marks = false;
	protected $withheld = false;
	protected $fail_tot_marks;
	protected $fail_obt_marks;
	protected $fail_min_marks;
	protected $result_array = array();

	function __construct()
	{
		
	}

	public function view_result($student_id,$course_group_id,$class_id,$mode)
	{
		
		$std  = $this->Common_model->getRecordByWhere('new_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
		
		if($std[0]->sub_group_id == 1 || in_array($class_id, [325,328,329,143])){
			$papers = $this->Common_model->get_all_papers($student_id,$class_id);
			// $this->Common_model->last_query();
		}
		
		if($this->classData->class_group == 'Y'){
			$papers_list = $this->Common_model->get_all_group_papers($student_id,$class_id);
		}
       
		
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
		// $this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit = 0;
		$this->grade_tot_point=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->check_grace_marks = false;
		$this->withheld = false;
        $this->withheld_practical = false;
        $this->withheld_internal = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
		foreach($papers_list as $paper){
			$this->paper = $paper;
			$this->_row();
		}
		// var_dump($this->result_array);
		$this->echo_result(); 
		$this->total();
		// if($this->mode=='REG'){
			$this->result_head();
			$this->set_result();
			$this->AGPA();
		// }else{
		// 	$this->result_head_pvt();
		// 	$this->set_result();
		// 	$this->AGPA_pvt();
		// }
		return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}

	// notification 
	public function view_notification($student_id,$course_group_id,$class_id,$mode)
	{
		
		$std  = $this->Common_model->getRecordByWhere('new_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
		
		if($std[0]->sub_group_id == 1 || in_array($class_id, [325,328,329])){
			$papers = $this->Common_model->get_all_papers($student_id,$class_id);
		}

		if($this->classData->class_group == 'Y'){
			$papers_list = $this->Common_model->get_all_group_papers($student_id,$class_id);
		}
	
		
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
		// $this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit = 0;
		$this->grade_tot_point=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->check_grace_marks = false;
		$this->withheld = false;
        $this->withheld_practical = false;
        $this->withheld_internal = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
		foreach($papers_list as $paper){
			$this->paper = $paper;
			$this->_row();
		}
		
		$this->notification_agpa();
        return $this->result();
		
	}

	public function view_notification_result($student_id,$course_group_id,$class_id,$mode)
	{
		
		$std  = $this->Common_model->getRecordByWhere('new_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
		
		if($std[0]->sub_group_id == 1 || in_array($class_id, [325,328,329])){
			$papers = $this->Common_model->get_all_papers($student_id,$class_id);
		}

		if($this->classData->class_group == 'Y'){
			$papers_list = $this->Common_model->get_all_group_papers($student_id,$class_id);
		}
	
		
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
		// $this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
		$this->obt_tot_credit = 0;
		$this->grade_tot_point=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->check_grace_marks = false;
		$this->withheld = false;
        $this->withheld_practical = false;
        $this->withheld_internal = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
		foreach($papers_list as $paper){
			$this->paper = $paper;
			$this->_row();
		}
		
		$this->notification_result();
		return $this->set_result();
		
	}



	public function _row()
	{
		if($this->paper['sub_group_id']=='1'){
			if (isset($this->foundation_paper[$this->paper['group_paper_name']])) {
				if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
				if($this->paper['theory_marks'] === 'ABS'){
					$this->foundation_paper[$this->paper['group_paper_name']]['obt'] = 'ABS';
				}
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] += $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['sub_group'] = $this->paper['sub_group_id'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] += $this->paper['credit_point'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] += $this->paper['max_theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['type'] = $this->paper['type'];
				$this->_echo_row_foudation($this->paper['group_paper_name']);
			}else{
				if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
				if($this->paper['theory_marks'] === 'ABS'){
					$this->foundation_paper[$this->paper['group_paper_name']]['obt'] = 'ABS';
				}
			
				$this->foundation_paper[$this->paper['group_paper_name']]['type'] = $this->paper['type'];
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] = $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['sub_group'] = $this->paper['sub_group_id'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] = $this->paper['max_theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['paper_code'] = $this->paper['paper_code'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] = $this->paper['credit_point'];
				$paper_name_post_fix = ($this->paper['group_paper_name']==1) ? 'I' : 'II';
				$this->foundation_paper[$this->paper['group_paper_name']]['paper_name'] = 'आधार पाठ्यक्रम - '.$paper_name_post_fix;
			}
		}else{
			$this->_echo_row();
		}
	}

	private function _echo_row()
	{
		$this->paper_code();
		$this->paper_name();
		$this->min_max_no();
		$this->credit();
		$this->grade();
		$this->grade_point();
		$this->credit_point();
	}

	private function _echo_row_foudation($sub_group_id)
	{
		$this->paper_code_foudation($sub_group_id);
		$this->paper_name_foudation($sub_group_id);
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

	private function paper_name()
	{
		$this->result_array[$this->paper['paper_code']]["type"] = $this->paper["type"];
		$this->result_array[$this->paper['paper_code']]['sub_group'] = $this->paper['sub_group_id'];
		$this->result_array[$this->paper['paper_code']]["paper_name"] = $this->paper["group_paper_code"].' - '.$this->paper["paper_name"];
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
			if($this->mode=='REG'){
				
                if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
                if($this->paper["int_marks"]=='' || $this->paper["int_marks"]=='N'){
                    $this->withheld_internal = true;
                }
				$check_fail_marks = $this->paper["theory_marks"] +  $this->paper["int_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
				$check_fail_tot_marks = $this->paper["max_theory_marks"] +  $this->paper["max_internal_marks"];
				$tot_obt_marks = $this->paper["theory_marks"] + $this->paper["int_marks"];
				$tot_marks = $this->paper["max_theory_marks"] + $this->paper["max_internal_marks"];
				$min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
			}else{
				if ($this->paper['theory_marks']==''){
					$this->withheld = true;
				}
				$check_fail_marks = $this->paper["theory_marks"];
				// $check_fail_min_marks = $this->paper["private_min_theory_marks"];
                $check_fail_min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
				$check_fail_tot_marks = $this->paper["private_max_theory_marks"];
				$tot_obt_marks = $this->paper["theory_marks"];
				$tot_marks = $this->paper["private_max_theory_marks"];
				// $min_marks = $this->paper["private_min_theory_marks"];
                $min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
			}
		}else{
            if ($this->paper['p_marks']=='' || $this->paper['p_marks']=='N'){
                $this->withheld_practical = true;
            }
            
            if($this->paper['int_marks']=='N'&& $mode != 'PVT' && $this->paper['max_internal_marks'] !=0 && $this->classData->practical_internal_marks == 'Y'){
                // $rwas_count++;
                // $this->withheld = true;
                $this->withheld_internal = true;
              }
			$check_fail_marks = $this->paper["p_marks"]+  $this->paper["int_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
				$check_fail_tot_marks = $this->paper["max_theory_marks"] + $this->paper["max_internal_marks"];
			$tot_obt_marks = $this->paper["p_marks"]+  $this->paper["int_marks"];
			$tot_marks = $this->paper["max_theory_marks"]+ $this->paper["max_internal_marks"];
			$min_marks = $this->paper["min_theory_marks"]+ $this->paper["min_internal_marks"];
		}
		// echo $tot_obt_marks.'ss';
		// echo $tot_marks.'<br>';
			$persent = $tot_obt_marks*100/$tot_marks;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade',$where);
		// echo $this->db->last_query().'<br>';
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			$this->fail_count++;
			$this->fail_obt_marks += $check_fail_marks;
			$this->fail_tot_marks += $check_fail_tot_marks;
			$this->fail_min_marks += $check_fail_min_marks;
			$this->result_array[$this->paper['paper_code']]["obt_credit"] = 0;
		}else{
			$this->obt_tot_credit += $this->paper['credit_point'];
			// $this->grade_tot_point += $this->paper['grade_point'];
			$this->result_array[$this->paper['paper_code']]["obt_credit"] = $this->paper["credit_point"];
		}
		$this->grade_point = $gradeData[0]->grade_point;
		$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
		

	}

	private function grade_point()
	{
		 $this->result_array[$this->paper['paper_code']]['grade_point'] = $this->grade_point;
		$this->grade_tot_point += $this->grade_point;
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

	private function paper_name_foudation($sub_group_id){
		$data = $this->paper["group_paper_code"].' - '.$this->foundation_paper[$sub_group_id]["paper_name"];
		$this->result_array[$this->paper['paper_code']]['paper_name'] = $data;
		$this->result_array[$this->paper['paper_code']]['type'] = $this->foundation_paper[$sub_group_id]["type"];
		$this->result_array[$this->paper['paper_code']]['sub_group'] = $this->foundation_paper[$sub_group_id]['sub_group'];
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
		//echo "<pre>";
		//print_r($this->foundation_paper);echo "</pre>";
		
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			$this->fail_count++;
			$this->fail_obt_marks += $tot_obt_marks;
			$this->fail_tot_marks += $tot_marks;
			$this->fail_min_marks += 35;
		 	$this->result_array[$this->paper['paper_code']]["obt_credit"] = 0;
			
		}else{
			 $this->obt_tot_credit += $this->foundation_paper[$sub_group_id]['credit_point'];
			// $this->grade_tot_point += $this->foundation_paper[$sub_group_id]['grade_point'];
			 $this->result_array[$this->paper['paper_code']]["obt_credit"] = $this->foundation_paper[$sub_group_id]['credit_point'];
		}
		$this->grade_point = $gradeData[0]->grade_point;
		$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
	}

	public function set_result()
	{
		if ($this->withheld==true) {
			return $this->result = 'RW';
		}elseif($this->withheld_internal == true){
            return $this->result = 'RWAS';
        }elseif($this->withheld_practical == true){
            return $this->result = 'RWPR';
        }elseif ($this->fail_count!=0 && $this->agpa>=4) {
			if ($this->check_grace_marks) {
				return	$this->result = 'PASS BY GRACE';
			}else{
				return 	$this->result = 'FAIL';
			}
		}else if($this->agpa<4){
			
			return $this->result = 'FAIL';
		}else{
			return $this->result = 'PASS';
		}
		
	}

	public function result()
	{
		return $data = array(
				'tot_credit' => $this->tot_credit,
                'obt_credit'=>$this->obt_tot_credit,
				'tot_credit_point' => $this->tot_credit_point,
				'agpa' => $this->agpa,
				'result' => $this->result
			);
	}

	public function min_max_no()
	{
		if ($this->mode=='PVT') {
			$this->result_array[$this->paper['paper_code']]['max_marks'] = $this->paper['private_max_theory_marks'];
			if ($this->paper['type']=='theory') {
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'] +$this->paper['min_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks']= $this->paper['theory_marks'];
			}else{
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'] + $this->paper['min_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['p_marks'];
			}
		}else{
			$this->result_array[$this->paper['paper_code']]['max_marks'] = $this->paper['max_theory_marks'];
			if ($this->paper['type']=='theory') {
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'];
				$this->result_array[$this->paper['paper_code']]['int_max_marks'] = $this->paper['max_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['int_min_marks'] = $this->paper['min_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['theory_marks'];
				$this->result_array[$this->paper['paper_code']]['int_obt_marks'] = $this->paper['int_marks'];
			}else{
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'];
				$this->result_array[$this->paper['paper_code']]['int_max_marks'] = $this->paper['max_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['int_min_marks'] = $this->paper['min_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['p_marks'];
				$this->result_array[$this->paper['paper_code']]['int_obt_marks'] = $this->paper['int_marks'];
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
		// echo "fail_count =>".$this->fail_count;
		// echo "<br>";
		// echo "fail_min_marks =>".$this->fail_min_marks;
		// echo "<br>";
		// echo "fail_obt_marks =>".$this->fail_obt_marks;
		// echo '<pre>';
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		
		echo "<tr>";
		echo '<td class="align-middle text-right">'.'Credit Earned'.'</td>';
	//	$i=1;
		foreach ($this->result_array as $key => $result) {
	//if($i==1){echo "<pre> ".$i;	print_r($result);echo "</pre>";$i++;}
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory'&& !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
				$this->check_grace_marks = true;
				 $this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				if(($result['f_abs'] == 'ABS' && $result['obt_marks'] != '0' )){
					$result['credit']=2;
					$result['obt_credit'] = 2;
					$this->obt_tot_credit -=2;
					$this->result_array[$key]['credit']=2;
					$credit_point = $result['obt_credit']*4;
					$this->result_array[$key]['credit_point']=$credit_point;
					$this->tot_credit_point += $credit_point;
					
				}else{
				$credit_point = $result['credit']*4;
				$this->result_array[$key]['credit_point']=$credit_point;
				 $this->tot_credit_point += $credit_point;
				}
				 
				$paper_codes=array('1RCBBA2','1RCBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4','2RBBA2','2RBBA4','2RBA2','2RBA4','2RBCOM2','2RBCOM4','2RBCOMCA2','2RBCOMCA4','2RBCOMT2','2RBCOMT4','2RBCA2','2RBCA4','2RBSCCBC2','2RBSCCBC4','2RBSCCS2','2RBSCCS4','2RBSCPCM2','2RBSCPCM4','2RBSW2','2RBSW4','3RBBA2','3RBBA4','3RBA2','3RBA4','3RBCOM2','3RBCOM4','3RBCOMCA2','3RBCOMCA4','3RBCOMT2','3RBCOMT4','3RBCA2','3RBCA4','3RBSCCBC2','3RBSCCBC4','3RBSCCS2','3RBSCCS4','3RBSCPCM2','3RBSCPCM4','3RBSW2','3RBSW4','3RRBBA2','3RRBBA4','2RCBBA2','2RCBBA4');
				if(in_array($key,$paper_codes))	
				{
					// $result['credit']=$result['credit']-2;
				echo "<td colspan= '2' class='text-center'>".$result['credit']."</td>";
				}else{
					
					echo "<td class='text-center'>".$result['credit']."</td>";
				}
				
				
			}else{
				if(($result['f_abs'] == 'ABS' && $result['obt_marks'] != '0'  && $result['sub_group'] == 1 && $this->fail_count == 0) || ($result['f_abs'] == 'ABS' && $result['obt_marks'] >= '35'  && $result['sub_group'] == 1 && $this->fail_count > 0) ){
					$result['obt_credit'] = 2;
					$this->obt_tot_credit -=2;
					$credit_point = $result['obt_credit']*$result['grade_point'];
				$this->result_array[$key]['credit_point']=$credit_point;
				$this->tot_credit_point -= $credit_point;
					
				}
				$paper_codes=array('1RCBBA2','1RCBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4','2RBBA2','2RBBA4','2RBA2','2RBA4','2RBCOM2','2RBCOM4','2RBCOMCA2','2RBCOMCA4','2RBCOMT2','2RBCOMT4','2RBCA2','2RBCA4','2RBSCCBC2','2RBSCCBC4','2RBSCCS2','2RBSCCS4','2RBSCPCM2','2RBSCPCM4','2RBSW2','2RBSW4','3RBBA2','3RBBA4','3RBA2','3RBA4','3RBCOM2','3RBCOM4','3RBCOMCA2','3RBCOMCA4','3RBCOMT2','3RBCOMT4','3RBCA2','3RBCA4','3RBSCCBC2','3RBSCCBC4','3RBSCCS2','3RBSCCS4','3RBSCPCM2','3RBSCPCM4','3RBSW2','3RBSW4','3RRBBA2','3RRBBA4','2RCBBA2','2RCBBA4');
				if(in_array($key,$paper_codes))	
				{
				echo "<td colspan= '2' class='text-center'>".$result['obt_credit']."</td>";
				}else{
					
					echo "<td class='text-center'>".$result['obt_credit']."</td>";
				}
				
				
			}
			
		}
		echo '<td class="text-center">'.$this->obt_tot_credit.'</td>';
		echo '</tr>';
		echo "<tr>";
		echo '<td class="align-middle text-right">'.'Grade Point'.'</td>';
		foreach ($this->result_array as $key => $result) {
			// echo $this->fail_obt_marks.'<br>';
			//  print_r( $result['type']);
			// echo "<td>".$result['grade_point']."</td>";
			// echo "<td>".$result['paper_name']."</td>";
			// echo "<td>".$result['max_marks']."</td>";
			// echo "<td>".$result['min_marks']."</td>";
			// if ($this->mode=='reg') {
			// 	echo "<td>".$result['int_max_marks']."</td>";
			// 	echo "<td>".$result['int_min_marks']."</td>";
			// }
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory'&& !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
				
				// $this->obt_tot_credit +=$result['credit'];
			//	echo "XX ". $result['obt_marks'].'credit'.$result['credit'];
				$this->check_grace_marks = true;
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				$credit_point = $result['credit']*4;
				$this->result_array[$key]['credit_point']=$credit_point;
				
				// $this->tot_credit_point += $credit_point;
				$this->grade_tot_point += 4;
				$result['grade_point'] =4;
				
			}else{
				$result['grade_point'] =$result['grade_point'];
			}
				
			// 	// echo "<td>".$obt_marks." G </td>";
			// 	// if ($this->mode=='reg') {
			// 	// 	echo "<td class='text-center'>".$result['int_obt_marks']."</td>";
			// 	// }
			// 	// echo "<td class='text-center'>".$result['credit']."</td>";
			// 	// echo "<td class='text-center'>P</td>";
			// 	// echo "<td class='text-center'>4</td>";
			// 	// echo "<td class='text-center'>".$credit_point."</td>";
			// }else{
				// echo "<td>".$result['obt_marks']."</td>";
				// if ($this->mode=='reg') {
				// 	echo "<td class='text-center'>".$result['int_obt_marks']."</td>";
				// }
				// echo "<td class='text-center'>".$result['obt_credit']."</td>";

				// if($key=='1RBCOM2' || $key=='1RBCOM4' || $key == '1RBCOMCA2' || $key == '1RBCOMCA4' || $key == '1RBA2' || $key == '1RBA4' || $key == '1RBBA2' || $key == '1RBBA4' || $key == '1RBCOMT2' || $key == '1RBCOMT4' || $key == '1RBSW2' || $key == '1RBSW4' || $key =='1RBCOMCA2'	|| $key == '1RBCOMCA4' || $key == '1RBCA2' || $key == '1RBCA4' || $key == '1RBSCPCM2' || $key == '1RBSCPCM4')

				$paper_codes=array('1RCBBA2','1RCBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4','2RBBA2','2RBBA4','2RBA2','2RBA4','2RBCOM2','2RBCOM4','2RBCOMCA2','2RBCOMCA4','2RBCOMT2','2RBCOMT4','2RBCA2','2RBCA4','2RBSCCBC2','2RBSCCBC4','2RBSCCS2','2RBSCCS4','2RBSCPCM2','2RBSCPCM4','2RBSW2','2RBSW4','3RBBA2','3RBBA4','3RBA2','3RBA4','3RBCOM2','3RBCOM4','3RBCOMCA2','3RBCOMCA4','3RBCOMT2','3RBCOMT4','3RBCA2','3RBCA4','3RBSCCBC2','3RBSCCBC4','3RBSCCS2','3RBSCCS4','3RBSCPCM2','3RBSCPCM4','3RBSW2','3RBSW4','3RRBBA2','3RRBBA4','2RCBBA2','2RCBBA4');
				if(in_array($key,$paper_codes))	
				{
				echo "<td colspan= '2' class='text-center'>".$result['grade_point']."</td>";
				}else{
					
					echo "<td class='text-center'>".$result['grade_point']."</td>";
				}
						
				// echo "<td class='text-center'>".$result['grade_point']."</td>";
				// echo "<td class='text-center'>".$result['credit_point']."</td>";
			
			
		}
		echo '<td class="text-center">'.$this->grade_tot_point.'</td>';
		echo "</tr>";
		echo "<tr>";
		echo '<td class="align-middle text-right">'.'Credit Points'.'</td>';
		foreach ($this->result_array as $key => $result) {
			
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory'&& !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
				$this->check_grace_marks = true;
				// $this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				$credit_point = $result['credit']*4;
				$this->result_array[$key]['credit_point']=$credit_point;
				// $this->tot_credit_point += $credit_point;
				
				
				$paper_codes=array('1RCBBA2','1RCBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4','2RBBA2','2RBBA4','2RBA2','2RBA4','2RBCOM2','2RBCOM4','2RBCOMCA2','2RBCOMCA4','2RBCOMT2','2RBCOMT4','2RBCA2','2RBCA4','2RBSCCBC2','2RBSCCBC4','2RBSCCS2','2RBSCCS4','2RBSCPCM2','2RBSCPCM4','2RBSW2','2RBSW4','3RBBA2','3RBBA4','3RBA2','3RBA4','3RBCOM2','3RBCOM4','3RBCOMCA2','3RBCOMCA4','3RBCOMT2','3RBCOMT4','3RBCA2','3RBCA4','3RBSCCBC2','3RBSCCBC4','3RBSCCS2','3RBSCCS4','3RBSCPCM2','3RBSCPCM4','3RBSW2','3RBSW4','3RRBBA2','3RRBBA4','2RCBBA2','2RCBBA4');
				if(in_array($key,$paper_codes))	
				{
				echo "<td colspan= '2' class='text-center'>".$credit_point."</td>";
				}else{
					
					echo "<td class='text-center'>".$credit_point."</td>";
				}
				
				
			}else{
				if($result['obt_marks'] === 'ABS'){
					$result['letter_grade'] = 'ABS';
				}
				$paper_codes=array('1RCBBA2','1RCBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4','2RBBA2','2RBBA4','2RBA2','2RBA4','2RBCOM2','2RBCOM4','2RBCOMCA2','2RBCOMCA4','2RBCOMT2','2RBCOMT4','2RBCA2','2RBCA4','2RBSCCBC2','2RBSCCBC4','2RBSCCS2','2RBSCCS4','2RBSCPCM2','2RBSCPCM4','2RBSW2','2RBSW4','3RBBA2','3RBBA4','3RBA2','3RBA4','3RBCOM2','3RBCOM4','3RBCOMCA2','3RBCOMCA4','3RBCOMT2','3RBCOMT4','3RBCA2','3RBCA4','3RBSCCBC2','3RBSCCBC4','3RBSCCS2','3RBSCCS4','3RBSCPCM2','3RBSCPCM4','3RBSW2','3RBSW4','3RRBBA2','3RRBBA4','2RCBBA2','2RCBBA4');
				if(in_array($key,$paper_codes))	
				{
				echo "<td colspan= '2' class='text-center'>".$result['credit_point']."</td>";
				}else{
					
					echo "<td class='text-center'>".$result['credit_point']."</td>";
				}
				
				
			}
			
		}
		echo '<td class="text-center">'.$this->tot_credit_point.'</td>';
		echo '</tr>';
		
		
	}

	private function total()
	{
		echo '<tr>';
		echo '<td class="align-middle text-right">'.'Letter Grade'.'</td>';
		if ($this->fail_count>0) {
			//  echo $this->fail_min_marks.'ss'.$this->fail_obt_marks;
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		
		foreach ($this->result_array as $key => $result) {
		
			// echo $this->fail_count.'grace'.$require_grace_marks.'kkkg'.$result['letter_grade'].'<br>';
			if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0') 
			){
				
				$result['letter_grade'] = 'ABS';
			}
			elseif ($this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') { 
			// echo $require_grace_marks;
				$result['letter_grade'] = 'P-G';
			}else{
				$result['letter_grade'] = $result['letter_grade'];
			}

			// if($key=='1RBCOM2' || $key=='1RBCOM4' || $key == '1RBCOMCA2' || $key == '1RBCOMCA4' || $key == '1RBA2' || $key == '1RBA4' || $key == '1RBBA2' || $key == '1RBBA4' || $key == '1RBCOMT2' || $key == '1RBCOMT4' || $key == '1RBSW2' || $key == '1RBSW4' || $key =='1RBCOMCA2' || $key == '1RBCOMCA4' || $key == '1RBCA2' || $key == '1RBCA4' || $key == '1RBSCPCM2' || $key == '1RBSCPCM4')

			$paper_codes=array('1RCBBA2','1RCBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4','2RBBA2','2RBBA4','2RBA2','2RBA4','2RBCOM2','2RBCOM4','2RBCOMCA2','2RBCOMCA4','2RBCOMT2','2RBCOMT4','2RBCA2','2RBCA4','2RBSCCBC2','2RBSCCBC4','2RBSCCS2','2RBSCCS4','2RBSCPCM2','2RBSCPCM4','2RBSW2','2RBSW4','3RBBA2','3RBBA4','3RBA2','3RBA4','3RBCOM2','3RBCOM4','3RBCOMCA2','3RBCOMCA4','3RBCOMT2','3RBCOMT4','3RBCA2','3RBCA4','3RBSCCBC2','3RBSCCBC4','3RBSCCS2','3RBSCCS4','3RBSCPCM2','3RBSCPCM4','3RBSW2','3RBSW4','3RRBBA2','3RRBBA4','2RCBBA2','2RCBBA4');
			if(in_array($key,$paper_codes))	
				{
				echo "<td colspan= '2' class='text-center'>".$result['letter_grade']."</td>";
				}else{
					echo "<td class='text-center'>".$result['letter_grade']."</td>";
				}
		}


		// 	echo '<td></td>';
		// 	echo '<td class="text-right" style="padding-right: 3rem!important;">कुल योग</td>';
		// 	echo '<td></td>';
		// 	if ($this->mode=='pvt') {

		// 	}else{
		// 		echo '<td></td>';
		// 		echo '<td></td>';
		// 		echo '<td></td>';
		// 	}
		// 	echo '<td></td>';
		// 	echo '<td></td>';
			echo '<td class="text-center">'.''.'</td>';
		// 	echo '<td></td>';
		// 	echo '<td></td>';
			// echo '<td class="text-center">'.$this->obt_tot_.'</td>';
		echo '</tr>';
	}

	private function result_head_pvt()
	{
		// $this->agpa = $this->tot_credit_point/$this->tot_credit;
		// echo '<tr>';
		// echo '<td></td>';
		// echo '<td class="text-right" style="padding-right: 3rem!important;">सत्रार्द्ध/ वर्ष</td>';
		// foreach ($this->allclass as $key => $class) {
		// 	echo '<td style="width: 80px;">'.$class->class_name.'</td>';	
		// }
		// echo '<td class="text-center">महायोगः</td>';
		// echo '<td class="text-center">परिणामः	</td>';
		// echo '<td class="text-center">श्रेणी</td>';
		// echo '<td></td>';
		// echo '</tr>';
	}

	private function AGPA_pvt()
	{
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		// echo '<tr>';
		// echo '<td></td>';
		// echo '<td class="text-right" style="padding-right: 3rem!important;">AGPA</td>';
		// echo '<td>'.round($this->agpa,2).'</td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '<td class="text-center"></td>';
		// echo '</tr>';
		// echo '<tr>';
		// echo '<td></td>';
		// echo '<td class="text-right" style="padding-right: 3rem!important;">Result</td>';
		// echo '<td>'.$this->result.'</td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '<td class="text-center"></td>';
		// echo '</tr>';
	}

	private function result_head()
	{
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		// echo '<tr>';
		// echo '<td></td>';
		// echo '<td class="text-right" style="padding-right: 3rem!important;" >सत्रार्द्ध/ वर्ष</td>';
		// foreach ($this->allclass as $key => $class) {
		// 	echo '<td colspan="2" >'.$class->class_name.'</td>';	
		// }
		// echo '<td class="text-center">महायोगः</td>';
		// echo '<td class="text-center">परिणामः	</td>';
		// echo '<td class="text-center">श्रेणी</td>';
		// echo '<td></td>';
		// echo '</tr>';
	}

	private function notification_agpa(){
		if ($this->fail_count>0) {
			$require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
	   }
	  
	   foreach ($this->result_array as $key => $result) {
		  
		   if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory'&& !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
			   
			   $this->obt_tot_credit +=$result['credit'];
			   
			   $this->check_grace_marks = true;
			   $req_marks = $result['min_marks']-$result['obt_marks'];
			   $obt_marks = $result['obt_marks']+$req_marks;
			   $credit_point = $result['credit']*4;
			   $this->result_array[$key]['credit_point']=$credit_point;
			   
			   $this->tot_credit_point += $credit_point;
			   $this->grade_tot_point += 4;
			   $result['grade_point'] =4;
		   }else{
			   $result['grade_point'] =$result['grade_point'];
		   }
		}
		// echo $this->tot_credit_point.'dd'.$this->tot_credit;
		
		
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		if($this->result == 'RW'|| $this->result=='RWPR'|| $this->result == 'RWAS'){
            $agpa = '';
        }else{
            $agpa = ($this->result == 'FAIL')?'0.00':number_format((float)$this->agpa, 2, '.', '');
        }
		
		//$agpa = number_format((float)$this->agpa, 2, '.', '');
		echo '<td class="text-center" style="padding:0px" align="center">'.$agpa.'</td>';
		
	}

	private function notification_result(){
		if ($this->fail_count>0) {
			$require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
	   }
	  
	   foreach ($this->result_array as $key => $result) {
		  
		   if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory'&& !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
			   
			   $this->obt_tot_credit +=$result['credit'];
			   
			   $this->check_grace_marks = true;
			   $req_marks = $result['min_marks']-$result['obt_marks'];
			   $obt_marks = $result['obt_marks']+$req_marks;
			   $credit_point = $result['credit']*4;
			   $this->result_array[$key]['credit_point']=$credit_point;
			   
			   $this->tot_credit_point += $credit_point;
			   $this->grade_tot_point += 4;
			   $result['grade_point'] =4;
		   }else{
			   $result['grade_point'] =$result['grade_point'];
		   }
		}
		// echo $this->tot_credit_point.'dd'.$this->tot_credit;
		
		
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		echo $this->result;
		// $agpa = ($this->result == 'FAIL')?'0.00':number_format((float)$this->agpa, 2, '.', '');
		// echo '<td class="text-center" style="padding:0px" align="center">'.$agpa.'</td>';
		
	}


	private function AGPA()
	{
		// echo  $this->tot_credit_point.' ppp'.$this->tot_credit;
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		// echo '<tr>';
		// echo '<td></td>';
		// echo '<td class="text-right" style="padding-right: 3rem!important;">AGPA</td>';
		// echo '<td colspan="2">'.round($this->agpa,2).'</td>';
		// echo '<td colspan="2"></td>';
		// echo '<td colspan="2"></td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '</tr>';
		// echo '<tr>';
		// echo '<td></td>';
		// echo '<td class="text-right" style="padding-right: 3rem!important;">Result</td>';
		// echo '<td colspan="2" style="width: 80px;">'.$this->result.'</td>';
		// echo '<td colspan="2"></td>';
		// echo '<td colspan="2"></td>';
		// echo '<td></td>';
		// echo '<td></td>';
		// echo '</tr>';
	}
}
