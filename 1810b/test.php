<?php

//$url='http://www.api.com/goods/insert.php';
//'goods_name=iphone8&goods_price=5000&goods_number=500'
//$url='http://www.api.com/goods/update.php';
// 'goods_id=2&goods_name=iphone7&goods_price=5000&goods_number=500'
//$url='http://www.api.com/goods/delete.php?goods_id=1';

$url='http://www.api.com/goods/goods.php';
//初始化连接
$ch=curl_init();
curl_setopt_array($ch,[
    CURLOPT_URL=>$url,  //初始化路径
//    CURLOPT_POST=>true, //访问方式
//    CURLOPT_POSTFIELDS=>'goods_name=huaweip30&goods_price=6000&goods_number=500', //post方式访问设置参数
    CURLOPT_RETURNTRANSFER=>true, //让返回的结果不在页面直接输出
    CURLOPT_FOLLOWLOCATION=>false,  //如果返回的是重定向，是否直接跳转
    CURLOPT_HTTPHEADER=>[
        'X-TOKEN'=>'1810'   //添加header头
    ],
//    CURLOPT_SSL_VERIFYPEER=>false,
//    CURLOPT_SSL_VERIFYHOST=>false,
]);
//发起请求
$res=curl_exec($ch);

//获取错误信息
if(!$res){
    exit(curl_error($ch));
}
//关闭请求
curl_close($ch);
var_dump($res);