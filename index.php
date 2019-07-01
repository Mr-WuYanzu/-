<?php
header('Access-Control-Allow-Origin: *');//允许所有的域名访问
//header('Access-Control-Allow-Origin: http://www.baidu.com');//允许授权用户访问
header('Access-Control-Allow-Headers: x-user-token,Content-Type,Content-Length');
header('Access-Control-Allow-Methods: GET,PUT');

include_once "libs\Autoload.php";
//include_once 'libs\Http.php';
define("DEFAULT_PATH",__DIR__.DIRECTORY_SEPARATOR.'config');
// ini_set("display_errors","On");
// ini_set("log_error","On");
// ini_set("error_log",__DIR__.'/logs/error.log');
//$http=new \libs\Http();
//$http->postCurl('http://test.1810.com/test1.php',['name'=>'zhangsan']);
include_once "vendor\autoload.php";
$route=new \libs\Route();
list($controller,$action)=$route->routePare();
$controller='controllers\\'.$controller;
(new $controller())->$action();
