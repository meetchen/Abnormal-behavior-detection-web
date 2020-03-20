<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Exception;

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
        $result = Db::Query("select count(*) from user where user_user =? and user_password = ?", [$user, $pwd]);
        if ($result[0]["count(*)"] == 1) {
            $file = request()->file('attachment');
            $path = 'www.xust17.top/public/imageUser';
            $info = $file->move('../www.xust17.top/public/imageUser');

            if ($info) {
                $pathFile = $info->getSaveName();
            } else {
                return json_encode("图片上传出错，请重新再试",JSON_UNESCAPED_UNICODE);
            }
            try{
                $path = $path.'/'.$pathFile;
                if ($this->saveImageToDb($user,$path)==1){
                    return json_encode('异常上传成功，已更新用户数据库信息',JSON_UNESCAPED_UNICODE);
                }
            }catch (Exception $exception ){
                return json_encode('异常上传成功，已更新用户数据库信息',JSON_UNESCAPED_UNICODE);
            }
            return json_encode("更新用户数据库信息失败，请重新再试",JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode("用户名或密码错误",JSON_UNESCAPED_UNICODE);
        }
    }

    public function saveImageToDb($user,$path){
        return Db::table('abnormal')->insert(['abnormal_user'=>$user,'abnormal_image_url'=>$path]);
    }
}