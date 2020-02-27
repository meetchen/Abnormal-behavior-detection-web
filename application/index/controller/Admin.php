<?php
namespace app\index\controller;
use think\Controller;

class Admin extends Controller
{
    public function ceshi()
    {
    	$user = input("id");
        $password = input("pwd");
        $dbvar = \Db::Query("select count(*) from xust_user where user_user =? and user_password = ?",[$user,$password]);
		if ($dbvar[0]["count(*)"] == 1) {
			return 'Y';
		} else {
			return 'N';
		}


    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}