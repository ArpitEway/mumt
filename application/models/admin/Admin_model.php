<?php 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Admin_model extends CI_Model {

        public $table = 'admin_master'; 
        public $id = 'id';
        public $order = 'DESC';

        public function insertEntry()
        {
                $this->username    = $_POST['username'];
                $this->password  = md5($_POST['password']);
                $this->db->insert($table, $this);
        }

        public function updateEntry()
        {
                $this->title    = $_POST['title'];
                $this->content  = $_POST['content'];
                $this->db->update($table, $this, array('id' => $_POST['id']));
        }

        public function checkUser($username,$password)
        {
                $password = md5($password);
                $query = $this->db->get(" admin_master where user_name='".$username."' and password='".$password."'");
                
                if($query->num_rows()>0){

                        $result = $query->result();
                        return $result[0];
						
                }else{
                        return false;
                }
        }
		public function checkUserByUsername($username)
        {
                $password = md5($password);
                $query = $this->db->get("admin_master where user_name='".$username."' ");
                
                if($query->num_rows()>0){
                        $result = $query->result();
                        return $result[0];
                }else{
                        return false;
                }
        }
        public function checkUserByUserID($ID)
        {
        	$query = $this->db->get("admin_master where id='".$ID."' ");

        	if($query->num_rows()>0){
        		$result = $query->result();
        		return $result[0];
        	}else{
        		return false;
        	}
        }
    
 
	public function create_menu_heading()
	{
		$data['admin_id'] = html_escape($this->input->post('admin_id'));
		$data['heading'] = html_escape($this->input->post('heading_name'));
		
		
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$heading = $this->db->get_where('menu_heading', array('admin_id' => $data['admin_id']))->row_array();
		if($heading){
			
			 $data['heading_order'] = $heading['heading_order'] + 1;
			
		 }else{
			
			 $data['heading_order'] = 1;

		 }
		
		$this->db->insert('menu_heading', $data);
		
		$ins_id = $this->db->insert_id();
		
		$response = array(
				'status' => 'true',
				'insert_id' => $ins_id
				);
				
		return json_encode($response);
	}


	public function create_student_menu_heading()
	{
		
		$data['heading'] = html_escape($this->input->post('heading_name'));
		
		
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$heading = $this->db->get_where('student_menu_heading', array())->row_array();
		
		 if($heading){
			
			 $data['heading_order'] = $heading['heading_order'] + 1;
			
		 }else{
			
			 $data['heading_order'] = 1;

		 }
		
		$this->db->insert('student_menu_heading', $data);
			
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
	
	public function create_menu()
	{
		$data['admin_id']   = html_escape($this->input->post('admin_id'));
		$data['heading_id'] = html_escape($this->input->post('heading_id'));
		$data['option'] 	= html_escape($this->input->post('menu'));
		$data['url'] 		= html_escape($this->input->post('menu_url'));
		$data['status'] 	= html_escape($this->input->post('status'));
		
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$menu = $this->db->get_where('menu', array('admin_id' => $data['admin_id']))->row_array();
		
		 if($menu){
			
			 $data['menu_order'] = $menu['menu_order'] + 1;
			
		 }else{
			
			 $data['menu_order'] = 1;

		 }
		
		$this->db->insert('menu', $data);
			
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
	public function create_student_menu()
	{
		
		$data['heading_id'] = html_escape($this->input->post('heading_id'));
		$data['option'] = html_escape($this->input->post('menu'));
		$data['url'] = html_escape($this->input->post('menu_url'));
		$data['status'] = html_escape($this->input->post('status'));
		
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$menu = $this->db->get_where('menu', array())->row_array();
		
		 if($menu){
			
			 $data['menu_order'] = $menu['menu_order'] + 1;
			
		 }else{
			
			 $data['menu_order'] = 1;

		 }
		
		$this->db->insert('student_menu', $data);
			
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
	
	public function update_menu_heading($param1)
	{
		$data['admin_id'] = html_escape($this->input->post('admin_id'));
		$data['heading']  = html_escape($this->input->post('heading_name'));
		
		$this->db->where('id', $param1);
		$this->db->update('menu_heading', $data);
		
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
	
	public function update_student_menu_heading($param1)
	{
		
		$data['heading']  = html_escape($this->input->post('heading_name'));
		
		$this->db->where('id', $param1);
		$this->db->update('student_menu_heading', $data);
		
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}

	public function update_center_menu_heading($param1)
	{
		
		$data['heading']  = html_escape($this->input->post('heading_name'));
		
		$this->db->where('id', $param1);
		$this->db->update('center_menu_heading', $data);
		
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
	
	public function update_menu($param1)
	{
		$data['admin_id'] = html_escape($this->input->post('admin_id'));
		$data['heading_id']  = html_escape($this->input->post('heading_id'));
		$data['option']   = html_escape($this->input->post('menu'));
		$data['url']  	  = html_escape($this->input->post('menu_url'));
		$data['status']  = html_escape($this->input->post('status'));
		
		$this->db->where('id', $param1);
		$this->db->update('menu', $data);
		
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
	public function update_student_menu($param1)
	{
		
		$data['heading_id']  = html_escape($this->input->post('heading_id'));
		$data['option']   	 = html_escape($this->input->post('menu'));
		$data['url']  	  	 = html_escape($this->input->post('menu_url'));
		$data['status']  	 = html_escape($this->input->post('status'));
		
		$this->db->where('id', $param1);
		$this->db->update('student_menu', $data);
		
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
	
	
	public function menu_heading_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('menu_heading');

		$response = array(
			'status' => 'true',
		);

		return json_encode($response);
	}
	public function student_menu_heading_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('student_menu_heading');

		$response = array(
			'status' => 'true',
		);

		return json_encode($response);
	}
	
	public function menu_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('menu');

		$response = array(
			'status' => 'true',
		);
		return json_encode($response);
	}
	public function student_menu_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('student_menu');

		$response = array(
			'status' => 'true',
		);
		return json_encode($response);
	}
	

    public function create_session()
    {
        $data['session'] = html_escape($this->input->post('session'));
		$data['type'] = html_escape($this->input->post('type'));
       $data['enrollment_code'] = html_escape($this->input->post('enrollment'));
       
        $this->db->insert('session', $data);
        $session_id = $this->db->insert_id();
        $response = array(
        			'status' => true,
        			'notification' => 'Session_added_successfully'
        			);
        return json_encode($response);
    }

    public function session_update($param1 = '')
	{
	    
		$data['session'] = html_escape($this->input->post('session'));
		$data['type'] = html_escape($this->input->post('type'));

		$this->db->where('id', $param1);
		$this->db->update('session', $data);
		// echo $this->db->last_query();
		$response = array(
			'status' => true,
			'notification' => 'session_has_been_updated_successfully'
		);

		return json_encode($response);
	}

	public function session_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('session');

		$response = array(
			'status' => true,
			'notification' => 'session_has_been_deleted_successfully'
		);

		return json_encode($response);
	}

	public function create_course()
    {

        $data_group['course_name'] = html_escape($this->input->post('course_name'));
		$data_group['eligibility'] = html_escape($this->input->post('eligibility'));
		$data_group['mode'] = html_escape($this->input->post('mode'));
		$data_group['university_mode'] = html_escape($this->input->post('university_mode'));
		
		$data['course_name'] = html_escape($this->input->post('course_name'));
		$data['course_code'] = html_escape($this->input->post('course_code'));
		
		$data['min_duration'] = html_escape($this->input->post('min_duration'));
		$data['max_duration'] = html_escape($this->input->post('max_duration'));
		$data['session'] = html_escape($this->input->post('session'));

		$data['admission_fees'] = html_escape($this->input->post('admission_fees'));
		$data['program_fees'] = html_escape($this->input->post('program_fees'));
		$data['form_fees'] = html_escape($this->input->post('form_fees'));
		$data['exam_fees'] = html_escape($this->input->post('exam_fees'));

		$data['p_admission_fees'] = html_escape($this->input->post('p_admission_fees'));
		$data['p_program_fees'] = html_escape($this->input->post('p_program_fees'));
		$data['p_form_fees'] = html_escape($this->input->post('p_form_fees'));
		$data['p_exam_fees'] = html_escape($this->input->post('p_exam_fees'));
		
        $this->db->insert('course_group', $data_group);
		
        $course_ins_id = $this->db->insert_id();
		
		$data['course_group_id'] = $course_ins_id;
		
		$this->db->insert('course', $data);
		
        $ins_id = $this->db->insert_id();
		
        $response = array(
        			'status' => true,
        			'notification' => 'course_added_successfully'
        			);
        return json_encode($response);
    }
    public function course_update($param1 = '')
	{
	    
		$data_group['course_name'] = html_escape($this->input->post('course_name'));
		$data_group['eligibility'] = html_escape($this->input->post('eligibility'));
		$data_group['mode'] = html_escape($this->input->post('mode'));
		$data_group['university_mode'] = html_escape($this->input->post('university_mode'));

		
		$course_group_id = $this->input->post('group_id');
		
		$data['course_name'] = html_escape($this->input->post('course_name'));
		$data['course_code'] = html_escape($this->input->post('course_code'));
		$data['min_duration'] = html_escape($this->input->post('min_duration'));
		$data['max_duration'] = html_escape($this->input->post('max_duration'));
		$data['session'] = html_escape($this->input->post('session'));

		$data['admission_fees'] = html_escape($this->input->post('admission_fees'));
		$data['program_fees'] = html_escape($this->input->post('program_fees'));
		$data['form_fees'] = html_escape($this->input->post('form_fees'));
		$data['exam_fees'] = html_escape($this->input->post('exam_fees'));

		

		$data['p_admission_fees'] = html_escape($this->input->post('p_admission_fees'));
		$data['p_program_fees'] = html_escape($this->input->post('p_program_fees'));
		$data['p_form_fees'] = html_escape($this->input->post('p_form_fees'));
		$data['p_exam_fees'] = html_escape($this->input->post('p_exam_fees'));
		
		$this->db->where('id', $course_group_id);
		$this->db->update('course_group', $data_group);
		
		$this->db->where('id', $param1);
		$this->db->update('course', $data);
		
	
		$response = array(
			'status' => true,
			'notification' => 'course_has_been_updated_successfully'
		);

		return json_encode($response);
	}
	
	public function course_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('course');
		
		$courses = $this->db->get_where('course', array('id' => $param1))->row_array();
		$c_id = $courses['course_group_id'];
		
		$this->db->where('id', $c_id);
		$this->db->delete('course_group');

		$response = array(
			'status' => true,
			'notification' => 'course_has_been_deleted_successfully'
		);

		return json_encode($response);
	}
	
	public function create_class()
    {
		$data['course_group_id'] = html_escape($this->input->post('course_group_id'));
		$data['class_name'] = html_escape($this->input->post('class_name'));
		$data['class_group'] = html_escape($this->input->post('class_group'));
		$data['mode'] = html_escape($this->input->post('mode'));
		$data['total_paper'] = html_escape($this->input->post('total_paper'));
		$data['select_group'] = html_escape($this->input->post('select_group'));
		$data['group_type'] = html_escape($this->input->post('Select_group_type'));
		$data['class_group'] = html_escape($this->input->post('class_group'));
		
		if($data['class_group']=='Y'){
			$group_names = html_escape($this->input->post('group_name'));
			$count = count($group_names);
		}else{
			$count = 0;
		}
		
		$data['total_group'] = $count;
		
		$courses = $this->db->get_where('course', array('course_group_id' => $data['course_group_id']))->row_array();
		
		$course_count = $this->db->get_where('class_master', array('course_group_id' => $data['course_group_id']))->result_array();
		
		$course_count =  @count($course_count);
		
		$class_order  = @$course_count + 1 ;
		
		$data['class_order'] = $class_order;
		
		$course_name  = $courses['course_name'];
		
		$this->db->insert('class_master', $data);
        $ins_id = $this->db->insert_id();
		
			if($data['class_group']=='Y'){
			
				foreach($group_names as $group_name ){
				
				$data_group['group_name'] = $group_name;
				$data_group['class_id'] 	= $ins_id;
				$data_group['course_group_id'] = $data['course_group_id'];
				$data_group['course_name'] = $course_name;
				
				$this->db->insert('group', $data_group);
				$insid = $this->db->insert_id();
				
				}
			}
			
        $response = array(
        			'status' => true,
        			'notification' => 'class_added_successfully'
        			);
					
        return json_encode($response);
    }
	public function class_update($param1 = '')
	{	
		$data['course_group_id'] = html_escape($this->input->post('course_group_id'));
		$data['class_name']      = html_escape($this->input->post('class_name'));
		$data['class_group']  	 = html_escape($this->input->post('class_group'));
		$data['mode'] 		  	 = html_escape($this->input->post('mode'));
		$data['total_paper']  	 = html_escape($this->input->post('total_paper'));
		$data['group_type']          = html_escape($this->input->post('Select_group_type'));
		$data['class_group']  	 = html_escape($this->input->post('class_group'));
		$data['select_group'] 	 = html_escape($this->input->post('select_group'));
		
		
		if($data['class_group']=='Y'){
			$group_names = html_escape($this->input->post('group_name'));
			$group_ids = html_escape($this->input->post('group_id'));
			$count_group_id = count($group_ids);
			$count = count($group_names);
			
		}else{
			
			$count = 0;
		}
		
		$data['total_group'] = $count;
		
		$courses = $this->db->get_where('course', array('course_group_id' => $data['course_group_id']))->row_array();
		
		$course_count = $this->db->get_where('class_master', array('course_group_id' => $data['course_group_id']))->result_array();
		
		
		$course_name  = $courses['course_name'];
		
		if($data['class_group']=='Y'){
			
			$group_names = html_escape($this->input->post('group_name'));
			$count = count($group_names);
			
		}else{
			
			$count = 0;
		}
		
		$this->db->where('id', $param1);
		$this->db->update('class_master', $data);
		
		if($data['class_group']=='Y')
		{
			$this->db->where('class_id', $param1);
			//$this->db->delete('group');
    		$i=0;
    		$data_group['class_id'] 		= $param1;
    		$data_group['course_group_id']  = $data['course_group_id'];
    		$data_group['course_name'] 		= $course_name;
    		$data['total_group'] 			= $count;
    		
    		foreach($group_ids as $group_id )
    		{
    			$data_group['group_name'] = $group_names[$i];
    			$this->db->where('id', $group_id);
    			$this->db->update('group', $data_group);
    			$i++;
    		}
    		
    		if($count_group_id < $count){
    			
    			for($j=$i; $j<$count; $j++){
    				$data_group['group_name'] = $group_names[$j];
    				$this->db->insert('group', $data_group);
    				$insid = $this->db->insert_id();
    			}
    		}
    	}
    	
    	
    }
	
	public function class_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('class_master');
		
		$this->db->where('class_id', $param1);
		$this->db->delete('group');

		$response = array(
			'status' => true,
			'notification' => 'class_has_been_deleted_successfully'
		);

		return json_encode($response);
	}
	public function create_paper()
    {
		
        $data['course_group_id'] = html_escape($this->input->post('course_group_id'));
		$data['class_id'] 		 = html_escape($this->input->post('class_id'));
		
		$ces 					 = html_escape($this->input->post('ce'));
		$data['ce'] 			 = html_escape($this->input->post('ce'));
		$data['paper_name']      = html_escape($this->input->post('paper_name'));
		$data['paper_code']      = html_escape($this->input->post('paper_code'));
		$data['type'] 		     = html_escape($this->input->post('type'));
		$data['group_name']		 = html_escape($this->input->post('group_name'));
		
		$courses = $this->db->get_where('course', array('course_group_id' => $data['course_group_id']))->row_array();
		$course_name  = $courses['course_name'];
		
		$count = count($ces);
		$j = 0;
		$paper_no = 1;
		for($i=0; $i < $count;$i++)
		{
			
			$paper_data['course_group_id'] 	= $data['course_group_id'];
			$paper_data['class_id'] 		= $data['class_id'];
			$paper_data['course_name'] 		= $course_name;
			$paper_data['ce'] 		   		= $data['ce'][$i];
			
			$paper_data['paper_name']  		= $data['paper_name'][$i];
			$paper_data['paper_no']  		= $paper_no;
			$paper_data['paper_code']  		= $data['paper_code'][$i]; 
			$paper_data['type'] 	   		= $data['type'][$i];
			
			$this->db->insert('paper_master', $paper_data);
			$ins_id = $this->db->insert_id();
			
			
			if($data['ce'][$i] == "elective")
			{
				//echo "test out";
				
				if(!empty($data['group_name'][$j]))
				{
					
				//echo "test in";
				
				$group_data['group_id']   = $data['group_name'][$j];
				$group_data['paper_name'] = $data['paper_name'][$i];
				$group_data['paper_code'] = $data['paper_code'][$i]; 
				$group_data['paper_id']   = $ins_id;
				
				$this->db->insert('group_paper', $group_data);
				$j++;
				}
			}
		$paper_no ++;		
		}
		
		
        $response = array(
        			'status' => true
        			);
        return json_encode($response);
	}
	public function paper_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('paper_master');
		
		$this->db->where('paper_id', $param1);
		$this->db->delete('group_paper');

		$response = array(
			'status' => true
		);

		return json_encode($response);
	}


	public function add_company()
    {
        $data['company_name'] 	   = html_escape($this->input->post('company_name'));
		$data['job_title'] 		   = html_escape($this->input->post('job_title'));
		$data['min_qualification'] = html_escape($this->input->post('min_qualification'));
		$data['description']       = html_escape($this->input->post('description'));
		$data['other_detail'] 	   = html_escape($this->input->post('other_detail'));
		
        $this->db->insert('company', $data);
		
        $company_id = $this->db->insert_id();
		
        $response = array(
        			'status' => true
        			);
        return json_encode($response);
    }
	public function update_company($param1 = '')
	{
	    
		$data['company_name'] 	   = html_escape($this->input->post('company_name'));
		$data['job_title'] 		   = html_escape($this->input->post('job_title'));
		$data['min_qualification'] = html_escape($this->input->post('min_qualification'));
		$data['description']       = html_escape($this->input->post('description'));
		$data['other_detail'] 	   = html_escape($this->input->post('other_detail'));
		
		$this->db->where('id', $param1);
		$this->db->update('company', $data);
		$response = array(
			'status' => true
		);

		return json_encode($response);
	}
	public function delete_company($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('company');
	
		$response = array(
			'status' => true
		);

		return json_encode($response);
	}
	
	public function create_account()
    {
        $data['name'] = html_escape($this->input->post('name'));
		$data['account_type'] = html_escape($this->input->post('account_type'));
		$data['designation'] = html_escape($this->input->post('designation'));
		$data['user_name'] = html_escape($this->input->post('user_name'));
		$data['password'] = md5($this->input->post('password'));
		
        $this->db->insert('admin_master', $data);
		
        $department_id = $this->db->insert_id();
		
        $response = array(
        			'status' => true
        			);
        return json_encode($response);
    }
	
	public function account_update($param1 = '')
	{
	    
		$data['name'] 		  = html_escape($this->input->post('name'));
		$data['account_type'] = html_escape($this->input->post('account_type'));
		$data['designation']  = html_escape($this->input->post('designation'));
		$data['user_name'] = html_escape($this->input->post('user_name'));
		$data['password'] 	  = md5($this->input->post('password'));
		
		$this->db->where('id', $param1);
		$this->db->update('admin_master', $data);
		$response = array(
			'status' => true
		);

		return json_encode($response);
	}
	
	public function student_doc_update($param1 = '')
	{
	    
		$remark = html_escape($this->input->post('remark'));
		$remark_detail = html_escape($this->input->post('remark_detail'));
		$other_remark = html_escape($this->input->post('other_remark'));
		
		
		$data['remark'] = implode(",",$remark);
		$data['remark_detail'] = $remark_detail;
		$data['remark_detail'] .= (count($other_remark)>0) ? ' ( '.implode(", ",$other_remark).' ) ' : '';
		$data['approved'] = "N";
		
		$this->db->where('student_id', $param1);
		$this->db->update('student', $data);
		
		$data_student['status'] = "Y";
		$this->db->where('student_id', $param1);
		$this->db->update('admission_document', $data_student);
		
	}
	
	public function student_approve($param1 = '')
	{
	    $students = $this->db->get_where('student', array('student_id' => $param1))->row_array();
		$data['remark'] = "N";
		$data['approved'] = "Y";
		$this->db->where('student_id', $param1);
		$this->db->update('student', $data);
		$response = array('status' => 'true');
		return json_encode($response);
	}
	
	public function account_delete($param1 = '')
	{
		$this->db->where('account_type != "Admin"');
		$this->db->where('id', $param1);
		$this->db->delete('admin_master');
	
		$response = array(
			'status' => true
		);

		return json_encode($response);
	}
	
	public function create_center($data)
    {
        $this->db->insert('center', $data);
        $center_id = $this->db->insert_id();
        $response = array(
        			'status' => true,
        			);
        return json_encode($response);
    }

	public function center_update($param1 = '',$data)
	{
	    
		$this->db->where('id', $param1);
		$this->db->update('center', $data);
		// echo $this->db->last_query();
		$response = array(
			'status' => true,
		);

		return json_encode($response);
	}
	
	public function center_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('center');
		
		$response = array(
			'status' => 'true',
		);
		return json_encode($response);
	}
	
	public function allot_course($param1)
	{
		$data['alloted_course_group_id'] = html_escape($this->input->post('course_group_id'));
		
		$data['alloted_course_group_id'] = implode(",", $data['alloted_course_group_id']);
		
		$this->db->where('id', $param1);
		$this->db->update('center', $data);
		
		$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	}
		
	public function unpaid_program_fees($updateData)
	{	
		$student_info = $this->Common_model->student_info($updateData['student_id']);	
		$payment_date = $this->Common_model->DB_Date($updateData['payment_date']);
		
		$data['student_id'] 	 = $updateData['student_id'];
		$data['course_group_id'] = $student_info['course_group_id'];
		$data['class_id'] 		 = $student_info['class_id'];
		$data['student_name']    = $student_info['name'];
		$data['amount'] 		 = $updateData['amount'];
		$data['remark'] 		 = $updateData['remark'];
		$data['fees_head'] 		 = 'Program Fees';
		$data['payment_mode'] 	 = $updateData['payment_mode'];
		$data['payment'] 		 = "Y";
		$data['payment_status']  = "Verified By University";
		$data['image']  		 = $updateData['filename'];
		$data['payment_date'] 	 = $payment_date;
		$data['exam_session'] 	 = "";
		$data['clientTxnId'] 	 = "";
		$data['PGTxnNo'] 	 	 = "";
		$data['SabPaisaTxId'] 	 = "";
		$data['issuerRefNo'] 	 = "";
		
		$old_record = $this->db->get_where('online_payment_transaction', array('student_id' => $updateData['student_id'], 'fees_head' => 'Program Fees'))->row_array();
		
		if($old_record){
			$this->db->where(array('student_id' => $updateData['student_id'], 'fees_head' => 'Program Fees'));
			$this->db->update('online_payment_transaction', $data);
		}else{
			$this->db->insert('online_payment_transaction', $data);
		}
		$student_data['program_fees'] = 'Y';
		$this->db->where('student_id', $updateData['student_id']);
		$this->db->update('student', $student_data);
		$response = array(
				'status' => 'true',
			);
		return json_encode($response);
	}

	// add student by enrolle
	public function checkStudentEnrollment($enroll)
        {
               
                $query = $this->db->get(" student where enrollment_no='".$enroll."' ");

                if($query->num_rows()>0){
                        $result = $query->result();
                        return $result[0];
                }else{
                        return false;
                }
        }

        public function create_center_menu_heading()
	 {


       $data['heading'] = html_escape($this->input->post('heading_name'));

        $this->db->order_by('id','desc');
		$this->db->limit(1);
        $in_heading = $this->db->get_where('center_menu_heading',array())->row_array();

        if($in_heading)
        {
             $data['heading_order'] = $in_heading['heading_order'] + 1;
        }else
        {
             $data['heading_order'] = 1;
        }
        $this->db->insert('center_menu_heading',$data);

        $response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
        
	 }

	 public function delete_center_menu_heading($id='')
	 {
	 	$this->db->where('id',$id);
	 	$this->db->delete('center_menu_heading');
	 	$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
	 }


	public function getcenterCode(){
		$qry = $this->db->select("count(*) as cnt");
		$qry = $this->db->get("center");
		if(isset($qry->row()->cnt))
		{
			$count = $qry->row()->cnt;
			$count = $count + 1;
			if($count < 10){
				$center_code = "IC0".$count;	
			}else{
				$center_code = "IC".$count;
			}	
			return $center_code;
		}else{
			return "MPSVV01";
		}
	}


	public function aadhar_update($param1 = '')
	{
	    
		$aadhar_no = html_escape($this->input->post('aadhar_number'));
		
		$data['adhar_no'] = $this->input->post('aadhar_number');
	
		$this->db->where('student_id', $param1);
		$this->db->update('student', $data);
		
		$response = array(
			'status' => true
		);

		return json_encode($response);
	}

	public function create_form_request(){

		$data['center_id'] 	= $this->session->center_id;
		$data['student_id'] = html_escape($this->input->post('student'));
		$data['detail'] 	= html_escape($this->input->post('detail'));
		$data['date'] 		= date("Y-m-d");

		$this->db->insert('request', $data);
		$session_id = $this->db->insert_id();
		$response = array(
			'status' => true,
			'notification' => 'form_request_added_successfully'
		);
		return json_encode($response);
	}

    function create_center_menu()
    {

    	$data['heading_id']=$this->input->post('heading_id');
    	$data['option']=$this->input->post('menu');
    	$data['url']=$this->input->post('menu_url');
    	$data['status']=$this->input->post('status');

    	//$insert_menu = $this->db->insert('center_menu',$data,true);
    	//checking menu already
    	$this->db->order_by('id','DESC');
    	$this->db->limit(1);
    	$already = $this->db->get_where('center_menu',array())->row_array();
    	if($already)
    	{
    		$data['menu_order'] = $already['menu_order']+1;

        }else
        {
        	$data['menu_order'] = 1;
        }

        $insert_menu = $this->db->insert('center_menu',$data);
        if($insert_menu)
        {
        	$last_id = $this->db->insert_id();

	    	$response = array(
				'status' => 'true',
				'insert_id' => $last_id
				);
				
		   return json_encode($response);
        }
    }

    function update_center_menu($id)
    {
      if($id)
      {
      	$data['heading_id']=html_escape($this->input->post('heading_id'));
      	$data['option']=html_escape($this->input->post('menu'));
      	$data['url']=html_escape($this->input->post('menu_url'));
      	$data['status']=html_escape($this->input->post('status'));

      	$this->db->where('id',$id);
      	$this->db->update('center_menu',$data);

      	$response = array(
				'status' => 'true',
				);
				
		return json_encode($response);
      }
    }

    function delete_center_menu($id)
	{
		if($id)
		{
			$this->db->where('id',$id);
			$this->db->delete('center_menu');
			$response = array('status'=>true);
			return json_encode($response);
		}
	}


}

?>