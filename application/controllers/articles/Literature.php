<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Literature extends Base_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('post_model');

        $catalog = "文学";
        $articles = $this->post_model->getCatalogs($catalog);
        $type = $this->checkCatalog($catalog);
        array_pop($type);

        $this->smarty->assign('articles', $articles);
        $this->smarty->assign('catalog', $catalog);
        $this->smarty->assign('type', $type);
        $this->smarty->display('main/pages/catalogs/catalogview.html');
    }

    public function prose($cata=false) {
        $this->load->model('post_model');

        $catalog = $cata ? $cata : "散文";
        $articles = $this->post_model->getCatalogs($catalog);
        $type = $this->checkCatalog($catalog);
        array_pop($type);

        $this->smarty->assign('articles', $articles);
        $this->smarty->assign('catalog', $catalog);
        $this->smarty->assign('type', $type);
        $this->smarty->display('main/pages/catalogs/catalogview.html');
    }
}