<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function list_return($count, $data) //返回用户列表数据
{
    $ret = 0;
    $msg = 'ok';
    if (empty($data))
    {
        $output = array(
            'ret' => $ret,
            'msg' => $msg,
            'count' => $count
        );
    }else
    {
        $output = array(
            'ret' => $ret,
            'msg' => $msg,
            'count' => $count,
            'data' => $data
        );
    }
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($output);
    exit();
}

// 成功返回结果
function success_return($data="") {
    $ret = 0;
    $msg = 'ok';
    output_json($ret, $msg, $data);
    exit();
}

// 缺少参数返回结果
function miss_params() {
    $ret = -1;
    $msg = 'missing parameter';
    output_json($ret, $msg);
    exit();
}

// 图片验证码出错
function imgcode_error() {
    $ret = -2;
    $msg = 'imgcode error';
    output_json($ret, $msg);
    exit();
}

// 登录用户名密码不匹配
function login_fail() {
    $ret = -3;
    $msg = 'login failed';
    output_json($ret, $msg);
    exit();
}

// 数据库操作出错
function db_error() {
    $ret = -4;
    $msg = 'db fail';
    output_json($ret, $msg);
    exit();
}

// 用户名已存在
function username_exist() {
    $ret = -5;
    $msg = 'username already exists';
    output_json($ret, $msg);
    exit();
}

// 邮箱已存在
function email_exist() {
    $ret = -6;
    $msg = 'email already exists';
    output_json($ret, $msg);
    exit();
}

// 输出帮助函数
function output_json($ret, $msg, $data="") {
    if (empty($data)) {
        $output = array(
            'ret' => $ret,
            'msg' => $msg
        );
    } else {
        $output = array(
            'ret' => $ret,
            'msg' => $msg,
            'data' => $data
        );
    }
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($output);
    exit();
}

// 输出任意长度随机字符串
function getRandomStr($len, $special=true) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    if($special){
        $chars = array_merge($chars, array(
            "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
            "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
            "}", "<", ">", "~", "+", "=", ",", "."
        ));
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);                            //打乱数组顺序
    $str = '';
    for($i=0; $i<$len; $i++){
        $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
    }
    return $str;
}
