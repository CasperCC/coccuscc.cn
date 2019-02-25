<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->checkSvip();
        $this->smarty->display('admin/sysconf/setting.html');
    }

    // 编辑页面
    public function edit() {
        $this->checkSvip();
        $this->load->model('config_model');

        $id = $this->input->get('id');

        $config = $this->config_model->getConfig($id);

        $this->smarty->assign('config', $config);
        $this->smarty->display('admin/sysconf/edit.html');
    }

    // 编辑接口
    public function updateConfigInfo() {
        $this->load->model('config_model');

        $id = $this->input->post('id');
        $content = $this->input->post('content');

        $result = $this->config_model->changeConfigInfo($id, $content);

        if (!$result) {
            db_error();
        }
        success_return();
    }

    // 列表接口
    public function configListInterface() {
        $this->load->model('config_model');

        $page = $this->input->get('page');
        $size = $this->input->get('size');

        $config = $this->config_model->getConfigList($page, $size);

        if (!isset($config)) {
            miss_params();
        }

        list_return($config["count"], $config["config"]);
    }
}
