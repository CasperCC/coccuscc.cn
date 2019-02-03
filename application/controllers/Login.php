<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $redirect = $this->input->get("redirect") ? $this->input->get("redirect") : "/coc";

        $this->smarty->assign('redirect', $redirect);
        $this->smarty->display('main/pages/signin.html');
    }

    public function out(){
        $this->load->library('session');
        $this->load->helper('url');

        $this->session->unset_userdata('uid');
        $this->session->unset_userdata('nickname');
        redirect('/login');
    }

    public function getcode(){
        $this->load->library('session');
        $this->load->library('captcha');

        $captcha_value = $this->captcha->createImage(3, 4, 80, 40);
        $this->session->captcha = $captcha_value;
    }

    public function checklogin(){
        $this->load->library('session');
        $this->load->model('user_model');
        $this->load->helper('hashpass');

        $username = $this->input->post('usertag'); //用户输入的用户名
        $password = $this->input->post('password'); //用户输入的密码
        $captcha = $this->input->post('imgcode'); //用户输入的验证码

        // 检查用户名/密码/验证码是否为空
        if(!isset($username) || !isset($password) || !isset($captcha)){
            miss_params();
        }

        // 检查验证码
        if(strtolower($captcha) != strtolower($this->session->captcha)){
            imgcode_error();
        }

        $user = $this->user_model->get_user($username,$password); //返回true(信息匹配成功)，false(信息匹配失败)
        // 检查用户名或密码
        if(!$user){
            login_fail();
        }

        $this->session->unset_userdata('captcha');
        success_return();
    }

}
