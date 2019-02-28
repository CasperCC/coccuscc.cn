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

    public function list() {
        $this->checkSvip();
        $this->smarty->display('admin/category/list.html');
    }

    public function add() {
        $this->checkSvip();
        $this->smarty->display('admin/category/add.html');
    }
}
