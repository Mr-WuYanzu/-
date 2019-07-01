<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/6
 * Time: 13:53
 */

namespace libs;


class Request
{
    protected $headers=[];
    public function __construct()
    {
        $this->getHeader();
    }

    public function all($data='',$arr=['a','c','access_token']){
        if($data!==''){
            return $_REQUEST[$data]??'';
        }else{
            $request=$_REQUEST;
            foreach ($arr as $v){
                if(isset($request[$v])){
                    unset($request[$v]);
                }
            }
            return $request;
        }
//        $request =  $_POST + $_GET;
//
//        if($data!=''){dd($_REQUEST);
//            return isset($request[$data]) ? $request[$data] : '';
//        }
//
//        foreach ($arr as $v){
//            if(isset($request[$v])){
//                unset($request[$v]);
//            }
//        }
//        return $request;
    }
    // 获取header头
    protected function getHeader(){
        $headers=[];
        isset($_SERVER['CONTENT_TYPE']) && $headers['Content-Type']=$_SERVER['CONTENT_TYPE'];
        isset($_SERVER['CONTENT_LENGTH']) && $headers['Content-Length']=$_SERVER['CONTENT_LENGTH'];
        foreach($_SERVER as $key=>$v){
            if(strpos($key,'HTTP_')===0){
                $k=$this->dealHead($key);
                if($k=="Authorization"){
                    $v=str_replace('Bearer ','',$v);
                }
                $headers[$k]=$v;
            }
        }
        $this->headers=$headers;
    }
    // 处理header头信息
    protected function dealHead($key){
        $k=str_replace('HTTP_','',$key);
        $arr=explode('_',$k);
        $k = array_map(function($v){
            return ucfirst(strtolower($v));
        },$arr);
        $k=implode('-',$k);
//        $k=ucfirst(strtolower($k));
        return $k;
    }

    public function Head($name=''){
        if($name){
            return isset($this->headers[$name]) ? $this->headers[$name] : '';
        }
        return $this->headers;
    }

    public function get($val=''){
        if($val!=''){
            $response=isset($_GET[$val])?$_GET[$val]:'';
            return $response;
        }
        return $_GET;

    }
    public function post($val=''){
        if($val!==''){
            return isset($_POST[$val])?$_POST[$val]:'';
        }
        return $_POST;
    }
}