<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    public function getPostList() {
        $this->load->model('post_model');

        $page = $this->input->get('page');
        $size = $this->input->get('size');

        $posts = $this->post_model->getArticlesList($page, $size);
        list_return($posts["count"], $posts["postinfo"]);
    }

    public function deletePost() {
        $this->load->model('post_model');

        $a_id = $this->input->post('a_id');

        $result = $this->post_model->deleteArticle($a_id);

        if ($result) {
            success_return();
        }
    }

    public function edit() {
        $a_id = $this->input->get('a_id');

        $this->smarty->display('admin/post/edit.html');
    }

    public function view() {
        $this->load->model('post_model');

        $a_id = $this->input->get('a_id');
        $info = $this->post_model->getArticle($a_id);
        $postinfo = $info["postinfo"];
        $parentinfo = $info["parentinfo"];
        $grandparentinfo = $info["grandparentinfo"];

        $this->smarty->assign('postinfo', $postinfo);
        $this->smarty->assign('parentinfo', $parentinfo);
        $this->smarty->assign('grandparentinfo', $grandparentinfo);
        $this->smarty->display('main/pages/articleview.html');
    }

    public function list() {
        $this->smarty->display('admin/post/list.html');
    }

    public function add() {
        $this->smarty->display('admin/post/add.html');
    }
}
