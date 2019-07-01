<?php
$goods_id=$_GET['goods_id'];//接收商品id
$link=mysqli_connect('127.0.0.1','root','root');
mysqli_select_db($link,'test');
mysqli_set_charset($link,'utf8');
$sql="delete from goods where goods_id='$goods_id'";
$res=mysqli_query($link,$sql);
if(!$res){
    $response=[
        'code'=>50006,
        'message'=>'delete faild!'
    ];
    echo json_encode($response);die;
}
$response=[
    'code'=>0,
    'message'=>'delete success'
];
echo json_encode($response);