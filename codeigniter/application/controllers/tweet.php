<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tweet extends CI_Controller
{
    
    //コンストラクタ
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tweet_model');
        $this->load->library('encrypt');
        $this->load->helper('url');
        $this->load->library('session');
    }
    
       
    //ログイン
    public function login()
    {
            
        $this->load->helper('form');
        
        $this->load->library("form_validation");
        $this->form_validation->set_rules('mailaddress', 'メールアドレス', 'required|valid_email');
        $this->form_validation->set_rules('password', 'パスワード', 'required|callback_pass_check');
        
        $this->session->set_userdata('user_status','Out');
        
        if ($this->form_validation->run() == false){
            $this->load->view('tweet/login/header');
            $this->load->view('tweet/login/login');
            $this->load->view('tweet/login/footer');
        } else {
            $address = $this->input->post('mailaddress');
            $info = $this->tweet_model->get_user_info_from_mail($address);
            $this->session->set_userdata('user_id',$info['user_id']);
            $this->session->set_userdata('user_status','Login');
            redirect('tweet/main','location');
        }
        
    }
    
    //メイン画面
    public function main()
    {
        $user_status = $this->session->userdata('user_status');
        if($user_status == 'Login'){
            
            $this->load->helper('form');
            $this->load->library("form_validation");
            
            $user_id =  $this->session->userdata('user_id');
            $user_data = $this->tweet_model->get_user_info_from_id($user_id);
            $this->load->view('tweet/main/header',$user_data);
            $this->load->view('tweet/main/main');
            $this->load->view('tweet/main/footer');
            $this->tweet_model->set_tweet();
            
        } else {
            redirect('tweet/login','location');
        }
    }
    
    //ユーザー登録
    public function registry()
    {
        
        $this->load->helper('form');
        $this->load->library("form_validation");
        
        $this->form_validation->set_rules('name', 'ユーザー名', 'required');
        $this->form_validation->set_rules('mailaddress', 'メールアドレス', 'required|valid_email|callback_mail_check');
        $this->form_validation->set_rules('password', 'パスワード', 'required|min_length[6]|alpha_numeric');
        $this->form_validation->set_rules('passconfirm', '確認用パスワード', 'required|matches[password]');
        
        if ($this->form_validation->run() == false){
            $this->load->helper('form');
            $this->load->view('tweet/registry/header');
            $this->load->view('tweet/registry/registry');
            $this->load->view('tweet/registry/footer');
        } else {
            $this->tweet_model->set_user_info();
            $address = $this->input->post('mailaddress');
            $info = $this->tweet_model->get_user_info_from_mail($address);
            $this->session->set_userdata('user_id',$info['user_id']);
            $this->session->set_userdata('user_status','Login');
            redirect('tweet/main','location');
        }
        
    }
    
    //パスワードチェック
    public function pass_check($input)
    {
        
        $address = $this->input->post('mailaddress');
        $info = $this->tweet_model->get_user_info_from_mail($address);
        
        if (empty($info)) {
            
            $this->form_validation->set_message('pass_check',"登録されたアドレスではありません");
            return  false;
            
        } else {
            $ency = hash("sha256",$input);
            if ($info['password'] === $ency) return true;
            else {
                $this->form_validation->set_message('pass_check',"パスワードが一致しません");
                return  false;
            }
        }
        return true;
    }
    
    //アドレスのチェック
    public function mail_check($input)
    {
        $info = $this->tweet_model->get_user_info_from_mail($input);
        
        if (empty($info)) return true;
        else {
            $this->form_validation->set_message('mail_check',"既に登録されているアドレスです");
            return false;
        }
    }
    
        
}