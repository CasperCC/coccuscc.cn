<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function list_return($count, $data)
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
