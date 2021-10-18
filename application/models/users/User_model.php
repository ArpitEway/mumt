<?php 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class User_model extends CI_Model {

        public $table = 'user_enquiry';
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
                $password = date("Y-m-d",strtotime($password));
                $query = $this->db->get("user_enquiry where mobile_no='".$username."' and dob='".$password."'");                
                if($query->num_rows()>0){
                        $result = $query->result();
                        return $result[0];
                }else{
                        return false;
                }
        }

        public function checkValidStudent($student_id)
        {
                $id =  $this->session->Users_id;
                $userData = $this->Common_model->getRecordById('user_enquiry','id',$id);
                $stduent_ids = explode(',', $userData->student_id);
                if(!in_array($student_id,$stduent_ids)){
                        redirect(base_url('user'));
                }
        }

        public function hasNewAdmissionAccess()
        {
                $user_id = $this->session->Users_id;
                $count = $this->Common_model->getCountByWhere('student','user_id = '.$user_id.' and approved!="Y"');
                return ($count>0) ? FALSE : TRUE;
        }
}
?>