<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    public function getTagList() {
        $this->checkSvip();
        $this->load->model('tag_model');

        $page = $this->input->get("page");
        $size = $this->input->get("size");

        $tags = $this->tag_model->getTagListInterface($page, $size);
        list_return($tags["count"], $tags["tagsinfo"]);
    }

    public function getTags() {
        $this->load->model('tag_model');

        $tagsinfo = $this->tag_model->getTagsInfo();
        success_return($tagsinfo);
    }

    public function delete() {
        $this->load->model('tag_model');

        $t_id = $this->input->post('t_id');

        $result = $this->tag_model->deleteTag($t_id);
        if (!$result) {
            db_error();
        }
        success_return();
    }

    public function updateTagInfo() {
        $this->load->model('tag_model');

        $t_id = $this->input->post('t_id');
        $t_name = $this->input->post('t_name');

        $result = $this->tag_model->updateTag($t_id, $t_name);
        if (!$result) {
            db_error();
        }
        success_return();
    }

    public function addTag() {
        $this->load->model('tag_model');

        $t_name = $this->input->post('t_name');

        $result = $this->tag_model->addTagInfo($t_name);
        if (!$result) {
            db_error();
        }
        success_return();
    }

    public function edit() {
        $this->load->model('tag_model');

        $t_id = $this->input->get('t_id');
        $info = $this->tag_model->getTagInfo($t_id);

        $this->smarty->assign('info', $info);
        $this->smarty->display('admin/tag/edit.html');
    }

    public function list() {
        $this->checkSvip();
        $this->smarty->display('admin/tag/list.html');
    }

    public function add() {
        $this->smarty->display('admin/tag/add.html');
    }
}
