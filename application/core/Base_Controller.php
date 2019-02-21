<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->_catalog();
        $this->_config();
    }

    private function _catalog() {
        $this->db->from('catalogs');
        $this->db->select('c_name, url');
        $this->db->where('level', 2);
        $cataloginfo = $this->db->get()->result_array();

        $this->smarty->assign('catalogs', $cataloginfo);
    }

    private function _config() {
        $this->db->from('config');
        $this->db->select('config, description, content');
        $configinfo = $this->db->get()->result_array();

        $this->smarty->assign('config', $configinfo);
    }

    public function checkCatalog($type) {
        $this->db->from('catalogs');
        $this->db->select('s_id, c_name, p_id, level, url');
        $this->db->where('s_id', $type);
        $this->db->or_where('c_name', $type);
        $info = $this->db->get()->row_array();
        $parentinfo[$info["level"]] = $info;

        for ($i=$parentinfo[$info["level"]]["level"]; $i > 1; $i--) {
            $this->db->from('catalogs');
            $this->db->select('s_id, c_name, p_id, level, url');
            $this->db->where('s_id', $parentinfo[$i]["p_id"]);
            $parentinfo[$i-1] = $this->db->get()->row_array();
        }
        $parentinfo = array_reverse($parentinfo);
        return $parentinfo;
    }
}
