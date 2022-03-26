<?php 

class Common_Model extends CI_Model{

	/* Get  Data Using Id's */

	function getRecordById($table,$field_name,$id){

		 $qry= $this->db->where($field_name,$id);

		$qry= $this->db->get($table);

		return $qry->row();

	}
	
	function getMobileNoByStudentID($id){

		$qry = $this->db->select("p_mobile_no");
		
		$qry = $this->db->where("student_id",$id);
 
		$qry = $this->db->get("student_data");
		
		
		if(isset($qry->row()->p_mobile_no)){
		$mobile = $qry->row()->p_mobile_no;
		return ($mobile!='') ? $mobile : $this->getSinglefield('student_data','p_mobile_no','student_id='.$id);
		}else{
			return $this->getSinglefield('student_data','p_mobile_no','student_id='.$id);
		}

	}

	function getStudentRemarkNameById($id){

		$qry = $this->db->select("document");
		
		$qry = $this->db->where("id",$id);

		$qry = $this->db->get("document_category");
		
		$document_name = $qry->row()->document;
		
		return $document_name;
		

	}
	function getState($id){

		$qry = $this->db->select("name");
		
		$qry = $this->db->where("state_id",$id);

		$qry = $this->db->get("state");
		
		$state_name = $qry->row()->name;
		
		return $state_name;
		
	}
	function getDistrict($id){

		$qry = $this->db->select("name");
		$qry = $this->db->where("distt_id",$id);
		$qry = $this->db->get("distt");
		
		$district_name = $qry->row()->name;
		
		return $district_name;
		
	}
	function getStudentRemarkID($id){

		$qry = $this->db->select("remark");
		
		$qry = $this->db->where("student_id",$id);

		$qry = $this->db->get("student");
		
		return $qry->row()->remark;

	}
	
	function getAdminNameById($id){

		$qry = $this->db->select("name");
		
		$qry = $this->db->where("id",$id);

		$qry = $this->db->get("admin_master");
		
		return $qry->row()->name;

	}
	
	function getHeadingNameById($id){

		$qry = $this->db->select("heading");
		
		$qry = $this->db->where("id",$id);

		$qry = $this->db->get("menu_heading");
		
		return $qry->row()->heading;

	}
	function getStudentHeadingNameById($id){

		$qry = $this->db->select("heading");
		
		$qry = $this->db->where("id",$id);

		$qry = $this->db->get("student_menu_heading");
		
		return $qry->row()->heading;

	}

	function getcenterHeadingNameById($id){

		$qry = $this->db->select("heading");
		
		$qry = $this->db->where("id",$id);

		$qry = $this->db->get("center_menu_heading");
		
		return $qry->row()->heading;

	}
	
	/* Get  Data Using Id's  desc*/

	function getRecordByIdDesc($table,$field_name,$id){

		$qry= $this->db->order_by('created_date',"desc");	

		$qry= $this->db->where($field_name,$id);

		$qry= $this->db->get($table);

		return $qry->row();

	}

	/* Get Record By Where Data */

	public function getRecordByWhere($table,$where=''){
		if($where!=''){
		$qry= $this->db->where($where);
		}

		$qry= $this->db->get($table);

		return $qry->result();
	}	

	/* Get Record By Where Data */

	public function getRecordByWhereByAsc($table,$where){

		$qry= $this->db->order_by('id','ASC');

		$qry= $this->db->where($where);

		$qry= $this->db->get($table);

		return $qry->result();

	}

	/* Get Record By Where Data */

	public function getRecordByWhereByOrder($table,$where,$filed,$order){

		$qry= $this->db->order_by($filed,$order);

		$qry= $this->db->where($where);

		$qry= $this->db->get($table);

		//echo $this->db->last_query();

		return $qry->result();

	}

	

	/* Get Record By order */

	public function getRecordByOrder($table,$filed,$order){

		$qry= $this->db->order_by($filed,$order);

		$qry= $this->db->get($table);

		//echo $this->db->last_query();

		return $qry->result_array();

	}

	function student_data($where = "",$group_by = ""){

		if($group_by != ""){
				$this->db->select('count(*) as cnt,course_name');
				$this->db->group_by($group_by);
				
		}else{
			
				$this->db->select('*');
		}
		$this->db->from("student");
		$this->db->where($where);
		
		$this->db->join("student_data", "student.student_id = student_data.student_id", 'left'); 
		$query = $this->db->get();
		
		return $query->result_array();

	}

	function student_data_consolidate($where = "",$group_by = ""){
		
		if($group_by != ""){
				$this->db->select('count(*) as cnt,'.$group_by);
				$this->db->group_by($group_by);
		}else{
				$this->db->select('*');
		}
		$this->db->from("student");
		$this->db->where($where);
		$this->db->join("course_group", "student.course_group_id = course_group.id", 'left'); 
		$query = $this->db->get();
			
		return $query->result_array();

	}
	
	function student_info($id){
		
		$this->db->select('*, student.student_id as form_no');
		$this->db->from("student");
		$this->db->Where("student.student_id",$id);
		$this->db->join("student_data", "student.student_id = student_data.student_id", 'left'); 
		$query = $this->db->get();
		return $query->row_array();
		
	}
	function student_info_by_where($where = ""){
		
		$this->db->select('*');
		$this->db->from("student");
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}
	function all_student_info_by_where($where = ""){
		
		$this->db->select('*');
		$this->db->from("student");
		$this->db->where($where);
		$this->db->where("name !=","");
		$query = $this->db->get();
		//echo $this->db->last_query();
		//die;
		return $query->result_array();
	}
	
	function student_data2($where = "",$group_by = "")
	{
		if($group_by != ""){
			$this->db->select('count(*) as cnt,course_name');
			$this->db->group_by($group_by);
		}else{
			$this->db->select('*'); 
		}
			$this->db->from("student");
			$this->db->where($where);
			$query = $this->db->get();
			
			return $query->result_array();
	}
		
	function heading_data($where = "")
	{
		$this->db->select('*');
		$this->db->from("menu_heading");
		$this->db->where($where);
		$this->db->order_by("heading_order",'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function student_menu_heading_data()
	{
		$this->db->select('*');
		$this->db->from("student_menu_heading");
		$this->db->order_by("heading_order",'ASC');
		$query = $this->db->get();
			
		return $query->result_array();
	}

	function center_menu_heading_data()
	{
		$this->db->select('*');
		$this->db->from("center_menu_heading");
		$this->db->order_by("heading_order",'ASC');
		$query = $this->db->get();
			
		return $query->result_array();
	}
	
	function student_menu_data($where = '')
	{	
		$this->db->select('*');
		$this->db->from("student_menu");
	   if($where!=null)
		{
		$this->db->where($where);	
		}
		$this->db->order_by("menu_order",'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function menu_data($where = "")
	{
		$this->db->select('*');
		$this->db->from("menu");
		$this->db->where($where);
		$this->db->order_by("menu_order",'ASC');
		$query = $this->db->get();
			
		return $query->result_array();
	}
	
	/* Update Record By Where Condition */

	function updateRecordByConditions($table,$where,$data)
	{

		if($where != ""){
		$qry = $this->db->where($where);
		}

		$qry = $this->db->update($table,$data);
		
		return true;
	}

	

/* Insert Data */

	public function insertAll($table,$data){

		$qry= $this->db->insert($table, $data);

		$insert_id = $this->db->insert_id();

		return  $insert_id;

	}

	

/* Delete Record By Id */

	public function deleteById($table,$field,$id){

		$qry=$this->db->where($field,$id);

		$qry=$this->db->delete($table);

		return $qry;

	}



/* Delete Record By Where */

	public function deleteByWhere($table,$where){

		$qry=$this->db->where($where);

		$qry=$this->db->delete($table);

		return $qry;

	}



/* Get Record By Array */

	public function getRecordByArray($table,$filed1,$arr,$filed2,$order){

		$qry= $this->db->order_by($filed2,$order);

		$qry= $this->db->where_in($filed1,$arr);

		$qry= $this->db->get($table);

		//echo $this->db->last_query();

		return $qry->result();

	}



	/* Get  Data Using Id */

	public function getRecordsById($table,$field_name,$id){

		$qry= $this->db->where($field_name,$id);

		$qry= $this->db->get($table);

		return $qry->result();

	}



	/* Get  Data Using Id's */

	public function getRecordByLike($table,$field_name,$keywords,$liketype){

		$qry= $this->db->like($field_name, $keywords, $liketype);

		$qry= $this->db->get($table);

		return $qry->row();

	}

	

	public function get_record($table,$field,$wrr='')

	{

		if($wrr!=''){

			$this->db->where($wrr);

		}

		return $this->db->select($field)->from($table)->get()->result_array();

	}

	/* Get Next Order */

	public function getNextOrder($table,$field,$where=''){

	
		$this->db->select_max($field);

		if($where!=''){

			$this->db->where($where);

		}

		$qry= $this->db->get($table);

		$result = $qry->row();

		return $result->$field+1;

	}

	public function getSinglefield($table,$field,$where=''){
		$this->db->select($field);
		if($where!=''){
			$this->db->where($where);
		}
		$qry= $this->db->get($table);
		$result = $qry->row();
		return $result->$field;
	}

	public function getCountByWhere($table,$where=''){

		if($where!=''){
			$this->db->where($where);
		}

		$qry = $this->db->get($table);
		return $qry->num_rows();

	}
	public function getDistinct($table,$field,$where=''){
		$this->db->select($field);
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->distinct();
		$qry = $this->db->get($table)->result_array();
		return $qry;
	}
	
	public function getSingleRow($table_name,$fields='*',$where=array())
	{
		$this->db->select($fields);
		$this->db->from($table_name);
		$this->db->where($where);
		$q = $this->db->get();
		return $q->row();
	}

	public function getAllRow($table_name, $fields='*', $where=array(), $order_by='id DESC')
	{
		$this->db->select($fields);
		$this->db->from($table_name);
		$this->db->where($where);
		$this->db->order_by($order_by);
		$q=$this->db->get();
		 //die($this->db->last_query
		return $q->result_array();
	}
	public function getCountById($table_name,$where=array(),$order_by='student_id DESC')
	{
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where($where);
		$this->db->order_by($order_by);
		$q=$this->db->get();
		 //die($this->db->last_query
		return $q->num_rows();
	}

	public function getClassNameByClassId($class_id){
		$this->db->select('class_name');
		$this->db->where('id='.$class_id);
		$qry= $this->db->get('class_master');
		$result = $qry->row();
		return $result->class_name;
	}
	
	public function getCourseNameByCourseId($course_group_id){
		$this->db->select('course_name');
		$this->db->where('id='.$course_group_id);
		$qry= $this->db->get('course_group');
		$result = $qry->row();
		if(isset($result->course_name)){
		return $result->course_name;
		}else{
			return;
		}
	}
	
	public function getPaperNameById($paper_id){
		$this->db->select('paper_name');
		$this->db->where('id='.$paper_id);
		$qry= $this->db->get('paper_master');
		$result = $qry->row();
		return $result->paper_name;
	}
	
	public function GetAdmissonDocFile($student_id,$document_category_id){
		$this->db->select('document_image');
		$this->db->where('student_id='.$student_id.' and  document_category_id='.$document_category_id);
		$qry= $this->db->order_by('id',"desc");	
		$qry= $this->db->get('admission_document');
		$count = $qry->num_rows();
		if($count>0){
			$result = $qry->row();
			return $result->document_image;
		}else{
		return false;
		}
	}
	
	public function genrateEnrollment($student_id,$enrollment_no){
		$i=0;
		foreach($student_id as $student){
			$where = 'student_id='.$student;
			$data = array(
				'enrollment_no' => $enrollment_no[$i],
			);
			$this->updateRecordByConditions('student',$where,$data);
			$i++;
		}
		return true;
	}
	// change date format
	public function viewDate($date){
		return date("d-m-Y", strtotime($date));
	}

	public function DB_Date($date){
		return date("Y-m-d", strtotime($date));
	}
	
	public function getPaperCode($course_group_id,$class_id){
		
		$where = array("course_group_id" => $course_group_id,"class_id" => $class_id);
		$this->db->select("count(*) as cnt");
		$this->db->from("paper_master");
		$this->db->where($where);
		$query = $this->db->get();
		
		if(isset($query->row()->cnt))
		{
				$count = $query->row()->cnt;
				$paper_count  = $count + 1;
				
					
		}else{
			
			$paper_count =  '1';
		}

		$where3 = array("id" => $course_group_id);
		$this->db->select('paper_code_pattern');
		$this->db->from("course_group");
		$this->db->where($where3);
		$query = $this->db->get();
		$course_code = $query->row_array();
		$course_code = $course_code['paper_code_pattern'];
		
		
		$where2 = array("course_group_id" => $course_group_id,"id" => $class_id);
		$this->db->select('*');
		$this->db->from("class_master");
		$this->db->where($where2);
		$query = $this->db->get();
		$class = $query->row_array();		
		
		$class_order = $class['class_order'];

		$response = array();
		$response['paper_code']  = $class_order."R".$course_code."".$paper_count;
		$response['paper_code1']  = $class_order."R".$course_code;
		$response['paper_count'] = $paper_count;
		
		return $response;
	}


	public function getStudentProgramFeeByClass($course_group_id,$class_id,$gender){

		$whereCourse = "course_group_id=".$course_group_id;
		$courseData = $this->Common_model->get_record('course','*',$whereCourse);
		$txnAmt = ($gender=='Male') ? $courseData[0]['program_fees_male'] : $courseData[0]['program_fees_female'];
		$classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		if($course_group_id==43){
			$txnAmt = $courseData[0]['exam_fees'];
		}

		return ($classData->admission_permission=='Y') ?	$txnAmt : $txnAmt-500;
	}

	public function set_upload_options($path,$name)
	{   
		//upload an image options
		$config = array();
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|pdf|JPEG|jpeg';
		$config['max_size']      = '0';
		$config['overwrite']     = True;
		$config['file_name'] =  $name;
		
		return $config;
	}


	public function encrypt_decrypt($string, $action = 'encrypt')
	{
		$encrypt_method = "AES-256-CBC";
		    $secret_key = '0734MMYEway'; // user define private key
		    $secret_iv = 'MMYEway0734'; // user define secret key
		    $key = hash('sha256', $secret_key);
		    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
		    if ($action == 'encrypt') {
		    	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		    	$output = base64_encode($output);
		    } else if ($action == 'decrypt') {
		    	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		    }
		    return $output;
	}

	public function get_record_by_order($table,$field,$order,$wrr=''){
		if($wrr!=''){
			$this->db->where($wrr);
		}
		$qry= $this->db->order_by($order);
		return $this->db->select($field)->from($table)->get()->result_array();

	}
	
	public function get_record_group_by_where($table,$field,$wrr=''){
		if($wrr!=''){
			$this->db->where($wrr);
		}
		$qry= $this->db->group_by($field);
		return $this->db->select('count(*) as count,'.$field)->from($table)->get()->result_array();
	}

	function getCenterNameById($id){

		$qry = $this->db->select("center_name");
		
		$qry = $this->db->where("id",$id);

		$qry = $this->db->get("center");
		
		return $qry->row()->center_name;

	}
	function getCenterCodeById($id){

		$qry = $this->db->select("center_code");
		
		$qry = $this->db->where("id",$id);

		$qry = $this->db->get("center");
		
		return $qry->row()->center_code;

	}
	

	function center_menus_added($where='')
	{
		$heading_id = $this->input->post('heading_id');
		$this->db->select('*');
		$this->db->from('center_menu');
		$this->db->where($where);	
		 $this->db->order_by('id','DESC');
		$qry = $this->db->get();
		return $qry->result_array();
	}

	function getCenterMenuHeadingById($id)
	{
		$this->db->select('heading');
		$this->db->where('id',$id);
		$qry = $this->db->get('center_menu_heading');
        //return $qry->row_array();
		return $qry->row()->heading;


	}

	public function getSessionForEnrollment(){
		$qry= $this->db->order_by('id','DESC');
		$qry= $this->db->limit(1);
		$qry= $this->db->get('session');

		//echo $this->db->last_query();

		return $qry->result()[0]->session;

	}


	public function last_query(){
		echo $this->db->last_query();
		die;
	}	

	public function new_exam_form_permission_status($where){

		$this->db->where($where);
		$this->db->select('count(*) as cnt,course_name,class_name,class_id');
		$this->db->group_by('class_id');			
		$this->db->from("student");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function debug_data($data)
	{
		echo '<pre>';
		print_r($data);
		// die;
	}

}

?>