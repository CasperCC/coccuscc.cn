<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->smarty->display('main/pages/signin.html');
    }

    public function getcode()
    {
        $this->load->library('session');
        $this->load->library('captcha');

        $captcha_value = $this->captcha->createImage(3, 4, 80, 40);
        $this->session->captcha = $captcha_value;
    }

    public function checklogin()
    {
        $this->load->library('session');
        // $this->load->helper('output');

        $username = $this->input->post('usertag');
        $password = $this->input->post('password');
        $captcha = $this->input->post('imgcode');

        echo $username;echo $password;echo $captcha;
        if(!isset($captcha))
        {
            miss_params();
        }

        if(strtolower($captcha) != strtolower($this->session->captcha))
        {
            imgcode_error();
        }

        success_return();
    }

}
