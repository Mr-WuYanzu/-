<?php
function dd($val){
    echo '<pre>';
    var_dump($val);
    echo '</pre>';
    exit;
}
function randomStr($length=16){
    $randomStr='';
    $string='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIPASDFGHJKLZXCVBNM';
    for($i=0;$i<=$length;$i++){
        $rand=rand(0,strlen($string)-1);
        $randomStr .= $string[$rand];
    }
    return $randomStr;
}
function config($name=''){
    $config = libs\Config::getInstance();
    if($name){
        return $config->getConfig($name);
    }
    return $config->getConfig();
}

function setLog($type,$message){
    $dir = date('Y-m-d')."_err.log";
    $msg = '['.date('Y-m-d H:i:s').'] '.'['.$type.'] '.$message."\r\n";
    file_put_contents('./logs/'.$dir,$msg);
}