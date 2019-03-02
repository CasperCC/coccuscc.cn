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

    public function getCatalogInfo($s_id) {
        $this->db->select('s_id, c_name, p_id, created, updated');
        $this->db->from('catalogs');
        $this->db->where('status !=', 0);
        $this->db->where('s_id', $s_id);
        $info = $this->db->get()->row_array();
        $info["created"] = date("Y-m-d H:i:s", $info["created"]);
        $info["updated"] = date("Y-m-d H:i:s", $info["updated"]);
        return $info;
    }

    public function updateCatalogInfo($s_id, $c_name) {
        $this->db->set('c_name', $c_name);
        $this->db->set('updated', time());
        $this->db->where('s_id', $s_id);
        $this->db->update('catalogs');

        $this->db->set('type', $c_name);
        $this->db->where('type_id', $s_id);
        $this->db->update('articles');
        return true;
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

    public function addCatalogInfo($c_name, $p_id, $level) {
        $info = array(
            'c_name' => $c_name,
            'p_id' => $p_id,
            'level' => $level,
            'created' => time(),
            'updated' => time()
        );
        $this->db->insert('catalogs', $info);
        $s_id = $this->db->insert_id();

        if ($level == 2) {
            $childinfo = array(
                'c_name' => "默认目录",
                'p_id' => $s_id,
                'level' => 3,
                'created' => time(),
                'updated' => time()
            );
            $this->db->insert('catalogs', $childinfo);
            $s_id = $this->db->insert_id();
        }
        return $s_id;
    }

    public function getParentCatelogs($level) {
        $this->db->select('s_id, c_name');
        $this->db->from('catalogs');
        $this->db->where('level', $level);
        $category = $this->db->get()->result_array();
        return $category;
    }

    public function getCategoryArticles($type_id, $page, $size) {
        $this->db->where('status !=', 0);
        $this->db->where('type_id', $type_id);
        $count = $this->db->count_all_results('articles', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $articlesinfo = $this->db->get()->result_array();
        foreach ($articlesinfo as &$v) {
            $v["updated"] = date("Y-m-d H:i:s", $v["updated"]);
        }
        return array(
            'count' => $count,
            'articlesinfo' => $articlesinfo
        );
    }

    public function getCategory($p_id) {
        $this->db->from('catalogs');
        $this->db->where('status', 1);
        $this->db->where('p_id', $p_id);
        $category = $this->db->get()->result_array();
        return $category;
    }

    public function deleteCategory($s_id) {
        $this->db->set('status', 0);
        $this->db->set('updated', time());
        $this->db->where('s_id', $s_id);
        $this->db->update('catalogs');

        $this->db->set('status', 0);
        $this->db->set('updated', time());
        $this->db->where('type_id', $s_id);
        $this->db->update('articles');
        return true;
    }

}