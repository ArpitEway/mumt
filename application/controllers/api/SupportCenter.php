<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

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
    


    public function index_post()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = $request->data;
        $student_id = $this->input->post('student_id');
        $center_code = $this->input->post('center_code');
        $type = $this->input->post('type');
        $where = array('student_id' => $student_id,'center_code' => $center_code);
        $student_data = $this->Common_model->get_record('student','*',$where);

        if(count($student_data)>0){
            $htmlData = array('type' => $type,'student_data' => $student_data);
            $results['data'] = $this->load->view('api/student_data',$htmlData);
        }else{
            if($type=='Result Complaint'){
                $results['data'] = '<h4 align="center">Exam Data Not Found for this Student. </h4>';
            }else{
                $results['data'] = '<h4 align="center">Data Not Found </h4>';
            }
        }
        return $this->response($results, REST_Controller::HTTP_OK);
    }



  // insert_api_data

    public function insert_api_data_post () {
       $center_id    = $this->input->post("center_id");
       $student_id    = $this->input->post("student_id");
       $type    = $this->input->post("type");
       $status    = $this->input->post("status");
       $details    = $this->input->post("details");

       $data = array('center_id'=>$center_id, 'student_id' =>$student_id,'type'=>$type,'status'=>$status,'details'=>$details);

       $insert = $this->Api_Model->insert('center_complaint',$data);
       if($insert){
            $data_res['msg'] = 'Data successfully saved';
            $res = json_encode($data_res);
            return $this->response($res, REST_Controller::HTTP_OK );
        }else{
        $results['msg']= "Error";
            return $this->response($results, REST_Controller::HTTP_OK);
        }
    }
}