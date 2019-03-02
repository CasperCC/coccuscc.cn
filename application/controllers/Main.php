<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends Base_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load->model('post_model');

        $page = $this->input->get('page');
        $size = 4;
        if (!isset($page)) {
            $page = 1;
        }

        $info = $this->post_model->getArticles($page, $size);

        $this->smarty->assign('page', $page);
        $this->smarty->assign('articles', $info["postinfo"]);
        $this->smarty->display('main/pages/page.html');
    }

    public function pagination() {
        $this->load->model('main_model');

        $info = $this->main_model->getCount();
        $info = ceil($info/4);
        list_return($info);
    }

    public function query() {
        $this->load->model('main_model');

        $title = $this->input->get('title');
        $page = $this->input->get('page');
        $size = 4;
        if (!isset($page)) {
            $page = 1;
        }
        $query = $this->main_model->getQuery($title, $page, $size);

        $this->smarty->assign('result', $query["query"]);
        $this->smarty->assign('title', $title);
        $this->smarty->assign('page', $page);
        $this->smarty->display('main/pages/queryview.html');
    }

    public function queryPagination() {
        $this->load->model('main_model');

        $page = $this->input->post('page');
        $size = 4;
        $title = $this->input->post('title');
        if (!isset($page)) {
            $page = 1;
        }

        $info = $this->main_model->getQuery($title, $page, $size);
        $total = ceil($info["count"]/$size);
        list_return($total);
    }

    public function view() {
        $this->load->model('post_model');
        $this->load->library('session');

        $a_id = $this->input->get('a_id');
        $info = $this->post_model->getArticle($a_id);
        $category = $this->post_model->getArticleCatalog($a_id);

        // 检查用户查看文章权限
        $vip = $this->session->administrator;
        if ($info["status"] == "2" && $vip != "1") {
            lack_permission();
        }

        $this->smarty->assign('postinfo', $info);
        $this->smarty->assign('category', $category);
        $this->smarty->display('main/pages/articleview.html');
    }

    public function tag() {
        $this->load->model('tag_model');

        $t_id = $this->input->get('t_id');
        $t_name = $this->input->get('t_name');
        $page = $this->input->get('page');
        $size = 4;
        if (!isset($page)) {
            $page = 1;
        }
        $info = $this->tag_model->getTagArticles($t_id, $page, $size);

        $this->smarty->assign('articles', $info["articles"]);
        $this->smarty->assign('tag_name', $t_name);
        $this->smarty->assign('page', $page);
        $this->smarty->assign('t_id', $t_id);
        $this->smarty->display('main/pages/tags/tagview.html');
    }

    public function tagPagination() {
        $this->load->model('tag_model');

        $t_id = $this->input->post('t_id');
        $page = $this->input->get('page');
        $size = 4;
        if (!isset($page)) {
            $page = 1;
        }
        $info = $this->tag_model->getTagArticles($t_id, $page, $size);
        $total = ceil($info["count"]/$size);
        list_return($total);
    }

    public function category() {
        $this->load->model('category_model');

        $type_id = $this->input->get('s_id');
        $catalog = $this->input->get('catalog');
        $page = $this->input->get('page');
        $size = 4;
        if (!isset($page)) {
            $page = 1;
        }
        $info = $this->category_model->getCategoryArticles($type_id, $page, $size);

        $this->smarty->assign('articles', $info["articlesinfo"]);
        $this->smarty->assign('catalog', $catalog);
        $this->smarty->assign('page', $page);
        $this->smarty->assign('type_id', $type_id);
        $this->smarty->display('main/pages/catalogs/catalogview.html');
    }

    public function catalogPagination() {
        $this->load->model('category_model');

        $type_id = $this->input->post('s_id');
        $page = $this->input->get('page');
        $size = 4;
        if (!isset($page)) {
            $page = 1;
        }
        $info = $this->category_model->getCategoryArticles($type_id, $page, $size);
        $total = ceil($info["count"]/$size);
        list_return($total);
    }

}
