<?php
// Load the Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

 // use chriskacerguis\RestServer\REST_Controller;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept"); 

class SupportCenter extends REST_Controller {
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
    




  // insert_api_data

    public function insertcomplaint_post() {

        $student_id    = $this->input->post("student_id");
        $enrollment_no    = $this->input->post("enrollment_no");
        $center_code    = $this->input->post("center_code");
        $complaint_type    = $this->input->post("complaint_type");
        $details    = $this->input->post("details");
        $center = $this->Common_model->getRecordById('center','center_code',$center_code);
        $data = array('center_id'=>$center->center_id, 'student_id' =>$student_id,'type'=>$complaint_type,'status'=>'P','date'=>date('Y-m-d'),'details'=>$details);
        $insert = $this->Common_model->insertAll('center_complaint',$data);
        if($insert){
            $results['msg'] = 'Complaint Successfully Saved Your Complaint No Is';
        }else{
            $results['msg']= "Error";
        }
            return $this->response($results, REST_Controller::HTTP_OK);
    }


    public function searchStudent_post()
    {
        // $postdata = file_get_contents("php://input");
        // $request = json_decode($postdata);
        // $data = $request->data;
        $student_id = $this->input->post('student_id');
        $center_code = $this->input->post('center_code');
        $type = $this->input->post('type');
        $where = array('student_id' => $student_id,'center_code' => $center_code);
        $student_data = $this->Common_model->get_record('student','*',$where);

        if(count($student_data)>0){
            $htmlData = array('type' => $type,'student_data' => $student_data[0]);
            $results['data'] = $this->load->view('api/student_data',$htmlData,true);
        }else{
            if($type=='Result Complaint'){
                $results['data'] = '<h4 align="center">Exam Data Not Found for this Student. </h4>';
            }else{
                $results['data'] = '<h4 align="center">Data Not Found </h4>';
            }
        }
        return $this->response($results, REST_Controller::HTTP_OK);
    }

}