<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->smarty->display('main/pages/page1.html');
    }

}
