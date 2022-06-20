<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datatable_join_model extends CI_Model{
    
    /*
     * Fetch members data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData,$array){
        $this->_get_datatables_query($postData,$array);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
		//die($this->db->last_query());
        return $query->result();
    }
    
    
    /*
     * Count all records
     */
    public function joincountAll($postData,$array){
        $this->_get_datatables_query($postData,$array);
        
            return $this->db->count_all_results();
        }
    /*
     * Count all records
     */
    public function countAll($table,$where=''){
	if($where!=''){
		$this->db->where($where);
		}
        $this->db->from($table);
        return $this->db->count_all_results();
    }
    
    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData,$array){
        $this->_get_datatables_query($postData,$array);
		if($array['where']!=''){
		$this->db->where($array['where']);
		}
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData,$array){
         
        $this->db->from($array['table']);

        if(isset($array['select']));
        $this->db->select($array['select']);
        
        if(isset($array['group_by']));
        $this->db->group_by($array['group_by']);

        if($array['where']!=''){
            $this->db->where($array['where']);
        }

		if(isset($array['table2']) && $array['table2']!='' && $array['joinOn']!='' && isset($array['joinOn'])){
			$this->db->join($array['table2'],$array['joinOn']);
		}
        $i = 0;
        // loop searchable columns 
        foreach($array['column_search'] as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($array['column_search']) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
		if(isset($postData['order']['0']['column'])){
		$this->db->order_by($array['column_order'][$postData['order']['0']['column']], $postData['order']['0']['dir']);
			}
    }
}