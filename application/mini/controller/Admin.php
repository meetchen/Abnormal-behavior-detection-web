<?php

namespace app\mini\controller;

use think\Controller;
use think\Db;
use think\Exception;

class Admin extends Controller
{

    /**
     * 用户登录
     * @return int|mixed 登陆成功返回设备号
     * 用户名不存在或密码错误返回0
     * 用户未注册设备返回-1
     */
    public function login()
    {
        $user = input("user");
        $pwd = input("pwd");
        if ($user == null || $pwd == null)
            return 1;
        $isExist = Db::query("select user_id from user where user_user= ? and user_password = ?", [$user, $pwd]);
        if ($isExist == null)
            return 0;
        $isPass = Db::Query("select equipment_id FROM equipment_user WHERE user_id = (SELECT user_id from `user` 
                                    WHERE user_user= ? and user_password = ?) ", [$user, $pwd]);
        if ($isPass != null)
            return $isPass[0]['equipment_id'];
        return -1;
    }

    /**
     *
     * 用户注册
     * @return int 0 成功
     *          1 传入数据有误
     *          -1 用户名重复
     */
    public function register()
    {
        $userName = input("user");
        $password = input("pwd");
        $email = input("email");
        if ($userName == null || $password == null || $email == null)
            return 1;
        $data = ['user_user' => $userName, 'user_password' => $password, 'user_email' => $email];
        try {
            if (db::name('user')->where('user_user', $userName)->find() != null)
                return -1;
            if (db::name('user')->insert($data) == 1)
                return 0;
            else
                return 1;
        } catch (Exception $exception) {
            return 1;
        }
    }



}