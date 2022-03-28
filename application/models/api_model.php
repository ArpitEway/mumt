<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class api_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

  
    public function insert($table,$userData){
        //add created and modified date if not exists
       $this->db->insert($table, $data);
        $qry = $this->db->insert_id();
        return $qry;
    }
    
  
  function getSingleRowByWhere($table,$where)
    {   
        $this->db->where($where);
        $qry = $this->db->get($table);
        $this->db->last_query();
        
        //$this->db->last_query();
        
        return $qry->row();
    }

}