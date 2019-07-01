<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/10
 * Time: 18:34
 */

namespace controllers;
use libs\Controllers;
use model\UserModel;


class CheckToken extends Controllers
{
    public $user;
    public function __construct()
    {
        //继承父类的构造方法
        parent::__construct();
        //验证token
        $this->checkToken();
    }
    //验证token
    public function checkToken(){
        $model=new UserModel();
        $token=request()->head('Authorization') ? request()->head('Authorization') : (request()->all('access_token') ? request()->all('access_token') : "");

        $res=$model->checkToken($token);
        if(is_string($res)){
            echo response()->json(10005,$res);die;
        }else if(is_null($res)){
            echo response()->json(1002,'NOT EXIST USER');die;
        }
        $this->user=$res;
    }
}