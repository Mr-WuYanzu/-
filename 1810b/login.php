<?php
//echo 'aaaa';
$user_name=$_POST['user_name'];
$password=$_POST['password'];
$link=mysqli_connect('127.0.0.1','root','root');
mysqli_select_db($link,'test');
mysqli_set_charset($link,'utf8');
//查询用户
$sql='select * from user where user_name='."'$user_name'";
//echo $sql;
$res=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($res);
if(!$row){
    $response=[
        'code'=>'5001',
        'message'=>'There is no user',  //没有此用户
    ];
    echo json_encode($response);die;
}
//验证密码
if($row['password']!=$password){
    $response=[
        'code'=>'5002',
        'message'=>'password error!', //密码错误
    ];
    echo json_encode($response);die;
}else{
    $response=[
        'code'=>'0',
        'message'=>'ok',
        'data'=>$row
    ];
    echo json_encode($response);die;
}