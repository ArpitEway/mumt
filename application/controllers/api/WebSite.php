<?php
// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

 // use chriskacerguis\RestServer\REST_Controller;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept"); 

class WebSite extends REST_Controller {
/**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() { 
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        // Load the user model
        $this->load->model('Common_model');
    }

    public function insertEnquiry_post() {

        $name = html_escape($this->input->post("name"));
        $email = html_escape($this->input->post("email"));
        $department    = html_escape($this->input->post("department"));
        $program    = html_escape($this->input->post("program"));
        $mobile    = html_escape($this->input->post("mobile"));
        $city = html_escape($this->input->post("city"));
        $data = array('name'=>$name, 'email' =>$email,'department'=>$department,'program'=>$program,'mobile'=>$mobile,'city'=>$city);
        $insert = $this->Common_model->insertAll('enquiry',$data);
        if($insert){
            $results['msg'] = 'Enquiry Submitted Successfully';
        }else{
            $results['msg']= "An Error Occurred";
        }
            return $this->response($results, REST_Controller::HTTP_OK);
    }

    public function getDepartment_get()
    {
        $results = $this->Common_model->get_record('department','*');

        return $this->response($results, REST_Controller::HTTP_OK);
    }

    public function GetPrograms_get()
    {
       $department_id = $this->input->get("department_id");
       $results = $this->Common_model->getRecordByWhere('program',array('department_id' => $department_id));
        return $this->response($results, REST_Controller::HTTP_OK);
    }
}