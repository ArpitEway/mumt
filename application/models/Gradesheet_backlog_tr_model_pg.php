<?php 

/**
 * 
 */
class Gradesheet_backlog_tr_model_pg extends CI_Model
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
	protected $withheld = false;
	protected $fail_tot_marks;
	protected $fail_obt_marks;
	protected $fail_min_marks;
	protected $result_array = array();


	public function view_result($student_id,$course_group_id,$class_id,$mode,$exam_id)
	{
		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('backlog_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id,'backlog_student_id'=>$exam_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$papers = $this->Common_model->get_all_backlog_papers($student_id,$class_id,$exam_id);
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
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
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
           
			$this->_row();
		}
        $this->echo_result();
		$this->total();
		$this->AGPA();
		$this->set_result();
		return $this->result();
		
	}

	// notification 
	public function view_notification($student_id,$course_group_id,$class_id,$mode,$exam_id)
	{
		
		$std  = $this->Common_model->getRecordByWhere('backlog_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id,'backlog_student_id'=>$exam_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$papers = $this->Common_model->get_all_backlog_papers($student_id,$class_id,$exam_id );
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
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
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
	
		
		$this->notification_agpa();
		
	}

	public function view_notification_result($student_id,$course_group_id,$class_id,$mode,$exam_id)
	{
		
		$std  = $this->Common_model->getRecordByWhere('backlog_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id,'backlog_student_id'=>$exam_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$papers = $this->Common_model->get_all_backlog_papers($student_id,$class_id,$exam_id );
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
		$this->classCount = count($this->allclass);
		
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
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
	
		
		$this->notification_result();
		
	}

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
		// $this->result_array[$this->paper['paper_code']]["credit"] = $this->paper["credit_point"];
        if($this->paper["status"]=="C"){
			$this->result_array[$this->paper['paper_code']]["credit"] = $this->paper["credit_point"]." C";
		}else{
			$this->result_array[$this->paper['paper_code']]["credit"] = $this->paper["credit_point"];
		}
	}

	private function grade()
	{

       
		if ($this->paper["type"]=='theory') {
			if($this->mode=='REG'){
				if ($this->paper['theory_marks']=='' || ($this->paper["int_marks"]=='' || $this->paper["int_marks"]=='N')) {
					$this->withheld = true;
				}
                if(($this->paper['theory_marks']=='00' || $this->paper['theory_marks']=='0') && $this->paper['status'] =='B'){
                    $this->zero_count++;
                }
                if ($this->paper["status"]=='B') {
                    $this->theory_fail_count++;
                }
				$check_fail_marks = $this->paper["theory_marks"] ;
				$check_fail_min_marks = $this->paper["min_theory_marks"] ;
				$check_fail_tot_marks = $this->paper["max_theory_marks"] ;
				$tot_obt_marks = $this->paper["theory_marks"] + $this->paper["int_marks"];
				$tot_marks = $this->paper["max_theory_marks"] + $this->paper["max_internal_marks"];
				$min_marks = $this->paper["min_theory_marks"] + $this->paper["min_internal_marks"];
			}else{
				if ($this->paper['theory_marks']==''){
					$this->withheld = true;
				}
                if(($this->paper['theory_marks']=='00' || $this->paper['theory_marks']=='0') && $this->paper['status'] =='B'){
                    $this->zero_count++;
                }
                if ($this->paper["status"]=='B') {
                    $this->theory_fail_count++;
                }
				$check_fail_marks = $this->paper["theory_marks"];
				$check_fail_min_marks = $this->paper["private_min_theory_marks"];
				$check_fail_tot_marks = $this->paper["private_max_theory_marks"];
				$tot_obt_marks = $this->paper["theory_marks"];
				$tot_marks = $this->paper["private_max_theory_marks"];
				$min_marks = $this->paper["private_min_theory_marks"];
			}
		}else{
			if ($this->paper['p_marks']==''){
				$this->withheld = true;
			}
            if($this->paper['int_marks']=='N'&& $mode != 'PVT' && $this->paper['max_internal_marks'] !=0 && $this->classData->practical_internal_marks == 'Y'){
                // $rwas_count++;
                $this->withheld = true;
              }
			$check_fail_marks = $this->paper["p_marks"];
			$check_fail_min_marks = $this->paper["min_theory_marks"] ;
			$check_fail_tot_marks = $this->paper["max_theory_marks"] ;
			$tot_obt_marks = $this->paper["p_marks"]+  $this->paper["int_marks"];
			$tot_marks = $this->paper["max_theory_marks"]+ $this->paper["max_internal_marks"];
			$min_marks = $this->paper["min_theory_marks"]+ $this->paper["min_internal_marks"];
		}
	
		$persent =$check_fail_marks*100/$check_fail_tot_marks;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
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
			// $this->result_array[$this->paper['paper_code']]["obt_credit"] = $this->paper["credit_point"];
            if($this->paper["status"] == "C"){
				$this->result_array[$this->paper['paper_code']]["obt_credit"] = $this->paper["credit_point"]." C";
			}else{
				$this->result_array[$this->paper['paper_code']]["obt_credit"] = $this->paper["credit_point"];
			}
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
		
		$this->tot_credit_point += $this->grade_point*$this->credit_point;
		$this->result_array[$this->paper['paper_code']]['credit_point'] = $this->grade_point*$this->credit_point;
	}

	

	public function set_result()
	{
       
		if ($this->withheld==true) {
			return $this->result = 'RW';
		}elseif ($this->fail_count!=0 && $this->agpa>=4) {
			return 	$this->result = 'FAIL';
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
				'tot_credit_point' => $this->tot_credit_point,
				 'obt_credit'=>$this->obt_tot_credit,
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
		
		echo "<tr>";
		echo '<td class="align-middle text-right">'.'Credit Earned'.'</td>';
		foreach ($this->result_array as $key => $result) {
							
					echo "<td class='text-center'>".$result['obt_credit']."</td>";
        }
		echo '<td class="text-center">'.$this->obt_tot_credit.'</td>';
		echo '</tr>';
		echo "<tr>";
		echo '<td class="align-middle text-right">'.'Grade Point'.'</td>';
		foreach ($this->result_array as $key => $result) {
			
				$result['grade_point'] =$result['grade_point'];
				echo "<td class='text-center'>".$result['grade_point']."</td>";

		}
		echo '<td class="text-center">'.$this->grade_tot_point.'</td>';
		echo "</tr>";
		echo "<tr>";
		echo '<td class="align-middle text-right">'.'Credit Points'.'</td>';
		foreach ($this->result_array as $key => $result) {
			
				if($result['obt_marks'] === 'ABS'){
					$result['letter_grade'] = 'ABS';
				}
				
				echo "<td class='text-center'>".$result['credit_point']."</td>";
		}
		
		echo '<td class="text-center">'.$this->tot_credit_point.'</td>';
		echo '</tr>';	
	}

	private function total()
	{
		echo '<tr>';
		echo '<td class="align-middle text-right">'.'Letter Grade'.'</td>';
	
		foreach ($this->result_array as $key => $result) {
			
			if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
		
            else{
				$result['letter_grade'] = $result['letter_grade'];
			}

	        echo "<td class='text-center'>".$result['letter_grade']."</td>";
				
		}

			echo '<td class="text-center">'.''.'</td>';
	
		echo '</tr>';
	}

	
    

	private function result_head()
	{
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
	
	}

	private function notification_agpa(){

	   foreach ($this->result_array as $key => $result) {
		
			   $result['grade_point'] =$result['grade_point'];
		
		}
		
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		
		 if($this->result == 'RW'){$agpa = ''; }else{$agpa =($this->result == 'FAIL')?'0.00':number_format((float)$this->agpa, 2, '.', '');}
		echo '<td class="text-center" style="padding:0px" align="center">'.$agpa.'</td>';
		
	}

	private function notification_result(){
		if ($this->fail_count>0) {
			$require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
	   }
	  
	   foreach ($this->result_array as $key => $result) {
	      $result['grade_point'] =$result['grade_point'];
		
		}
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		echo $this->result;
	
		
	}


	private function AGPA()
	{
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
	}
}
