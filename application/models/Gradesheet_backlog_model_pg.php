<?php 

/**
 * 
 */
class Gradesheet_backlog_model_pg extends CI_Model
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
	protected $withheld = false;
	protected $fail_tot_marks;
	protected $fail_obt_marks;
	protected $fail_min_marks;
	protected $result_array = array();

	public function view_result($student,$student_id,$course_group_id,$class_id,$mode,$exam_id)
	{

		$this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('backlog_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id,'backlog_student_id'=>$exam_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$papers = $this->Common_model->get_all_backlog_papers($student_id,$class_id,$exam_id);
        $this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
    	$this->classCount = count($this->allclass);
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$this->foundation_paper = array();
		$this->result_array = array();
		$this->tot_credit_point = 0;
		$this->tot_credit = 0;
		$this->mode = $mode;
		$this->fail_count=0;
        $this->zero_count = 0;
        $this->theory_fail_count = 0;
		$this->obt_tot_credit=0;
		$this->fail_tot_marks = 0;
		$this->fail_min_marks = 0;
		$this->fail_obt_marks = 0;
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			
			if($this->withheld || in_array($student->exam_center_code ,array('MDE052','MDE081','MDE156') )){
				
				echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
				 '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
				  '<h3 class="text-center">'.'WH'.'</h3>'.
				'</div>';
				return $this->result();
			
				die;
			}
			$this->_row();
			
		}
		
        if($this->theory_fail_count!=0 && ($this->zero_count == $this->theory_fail_count)){
            echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
                    '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
                    '<h3 class="text-center">'.'WH'.'</h3>'.
                    '</div>';
                    return $this->result();
                
                    die;
        }
		$this->echo_result(); 
		$this->total();
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		return $this->result();
	}

	public function view_result_grade($student_id,$course_group_id,$class_id,$mode,$exam_id)
	{
		// $table = $this->Common_model->getMaster('exam_form_table');
        $this->db->order_by('sub_group_id');
		$std  = $this->Common_model->getRecordByWhere('backlog_exam_form',array('class_id'=> $class_id,'student_id'=>$student_id,'backlog_student_id'=>$exam_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$papers = $this->Common_model->get_all_backlog_papers($student_id,$class_id,$exam_id);
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
		
		$this->echo_result_grade(); 
		
		 $this->agpa = $this->tot_credit_point/$this->tot_credit;
		 $this->set_result();
		$this->total_grade();
		return $this->result();
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
                if(($this->paper['theory_marks']=='00' || $this->paper['theory_marks']=='0') && $this->paper['status'] =='B'){
                    $this->zero_count++;
                }
                if ($this->paper["status"]=='B') {
                    $this->theory_fail_count++;
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
			$this->obt_marks += $this->paper["p_marks"]+$this->paper["int_marks"];
		 $this->total_marks += $this->paper["max_theory_marks"]+  $this->paper["max_internal_marks"];
		
			if ($this->paper['p_marks']==''){
				$this->withheld = true;
			}
            if($this->paper['int_marks']=='N'&& $mode != 'PVT' && $this->paper['max_internal_marks'] !=0 && $this->classData->practical_internal_marks == 'Y'){
                // $rwas_count++;
                $this->withheld = true;
                
              }
			$check_fail_marks = $this->paper["p_marks"];
				$check_fail_min_marks = $this->paper["min_theory_marks"];
				$check_fail_tot_marks = $this->paper["max_theory_marks"];
			$tot_obt_marks = $this->paper["p_marks"]+$this->paper["int_marks"];
			$tot_marks = $this->paper["max_theory_marks"]+$this->paper["max_internal_marks"];
			$min_marks = $this->paper["min_theory_marks"]+$this->paper["min_internal_marks"];
		}
	
        $persent =$check_fail_marks*100/$check_fail_tot_marks;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade_pg',$where);
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
			// $this->result_array[$this->paper['paper_code']]['obt_credit'] = $this->paper['credit_point'];
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
	}

	private function credit_point()

	{
		$this->tot_credit_point += $this->grade_point*$this->credit_point;
		$this->result_array[$this->paper['paper_code']]['credit_point'] = $this->grade_point*$this->credit_point;
	}

	public function set_result()
	{
		if ($this->withheld==true) {
			$this->result = 'RW';
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

	
	public function echo_result()
	{
		$this->fail_count;
		foreach ($this->result_array as $key => $result) {
			
			echo "<tr>";
			echo "<th>".$key."</th>";
			echo "<th>".$result['paper_name']."</th>";
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			
				echo "<th class='text-center'>".$result['obt_credit']."</th>";
				echo "<th class='text-center'>".$result['letter_grade']."</th>";				
				echo "<th class='text-center'>".$result['grade_point']."</th>";
				echo "<th class='text-center'>".$result['credit_point']."</th>";
			echo "</tr>";
		}
	}

	public function echo_result_grade()
	{
        if($this->withheld){
				
            echo '<div class="text-center text-primary border-right border-left border-bottom border-dark py-3">'.
             '<h1 class=" text-center mb-0">'.'Statement Of Marks'.'</h1>'.
              '<h3 class="text-center">'.'WH'.'</h3>'.
            '</div>';
            return $this->result();
        
            die;
        }
		$this->fail_count;
		
        
		foreach ($this->result_array as $key => $result) {
			
			echo '<tr style="padding:4px;font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="center">';
			echo '<td style="margin-top:2px;" align="center"><strong>'.$key.'</strong></td>';
			echo "<td align='left'><strong>".$result['paper_name']."</strong></td>";
			
				if($result['obt_marks'] === 'ABS' || ($result['f_abs'] === 'ABS' && $result['obt_marks'] == '0')
			){
					$result['letter_grade'] = 'ABS';
			}
			
				echo "<td align='center' colspan='3'><span class='style4'>".$result['credit']."</span></td>";
				echo "<td align='center' colspan='3'><span class='style4'>".$result['obt_credit']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['grade_point']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['credit_point']."</span></td>";
				echo "<td align='center' colspan='2'><span class='style4'>".$result['letter_grade']."</span></td>";
			
			echo "</tr>";
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
		
		
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$agpa = ($this->result == 'FAIL')?'0.00':number_format((float)$this->agpa, 2, '.', '');
		echo '<tr  style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="middle">';
		
		
		echo '<th colspan="2" style="font-size:14px;">Total</th>';
			echo '<th colspan="3" style="font-size:14px;">'.$this->tot_credit.'</th>';
			echo '<th colspan="3" style="font-size:14px;">'.$this->obt_tot_credit.'</th>';
			echo '<th colspan="2"></th>';
			echo '<th style="font-size:14px;" colspan="3">'.$this->tot_credit_point.'</th>';
			echo '<th colspan=""></th>';
			
			
		echo '</tr>';
		echo '<tr  style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="middle">';
		echo '<th colspan="14" style="font-size:13px;" >Result - '.$this->result.'</th>';
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
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		echo '<tr>';
		echo '<td></td>';
		echo '<td class="text-right" style="padding-right: 3rem!important;">AGPA</td>';
		echo '<td colspan="2">'.number_format((float)$this->agpa, 2, '.', '').'</td>';
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
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
		$result_data = $this->student_result_data(); 
		// $this->total();
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		$returndata['paper_details'] = $result_data;
		$returndata['final_result'] = $this->result();
		return $returndata;
	}


	public function student_result_data()
	{
		$this->fail_count;
		$result_data = array();
		$key  = 0;
		foreach ($this->result_array as $result) {
			$result_data[$key] = array();
			$result_data[$key]['paper_name'] = $result['paper_name'];
		    $result_data[$key]['credit'] = ($result['letter_grade']=='F' || $result['letter_grade']=='ABS') ? 0 : $result['credit'];
			$result_data[$key]['letter_grade'] = $result['letter_grade'];		
			$key++;
		}
		return $result_data;
	}

	public function echo_result_marksheet()
	{
		$this->fail_count;
		foreach ($this->result_array as $key => $result) {
			echo "<tr>";
			echo "<td class='text-center'>".$key."</td>";
			echo "<td>".$result['paper_name']."</td>";
			echo "<td class='text-center'>".$result['credit']."</td>";
			echo "<td class='text-center'>".$result['obt_credit']."</td>";
			echo "<td class='text-center'>".$result['letter_grade']."</td>";				
			echo "<td class='text-center'>".$result['grade_point']."</td>";
			echo "<td class='text-center'>".$result['credit_point']."</td>";
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
		$this->withheld = false;
		foreach ($papers as $paper) {
			$this->paper = $paper;
			$this->_row();
		}
		
		$this->echo_result_marksheet(); 
		$this->total_marksheet();
		$this->agpa = $this->tot_credit_point/$this->tot_credit;
		$this->set_result();
		return $this->result();
	}
}
