<?php 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Account_model extends CI_Model {

        public $table = 'admin_master'; 
        public $id = 'id';
        public $order = 'DESC';

        function account_data($where = "")
		{
			$this->db->select('*');
			$this->db->from("online_payment_transaction");
			$this->db->where($where);
			$query = $this->db->get();
			return $query->result_array();
		}
		public function countAll(){
			$this->db->from('online_payment_transaction');
			return $this->db->count_all_results();
		}
    
		public function countFiltered($where = ""){
			$this->db->select('*');
			$this->db->from("online_payment_transaction");
			$this->db->where($where);
			$query = $this->db->get();
			return $query->num_rows();
		}
        
}

?>