<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/20
 * Time: 11:56
 */

namespace controllers;


use libs\Http;
use libs\Request;

class HeadController
{
    public function actionIndex(){
        $request = new Request();
        dd($request->Head('Accept-Language'));
    }

    public function actionTest(){
        $http = new Http();
        $http->postCurl('http://www.apitest.com/index.php?c=head',[],false,["x-api-token: 1810b"]);
    }
}