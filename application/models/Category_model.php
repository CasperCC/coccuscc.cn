<?php

class Category_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getAllCategory() {
        $this->db->from('catalogs');
        $category = $this->db->get()->result_array();
        return $category;
    }

    public function getCategoryArticles($type_id) {
        $this->db->from('articles');
        $this->db->where('status !=', 0);
        $this->db->where('type_id', $type_id);
        $articlesinfo = $this->db->get()->result_array();
        foreach ($articlesinfo as &$v) {
            $v["updated"] = date("Y-m-d H:i:s", $v["updated"]);
        }
        return $articlesinfo;
    }

    public function getCategory($p_id) {
        $this->db->from('catalogs');
        $this->db->where('status', 1);
        $this->db->where('p_id', $p_id);
        $category = $this->db->get()->result_array();
        return $category;
    }

}