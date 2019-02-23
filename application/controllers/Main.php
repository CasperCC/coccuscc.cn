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
        $uid = $this->session->uid;
        if ($info["status"] == "2" && $uid != "1") {
            lack_permission();
        }
        $parentinfo = $this->checkCatalog($info["p_id"]);

        $this->smarty->assign('postinfo', $info);
        $this->smarty->assign('parentinfo', $parentinfo);
        $this->smarty->display('main/pages/articleview.html');
    }

}
