<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }
    //获取用户信息列表(超级账户功能)
    public function getUserList() {
        $this->checkSvip();
        $this->load->model('user_model');

        $page = $this->input->get("page");
        $size = $this->input->get("size");

        $users = $this->user_model->getUserList($page, $size);
        list_return($users["count"], $users["userinfo"]);
    }
    //删除账户(超级账户功能)
    public function deleteUser() {
        $this->checkSvip();
        $this->load->model('user_model');

        $uid = $this->input->post("uid");

        $result = $this->user_model->deleteUser($uid);
        if ($result) {
            success_return($result);
        }else {
            miss_params();
        }
    }
    //显示编辑用户信息页面(超级账户功能)
    public function edit() {
        $this->checkSvip();
        $this->load->model('user_model');

        $uid = $this->input->get('uid');
        $userinfo = $this->user_model->getUserInfo($uid);

        $this->smarty->assign('userinfo', $userinfo);
        $this->smarty->display('admin/user/edit.html');
    }
    //编辑用户信息(超级账户功能)
    public function updateUserInfo() {
        $this->checkSvip();
        $this->load->model('user_model');

        $username = $this->input->post('username');
        $nickname = $this->input->post('nickname');
        $oldpassword = $this->input->post('oldpassword');
        $newpassword = $this->input->post('newpassword');

        $result = $this->user_model->editUser($username, $nickname, $oldpassword, $newpassword);

        if ($result) {
            success_return();
        } else {
            login_fail();
        }
    }
    //解锁账户(超级账户功能)
    public function unlock() {
        $this->checkSvip();
        $this->load->model('user_model');

        $username = $this->input->post('username');

        $result = $this->user_model->clearLocks($username);

        if ($result) {
            success_return();
        } else{
            miss_params();
        }
    }
    //冻结账户(超级账户功能)
    public function frozen() {
        $this->checkSvip();
        $this->load->model('user_model');

        $username = $this->input->post('username');

        $result = $this->user_model->frozen($username);

        if ($result) {
            success_return();
        } else{
            miss_params();
        }
    }
    //解冻账户(超级账户功能)
    public function unfrozen() {
        $this->checkSvip();
        $this->load->model('user_model');

        $username = $this->input->post('username');

        $result = $this->user_model->unfrozen($username);

        if ($result) {
            success_return();
        } else{
            miss_params();
        }
    }

    public function list() {
        $this->checkSvip();
        $this->smarty->display('admin/user/list.html');
    }

    public function add() {
        $this->smarty->display('admin/user/add.html');
    }
}
