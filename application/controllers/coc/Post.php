<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends AdminBase_Controller {

    function __construct() {
        parent::__construct();
    }

    public function list() {
        $this->smarty->display('admin/post/list.html');
    }

    public function add() {
        $this->smarty->display('admin/post/add.html');
    }
}
