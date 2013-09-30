<?php

class Tweet_model extends CI_Model
{
    
    public function __construct()
    {
        $this->load->database();
        $this->load->helper('date');
    }
    
    //アドレスからユーザー情報の取得
    public function get_user_info_from_mail($user_mail)
    {
        $query = $this->db->get_where('user_info', array('mailaddress' => $user_mail));
        
        return $query->row_array();
    }
    
    //IDからユーザー情報の取得
    public function get_user_info_from_id($user_id)
    {
        $query = $this->db->get_where('user_info', array('user_id' => $user_id));
        
        return $query->row_array();
    }
    
    //ユーザーデータの登録
    public function set_user_info($user_data)
    {
        $enc_pass = hash("sha256",$user_data['password']);
        $info = array(
                      'name' => $user_data['name'],
                      'mailaddress' => $user_data['mailaddress'],
                      'password' => $enc_pass
        );
        
        $this->db->insert('user_info', $info);
        return $this->db->insert_id();
    }
    
    //ツイートの登録
    public function set_tweet($user_id, $tweet)
    {
        $info = array(
            'user_id' => $user_id,
            'substance' => $tweet,
            'time' => date("Y-m-d H:i:s")
        );
        return  $this->db->insert('tweet', $info);
    }
    
    //ツイートの取得
    public function get_tweet_data($user_id)
    {
        $this->db->order_by("tweet_id","desc");
        $query = $this->db->get_where('tweet', array('user_id' => $user_id));
        
        return $query->result_array();
    }
    
    public function get_nth_tweet($user_id,$num)
    {
        $this->db->order_by("tweet_id","desc");
        $this->db->limit(1,$num);
        $query = $this->db->get_where('tweet', array('user_id' => $user_id));
        
        return $query->row_array();
    }
    
}