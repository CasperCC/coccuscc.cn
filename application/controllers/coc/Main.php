<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('main_model');

        $u_id = $this->uid;
        $count = $this->main_model->getNewCount();
        $countnovip = $this->main_model->getNewCountNoVIP($u_id);
        $svip = $this->administrator;

        if ($svip == 1) {
            $this->smarty->assign('count', $count);
            $this->smarty->display('admin/indexsvip.html');
        } else {
            $this->smarty->assign('count', $countnovip);
            $this->smarty->display('admin/index.html');
        }
    }

    public function detail() {
        $this->load->model('main_model');

        $type = $this->input->get('type');
        $svip = $this->administrator;

        if ($svip == 1) {
            if ($type == "user") {
                $this->smarty->display('admin/latest/newUserList.html');
            } elseif ($type == "article") {
                $this->smarty->display('admin/latest/newArticleList.html');
            } elseif ($type == "tag") {
                $this->smarty->display('admin/latest/newTagList.html');
            } else {
                $this->smarty->display('admin/latest/newCategoryList.html');
            }
        } else {
            $this->smarty->display('admin/latest/newArticleListnovip.html');
        }
    }

    public function newUser() {
        $this->load->model('main_model');

        $page = $this->input->get('page');
        $size = $this->input->get('size');

        $list = $this->main_model->getUserList($page, $size);
        list_return($list["count"], $list["userinfo"]);
    }

    public function newArticle() {
        $this->load->model('main_model');

        $page = $this->input->get('page');
        $size = $this->input->get('size');

        $list = $this->main_model->getArticleList($page, $size);
        list_return($list["count"], $list["postinfo"]);
    }

    public function newTag() {
        $this->load->model('main_model');

        $page = $this->input->get('page');
        $size = $this->input->get('size');

        $list = $this->main_model->getTagList($page, $size);
        list_return($list["count"], $list["tagsinfo"]);
    }

    public function newCategory() {
        $this->load->model('main_model');

        $page = $this->input->get('page');
        $size = $this->input->get('size');

        $list = $this->main_model->getCategoryList($page, $size);
        list_return($list["count"], $list["categoryinfo"]);
    }

    public function newArticleNoVIP() {
        $this->load->model('main_model');

        $page = $this->input->get('page');
        $size = $this->input->get('size');
        $u_id = $this->uid;

        $list = $this->main_model->getCategoryListNoVIP($page, $size, $u_id);
        list_return($list["count"], $list["postinfo"]);
    }
}
