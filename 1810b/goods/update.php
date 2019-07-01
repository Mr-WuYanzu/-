<?php
$goods_id=$_POST['goods_id'];
$goods_name=$_POST['goods_name'];
$goods_price=$_POST['goods_price'];
$goods_number=$_POST['goods_number'];
$link=mysqli_connect('127.0.0.1','root','root');
mysqli_select_db($link,'test');
mysqli_set_charset($link,'utf8');
$sql="update goods set goods_name='$goods_name',goods_price='$goods_price',goods_number='$goods_number' where goods_id='$goods_id'";
$res=mysqli_query($link,$sql);
if($res!==false){
    $response=[
        'code'=>0,
        'message'=>'update success',
        'data'=>[
            'goods_id'=>$goods_id,
            'goods_name'=>$goods_name,
            'goods_price'=>$goods_price,
            'goods_number'=>$goods_number
        ]
    ];
    echo json_encode($response);die;
}else{
    $response=[
        'code'=>50010,
        'message'=>'update faild!'
    ];
    echo json_encode($response);die;
}