<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_hashpassword($uids,$usernames,$passwords,$salts,$created)
{
    $passwordstr = "uid=".$uids."username=".$usernames."password=".$passwords."salt=".$salts."created=".$created;
    for($i = 0;$i < 10;$i++)
    {
        $hash_password = hash('sha256',$passwordstr);
    }
    return $hash_password;
}
