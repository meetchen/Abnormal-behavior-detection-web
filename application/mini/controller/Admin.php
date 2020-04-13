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

    /**
     * @return int 0无异常
     * 否则返回异常图片的url
     */
    public function isAbnormal(){
        $user = input("user");
        $result = Db::table('abnormal')->where('abnormal_user',$user)->column('abnormal_image_url');
        if ($result){
            return $result[0]['abnormal_image_url'];
        }else{
            return 0;
        }
    }

    /**
     * 删除用户异常
     *  1 删除成功
     *  0 删除失败
     */
    public function delAbnormal(){
        $user = input("user");
        return Db::table('abnormal')->delete($user);
    }

    /**
     * 存储用户评价
     * @return int 0 成功  1 失败
     */
    public function userComment(){
        $userId = input("user_id");
        $userComment = input("user_comment");
        try{
            if (db::name("user_comment")->insert(['user_id'=>$userId,'user_comment'=>$userComment])==1){
                return 0;
            }else{
                return 1;
            }
        }catch (Exception $exception){
            return 1;
        }
    }

    /**
     * 查询用户评价信息
     * @return array|int  1 表示异常或不存在用户评价信息
     *                     或返回用户评价 数组
     */
    public function  selectUserComment(){
        $userId = input("user_id");
        try{
            $result = db::table("user_comment")->where('user_id',$userId)->column("user_comment");
            return $result;
        }catch (Exception $exception){
            return 1;
        }

    }
}