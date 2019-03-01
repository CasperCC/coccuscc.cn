<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends Base_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load->model('post_model');

        $articles = $this->post_model->getAllArticles();

        $this->smarty->assign('articles', $articles);
        $this->smarty->display('main/pages/page.html');
    }


    public function view() {
        $this->load->model('post_model');
        $this->load->library('session');

        $a_id = $this->input->get('a_id');
        $info = $this->post_model->getArticle($a_id);

        // 检查用户查看文章权限
        $vip = $this->session->administrator;
        if ($info["status"] == "2" && $vip != "1") {
            lack_permission();
        }

        $this->smarty->assign('postinfo', $info);
        $this->smarty->display('main/pages/articleview.html');
    }

    public function tag() {
        $this->load->model('tag_model');

        $t_id = $this->input->get('t_id');
        $t_name = $this->input->get('t_name');
        $info = $this->tag_model->getTagArticles($t_id);

        $this->smarty->assign('articles', $info);
        $this->smarty->assign('tag', $t_name);
        $this->smarty->display('main/pages/tags/tagview.html');
    }

    public function category() {
        $this->load->model('category_model');

        $type_id = $this->input->get('s_id');
        $catalog = $this->input->get('catalog');
        $info = $this->category_model->getCategoryArticles($type_id);

        $this->smarty->assign('articles', $info);
        $this->smarty->assign('catalog', $catalog);
        $this->smarty->display('main/pages/catalogs/catalogview.html');
    }

}
