<?php 

/**
 * 
 */
class Upload_old_data_pg extends CI_Model
{
    protected $student;
	protected $paper;
	protected $percent;
	protected $grade_point;
	protected $credit_point;
	protected $obt_marks;
	protected $total_marks;
	protected $agpa;
	protected $grace_agpa;
	protected $result;
	protected $fail_count = 0;
	protected $tot_credit_point = 0;
	protected $tot_credit = 0;
	protected $foundation_paper = array();
	protected $classCount = 0;
	protected $allclass;
	protected $classData;
    // protected $result_this_fc1;
    // protected $result_this_fc2;
	protected $obt_tot_credit;
	protected $check_grace_marks = false;
	protected $withheld = false;
	protected $fail_tot_marks;
	protected $fail_obt_marks;
	protected $fail_min_marks;
	protected $result_array = array();

	function __construct()
	{
		
	}

	
	// public function update_old_data($student_id,$course_group_id,$class_id,$mode)
    public function update_old_data($student)
	{
		// $table = $this->Common_model->getMaster('exam_form_table');
		$std  = $this->Common_model->getRecordByWhere('exam_form',array('class_id'=> $student->class_id,'student_id'=>$student->student_id));
        // print_r($std);die;
		$this->classData = $this->Common_model->getRecordById('class_master','id',$student->class_id);
		$papers = $this->Common_model->get_all_papers($student->student_id,$student->class_id);
	
		// get_all_group_papers
		// print_r($papers);die;
		
		// print_r($this->allclass);die;
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$student->class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->percent = 0;
		$this->tot_credit = 0;
        $this->student = $student;
		$this->mode = $student->university_mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->obt_marks = 0;
		$this->total_marks=0;
        // $this->result_this_fc1 = '';
        // $this->result_this_fc2 = '';
		$this->check_grace_marks = false;
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			
		
			$this->_row();
			
		}
		
        // echo $this->tot_credit_point.'ddd'.$this->tot_credit;
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		$this->upload_exam_data();
		echo "<hr>";
		// var_dump($this->result_array);
		
		// $this->echo_result_grade(); 
		
		//  $this->agpa = $this->tot_credit_point/$this->tot_credit;
		//  $this->set_result();
		// $this->total_grade();
		
		// return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
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
        $this->result_array[$this->paper['paper_code']]['paper_order'] = $this->paper['paper_order'];
        $this->result_array[$this->paper['paper_code']]['sub_group'] = $this->paper['sub_group_id'];
        $this->result_array[$this->paper['paper_code']]['group'] = $this->paper['group_id'];
		$this->result_array[$this->paper['paper_code']]["paper_name"] ='['. $this->paper["group_paper_name"].']'.$this->paper["paper_name"];
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
				$check_fail_marks = $this->paper["theory_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"];
				$check_fail_tot_marks = $this->paper["max_theory_marks"];
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
			$this->obt_marks += $this->paper["p_marks"]+$this->paper["int_marks"];
		 $this->total_marks += $this->paper["max_theory_marks"]+  $this->paper["max_internal_marks"];
		
			if ($this->paper['p_marks']==''){
				$this->withheld = true;
			}
			$check_fail_marks = $this->paper["p_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"];
				$check_fail_tot_marks = $this->paper["max_theory_marks"];
			$tot_obt_marks = $this->paper["p_marks"]+$this->paper["int_marks"];
			$tot_marks = $this->paper["max_theory_marks"]+$this->paper["max_internal_marks"];
			$min_marks = $this->paper["min_theory_marks"]+$this->paper["min_internal_marks"];
		}
		// $persent = $tot_obt_marks*100/$tot_marks;
		//  echo $tot_obt_marks.'tot'.$tot_marks.'<br>';
		//  $tot_marks ;die;
		$persent =$check_fail_marks*100/$check_fail_tot_marks;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
	
        // echo $gradeData[0]->letter_grade.$this->result_array[$this->paper['paper_code']]["paper_name"].'<br>';
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			$this->fail_count++;
			$this->fail_obt_marks += $check_fail_marks;
			$this->fail_tot_marks += $check_fail_tot_marks;
			$this->fail_min_marks += $check_fail_min_marks;
			$this->result_array[$this->paper['paper_code']]['obt_credit'] = 0;
			$this->grade_point = $gradeData[0]->grade_point;
			$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
		}else{
			$this->obt_tot_credit += $this->paper['credit_point'];
			 $this->result_array[$this->paper['paper_code']]['obt_credit'] = $this->paper['credit_point'];
			 $persent = $tot_obt_marks*100/$tot_marks;
			$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
			$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
			$this->grade_point = $gradeData[0]->grade_point;
			$this->result_array[$this->paper['paper_code']]['letter_grade'] = $gradeData[0]->letter_grade;
		}
		
		// var_dump($this->check_grace_marks);
		// echo $student_id;
		// echo $this->classData->final_result_permission;
		// echo $this->fail_count;die;
		
	}

	private function grade_point()
	{
		$this->result_array[$this->paper['paper_code']]['grade_point'] = $this->grade_point;
	}

	private function credit_point()

	{
		// echo $this->grade_point.'ff'.$this->credit_point.'<br>';
		$this->tot_credit_point += $this->grade_point*$this->credit_point;
		$this->result_array[$this->paper['paper_code']]['credit_point'] = $this->grade_point*$this->credit_point;
	}

	


	public function set_result()
	{
		// var_dump($this->withheld);die;
        // echo $this->agpa.'dd'.$this->fail_count;
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

	public function result()
	{
		
		 $this->percent = $this->obt_marks*100/$this->total_marks;
		return $data = array(
				'tot_credit' => $this->tot_credit,
				'obt_credit' => $this->obt_tot_credit,
				'credit_point' => $this->tot_credit_point,
				'agpa' => $this->agpa,
				'result' => $this->result,
				'equivalent' => ($this->agpa*10)
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
				$this->result_array[$this->paper['paper_code']]['int_max_marks'] = '-';
				$this->result_array[$this->paper['paper_code']]['int_min_marks'] = '-';
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['p_marks'];
				$this->result_array[$this->paper['paper_code']]['int_obt_marks'] = '-';
			}
		}
	}


    protected function upload_exam_data()
	{
        // echo $this->result;die;
		if($this->result=='WITHHELD'){
			return false;
		}elseif ($this->result=='FAIL') {
			$result = 'FAIL';
		}else{
			$result = $this->result;
		}
		$per = $this->obt_marks*100/$this->total_marks;
		$percentage = round($per, 2);
        $examData = array(
            'student_id' => $this->student->student_id,
            'session' => $this->student->session,
            'class_order' => $this->classData->class_order,
            'center_id' => $this->student->center_id,
            'center_code' => $this->student->center_code,
            'course_group_id' =>$this->student->course_group_id,
            'course_name' => $this->student->course_name,
            'class_id' => $this->student->class_id,
            'enrollment_no' => $this->student->enrollment_no,
            'roll_no' => $this->student->roll_number,
            'name' => $this->student->name,
            'exam_year' => 'Feb 2023',
            'f_h_name' => $this->student->f_h_name,
            'mother_name' => $this->student->mother_name,
            'marksheet_no' =>$this->student->marksheet_no,
            'university_mode'=>$this->student->university_mode,
            'photo'=>$this->student->photo,
            'total_marks'=>$this->total_marks,
            'obtain_marks'=>$this->obt_marks,
			'agpa'=>number_format((float)$this->agpa, 2, '.', ''),
            'percentage' => $percentage,
            'update_date'=>date('Y-m-d'),
            'exam_status'=>'R',
            'exam_result'=>$result
           
        );
		// print_r($examData);
       		 $exam_data_id = $this->Common_model->insertAll('old_exam_data',$examData);
		  echo $this->db->last_query().'<br>';
		 $this->upload_old_result_data($exam_data_id);
	}

    protected function upload_old_result_data($old_exam_data_id)
	{
		
		if ($this->fail_count>0) {
			$require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
        $x=0;
		foreach ($this->result_array  as $key => $result) {
           
       
       
			$paper_name = explode(']',$result['paper_name']);
            $ResultData = array(
                'exam_data_id' =>  $old_exam_data_id ,
                'student_id' =>  $this->student->student_id ,
                'course_group_id' => $this->student->course_group_id ,
                'class_id' =>  $this->student->class_id ,
                'paper_code'=> $key ,
                'type'=> $result['type'] ,
                 'sub_group_id'=>$result['sub_group'] ,
                 'group_id'=>$result['group'] ,
                // 'max_theory_marks'=> $max_theory_marks,
                // 'max_int_marks'=> $max_int_marks,
                // 'min_theory_marks'=> $min_theory_marks,
                // 'min_int_marks'=> $min_int_marks,
                // 'theory_marks'=> $marks->theory_marks,
                // 'p_marks'=> $marks->p_marks,
                // 'int_marks'=> $marks->int_marks,
                'paper_name'=>  $paper_name[1],
                // 'result' => $result ,
                'p_order'=> $result['paper_order'] 
            );
			// $oldreultdata = array(
			// 	'exam_data_id' => $old_exam_data_id,
			// 	'student_id' => $this->student->student_id,
			// 	'course_group_id' => $this->student->course_group_id,
			// 	'class_id' => $this->student->old_class_id,
			// 	'institute_id' => $this->student->institute_id,
			// 	'paper_code' => $key,
			// 	'type' => $result['type'],
			// 	'paper_id' => $result['paper_id'],
			// 	'paper_name' => $result['paper_name'],
			// 	'p_order' => $result['paper_order'],
			// 	'obtain_credit' => $result['obt_credit'],
			// 	'credit' => $result['credit'],
			// );
			if ($result['type']=='theory') {
				$ResultData['max_theory_marks'] = $result['max_marks'];
				$ResultData['min_theory_marks'] = $result['min_marks'];
				if ($this->fail_count>0 && $require_grace_marks<4 && ($result['letter_grade']=='F' || $result['letter_grade']=='ABS')) {
					$ResultData['theory_marks'] = $result['obt_marks'];
					$ResultData['result'] = 'PASS BY GRACE';
					// $oldreultdata['credit'] = $result['credit'];
					$tot_obt_grace = $result['obt_marks']+$result['int_obt_marks'];
				$tot_marks_grace = $result['max_marks']+$result['int_max_marks'];
				$persent = $tot_obt_grace*100/$tot_marks_grace;
			$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
			$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
				$result['grade_point'] = $gradeData[0]->grade_point;
				$this->tot_credit_point += $result['grade_point']*$result['credit'];
				$this->grace_agpa = $this->tot_credit_point/$this->tot_credit;
					$this->check_grace_marks = true;
				}else{
					if ($result['letter_grade']=='F' || $result['letter_grade']=='ABS') {
						$result_this = 'FAIL';
					}else{
						$result_this = 'PASS';
					}
					$ResultData['theory_marks'] = $result['obt_marks'];
					$ResultData['result'] = $result_this;
				}

				if ($this->student->university_mode=='REG') {
					$ResultData['max_int_marks'] = $result['int_max_marks'];
					$ResultData['min_int_marks'] = $result['int_min_marks'];
					$ResultData['int_marks'] = $result['int_obt_marks'];
				}
			}else{
				if ($result['letter_grade']=='F' || $result['letter_grade']=='ABS') {
					$result_this = 'FAIL';
				}else{
					$result_this = 'PASS';
				}
                if ($this->student->university_mode=='REG' && $this->classData->practical_internal_marks == 'Y') {
					$ResultData['max_int_marks'] = $result['int_max_marks'];
					$ResultData['min_int_marks'] = $result['int_min_marks'];
					$ResultData['int_marks'] = $result['int_obt_marks'];
				}
				$ResultData['max_p_marks'] = $result['max_marks'];
				$ResultData['min_p_marks'] = $result['min_marks'];
				$ResultData['p_marks'] = $result['obt_marks'];
				$ResultData['result'] = $result_this;
			}

			 $this->Common_model->insertAll('old_result_data',$ResultData);
			 echo $this->db->last_query().'<br>';
			// var_dump($oldreultdata);
            // echo '<pre>';
            // print_r($ResultData);
		
        $x++;
        
    }
    $this->update_old_exam_result($old_exam_data_id);	
	}
    public function update_old_exam_result($old_exam_data_id)
	{
		
		if ($this->check_grace_marks) {
			$this->Common_model->updateRecordByConditions('old_exam_data',array('id' => $old_exam_data_id), array('exam_result' =>'PASS BY GRACE','agpa'=>number_format((float)$this->grace_agpa, 2, '.', '')));
			echo $this->db->last_query().'<br>';
		}
        $studentData = array('upload_result'=>'Y');
        echo $this->agpa;
        if($this->agpa < 4){
            $studentData['promote'] = 'D';    
        }else{
            $studentData['promote'] = 'N';
        }
     
       $this->Common_model->updateRecordByConditions('student',array('student_id'=>$this->student->student_id),$studentData);   
	// 	// $this->Common_model->updateRecordByConditions('student_result_aug_22',array('student_id'=>$this->student->student_id),array('upload_result' => 'Y'));
	 	echo $this->db->last_query().'<br>';
	}
	
}
