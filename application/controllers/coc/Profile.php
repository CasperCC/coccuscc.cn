<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    public function updateUserInfo() {
        $this->load->model('user_model');

        $uid = $this->uid;
        $nickname = $this->input->post('nickname');
        $result = $this->user_model->alterUserInfo($uid, $nickname);

        success_return();
    }

    public function updatePassword() {
        $this->load->model('user_model');

        $uid = $this->uid;
        $oldpassword = $this->input->post('oldpassword');
        $newpassword = $this->input->post('newpassword');
        $checknewpassword = $this->input->post('checknewpassword');
        $result = $this->user_model->alterPassword($uid, $oldpassword, $newpassword);

        if ($result) {
            success_return();
        }else {
            login_fail();
        }
    }

    public function userinfo() {
        $this->load->model('user_model');

        $uid = $this->uid;
        $userinfo = $this->user_model->getUserInfo($uid);

        $this->smarty->assign('userinfo', $userinfo);
        $this->smarty->display('admin/alter/userinfo.html');
    }

    public function password() {
        $this->load->model('user_model');

        $uid = $this->uid;
        $userinfo = $this->user_model->getUserInfo($uid);

        $this->smarty->assign('userinfo', $userinfo);
        $this->smarty->display('admin/alter/password.html');
    }
}
