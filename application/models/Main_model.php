<?php

class Main_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getQuery($title, $page, $size) {
        $this->db->select('a_id, title, author, description, updated');
        $this->db->like('title', $title);
        $this->db->or_like('description', $title);
        $this->db->or_like('content', $title);
        $this->db->or_like('author', $title);
        $count = $this->db->count_all_results('articles', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $query = $this->db->get()->result_array();
        foreach ($query as &$v) {
            $v["updated"] = date("Y-m-d H:i:s", $v["updated"]);
        }

        return array(
            'count' => $count,
            'query' => $query
        );
    }

    public function getNewCount() {
        $this->db->where('created >=', strtotime("-1 month"));
        $count["user"] = $this->db->count_all_results('users');

        $this->db->where('created >=', strtotime("-1 month"));
        $count["category"] = $this->db->count_all_results('catalogs');

        $this->db->where('created >=', strtotime("-1 month"));
        $count["article"] = $this->db->count_all_results('articles');

        $this->db->where('created >=', strtotime("-1 month"));
        $count["tag"] = $this->db->count_all_results('tags');

        return $count;
    }

    public function getCount() {
        $this->db->where('status', 1);
        $count = $this->db->count_all_results('articles');
        return $count;
    }

    public function getNewCountNoVIP($u_id) {
        $this->db->where('uid', $u_id);
        $this->db->where('created >=', strtotime("-1 month"));
        $count["article"] = $this->db->count_all_results('articles');

        return $count;
    }

    public function getUserList($page, $size) {
        $this->db->select('uid, username, nickname, email, locks, state, created, updated');
        $this->db->where('state !=', '0');
        $this->db->where('created >=', strtotime("-1 month"));
        $count = $this->db->count_all_results('users', FALSE);
        $this->db->order_by('created', "desc");
        $this->db->limit($size, ($page-1)*$size);
        $userinfo = $this->db->get()->result_array();
        foreach ($userinfo as &$value)
        {
            if($value["state"]==1)
            {
                $value["state"] = "正常";
            }else if($value["state"]==2)
            {
                $value["state"] = "已封禁";
            }else
            {
                $value["state"] = "系统错误！";
            }
            $value["created"] = date("Y-m-d H:i:s", $value["created"]);
            $value["updated"] = date("Y-m-d H:i:s", $value["updated"]);
        }
        return array(
            'count' => $count,
            'userinfo' => $userinfo
        );
    }

    public function getArticleList($page, $size) {
        $this->db->select('a_id, title, uid, author, type, t_name, status, created, updated');
        $this->db->where('status !=', 0);
        $this->db->where('created >=', strtotime("-1 month"));
        $count = $this->db->count_all_results('articles', FALSE);
        $this->db->order_by('created', "desc");
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

    public function getTagList($page, $size) {
        $this->db->where('status !=', '0');
        $this->db->where('created >=', strtotime("-1 month"));
        $count = $this->db->count_all_results('tags', FALSE);
        $this->db->order_by('created', "desc");
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

    public function getCategoryList($page, $size) {
        $this->db->where('status !=', '0');
        $this->db->where('created >=', strtotime("-1 month"));
        $count = $this->db->count_all_results('catalogs', FALSE);
        $this->db->order_by('created', "desc");
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

    public function getCategoryListNoVIP($page, $size, $u_id) {
        $this->db->select('a_id, title, uid, author, type, t_name, status, created, updated');
        $this->db->where('uid', $u_id);
        $this->db->where('status !=', 0);
        $this->db->where('created >=', strtotime("-1 month"));
        $count = $this->db->count_all_results('articles', FALSE);
        $this->db->order_by('created', "desc");
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

}