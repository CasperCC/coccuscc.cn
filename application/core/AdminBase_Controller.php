<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminBase_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->_checkseccion();
    }

    private function _checkseccion() {

        $this->load->library('session');
        $this->load->helper('url');

        $uid = $this->session->uid;
        $nickname = $this->session->nickname;
        $username = $this->session->username;
        $administrator = $this->session->administrator;

        if(!isset($uid) || !isset($nickname)){
            redirect('/login?redirect=/'.uri_string());
        }

        $this->uid = $uid;
        $this->nickname = $nickname;
        $this->username = $username;
        $this->administrator = $administrator;

        $this->smarty->assign('nickname', $nickname);
    }

    public function checkSvip() {
        $svip = $this->administrator;
        if ($svip == 0) {
            $this->smarty->display('admin/nosvip.html');
            exit();
        }
    }

}

