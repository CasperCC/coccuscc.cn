<?php

class Post_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // 获取文章列表(超级用户功能)
    public function getArticlesList($page, $size) {
        $this->db->select('a_id, title, uid, author, type, status, created, updated');
        $this->db->where('status !=', 0);
        $count = $this->db->count_all_results('articles', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $postinfo = $this->db->get()->result_array();
        foreach ($postinfo as &$value)
        {
            if($value["status"]==1)
            {
                $value["status"] = "正常";
            }else if($value["status"]==2)
            {
                $value["status"] = "已封禁";
            }else
            {
                $value["status"] = "系统错误！";
            }
            $value["created"] = date("Y-m-d H:i:s", $value["created"]);
            $value["updated"] = date("Y-m-d H:i:s", $value["updated"]);
        }
        return array(
            'count' => $count,
            'postinfo' => $postinfo
        );
    }

    // 查看文章(通用)
    public function getArticle($a_id) {

        $this->db->select('articles.title, articles.author, articles.type_id, articles.type, articles.description, articles.content, articles.updated, catalogs.p_id');
        $this->db->from('articles');
        $this->db->join('catalogs', 'articles.type_id = catalogs.s_id');
        $this->db->where('a_id', $a_id);
        $postinfo = $this->db->get()->row_array();


        $this->db->from('catalogs');
        $this->db->select('s_id, c_name, p_id');
        $this->db->where('s_id', $postinfo["p_id"]);
        $parentinfo = $this->db->get()->row_array();

        $this->db->from('catalogs');
        $this->db->select('s_id, c_name, p_id');
        $this->db->where('s_id', $parentinfo["p_id"]);
        $grandparentinfo = $this->db->get()->row_array();

        return array(
            'postinfo' => $postinfo,
            'parentinfo' => $parentinfo,
            'grandparentinfo' => $grandparentinfo
        );
    }

    // 删除文章
    public function deleteArticle($a_id) {
        $this->db->set('status', 0);
        $this->db->set('updated', time());
        $this->db->where('a_id', $a_id);
        $this->db->update('articles');
        return true;
    }

}