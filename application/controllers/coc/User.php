<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    public function getUserList()
    {
        $this->load->model('user_model');

        $page = $this->input->get("page");
        $size = $this->input->get("size");

        $users = $this->user_model->getUserList($page, $size);
        list_return($users["count"], $users["userinfo"]);
    }

    public function deleteUser()
    {
        $this->load->model('user_model');

        $uid = $this->input->post("uid");

        $result = $this->user_model->deleteUser($uid);
        if ($result) {
            success_return($result);
        }else {
            miss_params();
        }
    }

    public function list() {

        $this->smarty->display('admin/user/list.html');
    }

    public function add() {
        $this->smarty->display('admin/user/add.html');
    }
}
