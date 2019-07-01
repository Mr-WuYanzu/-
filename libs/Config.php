<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/14
 * Time: 18:42
 */

namespace libs;


class Config
{
    private $config=[];
    private static $instance;
    private function __construct($path='')
    {
        $paths=[
            DEFAULT_PATH,
            $path
        ];
        $configs=[];
        //循环取出文件内容
        foreach($paths as $file){
            $files=$this->getFile($file);
            foreach ($files as $k=>$v){
                $configs = array_merge($configs,$this->getParseFile($v));
            }
        }
        $this->config=$configs;
    }

    //获取文件里面的内容
    private function getParseFile($file_name=''){
        return parse_ini_file($file_name,true);
    }
    private function getFile($dir){
        $files=[];
        if(is_dir($dir)){
            if(($fd=opendir($dir))!==false){
                while(($file=readdir($fd))!==false){
                    $path=$dir.DIRECTORY_SEPARATOR.$file;
                    if($file != '.' && $file != '..'){
                        if(is_dir($path)){
                            $arr=$this->getFile($path);
                            foreach ($arr as $v){
                                $files[]=$v;
                            }
                        }else{
                            $files[]=$path;
                        }
                    }
                }
            }
        }
        var_dump($files);
        return $files;
    }
    //获取配置
    public function getConfig($name=''){
        if($name){
            $arr=explode('.',$name);

            if(count($arr)==1 && $arr[0]){
                return isset($this->config[$arr[0]])?$this->config[$arr[0]]:null;
            }
            return isset($this->config[$arr[0]][$arr[1]])?$this->config[$arr[0]][$arr[1]]:null;
        }
        return $this->config;
    }
    //实例化类
    public static function getInstance(){
        if(!self::$instance instanceof self){
            self::$instance = new self;
        }
        return self::$instance;
    }
}