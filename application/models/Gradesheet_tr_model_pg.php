<?php 

/**
 * 
 */
class Gradesheet_tr_model_pg extends CI_Model
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
		
			$papers = $this->Common_model->get_all_papers($student_id,$class_id);
       
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
		
		// var_dump($this->result_array);
		$this->echo_result(); 
		$this->total();
		if($this->mode=='REG'){
			// $this->result_head();
			$this->AGPA();
			$this->set_result();
			
		}else{
			$this->result_head_pvt();
			$this->set_result();
			$this->AGPA_pvt();
		}
		return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}

	// notification 
	public function view_notification($student_id,$course_group_id,$class_id,$mode)
	{
		
		$std  = $this->Common_model->getRecordByWhere('new_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
		
		
			$papers = $this->Common_model->get_all_papers($student_id,$class_id);
		
	
		
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
	
       
		$this->notification_agpa();
       
        return $this->result();
		
	}

	public function view_notification_result($student_id,$course_group_id,$class_id,$mode)
	{
		
		$std  = $this->Common_model->getRecordByWhere('new_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
		
		
			$papers = $this->Common_model->get_all_papers($student_id,$class_id);
		
	
		
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
	
		
		$this->notification_result();
		
	}



	// public function _row()
	// {
		
	// 		$this->_echo_row();
		
	// }

	private function _row()
	{
		$this->paper_code();
		$this->paper_name();
		$this->min_max_no();
		$this->credit();
		$this->grade();
		$this->grade_point();
		$this->credit_point();
	}

	

	private function paper_code()
	{
		 
		$this->result_array[$this->paper['paper_code']] = array();
		array_push($this->result_array[$this->paper['paper_code']], $this->paper["paper_code"]);
	}

	private function paper_name()
	{
		$this->result_array[$this->paper['paper_code']]["type"] = $this->paper["type"];
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
				if ($this->paper['theory_marks']==''){
                    $this->withheld = true;
                }
                if($this->paper["int_marks"]=='' || $this->paper["int_marks"]=='N') {
					// 
                    $this->withheld_internal = true;
				}
                if($this->paper["int_marks"]=='ABS'){
                    $check_fail_marks = 'ABS';
                }else{
                    $check_fail_marks = $this->paper["theory_marks"] ;
                }
				
				$check_fail_min_marks = $this->paper["min_theory_marks"] ;
				$check_fail_tot_marks = $this->paper["max_theory_marks"] ;
				$tot_obt_marks = $this->paper["theory_marks"] + $this->paper["int_marks"];
				$tot_marks = $this->paper["max_theory_marks"] + $this->paper["max_internal_marks"];
				$min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
			}else{
				if ($this->paper['theory_marks']==''){
					$this->withheld = true;
				}
				$check_fail_marks = $this->paper["theory_marks"];
				$check_fail_min_marks = $this->paper["private_min_theory_marks"];
				$check_fail_tot_marks = $this->paper["private_max_theory_marks"];
				$tot_obt_marks = $this->paper["theory_marks"];
				$tot_marks = $this->paper["private_max_theory_marks"];
				$min_marks = $this->paper["private_min_theory_marks"];
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
			$check_fail_marks = $this->paper["p_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"] ;
				$check_fail_tot_marks = $this->paper["max_theory_marks"] ;
			$tot_obt_marks = $this->paper["p_marks"]+  $this->paper["int_marks"];
			$tot_marks = $this->paper["max_theory_marks"]+ $this->paper["max_internal_marks"];
			$min_marks = $this->paper["min_theory_marks"]+ $this->paper["min_internal_marks"];
		}
	// 	echo $tot_obt_marks.'ss';
	//  echo $tot_marks.'<br>';
			// $persent = $tot_obt_marks*100/$tot_marks;
			
			$persent =$check_fail_marks*100/$check_fail_tot_marks;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		// $gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
		if($this->classData->id=='268'){
				$gradeData = $this->Common_model->getRecordByWhere('letter_grade',$where);
			}else{
				$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
			}
		// echo $this->db->last_query().'<br>';
        // echo $gradeData[0]->letter_grade ;
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			$this->fail_count++;
			$this->fail_obt_marks += $check_fail_marks;
			$this->fail_tot_marks += $check_fail_tot_marks;
			$this->fail_min_marks += $check_fail_min_marks;
			$this->result_array[$this->paper['paper_code']]["obt_credit"] = 0;
			$this->grade_point = $gradeData[0]->grade_point;
			$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
		}else{
			$this->obt_tot_credit += $this->paper['credit_point'];
			// $this->grade_tot_point += $this->paper['grade_point'];
			$this->result_array[$this->paper['paper_code']]["obt_credit"] = $this->paper["credit_point"];
			$persent = $tot_obt_marks*100/$tot_marks;
			$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
			$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
			$this->grade_point = $gradeData[0]->grade_point;
			$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
		}
		
	
		

	}

	private function grade_point()
	{
		 $this->result_array[$this->paper['paper_code']]['grade_point'] = $this->grade_point;
		$this->grade_tot_point += $this->grade_point;
	}

	private function credit_point()
	{
		// echo $this->grade_point.'ss'.$this->credit_point;
		$this->tot_credit_point += $this->grade_point*$this->credit_point;
		// echo $this->tot_credit_point;
		$this->result_array[$this->paper['paper_code']]['credit_point'] = $this->grade_point*$this->credit_point;
	}

	

	public function set_result()
	{
		
		if ($this->withheld==true) {
			return $this->result = 'RW';
		}elseif($this->withheld_internal == true){
            return $this->result = 'RWAS';
        }elseif($this->withheld_practical == true){
            return $this->result = 'RWPR';
        }
       
       if(is_nan($this->agpa)){
        return $this->result = 'FAIL';
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
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['private_min_theory_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks']= $this->paper['theory_marks'];
			}else{
				$this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'];
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
		foreach ($this->result_array as $key => $result) {
			
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory' && !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
				$tot_obt_grace = $result['obt_marks']+$result['int_obt_marks'];
				$tot_marks_grace = $result['max_marks']+$result['int_max_marks'];
				$persent = $tot_obt_grace*100/$tot_marks_grace;
			$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
			if($this->classData->id=='268'){
				$gradeData = $this->Common_model->getRecordByWhere('letter_grade',$where);
			}else{
				$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
			}
				$result['grade_point'] = $gradeData[0]->grade_point;
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				$credit_point = $result['credit']*$result['grade_point'];
				$this->result_array[$key]['credit_point']=$credit_point;
				$this->tot_credit_point += $credit_point;
				
				// print_r($result);
			
					
					echo "<td class='text-center'>".$result['credit']."</td>";
				
				
				
			}else{
				
					echo "<td class='text-center'>".$result['obt_credit']."</td>";
				
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
				
				$this->check_grace_marks = true;
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				$tot_obt_grace = $result['obt_marks']+$result['int_obt_marks'];
				$tot_marks_grace = $result['max_marks']+$result['int_max_marks'];
				$persent = $tot_obt_grace*100/$tot_marks_grace;
			$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
			$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
				$result['grade_point'] = $gradeData[0]->grade_point;
				$credit_point = $result['credit']*$result['grade_point'];
				$this->result_array[$key]['credit_point']=$credit_point;
				
				// $this->tot_credit_point += $credit_point;
				$this->grade_tot_point += $result['grade_point'];
				echo "<td class='text-center'>".$result['grade_point']."</td>";
				// $result['grade_point'] =5;
			}else{
				$result['grade_point'] =$result['grade_point'];
				echo "<td class='text-center'>".$result['grade_point']."</td>";
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

			
			
					
				
						
				// echo "<td class='text-center'>".$result['grade_point']."</td>";
				// echo "<td class='text-center'>".$result['credit_point']."</td>";
			
			
		}
		echo '<td class="text-center">'.$this->grade_tot_point.'</td>';
		echo "</tr>";
		echo "<tr>";
		echo '<td class="align-middle text-right">'.'Credit Points'.'</td>';
		foreach ($this->result_array as $key => $result) {
			
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory' && !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
				$this->check_grace_marks = true;
				// $this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				 $tot_obt_grace = $result['obt_marks']+$result['int_obt_marks'];
				 $tot_marks_grace = $result['max_marks']+$result['int_max_marks'];
				$persent = $tot_obt_grace*100/$tot_marks_grace;
			$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
			$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
				$result['grade_point'] = $gradeData[0]->grade_point;
				$credit_point = $result['credit']*$result['grade_point'];
				$this->result_array[$key]['credit_point']=$credit_point;
				// $this->tot_credit_point += $credit_point;
				
				
			
					echo "<td class='text-center'>".$result['credit_point']."</td>";
				
				
				
			}else{
				if($result['obt_marks'] === 'ABS'){
					$result['letter_grade'] = 'ABS';
				}
				
					
					echo "<td class='text-center'>".$result['credit_point']."</td>";
				
				
				
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
			// print_r($this->result_array);
			// echo $this->fail_count.'grace'.$require_grace_marks.'kkkg'.$result['letter_grade'].'<br>';
			if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			elseif ($this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory' && !$this->withheld && !$this->withheld_practical && !$this->withheld_internal) {
			// echo $require_grace_marks;
			$tot_obt_grace = $result['obt_marks']+$result['int_obt_marks'];
			$tot_marks_grace = $result['max_marks']+$result['int_max_marks'];
		   $persent = $tot_obt_grace*100/$tot_marks_grace;
	   		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
	   		$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
				$result['letter_grade'] = $gradeData[0]->letter_grade.'-G';
			}else{
				$result['letter_grade'] = $result['letter_grade'];
			}

			// if($key=='1RBCOM2' || $key=='1RBCOM4' || $key == '1RBCOMCA2' || $key == '1RBCOMCA4' || $key == '1RBA2' || $key == '1RBA4' || $key == '1RBBA2' || $key == '1RBBA4' || $key == '1RBCOMT2' || $key == '1RBCOMT4' || $key == '1RBSW2' || $key == '1RBSW4' || $key =='1RBCOMCA2' || $key == '1RBCOMCA4' || $key == '1RBCA2' || $key == '1RBCA4' || $key == '1RBSCPCM2' || $key == '1RBSCPCM4')

		
					echo "<td class='text-center'>".$result['letter_grade']."</td>";
				
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
			   $tot_obt_grace = $result['obt_marks']+$result['int_obt_marks'];
			$tot_marks_grace = $result['max_marks']+$result['int_max_marks'];
		   $persent = $tot_obt_grace*100/$tot_marks_grace;
	   		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
	   		$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
				$result['grade_point'] = $gradeData[0]->grade_point;
			   $credit_point = $result['credit']*$result['grade_point'] ;
			   $this->result_array[$key]['credit_point']=$credit_point;
			   
			   $this->tot_credit_point += $credit_point;
			   $this->grade_tot_point += $result['grade_point'];
			  
		   }else{
			   $result['grade_point'] =$result['grade_point'];
		   }
		}
		// echo $this->tot_credit_point.'dd'.$this->tot_credit;
		
		
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		 if($this->result == 'RW' || $this->result == 'RWAS' || $this->result == 'RWPR'){$agpa = ''; }else{$agpa =($this->result == 'FAIL')?'0.00':number_format((float)$this->agpa, 2, '.', '');}
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
			   $tot_obt_grace = $result['obt_marks']+$result['int_obt_marks'];
			   $tot_marks_grace = $result['max_marks']+$result['int_max_marks'];
			  $persent = $tot_obt_grace*100/$tot_marks_grace;
				  $where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
				  $gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
				   $result['grade_point'] = $gradeData[0]->grade_point;
			   $credit_point = $result['credit']*$result['grade_point'];
			   $this->result_array[$key]['credit_point']=$credit_point;
			   
			   $this->tot_credit_point += $credit_point;
			   $this->grade_tot_point += $result['grade_point'];
			  
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
