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
        //$this->load->library('javascript');
        //$this->load->library('jquery');
    }
    
       
    //ログイン
    public function login()
    {
            
        $this->load->helper('form');
        
        $this->load->library("form_validation");
        $this->form_validation->set_rules('mailaddress', 'メールアドレス', 'required|valid_email');
        $this->form_validation->set_rules('password', 'パスワード', 'required|callback_pass_check');
        
        
        if ($this->form_validation->run() == false) {
            $this->load->view('tweet/login/login');
        } else {
            $address = $this->input->post('mailaddress');
            $info = $this->tweet_model->get_user_info_from_mail($address);
            $this->session->set_userdata('user_id', $info['user_id']);
            redirect('tweet/main', 'location');
        }
        
    }
    
    //メイン画面
    public function main()
    {
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id)) {
            
            $this->load->helper('form');
            $this->load->library("form_validation");
            
            $data['user'] = $this->tweet_model->get_user_info_from_id($user_id);
            $data['tweet'] = $this->tweet_model->get_tweet_data($user_id);
            
            
            $this->load->view('tweet/main/main', $data);
            //$tweet = $this->input->post('tweet');
            //$this->tweet_model->set_tweet($user_id, $tweet);
            
        } else {
            redirect('tweet/login', 'location');
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
        
        if ($this->form_validation->run() == false) {
            $this->load->helper('form');
            $this->load->view('tweet/registry/registry');
        } else {
            $user_data = array(
                'name' => $this->input->post('name'),
                'mailaddress' => $this->input->post('mailaddress'),
                'password' => $this->input->post('password')
            );
            $uid = $this->tweet_model->set_user_info($user_data);
            $this->session->set_userdata('user_id', $uid);
            redirect('tweet/main', 'location');
        }
        
    }
    
    //最新のtweetのデータベースへの挿入
    public function insert_tweet()
    {
        $user_id = $this->session->userdata('user_id');
        $tweet = $this->input->post('tweet');
        $this->tweet_model->set_tweet($user_id, $tweet);
        
    }
    
    public function send_tweet()
    {
        $user_id = $this->session->userdata('user_id');
        $data = $this->tweet_model->get_nth_tweet($user_id);
        
        $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($data));
         
    }
    
    //パスワードチェック
    public function pass_check($input)
    {
        
        $address = $this->input->post('mailaddress');
        $info = $this->tweet_model->get_user_info_from_mail($address);
        
        if (empty($info)) {
            
            $this->form_validation->set_message('pass_check', "登録されたアドレスではありません");
            return  false;
            
        } else {
            $ency = hash("sha256",$input);
            if ($info['password'] === $ency) {
                return true;
            } else {
                $this->form_validation->set_message('pass_check', "パスワードが一致しません");
                return  false;
            }
        }
        return true;
    }
    
    //アドレスのチェック
    public function mail_check($input)
    {
        $info = $this->tweet_model->get_user_info_from_mail($input);
        
        if (empty($info)) {
            return true;
        }else {
            $this->form_validation->set_message('mail_check', "既に登録されているアドレスです");
            return false;
        }
    }
    
        
}