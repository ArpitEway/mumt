<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept"); 

class Authentication extends REST_Controller {

    public function __construct($config = 'rest') { 
        parent::__construct($config);
        date_default_timezone_set('Asia/Kolkata');
        // Load the user model
        $this->load->model('api_model');
    }
    
  // insert_api_data

    public function index_post () {

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $center_id = $request->center_id; 
        $student_id = $request->student_id;
        $type = $request->type;
        $status = $request->status;
        $details = $request->details;

        // $where = array("center_id"=> $center_id ,"student_id"=> $student_id ,"type"=> $type,"status"=> $status,"details"=> $details
        //      );
        // $results = $this->api_model->getSingleRowByWhere("center_complaint",$where);

        // $center_id1 = $results->center_id;
        // $student_id1 = $results->student_id;
        // $type1 = $results->type;
        // $status1 = $results->status;
        // $details1 = $results->details;

        $data = array('center_id'=>$center_id, 'student_id' =>$student_id,'type'=>$type,'status'=>$status,'details'=>$details
                );

        $insert = $this->Api_Model->insert('center_complaint',$data);
        if($insert)
        {
            $data_res['msg'] = 'Data successfully saved';
              
         $res = json_encode($data_res);

            return $this->response($res, REST_Controller::
            );

        }else{
           $results['msg']= "Error";
            return $this->response($results, REST_Controller::HTTP_OK);
        }
               
    }
    






}