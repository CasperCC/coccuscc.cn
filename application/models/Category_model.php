<?php

class Category_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getAllCategory() {
        $this->db->from('catalogs');
        $this->db->where('status !=', 0);
        $category = $this->db->get()->result_array();
        return $category;
    }

    public function getCatalogsList($page, $size) {
        $this->db->where('status !=', '0');
        $count = $this->db->count_all_results('catalogs', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $categoryinfo = $this->db->get()->result_array();
        foreach ($categoryinfo as &$value) {
            if($value["status"]==1) {
                $value["status"] = "正常";
            } else {
                $value["status"] = "系统错误！";
            }
            $this->db->from('catalogs');
            $this->db->select('c_name');
            $this->db->where('s_id', $value["p_id"]);
            $p_name = $this->db->get()->row_array();
            $value["p_name"] = $p_name["c_name"];
            $value["created"] = date("Y-m-d H:i:s", $value["created"]);
            $value["updated"] = date("Y-m-d H:i:s", $value["updated"]);
        }
        return array(
            'count' => $count,
            'categoryinfo' => $categoryinfo
        );
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