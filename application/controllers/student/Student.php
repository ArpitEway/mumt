<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
		$this->master = $this->Common_model->getSingleRow('master');
		 $this->exam_table = $this->master->student_exam_table;
		 $this->exam_form = $this->master->exam_form_col;
		 $this->exam_form_result = $this->master->exam_form_col_result;
		 $this->roll_no = $this->master->roll_number_col;
		 $this->result_table = $this->master->student_result_table;
		 $this->old_result_table = $this->master->old_student_result_table;
		 $this->exam_form_table = $this->master->exam_form_table;
		 $this->old_exam_form_table = $this->master->old_exam_form_table;
	}
	
	public function index(){
		if($this->session->has_userdata('studentdata')){
			redirect(base_url('dashboard'));
		}else{
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('students/login',$csrf);
		}
	}

	public function dashboard(){
		if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url(''));
		}else{
		    $titleData = array('title' => 'Student Dashboard','page_slug' => ''); 
			$this->load->view('students/header',$titleData);
			$id =  $this->session->student_id;
			$student = $this->Common_model->getRecordById('student','student_id',$id);
			
			$data = array('studentData' => $student);
			//$this->getNotification();
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->load->view('students/dashboard',$data);
			$this->load->view('students/footer');
		}	
	}
	
	public function login(){

		$csrf = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('students/login',$csrf);
	}

	public function loginSub(){
		
		 if($this->session->has_userdata('studentdata')){
		 	redirect(base_url('dashboard'));
			 exit;
		  }

		$this->form_validation->set_rules('enrollment_no', 'Enrollment_no', 'required');
		$this->form_validation->set_rules('dob', 'dob', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
				$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
			);
				$this->load->view('students/login',$csrf);
		}
		else
		{ 

		
			$username = $_POST['enrollment_no'];
			$password = $_POST['dob'];
			
			$check_user = $this->Student_model->checkStudent($username,$password);
			//print_r($check_user); die;
			if($check_user){
				
				$data = array(
							'loged_in' 	  => true,
							'studentdata' => $check_user->enrollment_no,
							'dob' 	  	  => $check_user->dob,
							'student_id'  => $check_user->student_id,
							'admission_by' =>$check_user->admission_by
							//'Users_id'  => $check_user->user_id
						);
				
				$this->session->set_userdata($data);
		
			// $this->Student_model->checkStudentForm();
			redirect(base_url('dashboard'));
			}else{

			$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
		$this->session->set_flashdata('error','Enrollment no or Password are incorrect');
		
		$this->load->view('students/login',	$csrf );
		
		}
	}
}

	public function logout()
	{
		$this->session->student_id;
		$student= 	$this->Common_model->getRecordById('student','student_id',$this->session->student_id);

		$this->session->sess_destroy();
		 if($student->admission_by=='center'){
			redirect(base_url('login'));
			 exit;
		 }else{
			redirect('https://mmyvvonline.com/signin.php');
			 exit;	
		 }
	}

	public function profile(){
	if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url('students/login'));
		}
		$student_id = $this->session->student_id;
		$data = array();
		$data['student'] = $this->Common_model->student_info($student_id);
		
		$this->load->view('students/header',array('title' => 'Student Form','page_slug' => 'profile'));	
		$this->load->view('template/form',$data);
		$this->load->view('students/footer');
	}
	

	private function getNotification(){
		$student_id = $this->session->student_id;
		if($student_id!=''){
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);
			$studentdata = array('student' => $student);
			//$this->load->view('users/header');
			$this->load->view('students/notification',$studentdata);
			//$this->load->view('users/footer');	
		}
	}

	public function exam_paper(){
	
		if(!$this->session->has_userdata('studentdata')){
			redirect(base_url('students/login'));
	   }
	   $data = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
	);
	    $student_id = $this->session->student_id;
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->db->select('paper_master.*');
    	$this->db->from('paper_master');
    	$this->db->join('new_exam_form', 'paper_master.id = new_exam_form.paper_id');
    	
    	$class_id = $data['student']['class_id'];

    	$where = array('paper_master.class_id' =>$data['student']['class_id'],
    		'student_id' => $student_id ,'paper_type'=>'theory'
    	);
    	$this->db->where($where); 
    	$data['papers'] = $this->db->get()->result();
		$whereClass = array('class_id' => $class_id,
					'exam_permission' => 'Y',
		);
		$timeTableData = $this->Common_model->getRecordByWhere('time_table',$whereClass);
		if((count($timeTableData)==0) || ($data['student']['new_exam_form']!='Y')){
			redirect(base_url());
		}
	    $this->load->view('students/header',array('title' => 'Exam Paper','page_slug' => 'exam_paper'));	
		$this->load->view('students/exam_paper',$data);
		$this->load->view('students/footer');
	}

	public function upload_anwser_sheet($paper_id){
		$paper_id = $this->Common_model->encrypt_decrypt($paper_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$data['paperData'] = $this->Common_model->getRecordById('paper_master','id',$paper_id);
		$student_id = $this->session->student_id;
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->load->view('students/header',array('title' => 'Upload Answer Sheet'));	
		$this->load->view('students/upload_answer_sheet',$data);
		$this->load->view('students/footer');
	}

	public function upload_assignment_sub(){
		if($_FILES['file']['name']!='')
		{
			$ext1=strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
			$fname=$_POST['student_id']."_".$_POST['paper_code'];
			$document_image = $fname.".".$ext1;
			$date = date('Y-m-d');
			if (!is_dir('assets/exam_answersheet/'.$date)) {
				mkdir('assets/exam_answersheet/'.$date, 0777, TRUE);
			}
			$upload_file = move_uploaded_file($_FILES['file']['tmp_name'],"assets/exam_answersheet/".$date.'/'.$document_image);

			if($upload_file){
				$data = array('student_id' =>$_POST['student_id'],
					'course_group_id' =>$_POST['course_group_id'],
					'class_id' =>$_POST['class_id'],
					'paper_code' =>$_POST['paper_code'],
					'center_id' =>$_POST['center_id'],
					'answer_sheet' =>$document_image ,
					'upload_date' =>date("Y-m-d") ,
					'exam_status' => 'R',
					'file_exist' => 'Y'
				);
				$where = array(
					'class_id' => $_POST['class_id'],
					'student_id' => $_POST['student_id'],
					'paper_code' =>$_POST['paper_code']
				);
				$ansSheetCount = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
				if($ansSheetCount>0){
					$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
				}else{
					$insert = $this->Common_model->insertAll('upload_exam_ans_sheet',$data);
				}
			}
		}
	}

	public function delete_exam_answersheet($anssheet_id)
	{
		$where = array('id' =>$anssheet_id);
		$ansdata =	$this->Common_model->getRecordById('upload_exam_ans_sheet','id',$anssheet_id);
		$data = array('answer_sheet' => '',
						'upload_date' => '',
						'file_exist' => 'N',
						'old_upload_date' => $ansdata->upload_date,
					);
		$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
		redirect(base_url('exam_paper'));
	}

    public function student_model_paper(){
		if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url('students/login'));
		}
		$this->load->view('students/header',array('title'=>'Model Paper', 'page_slug' => 'student_model_paper'));
		$student_id = $this->session->student_id;
		$student = $this->Common_model->student_info($student_id);
		$this->db->select('*');
		$this->db->from('paper_master');
		$this->db->where('student_id',$student_id);
		$this->db->where('new_exam_form.class_id',$student['class_id']);
		$this->db->where('new_exam_form.course_group_id',$student['course_group_id']);
		$this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
		$this->db->where('paper_master.exam_date>=',date('Y-m-d'));
		$data['students'] = $this->db->get()->result();
		$this->load->view('students/student_model_paper',$data);
		$this->load->view('students/footer');					
	}

	public function student_login($student_id){
		
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$results=   $this->Common_model->getRecordById('student','student_id',$student_id);;
		
		if($results){
			$data = array(
				'loged_in' 	  => true,
				'studentdata' => $results->enrollment_no,
				'dob' 	  	  => $results->dob,
				'student_id'  => $student_id,
				'admission_by' =>$results->admission_by
				//'Users_id'  => $check_user->user_id
			);
			
			$this->session->set_userdata($data);
		}
	
		redirect(base_url('dashboard'));
	}

	public function admission_form(){
	// 	if(!$this->session->has_userdata('studentdata')){
	// 		redirect(base_url('students/login'));
	//    }
	
	   if($this->session->admission_by!="web"){
			redirect(base_url('login'));
	   }
	   $titleData = array('title' => 'Student Admission Form','page_slug' => 'admission_form'); 
			$this->load->view('students/header',$titleData);
			$id =  $this->session->student_id;
			$student = $this->Common_model->getRecordById('student','student_id',$id);
			$data = array('student' => $student);
			//$this->getNotification();
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();

			//$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
			$student_id=$this->session->student_id;
			$titleData = array('title' => 'Admission Form'); 
			$state_list = $this->Common_model->get_record('state','*');
			$eligibility_list = $this->Common_model->get_record('course_group','DISTINCT (eligibility)');
			$district_list = $this->Common_model->get_record('distt','*');
			$course_group_list = $this->Common_model->get_record('course','*');
	   		$stData= $this->db->get_where('student_data', array("student_id" => $student_id))->row();
			  
			   if($student->mother_name!=""){
				redirect(base_url('profile'));
			}
			   $data = array(
				'state_list' => $state_list,
				'district_list' => $district_list,
				'course_group_list' => $course_group_list,
				'eligibility_list' => $eligibility_list,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'student_detail' => $this->db->get_where('student', array("student_id" => $student_id))->row(),
				'student_data'  =>$this->db->get_where('student_data', array("student_id" => $student_id))->row()
			);


			$this->load->view('students/admissionForm',$data);
			$this->load->view('students/footer');

	}

	public function select_papers($student_id){
		if($this->session->admission_by!="web"){
			redirect(base_url('students/login'));
	   }
	   $student_id  =  $this->session->student_id;
	//	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData['title'] = 'Select Papers';
		$this->load->view('students/header',$titleData);
		$student = $this->Common_model->student_info($student_id);
		$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
		$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
		if($student['temp_exam_form'] == "Y"){
			$std_id = $this->Common_model->encrypt_decrypt($student_id);
			redirect(base_url('showPapers/'.$std_id.''));	
		}
		$this->db->order_by('id');
		if($student['university_mode'] != "PVT"){
		if(in_array($student['class_id'] , [268,273])) { 
			$this->db->where('paper_pattern','NEW');
			$condition = ' and group_pattern="NEW"';
		}else{
			$condition = '';
		}
		$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory" and cbcs_paper="'.$cbcs.'"');

		$groupPaper = $this->db->query('select p.*,g.group_name,m.paper_code_utd from `group` as g join group_paper as p  on g.id=p.group_id join paper_master as m on m.id=p.paper_id where g.class_id='.$student['class_id'].' and cbcs_paper="'.$cbcs.'" '.$condition.' Order by g.id,p.sub_group_id,p.id')->result();
		//echo $this->Common_model->last_query();
		}else{
			$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory" and type="theory" and cbcs_paper="'.$cbcs.'"');
			 $this->db->select('p.*,g.group_name') ;
			 $this->db->from('group_paper as p');
			 $this->db->join('group as g','g.id = p.group_id');
			 $this->db->join('paper_master','paper_master.id = p.paper_id') ;
			$this->db->where(array('g.class_id'=>$student['class_id'],'paper_master.type'=>"theory", 'cbcs_paper'=> $cbcs));
			$groupPaper =$this->db->get()->result();	
		
		}
		
		$data['compulsoryPapers'] = $compulsoryPapers;
		$data['student'] = $student;
		$data['student_id'] = $student['student_id'];
			// // CONDITION FOR GROUP PAPER
		$this->db->select('class_group,select_group,group_type');
		$this->db->from('class_master');
		$this->db->join('student', 'class_master.id = student.class_id');
		$this->db->where(array('class_master.id' => $data['student']['class_id'],
			'student_id' => $student['student_id']
		));
		$class_group = $this->db->get()->result();

		$data['class_group'] = $class_group;

		$data['groupPaper'] = $groupPaper;


		if($class_group[0]->group_type=='Paper'){
			$this->load->view('Centers/select_papers',$data);
		}
		else{
			$this->load->view('Centers/select_group',$data);
		}
		$this->load->view('students/footer');

	}

	public function showPapers($student_id){
		
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$titleData = array('title' => 'Student Papers');
		$this->load->view('students/header',$titleData);

		$where = array(
			'student_id' => $student_id,
		);
		$student = $this->Common_model->student_info($student_id);
		$data['student'] = $student;

		if($student['temp_exam_form'] == "N"){
			$std_id = $this->Common_model->encrypt_decrypt($student_id);
			redirect(base_url('select_papers/'.$std_id.''));	
		}
		$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
		$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
		$this->db->select('paper_master.*,new_exam_form.sub_group_id as sub_group');
		$this->db->from('paper_master');
		$this->db->order_by('new_exam_form.sub_group_id,paper_master.cbcs_paper,paper_order');
		$this->db->join('new_exam_form', 'paper_master.paper_code = new_exam_form.paper_code and  paper_master.class_id = new_exam_form.class_id');
		$where = array('paper_master.class_id' => $student['class_id'],
			'student_id' => $student_id,'cbcs_paper'=>$cbcs
		);
		$this->db->where($where);
		$data['papers'] = $this->db->get()->result();
		$data['name_csrf']= $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		
		// $this->Common_model->last_query();
		$this->load->view('Centers/showPapers',$data);
		$this->load->view('students/footer');
	
	}

	public function transactions()
	{
		$course_type ='REG'; 
		$id =  $this->session->student_id;
		$student = $this->Common_model->getRecordById('student','student_id',$id);
		if($student->mother_name==''){
			redirect(base_url(''));
		}
		$data = array( 
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'course_type' => $course_type,
			
		);
		
		$data['transactions']=$this->Common_model->getAllRow("online_payment_transaction", "", array(
			"student_id" => $this->session->student_id	),'id DESC');
			
		$titleData = array('title' => 'Student Transaction List','page_slug' => 'transactions');
		$this->load->view('students/header',$titleData);
		$this->load->view('students/transactions',$data);
		$this->load->view('students/footer');
	}

	public function remaining_documents($student_id){
		if($this->session->admission_by!="web"){
			redirect(base_url('students/login'));
	   }
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		if($student_id!=''){
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);
			$remark = $student->remark;
			if($remark!=''){
				$where = ' id in ( '.$remark.' ) ';
				$document = $this->Common_model->getRecordByWhere('document_category',$where);
			}else{
				// $document=array();
				$doc = array();
				$document_required = $this->Common_model->getRecordByWhere('admission_document',array('student_id'=>$student_id));
				foreach($document_required as $doc_req){
					array_push($doc,$doc_req->document_category_id);
				}
				$this->db->where_in('id',$doc);
				$document = $this->Common_model->getRecordByWhere('document_category');
				
			}
			$titleData = array('title' => 'Unapproved Document List');

			$data = array(
				'student' => $student,
				'documentData' => $document,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('students/header',$titleData);
			$this->load->view('students/remaining_documents',$data);
			$this->load->view('students/footer');
		}
	}
	public function show_fees($onlinePayTxnId)
	{
		$onlinePayTxnId = $this->Common_model->encrypt_decrypt($onlinePayTxnId,'decrypt');
		$where = 'id='.$onlinePayTxnId;
		$transaction = $this->Common_model->get_record('online_payment_transaction','*',$where);
		// if($transaction[0]['center_id']!=$this->session->center_id){
		// 	$this->session->set_flashdata('error','Details Not Found');
		// 	redirect(base_url('dashboard'));
		// }
		$wherestudent = 'student_id='.$transaction[0]['student_id'];
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		$data = array(
		'student' => $student[0],
		'transaction' => $transaction[0],
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);

		$titleData = array('title'=>'Payment Details');
		$this->load->view('students/header',$titleData);
		$this->load->view('Centers/payment_detail',$data);
		$this->load->view('students/footer');
	}

	public function student_result(){
		$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('students/search_student_result',$csrf);
	}

	public function getStudentMarksheetData(){
	
    // Input
    $roll_no   = $this->input->post('roll_no');
    $dob_raw   = $this->input->post('dob');
    $radio_val = $this->input->post('radio_val');

    // Validate basic inputs
    if (empty($roll_no) || empty($dob_raw) || empty($radio_val)) {
        echo json_encode([
            "status" => false,
            "data"   => "<p style='text-align: center;'><b>Invalid input!</b></p>"
        ]);
        return;
    }

    // Convert DOB safely (expected format: d-m-Y). If parse fails, return error.
    $dtObj = DateTime::createFromFormat('d-m-Y', $dob_raw);
    if (!$dtObj) {
        echo json_encode([
            "status" => false,
            "data"   => "<p style='text-align: center;'><b>Invalid date format!</b></p>"
        ]);
        return;
    }
    $dob = $dtObj->format('Y-m-d');

    // Common config
    $exam_session = 'June 2025';
    $class_ids = array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136,267,325);
    $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210,325);

    // Helper function (closure) to build and send "no data" / "not declared" responses
    $send_error = function($message) {
        echo json_encode([
            "status" => false,
            "data"   => "<p style='text-align: center;' id='result_msg'><b>{$message}</b></p>"
        ]);
    };

    // MAIN branch
    if ($radio_val === 'main') {
        // Build where for main student table
        $where = [
            'new_exam_form' => 'Y',
            'roll_no'       => $roll_no,
            'dob'           => $dob,
            // keep university_mode != 'PVT' logic using a where_not_in style
        ];

        // Fetch student record from $this->result_table (as original)
        // Because original used 'university_mode !='=>'PVT', use query builder to exclude PVT
        $this->db->where('new_exam_form', 'Y');
        $this->db->where('roll_no', $roll_no);
        $this->db->where('dob', $dob);
        $this->db->where('university_mode !=', 'PVT');
        $student = $this->db->get($this->result_table)->result();

        if (empty($student)) {
            echo json_encode([
                "status" => false,
                "data"   => "<p style='text-align: center;'><b>No data found!</b></p>"
            ]);
            return;
        }

        // If result not shown
        if (isset($student[0]->result_show) && $student[0]->result_show === "N") {
            $send_error('Student result not declared!');
            return;
        }

        // Prepare data
        $data = [];
        $data['student'] = $student[0];
        $data['exam_session'] = $exam_session;

        // Class data & marks form
        $classData = $this->Common_model->getRecordById('class_master','id',$data['student']->class_id);
        $data['practical_internal_marks'] = isset($classData->practical_internal_marks) ? $classData->practical_internal_marks : null;

        $this->db->select('*');
        $this->db->from($this->exam_form_table);
        $this->db->where($this->exam_form_table . '.student_id', $data['student']->student_id);
        $this->db->where($this->exam_form_table . '.class_id', $data['student']->class_id);
        $this->db->order_by($this->exam_form_table . '.paper_order,' . $this->exam_form_table . '.paper_id');
        $data['new_exam_form'] = $this->db->get()->result();

        $data['classData'] = $classData;

        // Decide which view to load
        if (in_array($data['student']->class_id, $class_ids) && $data['student']->exam_pattern == 'GRADE') {
            $this->load->model('Gradesheet_model');
            $dt = $this->load->view('Centers/grade_marksheet', $data, true);
        } elseif (in_array($data['student']->class_id, $class_cbcs) && $data['student']->university_mode == 'REG' && $data['student']->exam_pattern == 'GRADE') {
            $this->load->model('Gradesheet_model_pg');
            $this->load->model('GradeSheet_old_model_pg');
            $dt = $this->load->view('Centers/grade_marksheet_pg', $data, true);
        } else {
            // traditional marksheet (top + bottom)
            $marksheet_top = $this->load->view('Centers/marksheet_top', $data, true);

            if (isset($classData->internal) && $classData->internal === 'N') {
                $marksheet_bottom = $this->load->view('Centers/marksheet_without_int', $data, true);
            } else {
                if (isset($student[0]->class_id) && $student[0]->class_id == '168') {
                    $marksheet_bottom = $this->load->view('Centers/marksheet_mom', $data, true);
                } else {
                    $marksheet_bottom = $this->load->view('Centers/marksheet_bottom', $data, true);
                }
            }

            $dt = $marksheet_top . $marksheet_bottom;
        }

        echo json_encode([
            "status" => true,
            "data"   => $dt
        ]);
        return;
    }

    // BACKLOG branch
    if ($radio_val === 'backlog') {
        // Build query joining backlog_student and student similar to original
        $this->db->select('backlog_student.*,student.name,student.f_h_name,student.course_name,student.photo,student.session,student.exam_pattern,student.university_mode');
        $this->db->from('backlog_student');
        $this->db->join('student','backlog_student.student_id=student.student_id');
        $this->db->where('backlog_student.exam_form','Y');
        $this->db->where('backlog_student.roll_no', $roll_no);
        $this->db->where('student.dob', $dob);
        $this->db->where('student.university_mode','REG');
        $this->db->where('backlog_student.exam_year', $exam_session);
        $student = $this->db->get()->result();

        if (empty($student)) {
            echo json_encode([
                "status" => false,
                "data"   => "<p style='text-align: center;'><b>No data found!</b></p>"
            ]);
            return;
        }

        if (isset($student[0]->result_show) && $student[0]->result_show === "N") {
            $send_error('Student result not declared!');
            return;
        }

        // Prepare data
        $data = [];
        $data['student'] = $student[0];
        $data['exam_session'] = $exam_session;

        $classData = $this->Common_model->getRecordById('class_master','id',$data['student']->class_id);
        $data['practical_internal_marks'] = isset($classData->practical_internal_marks) ? $classData->practical_internal_marks : null;

        $this->db->select('*');
        $this->db->from('backlog_exam_form');
        $this->db->where('backlog_exam_form.backlog_student_id', $data['student']->id);
        $this->db->where('backlog_exam_form.student_id', $data['student']->student_id);
        $this->db->where('backlog_exam_form.class_id', $data['student']->class_id);
        $this->db->order_by('backlog_exam_form.paper_order');
        $data['backlog_exam_form'] = $this->db->get()->result();

        $data['classData'] = $classData;

        // Decide backlog view
        if (in_array($data['student']->class_id, $class_ids) && $data['student']->university_mode == 'REG') {
            $this->load->model('Gradesheet_backlog_model');
            $this->load->model('Gradesheet_model');
            $dt = $this->load->view('Centers/backlog_grade_marksheet', $data, true);
        } elseif (in_array($data['student']->class_id, $class_cbcs) && $data['student']->university_mode == 'REG' && $data['student']->exam_pattern == 'GRADE') {
            $this->load->model('Gradesheet_backlog_model_pg');
            $this->load->model('GradeSheet_old_model_pg');
            $dt = $this->load->view('Centers/backlog_grade_marksheet_pg', $data, true);
        } else {
            $marksheet_top = $this->load->view('Centers/marksheet_top_backlog', $data, true);

            if (isset($classData->internal) && $classData->internal === 'N') {
                $marksheet_bottom = $this->load->view('Centers/marksheet_without_int_backlog', $data, true);
            } else {
                if (isset($student[0]->class_id) && $student[0]->class_id == '168') {
                    $marksheet_bottom = $this->load->view('Centers/marksheet_mom_backlog', $data, true);
                } else {
                    $marksheet_bottom = $this->load->view('Centers/marksheet_bottom_backlog', $data, true);
                }
            }

            $dt = $marksheet_top . $marksheet_bottom;
        }

        echo json_encode([
            "status" => true,
            "data"   => $dt
        ]);
        return;
    }

    // If radio_val is neither main nor backlog
    echo json_encode([
        "status" => false,
        "data"   => "<p style='text-align: center;'><b>Invalid request type!</b></p>"
    ]);


	}
}