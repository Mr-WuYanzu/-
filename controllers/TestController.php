<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/6
 * Time: 14:02
 */

namespace controllers;
use libs\Config;
use libs\Http;
use libs\HttpClient;
use libs\Upload;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class TestController
{
    public function actionIndex(){
//////////////////////////////////
//        dd();                 //
//        if($_POST){           //
//            dd($_FILES);      //
//        }-+                   //
//        dd(request()->all()); //
//                              //
//////////////////////////////////
        include_once "./view/test.html";
        exit;
        $randNum=randomStr(16);//随机数
        $timestamp=time();//时间戳
        $key='1810b';
        $arr=[
            'randnum'=>$randNum,
            'timestamp'=>$timestamp,
            'key'=>$key,
            'user_name'=>'root',
            'password'=>'admin123'
        ];
        sort($arr,SORT_STRING);
        $str=implode($arr);
//        dd($str);
        echo $randNum;echo '<br>';
        echo $timestamp;echo '<br>';
        dd(sha1($str));
    }

    public function actionHttp(){

//            dd($_POST);

        $http=new HttpClient();
//        $url='http://test.1810.com/index.php?c=test&a=index';
        $url='https://test.1810.com/index.php?c=test&a=index';
        $res=$http->socketHttp($url,['user_name'=>'zhangsan']);
        if(substr($res['status'][1],0,1) == '2'){
            echo $res['body'];
        }else{
            echo $res['status'][1].":".$res['status'][2];
        }
    }

    // 多文件上传
    public function actionUpload(){
        $upload=new Upload();
        //设置参数
        $upload->setConfig('ext',['jpg','png','gif']);
        $upload->setConfig('mimeType',['image/jpeg','image/pneg','image/gif']);
        $res= $upload->uploadAll();
        if(!$res['res']){
            echo response()->json('20001','upload faild',$upload->uploadError());
        }else{
            echo response()->json('200','ok',['faild_num'=>$res['faild'],'data'=>$res['res']]);
        }
    }

    // 单文件上传
    public function actionUploadOne(){
        $upload=new Upload();
        //设置参数
        $upload->setConfig('ext',['jpg','png','gif']);
        $upload->setConfig('mimeType',['image/jpeg','image/pneg','image/gif']);
        $res= $upload->uploadOne();
        if(!$res){
            echo response()->json('20001','upload faild',$upload->uploadError());
        }else{
            echo response()->json('200','ok',['data'=>$res]);
        }
    }

    //curl文件上传
    public function actionUploadCurl(){
        $cfile = new \CURLFile('./upload/123.jpg','image/jpeg','123.jpg');
        $data=[
            'file'=>$cfile
        ];
        $http=new Http();
        $res=$http->postCurl('http://test.1810.com/index.php?c=upload&a=upload',$data,true);
    }

    //使用socket进行文件上传
    public function actionUploadS(){
        //报文内容
        $data = "----ABC\r\n";
        $data .= "Content-Disposition: form-data; name=\"username\"\r\n\r\n";
        $data .= "zhouguoqiang\r\n";

        $data .= "----ABC\r\n";
        $data .= "Content-Disposition: form-data; name=\"file\"; filename=\"bb.jpg\"\r\n";
        $data .= "Content-Type: image/jpeg\r\n\r\n";

        $data .= file_get_contents("./upload/bb.jpg");
        $data .= "\r\n----ABC--\r\n\r\n";
        $http = new HttpClient();

        $res = $http->fsockHttp('http://test.1810.com/index.php?c=upload&a=upload',$data,true);
       dd($res);
    }

    //文件上传到七牛云
    public function actionQiniu(){
        dd(config());
//        dd(1);
        $bucketName='test';
        $auth = new Auth(config('qiniu.AccessKey'),config('qiniu.SecretKey'));
        $token = $auth->uploadToken($bucketName);
//        $upManager = new UploadManager();
//        list($ret, $error) = $upManager->put($token, 'formput', 'hello world');
        include_once "view/upload.php";
    }
    public function actionJs(){
        $data=[
            'user_name'=>'root',
            'pwd'=>'123'
        ];
        $str=json_encode($data);
        echo "demo.$str";
    }
    public function actiondelDir(){
        unlink('./upload/test');
    }




}