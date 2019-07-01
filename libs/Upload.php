<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/13
 * Time: 10:24
 */

namespace libs;


class Upload
{
    protected $config=[
        'savePath'=>'./upload',//文件路径
        'size'=>-1,//限制文件上传大小
        'ext'=>[],//文件后缀
        'mimeType'=>[],//文件类型

    ];
    public $error=0;
    public function __construct($config=[])
    {
        $this->config=array_merge($this->config,$config);
    }
    // 配置参数设置
    public function setConfig($key,$val){
        $this->config[$key]=$val;
    }

    //单文件上传
    public function uploadOne($file=''){
            foreach ($_FILES as $v){
                $file=$v;
            }
            return $this->upload($file);
    }
    //多文件上传
    public function uploadAll(){
        foreach ($_FILES as $k=>$v){
            $file=$v;
        }
        $files=[];
        $faild=0;
        foreach ($file['name'] as $k=>$v){
            $arr=[
                'name'=>$v,
                'type'=>$file['type'][$k],
                'tmp_name'=>$file['tmp_name'][$k],
                'error'=>$file['error'][$k],
                'size'=>$file['size'][$k],
            ];
            $res=$this->upload($arr);
            if(!$res){
                $faild=$faild+1;
                continue;
            }
            $files[]=$res;
        }
        return ['res'=>$files,'faild'=>$faild];
    }
    // base64编码形式文件上传
    public function uploadByBase64($name,$size,$file_Name){

        if(!$this->fileSize($size)){
            $this->error=8;
            return;
        }

        // 3 判断文件后缀
        if(!$this->fileExt($name)){
            $this->error=9;
            return;
        }
        // 5 获取文件名
        $fileName=$this->newFileName($name);
        // 6 上传
        //判断上传目是否存在  不存在则创建
        if(!is_dir($this->config['savePath'])){
            mkdir($this->config['savePath'],0777,true);
        }

        $res=file_put_contents($this->config['savePath'].DIRECTORY_SEPARATOR.$fileName,base64_decode($file_Name));
        if(!$res){
            $this->error=12;
            return;
        }
        // 7 返回路径名
        return ['name'=>$name,'size'=>$size,'path'=>$this->config['savePath'],'fileName'=>$fileName];

    }

    //核心文件上传
    public function upload($file=[]){
        // 1 判断是否上传出错

        if($file['error']){
            $this->error=$file['error'];
            return $this->uploadError();
        }
//        echo 1;die;
        // 2 判断文件大小
        if(!$this->fileSize($file['size'])){
            $this->error=8;
            return;
        }

        // 3 判断文件后缀
        if(!$this->fileExt($file['name'])){
            $this->error=9;
            return;
        }
        // 4 判断文件类型
        if(!$this->fileType($file['tmp_name'],$file['type'])){
            $this->error=10;
            return;
        }
        // 5 获取文件名
        $fileName=$this->newFileName($file['name']);
        // 6 上传
                //判断上传目是否存在  不存在则创建
                if(!is_dir($this->config['savePath'])){
                    mkdir($this->config['savePath'],0777,true);
                }

        if(!is_uploaded_file($file['tmp_name'])){
            $this->error=11;
            return;
        }else{
            if(!move_uploaded_file($file['tmp_name'],$this->config['savePath'].DIRECTORY_SEPARATOR.$fileName)){
                $this->error=12;
                return;
            }else{
                // 7 返回路径名
                return ['name'=>$file['name'],'size'=>$file['size'],'path'=>$this->config['savePath'],'fileName'=>$fileName];
            }
        }

    }

    //文件错误码
    public function uploadError(){
        $error=$this->error;
        $errorStr='';
        switch ($error){
            case UPLOAD_ERR_INI_SIZE:
                $errorStr='上传的文件大小超过了php配置的最大值';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $errorStr='上传文件的大小超过了HTML表单中MAX_FILE_SIZE的值';
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorStr='文件只有部分被上传';
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorStr='没有文件被上传';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $errorStr='找不到临时文件夹';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $errorStr='文件写入失败';
                break;
            case 8:
                $errorStr='文件大小超过配置';
                break;
            case 9:
                $errorStr='文件后缀名不符合规定';
                break;
            case 10:
                $errorStr='文件类型不合法';
                break;
            case 11:
                $errorStr='文件来源不合法';
                break;
            case 12:
                $errorStr='上传失败';
                break;
        }
        return $errorStr;die;
    }
    //判断文件大小
    protected function fileSize($size){
        if($this->config['size']== -1){
            return true;
        }else{
            return $this->config['size']>=$size? true: false;
        }
    }
    //判断文件后缀
    protected function fileExt($name){
        $ext=$this->getFileExt($name);
        return in_array($ext,$this->config['ext']);
    }

    //判断文件类型
    protected function fileType($name,$type){
//        $file_type= mime_content_type($name);
//        if($file_type == $type){
            return in_array($type,$this->config['mimeType']);
//        }else{
//            return false;
//        }

    }

    //生成文件名
    protected function newFileName($name){
        $ext=$this->getFileExt($name);
        return uniqid().randomStr(5).'.'.$ext;
    }

    //获取文件后缀名
    protected function getFileExt($name){
        return pathinfo($name,PATHINFO_EXTENSION);
    }
}