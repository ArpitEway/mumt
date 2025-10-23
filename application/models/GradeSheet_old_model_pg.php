<?php 

/**
 * 
 */
class GradeSheet_old_model_pg extends CI_Model
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

	function __construct()
	{
		
	}

	public function view_result($student_id,$course_group_id,$class_id,$mode,$exam_data_id='')
	{
	
		$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
	
	
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

    public function view_old_results($student_id,$course_group_id,$class_id,$mode,$exam_id='')
	{
        $this->db->order_by('id','desc');
        //$this->db->limit(5);
        $old_data = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student_id,'class_id'=>$class_id));
        $papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_id);
	
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
        $this->total_grade_point =0;
        $this->check_grace_marks = false;
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
            $this->_row();
			
		}
		
		// var_dump($this->result_array);
		
		// $this->total();
        $this->check_grace_for_old();
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
		return $this->result('old');

    }
	public function view_result_grade($student_id,$course_group_id,$class_id,$mode,$exam_id='')
	{
		
		
		
		
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_id);
		
		
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
		
		
		// var_dump($this->result_array);
		
		$this->echo_result_grade(); 
		
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		 $this->set_result();
		$this->total_grade();
		
		return $this->result();
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}

	// public function _row()
	// {
		

	// 		$this->_echo_row();
	// 	}
	// }

    //DG Locker
	public function view_result_grade_for_dg_locker($student_id,$course_group_id,$class_id,$mode,$exam_data_id="")
	{
		
	
		$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
        // $this->Common_model->last_query();
		// echo '<pre>';
        // print_r($papers);die;
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
		$this->papercount=count($papers);
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
		
			$this->_row();
			
		}
		
		
		// var_dump($this->result_array);
		
		//$this->echo_result_grade(); 
		$this->echo_result_digi();
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		//$this->total_grade();
		
		return $this->result('y');
		// echo "<pre>";
		// print_r($this->foundation_paper);
	}

	public function check_agpa($student_id,$course_group_id,$class_id,$mode,$exam_data_id,$exam_status)
	{
		// $table = $this->Common_model->getMaster('exam_form_table');
		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$student = $this->Common_model->getRecordById('student','student_id',$student_id);
		$session = explode(' ',$student->session);
		
		
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
	
	
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
		
		$this->check_agpa_grade($exam_status); 
		
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		 $this->set_result();
		// $this->total_grade();
		
		return $this->result();
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
                    
        
           
            $this->html.= "<td>".$result['paper_name']."</td>";
			$this->html.= "<td>".$key."</td>";
			$this->html.="<td></td><td></td><td></td>";
			$this->html.= "<td></td>";//($result['type'] == 'theory')?$result['obt_marks']."</td>":"<td></td>";
			$this->html.= "<td></td>";//($result['type'] != 'theory')?$result['obt_marks']."</td>":"<td>
			$this->html.= "<td></td>";//((int)$result['obt_marks']+(int)$result['int_obt_marks'])
            if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
                $this->check_grace_marks = true;
                $this->obt_tot_credit += $result['credit'];
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
                $this->html.= "<td>".$gradeData[0]->letter_grade."-G</td>";
				$this->html.= "<td>".$gradeData[0]->grade_point."</td>";
                $this->html.= "<td>".$result['credit']."</td>";
                
                $this->html.= "<td>".$credit_point."</td>";
				$this->html.= "<td></td>";
            }else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
				){
						$result['letter_grade'] = 'ABS';
				}
                $this->html.= "<td>".$result['letter_grade']."</td>";
				$this->html.= "<td>".$result['grade_point']."</td>";	
                $this->html.= "<td>".$result['credit']."</td>";			
                
                $this->html.= "<td>".$result['credit_point']."</td>";
				$this->html.= "<td></td>";
            }
            $this->total_grade_point+=$result['grade_point'];

        }
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
		$this->result_array[$this->paper['paper_code']]["paper_name"] = $this->paper["paper_name"];
		$this->result_array[$this->paper['paper_code']]["paper_code_utd"] = $this->paper["paper_code_utd"];
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
				$check_fail_marks = $this->paper["theory_marks"] ;
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
		
		
		//  $tot_marks ;die;
		
        $persent =$check_fail_marks*100/$check_fail_tot_marks;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
		if ('F'==$gradeData[0]->letter_grade || 'ABS' ==$gradeData[0]->letter_grade) {
			array_push($this->paper_array, ($this->classData->id==267)?$this->paper["paper_code_utd"]:$this->paper["paper_code"]);
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



	

	public function set_result()
	{
		// var_dump($this->withheld);die;
		if ($this->withheld==true) {
			$this->result = 'RW';
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
				'equivalent' => ($this->agpa*10)
			);

            if($forDG == "old"){
                $data['total_grade_point']=$this->total_grade_point;
            }else if(!empty($forDG)){
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

	
	public function echo_result()
	{
		$this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		foreach ($this->result_array as $key => $result) {
			
			echo "<tr>";
			if($this->classData->id==267){
				echo "<th>".$result['paper_code_utd']."</th>";
			}else{
			echo "<th>".$key."</th>";
			}
			echo "<th>".$result['paper_name']."</th>";
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
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
				echo "<th class='text-center'>".$result['credit']."</th>";
				echo "<th class='text-center'>".$gradeData[0]->letter_grade."-G</th>";
				echo "<th class='text-center'>".$gradeData[0]->grade_point."</th>";
				echo "<th class='text-center'>".$credit_point."</th>";
			}else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			
				echo "<th class='text-center'>".$result['credit']."</th>";
				echo "<th class='text-center'>".$result['letter_grade']."</th>";				
				echo "<th class='text-center'>".$result['grade_point']."</th>";
				echo "<th class='text-center'>".$result['credit_point']."</th>";
			}
			echo "</tr>";
		}
	}

	public function echo_result_grade($back ="")
	{
		$this->fail_count;
		if ($this->fail_count>0 && $back == "") {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		foreach ($this->result_array as $key => $result) {
			
			
			
			echo '<tr style="padding:4px;font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="center">';
			if($this->classData->id==267){
				echo '<td style="margin-top:2px;" align="center"><strong>'.$result['paper_code_utd'].'</strong></td>';
			}else{
				echo '<td style="margin-top:2px;" align="center"><strong>'.$key.'</strong></td>';
			}
			echo "<td align='left'><strong>".$result['paper_name']."</strong></td>";
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory' && $back =="") {
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
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
				echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
				echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['grade_point']."</span></td>";
				echo "<td align='center' colspan='2''><span class='style4'>".$credit_point."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$gradeData[0]->letter_grade.'-G'."</span></td>";
				
			}else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
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

    public function check_grace_for_old(){
        $this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
        foreach ($this->result_array as $key => $result) {

            if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory') {
				
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
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
            }
        }
    }

	public function check_agpa_grade($exam_status){
		$this->fail_count;
		if ($this->fail_count>0) {
			 $require_grace_marks = $this->fail_min_marks-$this->fail_obt_marks;
		}
		
		foreach ($this->result_array as $key => $result) {
			
		
			if ($this->fail_count>0 && $this->fail_count<2 && $require_grace_marks<4 && $result['letter_grade']=='F' && $result['type'] == 'theory' && $exam_status == 'R') {
				$this->check_grace_marks = true;
					$this->obt_tot_credit += $result['credit'];
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
			}else{
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			
			}
		
		}
	}

	private function total()
	{
		echo '<tr>';
			echo '<td></td>';
			echo '<td class="text-right font-weight-bold" style="padding-right: 3rem!important;">Total</td>';
			echo '<td class="text-center font-weight-bold">'.$this->tot_credit.'</td>';
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
		$result = ($this->result == 'FAIL' && $this->agpa>=4 )?'ATKT IN '. $papers: $this->result;
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
			
		}
		return $this->result();
		
	}

    public function view_result_grade_backlog($student_id,$course_group_id,$class_id,$mode,$exam_data_id)
	{
		
		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('old_result_data',array('class_id'=> $class_id,'student_id'=>$student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
	
		
			$papers = $this->Common_model->get_all_old_papers($student_id,$class_id,$exam_data_id);
		
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
		
	
		$this->echo_result_grade('backlog'); 
		
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		 $this->set_result();
		$this->total_grade();
		
		return $this->result();
		
	}
}
// echo $this->fail_count;die;
// 			if ($this->fail_count>0 && !$this->check_grace_marks && $student_id!=684208 && $this->classData->final_result_permission!='Y' ) {  
// 				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
// 				'<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
// 				 '<h3 class="text-center">'.'WH'.'</h3>'.
// 			   '</div>';
// 			   return $this->result();
		   
// 			   die;
// 			}else{
// 			}