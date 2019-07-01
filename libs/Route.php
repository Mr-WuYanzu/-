<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/5
 * Time: 19:33
 */

namespace libs;


class Route
{
    protected $c='index';
    protected $a='index';
    //控制器后缀   Controller;
    //方法前缀      action;
    public function routePare(){ 

        list($c,$a) = $this->getRoute();
        
        $a!=='' && $this->a=$a;
        $c!=='' && $this->c=$c;

        $this->c=ucfirst($this->c).'Controller';
        $this->a='action'.ucfirst($this->a);
        return [$this->c,$this->a];
    }
    
    public function getRoute(){
        list($controller,$action) = $this->getRouteUrl();
        $controller || $action || list($controller,$action)=$this->getRouteByPath();
        $controller || $action || list($controller,$action)=$this->getRouteByRequest();
        return [$controller,$action];
    }
    //通过地址栏上的参数c和a获取对应的控制器和方法
    public function getRouteUrl(){
         $controller = request()->all('c');
        $action = request()->all('a')??'index';
        return [$controller,$action];
    }
    // 通过pathinf获取路径
    public function getRouteByPath(){
        $controller = '';
        $action = '';
        if(isset($_SERVER['PATH_INFO'])){
            $pathinfo = explode('/',$_SERVER['PATH_INFO']);
            for($i=3;$i<count($pathinfo);$i+=2){
                $_GET[$pathinfo[$i]]=$pathinfo[$i+1]??'';
            }
            $controller = $pathinfo[1];
            $action = $pathinfo[2]??'index';
        }
        return [$controller,$action];
    }
    // 通过request_uri获取路径
    public function getRouteByRequest(){
        $controller = '';
        $action = '';
        $uri = $_SERVER['REQUEST_URI'];
        $regs = $this->regRoute();
        foreach ($regs as $key => $value) {
            if(preg_match($key, $uri)){
                $newUri = preg_replace($key, $value, $uri);
                // 将字符串进行处理
                $params = explode("&", $newUri);
                foreach($params as $param){
                    $p = explode("=", $param);
                    if($p[0]=='c'){
                        $controller = $p[1];
                    }elseif($p[0] == 'a'){
                        $action = $p[1];
                    }else{
                        $_GET[$p[0]] = $p[1];
                    }
                }
                break;
            }
        }
        return [$controller,$action];
    }
    public function regRoute(){
        return [
            "#^/(\w+)/(\d+)\?(.*)$#"=>"c=$1&a=index&id=$2&$3",
            "#^/(\w+)/(\d+)$#" => "c=$1&a=index&id=$2",
            "#^/(\w+)/(\w+)\?(.*)$#" => "c=$1&a=$2&$3",
            "#^/(\w+)/(\w+)$#" => "c=$1&a=$2",
            "#^/(\w+)$#"=>"c=$1&a=index",
            "#^/(\w+)\?(.*)$#"=>"c=$1&a=index&$2",
        ];
    }
}