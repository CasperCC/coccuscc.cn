<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    // 获取二级目录(接口)
    public function getCatalogs() {
        $this->load->model('category_model');

        $p_id = $this->input->post('p_id');

        if (!isset($p_id) || $p_id == "") {
            miss_params();
        }

        $catalogs = $this->category_model->getCategory($p_id);

        if (!isset($catalogs)) {
            db_error();
        }
        success_return($catalogs);
    }

    //获取目录信息列表(超级账户功能)
    public function getCategoryList() {
        $this->checkSvip();
        $this->load->model('category_model');

        $page = $this->input->get("page");
        $size = $this->input->get("size");

        $category = $this->category_model->getCatalogsList($page, $size);
        list_return($category["count"], $category["categoryinfo"]);
    }

    public function updateCategoryInfo() {
        $this->load->model('category_model');

        $c_name = $this->input->post('c_name');
        $s_id = $this->input->post('s_id');

        $result = $this->category_model->updateCatalogInfo($s_id, $c_name);

        if (!$result) {
            db_error();
        }
        success_return();
    }

    // 获取上级目录(接口)
    public function getParentCatalogs() {
        $this->load->model('category_model');

        $level = $this->input->post('level');
        $category = $this->category_model->getParentCatelogs($level);

        if (!isset($category)) {
            db_error();
        }
        success_return($category);
    }

    public function addCategory() {
        $this->load->model('category_model');

        $c_name = $this->input->post('c_name');
        $p_id = $this->input->post('p_id');
        $level = $this->input->post('level');

        if (!isset($c_name) || !isset($p_id) || !isset($level)) {
            miss_params();
        }

        $s_id = $this->category_model->addCatalogInfo($c_name, $p_id, $level);

        if (!isset($s_id)) {
            db_error();
        }
        success_return($s_id);
    }

    public function edit() {
        $this->checkSvip();
        $this->load->model('category_model');

        $s_id = $this->input->get('s_id');
        $info = $this->category_model->getCatalogInfo($s_id);

        $this->smarty->assign('info', $info);
        $this->smarty->display('admin/category/edit.html');
    }

    public function delete() {
        $this->checkSvip();
        $this->load->model('category_model');

        $s_id = $this->input->post('s_id');
        $result = $this->category_model->deleteCategory($s_id);

        if (!$result) {
            db_error();
        }
        success_return();
    }

    public function list() {
        $this->checkSvip();
        $this->smarty->display('admin/category/list.html');
    }

    public function add() {
        $this->checkSvip();
        $this->smarty->display('admin/category/add.html');
    }
}
