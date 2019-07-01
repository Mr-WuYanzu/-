<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/13
 * Time: 16:57
 */

namespace controllers;


use libs\UploadBig;
use libs\Http;
use libs\Upload;

class UploadController
{
    //使用base64的方式上传文件
    public function actionUpload(){
        dd(file_get_contents('php://input'));
        dd($_FILES);
        $upload=new Upload();
        //设置参数
        $upload->setConfig('ext',['jpg','png','gif']);
        $upload->setConfig('mimeType',['image/jpeg','image/pneg','image/gif']);
        $res= $upload->uploadAll();
        if(!$res){
            echo response()->json('20001','upload faild',$upload->uploadError());
        }else{
//            echo "<script>alert('".json_encode($res)."');</script>";
            echo response()->json('200','ok',[$res]);
        }
    }
    public function actionFileUpload(){
        $upload=new UploadBig();
        $res= $upload->upload();
        if(!$res){
            echo response()->json('20001','upload faild',$upload->uploadError());
        }else{
            if(isset($res['currentChunk'])){
                echo response()->json('201','ok',[$res]);die;
            }
            echo response()->json('200','ok',[$res]);
        }
    }
    public function actionTest(){
        $file = $_FILES;
        $fileName = $_POST['name']; //文件名
        $size = $_POST['size']; //文件总大6                                                                 小
        $currentChunk = $_POST['currentChunk'];//当前文件段数
        $chunks = $_POST['chunks']; //总段数
        $fileInfo = base64_encode(file_get_contents($file['file']['tmp_name']));
        $data=[
            'name'=>$fileName,
            'size'=>$size,
            'currentChunk'=>$currentChunk,
            'chunks'=>$chunks,
            'fileInfo'=>$fileInfo
        ];
        $http=new Http();
        $res=$http->postCurl('http://test.1810.com/index.php?c=upload&a=fileUpload',$data);
//        dd($res);
    }
}