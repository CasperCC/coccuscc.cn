<?php

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //锁定计数清零(超级账户功能)
    public function clearLocks($username) {
        $this->db->set('locks', 0);
        $this->db->set('updated', time());
        $this->db->where('username', $username);
        $this->db->update('users');
        return true;
    }
    //冻结账户(超级账户功能)
    public function frozen($username) {
        $this->db->set('state', 2);
        $this->db->set('updated', time());
        $this->db->where('username', $username);
        $this->db->update('users');
        return true;
    }
    //解冻账户(超级账户功能)
    public function unfrozen($username) {
        $this->db->set('state', 1);
        $this->db->set('updated', time());
        $this->db->where('username', $username);
        $this->db->update('users');
        return true;
    }
    //获取用户信息列表(超级账户功能)
    public function getUserList($page, $size) {
        $this->db->select('uid, username, nickname, email, locks, state, created, updated');
        $this->db->where('state !=', '0');
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
    //删除账户(超级账户功能)
    public function deleteUser($uid) {
        if(!isset($uid)) {
            return false;
        }else {
            $this->db->set('state', 0);
            $this->db->set('updated', time());
            $this->db->where('uid', $uid);
            $this->db->update('users');
            return true;
        }
    }

    public function addUser($username, $email, $nickname, $password) {
        $this->load->helper('hashpass');

        // 检查用户名是否存在
        $this->db->from('users');
        $this->db->where('username', $username);
        $usertag = $this->db->get()->row_array();
        if (isset($usertag)) {
            return -5;
        }

        // 检查邮箱是否存在
        $this->db->from('users');
        $this->db->where('email', $email);
        $useremail = $this->db->get()->row_array();
        if (isset($useremail)) {
            return -6;
        }

        $salt = getRandomStr(32, false);
        $userinfo = array(
            'username' => $username,
            'nickname' => $nickname,
            'email' => $email,
            'salt' => $salt,
            'created' => time(),
            'updated' => time()
        );
        $this->db->insert('users', $userinfo);

        // 获取新用户uid和创建时间
        $this->db->from('users');
        $this->db->select('uid, created');
        $this->db->where('username', $username);
        $user = $this->db->get()->row_array();

        $hashpass = get_hashpassword($user["uid"], $username, $password, $salt, $user["created"]);

        // 更新用户密码
        $this->db->set('hash_password', $hashpass);
        $this->db->where('uid', $user["uid"]);
        $this->db->update('users');

        return true;
    }

    //更改账户信息(超级账户功能)
    public function editUser($username, $nickname, $oldpassword, $newpassword) {
        $this->load->helper('hashpass');

        $this->db->from('users');
        $this->db->select('uid, salt, hash_password, created');
        $this->db->where('username', $username);
        $userinfo = $this->db->get()->row_array();
        $olduserhash = get_hashpassword($userinfo["uid"],$username,$oldpassword,$userinfo["salt"],$userinfo["created"]);
        $newuserhash = get_hashpassword($userinfo["uid"],$username,$newpassword,$userinfo["salt"],$userinfo["created"]);

        if (!isset($oldpassword) || $oldpassword == "") {
            return true;
        } elseif ($olduserhash != $userinfo["hash_password"]) {
            return false;
        } else {
            $this->db->set('nickname', $nickname);
            $this->db->set('hash_password', $newuserhash);
            $this->db->set('updated', time());
            $this->db->where('uid', $userinfo["uid"]);
            $this->db->update('users');
            return true;
        }
    }

    //更改用户信息(暂时只支持更改昵称功能)
    public function alterUserInfo($uid, $nickname) {
        $this->db->set('nickname', $nickname);
        $this->db->set('updated', time());
        $this->db->where('uid', $uid);
        $this->db->update('users');
        return true;
    }

    public function alterPassword($uid, $oldpassword, $newpassword)
    {
        $this->load->helper('hashpass');

        $this->db->from('users');
        $this->db->select('username, salt, hash_password, created');
        $this->db->where('uid', $uid);
        $userinfo = $this->db->get()->row_array();
        $olduserhash = get_hashpassword($uid,$userinfo["username"],$oldpassword,$userinfo["salt"],$userinfo["created"]);
        $newuserhash = get_hashpassword($uid,$userinfo["username"],$newpassword,$userinfo["salt"],$userinfo["created"]);

        if ($olduserhash != $userinfo["hash_password"]) {
            return false;
        } else {
            $this->db->set('hash_password', $newuserhash);
            $this->db->set('updated', time());
            $this->db->where('uid', $uid);
            $this->db->update('users');
            return true;
        }
    }

    //获取用户信息
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
            $userinfo["state"] = "已封禁";
        }else {
            $userinfo["state"] = "系统错误！";
        }
        $userinfo["created"] = date("Y-m-d H:i:s", $userinfo["created"]);
        $userinfo["updated"] = date("Y-m-d H:i:s", $userinfo["updated"]);

        return $userinfo;
    }
    //检查账户信息是否匹配
    public function get_user($username,$password) {
        $this->load->helper('hashpass');
        $this->load->library('session');

        $this->db->from('users');
        $this->db->select('uid, username, nickname, salt, hash_password, locks, administrator, created');
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
            $this->db->set('locks', $userinfo["locks"]+1);
            $this->db->set('updated', time());
            $this->db->where('uid', $userinfo["uid"]);
            $this->db->update('users');
            return false;
        }
        else
        {
            $this->session->uid = $userinfo["uid"];
            $this->session->administrator = $userinfo["administrator"];
            if(!isset($userinfo["nickname"])) {
                $this->session->nickname = $userinfo["username"];
            } else {
                $this->session->nickname = $userinfo["nickname"];
            }
            $this->db->set('locks', 0);
            $this->db->set('updated', time());
            $this->db->where('uid', $userinfo["uid"]);
            $this->db->update('users');

            return true;
        }

    }
}




