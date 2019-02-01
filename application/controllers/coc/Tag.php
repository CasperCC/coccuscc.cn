<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function list() {
        $this->smarty->display('admin/tag/list.html');
    }

    public function add() {
        $this->smarty->display('admin/tag/add.html');
    }
}
