<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        // $this->_catalog();
        $this->_tag();
        $this->_config();
        $this->_checkCatalog();
        $query = "";
        $this->smarty->assign('title', $query);
    }

    private function _catalog() {
        $this->db->from('catalogs');
        $this->db->select('c_name');
        $this->db->where('level', 2);
        $cataloginfo = $this->db->get()->result_array();

        $this->smarty->assign('catalogs', $cataloginfo);
    }

    private function _config() {
        $this->load->model('config_model');

        $configinfo = $this->config_model->getAllConfig();

        $this->smarty->assign('config', $configinfo);
    }

    private function _tag() {
        $this->load->model('tag_model');

        $count = $this->tag_model->getTagCount();

        $this->smarty->assign('tag', $count);
    }

    // public function checkCatalog($type) {
    //     $this->db->from('catalogs');
    //     $this->db->select('s_id, c_name, p_id, level, url');
    //     $this->db->where('s_id', $type);
    //     $this->db->or_where('c_name', $type);
    //     $info = $this->db->get()->row_array();
    //     $parentinfo[$info["level"]] = $info;

    //     for ($i=$parentinfo[$info["level"]]["level"]; $i > 1; $i--) {
    //         $this->db->from('catalogs');
    //         $this->db->select('s_id, c_name, p_id, level, url');
    //         $this->db->where('s_id', $parentinfo[$i]["p_id"]);
    //         $parentinfo[$i-1] = $this->db->get()->row_array();
    //     }
    //     $parentinfo = array_reverse($parentinfo);
    //     return $parentinfo;
    // }
    private function _checkCatalog() {
        $this->load->model('category_model');

        $catalogs = $this->category_model->getAllCategory();
        $catalogList = array();

        foreach ($catalogs as $key => $value) {
            if ($value["level"] == 1) {
                $catalogList[$value["s_id"]] = $catalogs[$key];
            } else if ($value["level"] == 2) {
                $catalogList[$value["p_id"]]["child"][$value["s_id"]] = $catalogs[$key];
            } else {
                $catalogList[1]["child"][$value["p_id"]]["child"][$value["s_id"]] = $catalogs[$key];
            }
        }

        $this->smarty->assign('catalogList', $catalogList);
    }
}
