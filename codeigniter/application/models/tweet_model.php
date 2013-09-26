<?php

class Tweet_model extends CI_Model
{
    
    public function __construct()
    {
        $this->load->database();
        $this->load->helper('date');
    }
        
    public function get_user_info_from_mail($user_mail)
    {
        $query = $this->db->get_where('user_info',array('mailaddress' => $user_mail));
        
        return $query->row_array();
    }
    
    public function get_user_info_from_id($user_id)
    {
        $query = $this->db->get_where('user_info',array('user_id' => $user_id));
        
        return $query->row_array();
    }
    
    public function set_user_info()
    {
        
        $pass = $this->input->post('password');
        $enc_pass = hash("sha256",$pass);
        $info = array(
            'name' => $this->input->post('name'),
            'mailaddress' => $this->input->post('mailaddress'),
            'password' => $enc_pass
        );
        
        return $this->db->insert('user_info', $info);
    }
    
    public function set_tweet()
    {
        $user_id =  $this->session->userdata('user_id');
        $info = array(
            'user_id' => $user_id,
            'substance' => $this->input->post('tweet'),
            'time' => time()
        );
        return  $this->db->insert('tweet', $info);
    }
    
}