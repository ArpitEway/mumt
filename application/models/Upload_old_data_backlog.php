<?php 

/**
 * 
 */
class Upload_old_data_backlog extends CI_Model
{
    protected $student;
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
    protected $marksheetDate;
	protected $exam_year;
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
        $this->db->order_by('sub_group_id', 'asc');
		$std  = $this->Common_model->getRecordByWhere('backlog_exam_form',array('class_id'=> $student->class_id,'student_id'=>$student->student_id));
        // print_r($std);die;
		$this->classData = $this->Common_model->getRecordById('class_master','id',$student->class_id);
		
		
		if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_backlog_papers($student->student_id,$student->class_id,$student->id);
		}
		if($this->classData->class_group == 'Y' || $student->class_id == 101){
		$papers_list = $this->Common_model->get_all_backlog_group_papers($student->student_id,$student->class_id,$student->id);
		}
        $date =$this->Common_model->getRecordById('marksheet_variables','class_id',$student->class_id);
	
		$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$student->class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->percent = 0;
		$this->tot_credit = 0;
        $this->student = $student;
		$this->mode = $student->mode;
		$this->fail_count=0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->obt_marks = 0;
		$this->total_marks=0;
        $this->marksheetDate = ($student->mode == 'REG')?$date->backlog_result_date:$date->backlog_pvt_result_date;
		$this->exam_year = trim(preg_replace('/\s+/', ' ',str_replace('Examination', "", $date->exam_session)));
        // $this->result_this_fc1 = '';
        // $this->result_this_fc2 = '';
		$this->check_grace_marks = false;
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
			
		}
		foreach ($papers_list as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
        //  $this->tot_credit_point.'ddd'.$this->tot_credit.'<br>';
		
		$this->set_result();
         echo  $this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->upload_exam_data();
		echo "<hr>";
		// var_dump($this->result_array);
		
		// $this->echo_result_grade(); 
		
		//  $this->agpa = $this->tot_credit_point/$this->tot_credit;
		//  $this->set_result();
		// $this->total_grade();
		
		// return $this->result();
	}

	public function _row()
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
                $this->foundation_paper[$this->paper['group_paper_name']]['paper_order'] = $this->paper['paper_order'];
                $this->foundation_paper[$this->paper['group_paper_name']]['sub_group'] = $this->paper['sub_group_id'];
                $this->foundation_paper[$this->paper['group_paper_name']]['group'] = $this->paper['group_id'];
				$this->foundation_paper[$this->paper['group_paper_name']]['type'] = $this->paper['type'];
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] += $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] += $this->paper['credit_point'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] += $this->paper['max_theory_marks'];
				$this->_echo_row_foudation($this->paper['group_paper_name']);
			}else{
				if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
				if($this->paper['theory_marks'] === 'ABS'){
					$this->foundation_paper[$this->paper['group_paper_name']]['obt'] = 'ABS';
				}
                $this->foundation_paper[$this->paper['group_paper_name']]['paper_order'] = $this->paper['paper_order'];
                $this->foundation_paper[$this->paper['group_paper_name']]['sub_group'] = $this->paper['sub_group_id'];
                $this->foundation_paper[$this->paper['group_paper_name']]['group'] = $this->paper['group_id'];
				$this->foundation_paper[$this->paper['group_paper_name']]['type'] = $this->paper['type'];
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] = $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] = $this->paper['max_theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['paper_code'] = $this->paper['paper_code'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] = $this->paper['credit_point'];
				// $paper_name_post_fix = ($this->paper['group_paper_name']=='FC1') ? 'I' : 'II';
				$this->foundation_paper[$this->paper['group_paper_name']]['paper_name'] = 'A) '.$this->paper['paper_name'].' ';
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
		
         if ($this->paper['p_marks']=='' || $this->paper['p_marks']=='N'){
            $this->withheld = true;
        }
        if($this->paper['int_marks']=='N'&& $this->mode != 'PVT' && $this->paper['max_internal_marks'] !=0 && $this->classData->practical_internal_marks == 'Y'){
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
		// echo $tot_obt_marks.'tot'.$tot_marks.'<br>';
		//  $tot_marks ;die;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade',$where);
        // echo $gradeData[0]->letter_grade.$this->result_array[$this->paper['paper_code']]["paper_name"].'<br>';
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			$this->fail_count++;
			$this->fail_obt_marks += $check_fail_marks;
			$this->fail_tot_marks += $check_fail_tot_marks;
			$this->fail_min_marks += $check_fail_min_marks;
			$this->result_array[$this->paper['paper_code']]['obt_credit'] = 0;
		}else{
			$this->obt_tot_credit += $this->paper['credit_point'];
			 $this->result_array[$this->paper['paper_code']]['obt_credit'] = $this->paper['credit_point'];
		}

        
        $this->result_array[$this->paper['paper_code']]["status"] = ($this->paper["status"] == "C")?"C":"";
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

	private function paper_name_foudation($sub_group_id){
		$data = '['.$this->paper["group_paper_name"].']'.$this->foundation_paper[$sub_group_id]["paper_name"].'<br><br>'.'B) '.$this->paper["paper_name"];
		$this->result_array[$this->paper['paper_code']]['paper_name'] = $data;
        $this->result_array[$this->paper['paper_code']]['paper_order'] = $this->foundation_paper[$sub_group_id]["paper_order"];
		$this->result_array[$this->paper['paper_code']]['type'] = $this->foundation_paper[$sub_group_id]["type"];
        $this->result_array[$this->paper['paper_code']]['sub_group'] = $this->foundation_paper[$sub_group_id]['sub_group'];
        $this->result_array[$this->paper['paper_code']]['group'] = $this->foundation_paper[$sub_group_id]['group'];
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
        foreach ($this->result_array  as $key => $result) {
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
      $this->agpa = $this->tot_credit_point/$this->tot_credit;
        
		if ($this->withheld==true) {
			$this->result = 'WITHHELD';
		}
		else if ($this->fail_count!=0 && $this->agpa>=4) {
			
				$this->result = 'FAIL';
			
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
			// $this->result_array[$this->paper['paper_code']]['max_marks'] = $this->paper['private_max_theory_marks'];
            $this->result_array[$this->paper['paper_code']]['max_marks'] = $this->paper['max_theory_marks']+$this->paper['max_internal_marks'];
			if ($this->paper['type']=='theory') {
				// $this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['private_min_theory_marks'];
                $this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks']+$this->paper['min_internal_marks'];
				$this->result_array[$this->paper['paper_code']]['obt_marks']= $this->paper['theory_marks'];
			}else{
				// $this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks'];
                $this->result_array[$this->paper['paper_code']]['min_marks'] = $this->paper['min_theory_marks']+$this->paper['min_internal_marks'];
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
        $this->result_array[$this->paper['paper_code']]['credit_upload'] = $this->paper['credit_point'];
	}

	public function foudation_min_max_no($sub_group_id)
	{
       
		if($this->mode=='PVT'){
			$this->result_array[$this->paper['paper_code']]['max_marks'] = $this->foundation_paper[$sub_group_id]['max_theory_marks'];
			$this->result_array[$this->paper['paper_code']]['min_marks'] = 35;
			$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->foundation_paper[$sub_group_id]['tot_marks'];
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

    protected function upload_exam_data()
	{
        // echo $this->result;die;
		if($this->result=='WITHHELD'){
			// return false;
			$result = 'FAIL';
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
            'roll_no' => $this->student->roll_no,
            'name' => $this->student->name,
            'agpa_sgpa'=>number_format((float)$this->agpa, 2, '.', ''),
            'exam_year' => $this->exam_year,
            'marks_pattern' => 'GRADE',
            'f_h_name' => $this->student->f_h_name,
            'mother_name' => $this->student->mother_name,
            'marksheet_no' =>$this->student->back_marksheet_no,
            'marksheet_date'=>($this->marksheetDate == "")?"":DateTime::createFromFormat('d/m/Y', $this->marksheetDate)->format('Y-m-d'),
            'university_mode'=>$this->student->mode,
            'photo'=>$this->student->photo,
            'total_marks'=>$this->total_marks,
            'obtain_marks'=>$this->obt_marks,
            'percentage' => $percentage,
            'update_date'=>date('Y-m-d'),
            'exam_status'=>'B',
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
           
        //    echo $x; 
       
        if($result['sub_group'] != 1){
            // echo '<pre>';
            $status = ($result['status'] == 'C')?'C':'';
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
                 'credit_point'=>$result['credit_upload'],
                 'carry_theory'=>$status,
                 'carry_int'=>'C',
                 'status'=>'B',
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
				// if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && ($result['letter_grade']=='F' || $result['letter_grade']=='ABS')) {
				// 	$ResultData['theory_marks'] = $result['obt_marks'];
				// 	// $oldreResultDataultdata['result'] = 'PASS BY GRACE';
                //     $result_this =  'PASS BY GRACE';
                //     $ResultData['result'] = $result_this;
				// 	// $oldreultdata['credit'] = $result['credit'];
				// 	$this->check_grace_marks = true;
				// }else{
					if ($result['letter_grade']=='F' || $result['letter_grade']=='ABS') {
						$result_this = 'FAIL';
					}else{
						$result_this = 'PASS';
					}
					$ResultData['theory_marks'] = $result['obt_marks'];
					$ResultData['result'] = $result_this;
				// }

				if ($this->student->mode=='REG') {
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
                if ($this->student->mode=='REG' && $this->classData->practical_internal_marks == 'Y') {
					$ResultData['max_int_marks'] = $result['int_max_marks'];
					$ResultData['min_int_marks'] = $result['int_min_marks'];
					$ResultData['int_marks'] = $result['int_obt_marks'];
				}
				$ResultData['max_p_marks'] = $result['max_marks'];
				$ResultData['min_p_marks'] = $result['min_marks'];
				$ResultData['p_marks'] = $result['obt_marks'];
				$ResultData['result'] = $result_this;
			}
            // echo '<pre>';
            // print_r($ResultData);
			 $this->Common_model->insertAll('old_result_data',$ResultData);
			 echo $this->db->last_query().'<br>';
			// var_dump($oldreultdata);
            // echo '<pre>';
            // print_r($ResultData);
		}else{

           
             $pap = explode(']',$result['paper_name']);
          
                if ($result['letter_grade']=='F' || $result['letter_grade']=='ABS') {
                    $result_this = 'FAIL';
                }else{
                    $result_this = 'PASS';
                }
        
           if($x === 0){
            $this->result_this_fc1 = $result_this;
           }else{
            $this->result_this_fc2 = $result_this;
           }
            
             if($x == 1){
               
            $papers = $this->Common_model->get_all_backlog_papers($this->student->student_id,$this->student->class_id,$this->student->id);
          
           
        foreach($papers as $paper){
           
			if($paper['sub_group_id'] == 1){
                $status = ($paper['status'] == 'C')?"C":"";
				$ResultData1 = array(
					'exam_data_id' =>  $old_exam_data_id ,
					'student_id' =>  $this->student->student_id ,
					'course_group_id' => $this->student->course_group_id ,
					'class_id' =>  $this->student->class_id ,
					'paper_code'=> $paper['paper_code'] ,
					'type'=> $paper['type'] ,
					'sub_group_id'=>$paper['sub_group_id'] ,
					'group_id'=>$paper['group_id'] ,
                    'credit_point'=>2,
					'max_theory_marks'=> $paper['max_theory_marks'],
					'max_int_marks'=> $paper['max_int_marks'],
					'min_theory_marks'=> $paper['min_theory_marks'],
					'min_int_marks'=> $paper['min_int_marks'],
					'theory_marks'=> $paper['theory_marks'],
					'p_marks'=> $paper['p_marks'],
					'int_marks'=> $paper['int_marks'],
					'paper_name'=>  $paper['paper_name'],
                    'carry_theory'=>$status,
                    'status'=>'B',
					// 'result' => $result ,
					'p_order'=> $paper['paper_order'] 
				);
				// echo $paper['group_paper_name'];
				if($paper['group_paper_name'] == 'FC1'){
				
					$ResultData1['result'] =  $this->result_this_fc1;
				}else{
				
					$ResultData1['result'] =  $this->result_this_fc2;
				}
                // echo '<pre>';
                // print_r($ResultData1);
				  $this->Common_model->insertAll('old_result_data',$ResultData1);
				 echo $this->db->last_query().'<br>';
        	}
	}
         }
        }
        $x++;
        
    }
     $this->update_old_exam_result($old_exam_data_id);	
	}
    public function update_old_exam_result($old_exam_data_id)
	{
		
        $studentData = array('upload_result'=>'Y');
        $this->Common_model->updateRecordByConditions('backlog_student',array('student_id'=>$this->student->student_id),$studentData);    
         	echo $this->db->last_query().'<br>';      
    
	
	}
	
}
