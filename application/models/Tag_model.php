<?php

class Tag_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getTagListInterface($page, $size) {
        $this->db->where('status !=', '0');
        $count = $this->db->count_all_results('tags', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $tagsinfo = $this->db->get()->result_array();

        foreach ($tagsinfo as &$value) {
            if($value["status"]==1) {
                $value["status"] = "正常";
            } else {
                $value["status"] = "系统错误！";
            }
            $value["created"] = date("Y-m-d H:i:s", $value["created"]);
            $value["updated"] = date("Y-m-d H:i:s", $value["updated"]);
        }

        return array(
            'count' => $count,
            'tagsinfo' => $tagsinfo
        );
    }

    public function getTagsInfo() {
        $this->db->select('t_id, t_name');
        $this->db->from('tags');
        $this->db->where('status !=', 0);
        $tagsinfo = $this->db->get()->result_array();
        return $tagsinfo;
    }

    public function getTagInfo($t_id) {
        $this->db->from('tags');
        $this->db->where('status !=', 0);
        $this->db->where('t_id', $t_id);
        $taginfo = $this->db->get()->row_array();
        $taginfo["created"] = date("Y-m-d H:i:s", $taginfo["created"]);
        $taginfo["updated"] = date("Y-m-d H:i:s", $taginfo["updated"]);
        return $taginfo;
    }

    public function getTagCount() {
        $this->db->select('tags.t_id, tags.t_name, count(*) as count');
        $this->db->from('articles');
        $this->db->join('tags', 'tags.t_id=articles.t_id');
        $this->db->where('tags.status', 1);
        $this->db->where('articles.status', 1);
        $this->db->group_by('tags.t_id');
        $info = $this->db->get()->result_array();
        return $info;
    }

    public function addTagInfo($t_name) {
        $info = array(
            't_name' => $t_name,
            'created' => time(),
            'updated' => time()
        );
        $this->db->insert('tags', $info);
        return true;
    }

    public function getTagArticles($t_id, $page, $size) {
        $this->db->where('status !=', 0);
        $this->db->where('t_id', $t_id);
        $count = $this->db->count_all_results('articles', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $articlesinfo = $this->db->get()->result_array();
        foreach ($articlesinfo as &$v) {
            $v["updated"] = date("Y-m-d H:i:s", $v["updated"]);
        }
        return array(
            'articles' => $articlesinfo,
            'count' => $count
        );
    }

    public function updateTag($t_id, $t_name) {
        $this->db->set('t_name', $t_name);
        $this->db->set('updated', time());
        $this->db->where('t_id', $t_id);
        $this->db->update('tags');
        return true;
    }

    public function deleteTag($t_id) {
        $this->db->set('status', 0);
        $this->db->set('updated', time());
        $this->db->where('t_id', $t_id);
        $this->db->update('tags');
        return true;
    }

}