<?php

namespace app\index\controller;

use think\Controller;

class Behavior extends Controller
{
    public function receiver()
    {

        $user = input("user");
        $pwd = input("pwd");
        $msg = input("msg");
        if ($user == null && $pwd == null && $msg == null) {
            return json_encode("请求消息有误");
        }
        $result = \Db::Query("select count(*) from user where user_user =? and user_password = ?", [$user, $pwd]);
        if ($result[0]["count(*)"] == 1) {
            return json_encode([
                'result' => '200',
                'msg' => '发送成功'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            return '用户名密码错误';
        }
    }
}