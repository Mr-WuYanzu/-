<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/10
 * Time: 15:54
 */

namespace controllers;
use libs\Controllers;
use model\UserModel;
use redis;

class UserController
{
    public function actionLogin(){
        $redis=new Redis();
        $redis->connect('127.0.0.1',6379) or die('redis connect fail');
        dd($redis);
        die;
        $user_name=request()->all('user_name');
        $password = request()->all('password');
        //验证参数是否齐全
        if($user_name=='' || $password==''){
            echo response()->json(1001,'Parameter is not complete');
        }
        $model=new UserModel();
        //获取用户信息
        $user_info = $model->getUserInfoByName($user_name);
        if($user_info==''){
            echo response()->json(1002,'NOT EXIST USER');
        }
        //验证密码
        if(!password_verify($password,$user_info['password'])){
            echo response()->json(1003,'PASSWORD ERROR');
        }
        //登录成功  生成token
        $token=$model->createToken($user_info['uid']);
        
        echo response()->json(200,'ok',['token'=>$token]);
    }
}