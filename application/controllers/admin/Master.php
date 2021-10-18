<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Account_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='master'){
			redirect(base_url('admin/logout'));
		}
	}

	public function index(){
		$admin_id = $this->session->admin_id;
		$where = 'admin_id='.$admin_id.' and status="Y"';
		$menu = array(
			"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
			"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
		);
		$this->load->view('header',array('title' => 'Master Admin'));
		$this->load->view('admin/enrollment/dashboard',$menu);
		$this->load->view('footer');
	}

	public function consolidate_report(){
		$dt = array();
		$dt['title'] = "Student Consolidate Report";
		$this->load->view('header',$dt);
		$this->db->order_by('id', 'Desc');
		$dt['sessions'] = $this->db->get_where('session', array())->result_array();
		$this->load->view('admin/master/consolidate_report',$dt);
		$this->load->view('footer');
	}

	public function get_student_consolidate_data()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
			$course_group_id  = $this->input->post("course_group_id");
			$class_id  = $this->input->post("class_id");
			$approved 		  = $this->input->post("approved");
			$payment 		  = $this->input->post("payment");
			$enrolled 		  = $this->input->post("enrolled");
			$document_upload  = $this->input->post("document_upload");
			$filter  		  = $this->input->post("filter");
			$form_status  	  = $this->input->post("form_status");
			$program_fees  	  = $this->input->post("program_fees");
			$session 		  = $this->input->post("session");

			if($course_group_id != "all"){	 

				$dt['course_group_id'] = $course_group_id;
			}
			if($session != "All"){	 

				$dt['session'] = $session;
			}else{
				$dt['name!='] = '';
			}
			if($class_id != ""){	 

				$dt['class_id'] = $class_id;
			}
			if($program_fees != "all"){	

				$dt['program_fees'] = $program_fees;
			}

			if($form_status != "all"){

				$dt['form_status'] = $form_status;
			}
			if($approved != "all"){

				$dt['approved'] = $approved;
			}
			if($payment != "all"){

				$dt['payment_status'] = $payment;
			}
			if($enrolled != "all"){

				$dt['enrolled'] = $enrolled;
			}
			if($document_upload != "all"){

				$dt['document_uploaded'] = $document_upload;
			}
			if($filter == "list"){

				$data['students'] = $this->Common_model->student_data_consolidate($dt);
			}
			if($filter == "count"){

				$data['course_count'] = $this->Common_model->student_data_consolidate($dt,'course_group_id');

			}
			$dt = $this->load->view('admin/master/getStudentConsolidate',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		}
	}

	public function show_form($student_id){
		$data = array();
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->load->view('header',array('title' => 'Admission Form'));	
		$this->load->view('template/form',$data);
		$this->load->view('footer');
	}

/*
	1. js in edit_student js file 
	2. update code in  admin/updatestudentdata 
	3. copied from admission files
*/

	public function edit_student($student_id){		
		$wherestudent = 'student_id='.$student_id;
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		$courseData = $this->Common_model->getRecordById('course_group','id',$student[0]['course_group_id']);
		$titleData = array('title' => 'Admission Form'); 
		$category_list = $this->Common_model->getDistinct('course_group','category');
		$documentData = $this->Common_model->get_record('document_category','*','document_id='.$courseData->document_id);
		$state_list = $this->Common_model->get_record('state','*');
		$district_list = $this->Common_model->get_record('distt','*');
		$course_group_list = $this->Common_model->get_record('course_group','*','category="'.$student[0]['course_category'].'"');
		$classes = $this->Common_model->get_record('class_master','*','course_group_id='.$student[0]['course_group_id']);
		$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student[0]['class_id'].' and ce="compulsory"');
		$docLength = $this->Common_model->getCountByWhere('document_category','document_id ='.$courseData->document_id.' and status="Y"');
		$data = array(
			'course_group_list' => $course_group_list,
			'category_list' => $category_list,
			'documentData' => $documentData,
			'state_list' => $state_list,
			'district_list' => $district_list,
			'classes' => $classes,
			'compulsoryPapers' => $compulsoryPapers,
			'docLength' => $docLength,
			'courseData' => $courseData,
			'student' => $student[0],
		);
		$studentdata = $this->Common_model->get_record('student_data','*',$wherestudent);
		$data['studentdata'] = $studentdata[0];
		$data['courseData'] = $courseData;
		$exam_papers= array();
		if($class[0]['class_group']=='Y'){
			$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id where class_id='.$class[0]['id'])->result();
			$data['groupPaper'] = $groupPaper;
		}
		if($student[0]['temp_exam_form']=='Y'){
			$exam_papers = $this->Common_model->get_record('new_exam_form','paper_id,paper_code',$wherestudent);
			$data['exam_papers'] = $exam_papers;
		}
		$count = $this->Common_model->getCountByWhere('student_data',$wherestudent);
		if($class[0]['class_group']=='Y'){
			$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id where class_id='.$class[0]['id'])->result();
			$data['groupPaper'] = $groupPaper; 
		}
		$this->load->view('header',$titleData);
		$this->load->view('admin/master/edit_student',$data);
		$this->load->view('footer');
	}

	public function getClassAndEligibilityByCourse(){
		$course = $this->input->post('course');
		$courseData = $this->Common_model->getRecordById('course_group','id',$course);
		$class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$course."'");
		$data = array(
			'class_list' => $class_list,
		);
		$class = $this->load->view('template/getclass',$data,true);
		$eligibility = '<label>Eligibility</label><input type="text" readonly class="form-control form-control-solid form-control-lg" name="eligibility" placeholder="eligibility" value="'.$courseData->eligibility.'" >';
		$data = array(
			"class_id" => $class,
			"eligibility" => $eligibility,
		);
		echo json_encode($data);
	}
	

	public function search_student(){
		$data = array();
		
		$this->load->view('header',array('title' => 'Search Students'));	
		$this->load->view('admin/master/search_student',$data);
		$this->load->view('footer');
	}

	//ajax-----------
	public function getStudentData()
	{
		$text_val =$this->input->post('text_val');
		$radio_val = $this->input->post('radio_val');

		if($text_val !='')
		{
			if($text_val !='' && $radio_val == 'enroll_num')
			{
				$where = array('enrollment_no'=>$text_val);

			}else if($text_val !='' && $radio_val == 'form_num')
			{
				$where = array('student.student_id'=>$text_val);

			}else if($text_val !='' && $radio_val == 'roll_num')
			{
				$where = array('name'=>$text_val);

			}else if($text_val !='' && $radio_val == 'student_name')
			{
				$where =  $this->db->like('student.name',$text_val);
			}
			$data['students'] = $this->Common_model->student_data($where);
			$dt =  $this->load->view('admin/master/getStudentConsolidate',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));

     }// if $text_val !=''
	}//fun

	public function center_payment()
	{
		$titleData = array('title' => 'center Payment', );
		$this->load->view('header',$titleData);
		$this->load->view('admin/master/center_payment');
		$this->load->view('footer');
	}

	public function getcenterPayment()
	{
		$data = $row = array();

			$where = '';

			$column_order = array(null,'center_id','type','payment_date','amount','transection_num','bank','remark','enter_date','receipt_number','receipt_date');
			$column_search = array('center_id','type','payment_date','amount','transection_num','bank','remark','enter_date','receipt_number','receipt_date');
			
			$DataTableArray = array(
				'column_order' => $column_order,
				'column_search' => $column_search,
				'where' => $where,
				'table' => 'center_payment',
			);
			
			$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
			
			$i = $_POST['start'];
			foreach($tableData as $result){

				

				$data_amount = $this->Common_model->getStudentProgramFeeByClass($result->course_group_id,$result->class_id,$result->gender);

				if($result->installment_permission == 'Y'){
					$data_amount = $data_amount/2;
				}

				$btn = '<a href="#" class="btn btn-primary btn-sm font-weight-bold student" data-toggle="modal" data-target="#kt_datepicker_modal" data-id="'.$result->student_id.'" data-name="'.$result->name.'" data-amount="'.$data_amount.'">Receive</a>';
				
				$sts = $result->installment_permission;
				
				$i++;

				$data[] = array($i, $result->center_code, $result->type,$this->Common_model->viewDate($result->payment_date), $result->amount, $result->transection_num,$result->bank,$result->remark,$result->enter_date,$result->receipt_number,$result->receipt_date);
			}
			
			$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Datatable_join_model->countAll('student',$where),
            "recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
            "data" => $data,
			);
			
			// Output to JSON format
			echo json_encode($output);
	}

	public function addcenterPayment()
	{
		$data = array(
			'center_codes' => $this->Common_model->get_record('center','center_code')
		);
		$this->load->view('admin/master/add_center_payment',$data);
	}

	public function insertcenterPayment()
	{
		 $receipt_date = ($this->input->post('receipt_date')!='') ? $this->Common_model->DB_Date($this->input->post('receipt_date')) : '';
		 $wherecenter = 'center_code = "'.$this->input->post('center_code').'"';
		 $centerData = $this->Common_model->getRecordByWhere('center',$wherecenter);
		 $amount =trim($this->input->post('amount'));
		$data = array(
			'center_id' => $centerData[0]->id,
			'center_code' => trim($this->input->post('center_code')),
			'type' => trim($this->input->post('type')),
			'payment_date' => $this->Common_model->DB_Date($this->input->post('payment_date')),
			'amount' => $amount,
			'remark' => trim($this->input->post('remark')),
			'transection_num' => trim($this->input->post('txnid')),
			'bank' => trim($this->input->post('bank')),
			'receipt_number' => trim($this->input->post('receipt_number')),
			'receipt_date' => $receipt_date,
		);
		$this->Common_model->insertAll('center_payment',$data);
		$update = array('balance'=> $centerData[0]->balance+$amount);
		$this->Common_model->updateRecordByConditions('center',$wherecenter,$update);
		$response = array('status' => 'success',
					'notification' => 'Payment Added successfully'
				);
		echo json_encode($response);
	}

	public function centers()
	{
		$data = array();
				
				$data['title'] = "center";
				$this->load->view('header',$data);
				$data['center'] = $this->Common_model->get_record('center','');
				$this->load->view('admin/center',$data);
				$this->load->view('footer');
	}
}