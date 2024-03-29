<?php

class Post_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // 获取用户个人文章(普通用户功能)
    public function getUserArticlesList($page, $size, $uid) {
        $this->db->select('a_id, title, uid, author, type, t_name, status, created, updated');
        $this->db->where('uid', $uid);
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

    // 获取文章列表(超级用户功能)
    public function getArticlesList($page, $size) {
        $this->db->select('a_id, title, uid, author, type, t_name, status, created, updated');
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

    public function getArticles($page, $size) {
        $this->db->select('a_id, title, uid, author, type, t_name, description, created, updated');
        $this->db->where('status !=', 0);
        $count = $this->db->count_all_results('articles', FALSE);
        $this->db->order_by('updated', 'desc');
        $this->db->limit($size, ($page-1)*$size);
        $postinfo = $this->db->get()->result_array();
        foreach ($postinfo as &$value)
        {
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
        $this->db->select('articles.title, articles.author, articles.type_id, articles.type, articles.description, articles.content, articles.status, articles.updated, catalogs.p_id');
        $this->db->from('articles');
        $this->db->join('catalogs', 'articles.type_id = catalogs.s_id');
        $this->db->where('a_id', $a_id);
        $this->db->where('articles.status !=', 0);
        $this->db->where('catalogs.status !=', 0);
        $postinfo = $this->db->get()->row_array();
        return $postinfo;
    }

    // 获取文章信息(通用)
    public function getPostInfo($a_id) {
        $this->db->select('a_id, title, uid, author, type_id, type, t_id, description, content, status, created, updated');
        $this->db->from('articles');
        $this->db->where('a_id', $a_id);
        $postinfo = $this->db->get()->row_array();
        if($postinfo["status"]==1) {
            $postinfo["status"] = "正常";
        } else if($postinfo["status"]==2) {
            $postinfo["status"] = "已封禁";
        } else {
            $postinfo["status"] = 0;
        }
        $postinfo["created"] = date("Y-m-d H:i:s", $postinfo["created"]);
        $postinfo["updated"] = date("Y-m-d H:i:s", $postinfo["updated"]);
        return $postinfo;
    }

    // 获取文章目录
    public function getArticleCatalog($a_id) {
        $this->db->select('articles.type_id, articles.type, catalogs.p_id');
        $this->db->from('articles');
        $this->db->join('catalogs', 'articles.type_id = catalogs.s_id');
        $this->db->where('a_id', $a_id);
        $this->db->where('articles.status !=', 0);
        $this->db->where('catalogs.status !=', 0);
        $cataloginfo = $this->db->get()->row_array();
        $this->db->from('catalogs');
        $this->db->select('c_name');
        $this->db->where('s_id', $cataloginfo["p_id"]);
        $this->db->where('status', 1);
        $c_name = $this->db->get()->row_array();
        return array('cataloginfo' => $cataloginfo, 'parentinfo' => $c_name);
    }

    // 获取所有文章信息(普通用户功能)
    public function getAllArticles() {
        $this->db->from('articles');
        $this->db->select('a_id, title, author, description, updated');
        $this->db->where('status', 1);
        $postinfo = $this->db->get()->result_array();
        foreach ($postinfo as &$v) {
            $v["updated"] = date("Y-m-d H:i:s", $v["updated"]);
        }
        return $postinfo;
    }

    // 封禁文章(超级用户功能)
    public function frozenArticle($a_id) {
        $this->db->set('status', 2);
        $this->db->set('updated', time());
        $this->db->where('a_id', $a_id);
        $this->db->update('articles');
        return true;
    }

    // 解封文章(超级用户功能)
    public function unfrozenArticle($a_id) {
        $this->db->set('status', 1);
        $this->db->set('updated', time());
        $this->db->where('a_id', $a_id);
        $this->db->update('articles');
        return true;
    }

    // 更改文章信息(通用)
    public function changePostInfo($a_id, $title, $third, $thirdname, $description, $t_id, $t_name) {
        $this->db->set('title', $title);
        $this->db->set('type_id', $third);
        $this->db->set('type', $thirdname);
        $this->db->set('t_id', $t_id);
        $this->db->set('t_name', $t_name);
        $this->db->set('description', $description);
        $this->db->set('updated', time());
        $this->db->where('a_id', $a_id);
        $this->db->update('articles');
        return true;
    }

    // 更改文章内容
    public function changePostContent($a_id, $content) {
        $this->db->set('content', $content);
        $this->db->set('updated', time());
        $this->db->where('a_id', $a_id);
        $this->db->update('articles');
        return true;
    }

    // 删除文章
    public function deleteArticle($a_id) {
        $this->db->set('status', 0);
        $this->db->set('updated', time());
        $this->db->where('a_id', $a_id);
        $this->db->update('articles');
        return true;
    }

    public function addArticleInfo($uid, $author, $title, $description, $content, $type_id, $type, $t_id, $t_name) {
        $postinfo = array(
            'title' => $title,
            'uid' => $uid,
            'author' => $author,
            'content' => $content,
            'type_id' => $type_id,
            'type' => $type,
            't_id' => $t_id,
            't_name' => $t_name,
            'description' => $description,
            'created' => time(),
            'updated' => time()
        );
        $this->db->insert('articles', $postinfo);
        return true;
    }

    public function addArticleContent($a_id, $content) {
        $this->db->set('content', $content);
        $this->db->set('updated', time());
        $this->db->where('a_id', $a_id);
        $this->db->update('articles');
        return true;
    }

}