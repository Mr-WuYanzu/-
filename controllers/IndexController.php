<?php
namespace controllers;
use model\UserModel;
use libs\Request;
use controllers\CheckToken;
class IndexController extends CheckToken
{
    public function actionIndex()
    {
//        $model=new UserModel();
//        $sql='select * from __TABLE__';
//        print_r($model->query($sql));
//        dd($model->query($sql));
//        echo __METHOD__;
//        $request=new Request();
        echo __METHOD__;
//        dd($request->get());
    }
}