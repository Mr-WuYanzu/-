<?php
$user_name=$_POST['user_name'];
$password=$_POST['password'];
$link=mysqli_connect('127.0.0.1','root','root');
mysqli_select_db($link,'test');
mysqli_set_charset($link,'utf8');
//查询用户是否已经注册
$sql="select * from user user_name='$user_name'";
$res=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($res);
if($row){
    $response=[
        'code'=>50005,
        'message'=>'用户名已经注册'
    ];
    echo json_encode($response);die;
}
$sql="insert into user (user_name,password) value ('$user_name','$password')";
$res=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($res);
if($row){
    $response=[
        'code'=>0,
        'message'=>'register success',
        'data'=>$row,
    ];
    echo json_encode($response);
}else{
    $response=[
        'code'=>40001,
        'message'=>'register faild!'
    ];
    echo json_encode($response);die;
}