<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/13
 * Time: 10:24
 */

namespace libs;


class UploadBig
{
    protected $config=[
        'savePath'=>'',//文件路径
        'size'=>-1,//限制文件上传大小
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

    public function upload(){
        $fileName = $_POST['name']; //文件名
        $size = $_POST['size']; //文件总大小
        $currentChunk = $_POST['currentChunk'];//当前文件段数
        $chunks = $_POST['chunks']; //总段数
        if(!$this->fileSize($size)){
            $this->error=8;
            return;
        }
        $this->config['savePath']='./upload'.DIRECTORY_SEPARATOR.md5($fileName);
        // 6 上传
        //判断上传目是否存在  不存在则创建
        if(!is_dir($this->config['savePath'])){
            mkdir($this->config['savePath'],0777,true);
        }

        $fileInfo=base64_decode($_POST['fileInfo']);
        $res=file_put_contents($this->config['savePath'].DIRECTORY_SEPARATOR.$currentChunk.'.tmp',$fileInfo);
            $path=[];
            //取出里面文件
            if($fd=opendir($this->config['savePath'])){
                while(($file=readdir($fd))!==false){
                    if($file!=='.'&&$file!=='..'){
                        $path[]=$file;
                    }
                }
            }
            if(count($path)==$chunks){
                $sizeCount=0;
                foreach($path as $v){
                    $sizeCount = $sizeCount+filesize($this->config['savePath'].DIRECTORY_SEPARATOR.$v);
                }
                if($sizeCount!=$size){
                    $this->error=12;
                    return;
                }else{
                    $file_info='';
                    foreach ($path as $v){
                        $file_info .= file_get_contents($this->config['savePath'].DIRECTORY_SEPARATOR.$v);
                    }
                    $newname=$this->newFileName($fileName);
                    $res=file_put_contents('./upload'.DIRECTORY_SEPARATOR.$newname,$file_info);
                    if($res!==0){
//                        unlink($this->config['savePath']);
                        //上传成功
                        return ['name'=>$fileName,'size'=>$size,'path'=>'./upload','fileName'=>$newname];
                    }
                }
            }else{
                if($res!==0){
                    return ['currentChunk'=>$currentChunk];
                }
            }

        // 7 返回路径名
//        return ['name'=>filename,'size'=>$size,'path'=>$this->config['savePath'],'fileName'=>$fileName];

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