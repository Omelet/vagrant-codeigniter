<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Tweet extends CI_Controller{
        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('tweet_model');
        }
        
       
        //初めのログイン
        public function login()
        {
            
            $this->load->helper('form');
            $this->load->view('tweet/login/header');
            $this->load->view('tweet/login/login');
            $this->load->view('tweet/login/footer');
            
        }
        
        //二度目以降のログイン
        public function login1(){
            
            
            $this->load->helper('form');
            if($this::user_check($_POST)==FALSE)
            {
                $this->load->view('tweet/login/login');
                $this->load->view('tweet/login/footer');
            }
            else{
                $this->load->view('tweet/main/main');
            }
        }
        
        //ユーザー登録
        public function registry(){
            echo 'test';
        }
        
        //入力チェック、ユーザーチェックメソッド
        private function user_check($input){
            
            
            
            $count=0;
            if($input["mailaddress"]==""){
                if($count==0) $this->load->view('tweet/login/header');
                $this->load->view('tweet/message/no_mail');
                $count++;
            }
            if($input["password"]==""){
                if($count==0) $this->load->view('tweet/login/header');
                $this->load->view('tweet/message/no_password');
                $count++;
            }
            
            if($count!=0) return FALSE;
            
            
            if(!$this::is_mail($input["mailaddress"])){
                $this->load->view('tweet/login/header');
                $this->load->view('tweet/message/not_mail');
                return FALSE;
            }
            
            
            $data['user_info'] = $this->tweet_model->get_user_info($input["mailaddress"]);
            
            if(empty($data['user_info']))
            {
                $this->load->view('tweet/login/header');
                $this->load->view('tweet/message/not_match_mail');
                return FALSE;
            }
            
            
            if($data['user_info']['password']===$input["password"]){
                return TRUE;
            }
            else{
                $this->load->view('tweet/login/header');
                $this->load->view('tweet/message/not_match_pass');
                return FALSE;
            }
        }
        
        //メールアドレスのチェックメソッド
        //出典："http://phpspot.net/php/pg正規表現：メールアドレスかどうか調べる.html"
        private function is_mail($text) {
            if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $text)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }