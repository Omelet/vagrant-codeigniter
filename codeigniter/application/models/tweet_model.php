<?php
    class Tweet_model extends CI_Model{
    
        public function __construct()
        {
            $this->load->database();
        }
        
        public function get_user_info($user_mail){
            $query = $this->db->get_where('user_info',array('mailaddress' => $user_mail));
            return $query->row_array();
        }
    }