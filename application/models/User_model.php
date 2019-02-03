<?php

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_user($username,$password)
    {
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

        if(!isset($userinfo) || $userhash != $userinfo["hash_password"])
        {
            $updateinfo = array(
                'locks'   => $userinfo["locks"]+1,
                'updated' => time()
            );
            $this->db->update('users',$updateinfo);
            $this->db->where('uid', $userinfo["uid"]);

            return false;
        }
        else
        {
            $this->session->uid = $userinfo["uid"];
            if(!isset($userinfo["nickname"]))
            {
                $this->session->nickname = $userinfo["username"];
            }
            $this->session->nickname = $userinfo["nickname"];
            $updateinfo = array(
                'locks'   => '0',
                'state'   => '1',
                'updated' => time()
            );
            $this->db->update('users',$updateinfo);
            $this->db->where('uid', $userinfo["uid"]);

            return true;
        }

    }
}




