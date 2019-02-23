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

    // 封禁文章(超级用户功能)
    public function frozen() {
        $this->load->model('post_model');

        $a_id = $this->input->post('a_id');
        $result = $this->post_model->frozenArticle($a_id);

        if ($result) {
            success_return();
        }
    }

    // 解封文章(超级用户功能)
    public function unfrozen() {
        $this->load->model('post_model');

        $a_id = $this->input->post('a_id');
        $result = $this->post_model->unfrozenArticle($a_id);

        if ($result) {
            success_return();
        }
    }

    // 更改文章信息(通用)
    public function updatePostInfo() {
        $this->load->model('post_model');

        $a_id = $this->input->post('a_id');
        $title = $this->input->post('title');
        $firstcatalog = $this->input->post('firstcatalog');
        $secondcatalog = $this->input->post('secondcatalog');
        $thirdcatalog = $this->input->post('thirdcatalog');

        $result = $this->post_model->changePostInfo($a_id, $title, $firstcatalog, $secondcatalog, $thirdcatalog);
        if ($result) {
            success_return();
        }
    }

    // 获取需要更改文章的信息
    public function updatePostContent() {
        $this->load->model('post_model');

        $a_id = $this->input->get('a_id');
        $postinfo = $this->post_model->getPostInfo($a_id);

        $this->smarty->assign('postinfo', $postinfo);
        $this->smarty->display('admin/post/editcontent.html');
    }

    // 更改文章接口
    public function changePostContent() {
        $this->load->model('post_model');

        $a_id = $this->input->post('a_id');
        $content = $this->input->post('content');

        $result = $this->post_model->changePostContent($a_id, $content);
        if ($result) {
            success_return();
        }
    }

    public function edit() {
        $this->load->model('post_model');

        $a_id = $this->input->get('a_id');
        $postinfo = $this->post_model->getPostInfo($a_id);

        $this->smarty->assign('postinfo', $postinfo);
        $this->smarty->display('admin/post/editsvip.html');
    }

    public function list() {
        $this->smarty->display('admin/post/list.html');
    }

    public function add() {
        $this->smarty->display('admin/post/add.html');
    }
}
