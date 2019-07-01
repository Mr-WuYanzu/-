<?php
print_r($_SERVER);
if(!isset($_SERVER['HTTP_X_TOKEN'])){
    echo '拒绝访问';
    exit;
}
$link = mysqli_connect('127.0.0.1','root','root');
mysqli_select_db($link,'test');
mysqli_set_charset($link,'utf8');
$sql="select * from goods";
$res=mysqli_query($link,$sql);
if($res){
    $row=mysqli_fetch_assoc($res);
    $response=[
        'code'=>0,
        'message'=>'ok',
        'data'=>$row
    ];
    echo json_encode($response);
}else{
    $response=[
        'code'=>1,
        'message'=>'goods null'
    ];
    echo json_encode($response);
}