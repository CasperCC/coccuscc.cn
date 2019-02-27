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

    public function list() {
        $this->smarty->display('admin/category/list.html');
    }

    public function add() {
        $this->smarty->display('admin/category/add.html');
    }
}
