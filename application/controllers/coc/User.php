<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function list() {
        $this->smarty->display('admin/user/list.html');
    }

    public function add() {
        $this->smarty->display('admin/user/add.html');
    }
}
