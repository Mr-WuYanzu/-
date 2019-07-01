<?php
$goods_name=$_POST['goods_name'];
$goods_price=$_POST['goods_price'];
$goods_number=$_POST['goods_number'];
$link = mysqli_connect('127.0.0.1','root','root');
mysqli_select_db($link,'test');
mysqli_set_charset($link,'utf8');
//查询此商品是否添加过
$sql="select * from goods where goods_name='$goods_name'";
$res=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($res);
if($row){
    $response=[
        'code'=>'5004',
        'message'=>'The goodsname already exists',//商品名称已经存在
    ];
    echo json_encode($response);die;
}
//商品进行添加
$sql="insert into goods (goods_name,goods_price,goods_number) value ('$goods_name','$goods_price','$goods_number')";
$res=mysqli_query($link,$sql);
if(!$res){
    $response=[
        'code'=>'5003',
        'message'=>'goods insert error', //商品添加失败
    ];
    echo json_encode($response);die;
}else{
    $response=[
        'code'=>'0',
        'message'=>'goods insert success',
    ];
    echo json_encode($response);die;
}