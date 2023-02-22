<?php 

/**
 * 
 */
class Gradesheet_model extends CI_Model
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
		$papers = $this->Common_model->get_all_papers($student_id,$class_id);
		// print_r($papers);die;
		$this->allclass = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_group_id));
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

	public function _row()
	{
		// print_r($this->foundation_paper[$this->paper['sub_group_id']]);die;
		if($this->paper['sub_group_id']=='1'){
			if (isset($this->foundation_paper[$this->paper['group_paper_name']])) {
				if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] += $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] += $this->paper['credit_point'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] += $this->paper['max_theory_marks'];
				$this->_echo_row_foudation($this->paper['group_paper_name']);
			}else{
				if ($this->paper['theory_marks']=='') {
					$this->withheld = true;
				}
				$this->foundation_paper[$this->paper['group_paper_name']]['tot_marks'] = $this->paper['theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['max_theory_marks'] = $this->paper['max_theory_marks'];
				$this->foundation_paper[$this->paper['group_paper_name']]['paper_code'] = $this->paper['paper_code'];
				$this->foundation_paper[$this->paper['group_paper_name']]['credit_point'] = $this->paper['credit_point'];
				// $paper_name_post_fix = ($this->paper['group_paper_name']=='FC1') ? 'I' : 'II';
				$this->foundation_paper[$this->paper['group_paper_name']]['paper_name'] = ' 1)'.$this->paper['paper_name'].' ';
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
		$this->result_array[$this->paper['paper_code']]["paper_name"] ='['. $this->paper["group_paper_name"].'] '.$this->paper["paper_name"];
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
			if ($this->paper['p_marks']==''){
				$this->withheld = true;
			}
			$tot_obt_marks = $this->paper["p_marks"];
			$tot_marks = $this->paper["max_theory_marks"];
			$min_marks = $this->paper["min_theory_marks"];
		}
		$persent = $tot_obt_marks*100/$tot_marks;
		//  $tot_marks ;die;
		$where = 'min_marks <= '.$persent.' and  max_marks >= '.$persent.'';
		$gradeData = $this->Common_model->getRecordByWhere('letter_grade',$where);
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
		$data = '['.$this->paper["group_paper_name"].'] '.$this->foundation_paper[$sub_group_id]["paper_name"].' 2)'.$this->paper["paper_name"];
		// print_r($this->paper["paper_name"]);
		$this->result_array[$this->paper['paper_code']]['paper_name'] = $data;
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
		if ($this->withheld==true) {
			$this->result = 'WITHHELD';
		}
		if ($this->fail_count!=0 && $this->obt_tot_credit>=20) {
			if ($this->check_grace_marks) {
				$this->result = 'PASS BY GRACE';
			}else{
				$this->result = 'SUPPLEMENTARY';
			}
		}else if($this->agpa<4){
			$this->result = 'FAIL';
		}else{
			$this->result = 'PASS';
		}
	}

	public function result()
	{
		return $data = array(
				'tot_credit' => $this->tot_credit,
				'obt_credit' => $this->obt_tot_credit,
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
				$this->result_array[$this->paper['paper_code']]['int_max_marks'] = '-';
				$this->result_array[$this->paper['paper_code']]['int_min_marks'] = '-';
				$this->result_array[$this->paper['paper_code']]['obt_marks'] = $this->paper['p_marks'];
				$this->result_array[$this->paper['paper_code']]['int_obt_marks'] = '-';
			}
		}
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
			echo "<th>".$key."</th>";
			echo "<th>".$result['paper_name']."</th>";
			if ($this->fail_count>0 && $require_grace_marks<4 && $result['letter_grade']=='F') {
				$this->check_grace_marks = true;
				$this->obt_tot_credit += $result['credit'];
				$req_marks = $result['min_marks']-$result['obt_marks'];
				$obt_marks = $result['obt_marks']+$req_marks;
				$credit_point = $result['credit']*4;
				echo "<th class='text-center'>".$result['credit']."</th>";
				echo "<th class='text-center'>P-G</th>";
				echo "<th class='text-center'>4</th>";
				echo "<th class='text-center'>".$credit_point."</th>";
			}else{
				
				echo "<th class='text-center'>".$result['credit']."</th>";
				echo "<th class='text-center'>".$result['letter_grade']."</th>";				
				echo "<th class='text-center'>".$result['grade_point']."</th>";
				echo "<th class='text-center'>".$result['credit_point']."</th>";
			}
			echo "</tr>";
		}
	}

	private function total()
	{
		echo '<tr>';
			echo '<td></td>';
			echo '<td class="text-right font-weight-bold" style="padding-right: 3rem!important;">कुल योग</td>';
			echo '<td class="text-center font-weight-bold">'.$this->tot_credit.'</td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td class="text-center font-weight-bold">'.$this->tot_credit_point.'</td>';
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
			if ($this->fail_count>0 && $require_grace_marks<4 && $result['letter_grade']=='F') {
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
			if ($this->fail_count>0 && $require_grace_marks<4 && $result['letter_grade']=='F') {
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
}
