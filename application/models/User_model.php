<?php

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getUserList($page, $size) {
        $this->db->select('uid, username, nickname, email, locks, state, created, updated');
        $this->db->where('state !=', '3');
        $count = $this->db->count_all_results('users', FALSE);
        $this->db->limit($size, ($page-1)*$size);
        $userinfo = $this->db->get()->result_array();
        foreach ($userinfo as &$value)
        {
            if($value["state"]==1)
            {
                $value["state"] = "正常";
            }else if($value["state"]==2)
            {
                $value["state"] = "已冻结";
            }else
            {
                $value["state"] = "";
            }
            $value["created"] = date("Y-m-d H:i:s", $value["created"]);
            $value["updated"] = date("Y-m-d H:i:s", $value["updated"]);
        }
        return array(
            'count' => $count,
            'userinfo' => $userinfo
        );
    }

    public function deleteUser($uid) {
        if(!isset($uid)) {
            return false;
        }else {
            $this->db->set('state', 3);
            $this->db->set('updated', time());
            $this->db->where('uid', $uid);
            $this->db->update('users');
            return true;
        }
    }

    public function getUserInfo($uid) {
        $this->db->from('users');
        $this->db->select('username, nickname, email, locks, state, created, updated');
        $this->db->where('uid', $uid);
        $userinfo = $this->db->get()->row_array();

        if (!isset($userinfo["nickname"])) {
            $userinfo["nickname"] = $userinfo["username"];
        }

        if($userinfo["state"]==1) {
                $userinfo["state"] = "正常";
        }else if($userinfo["state"]==2) {
            $userinfo["state"] = "已冻结";
        }else {
            $userinfo["state"] = "系统错误！";
        }
        $userinfo["created"] = date("Y-m-d H:i:s", $userinfo["created"]);
        $userinfo["updated"] = date("Y-m-d H:i:s", $userinfo["updated"]);

        return $userinfo;
    }

    public function get_user($username,$password) {
        $this->load->helper('hashpass');
        $this->load->library('session');

        $this->db->from('users');
        $this->db->select('uid, username, nickname, salt, hash_password, locks, created');
        $this->db->group_start();
            $this->db->where('username', $username);
            $this->db->or_where('email', $username);
        $this->db->group_end();
        $this->db->group_start();
            $this->db->where('locks <=', '5');
            $this->db->or_where('updated <=', time()-3600);
        $this->db->group_end();
        $this->db->where('state', '1');
        $userinfo = $this->db->get()->row_array();
        if ( empty($userinfo) ) {
            return false;
        }

        $userhash = get_hashpassword($userinfo["uid"],$userinfo["username"],$password,$userinfo["salt"],$userinfo["created"]); //用户输入的密码(哈希)

        if(!isset($userinfo) || $userhash != $userinfo["hash_password"]) {
            $updateinfo = array(
                'locks'   => $userinfo["locks"]+1,
                'updated' => time()
            );
            $this->db->update('users',$updateinfo,'uid = '.$userinfo["uid"]);

            return false;
        }
        else
        {
            $this->session->uid = $userinfo["uid"];
            if(!isset($userinfo["nickname"]))
            {
                $this->session->nickname = $userinfo["username"];
            }
            else
            {
                $this->session->nickname = $userinfo["nickname"];
            }
            $updateinfo = array(
                'locks'   => '0',
                'state'   => '1',
                'updated' => time()
            );
            $this->db->update('users',$updateinfo,'uid = '.$userinfo["uid"]);

            return true;
        }

    }
}




