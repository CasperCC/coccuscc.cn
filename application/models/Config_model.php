<?php

class Config_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // 获取所有系统设置
    public function getAllConfig() {
        $this->db->from('config');
        $this->db->select('config, description, content, updated');
        $configinfo = $this->db->get()->result_array();

        foreach ($configinfo as &$v) {
            $v["updated"] = date('Y-m-d H:i:s', $v["updated"]);
        }

        return $configinfo;
    }

    // 获取系统设置
    public function getConfig($id) {
        $this->db->from('config');
        $this->db->select('id, config, description, content, updated');
        $this->db->where('id', $id);
        $configinfo = $this->db->get()->row_array();
        $configinfo["updated"] = date('Y-m-d H:i:s', $configinfo["updated"]);

        return $configinfo;
    }

    // 编辑设置接口
    public function changeConfigInfo($id, $content) {
        $this->db->set('content', $content);
        $this->db->set('updated', time());
        $this->db->where('id', $id);
        $this->db->update('config');
        return true;
    }

    // 系统设置列表接口
    public function getConfigList($page, $size) {
        $count = $this->db->count_all_results('config', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $config = $this->db->get()->result_array();

        foreach ($config as &$v) {
            $v["updated"] = date('Y-m-d H:i:s', $v["updated"]);
        }

        return array(
            'count' => $count,
            'config' => $config
            );
    }

}