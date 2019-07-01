<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/6
 * Time: 14:05
 */

namespace libs;
use libs\Response;

class Controllers
{
    protected $randomnum;//随机数
    protected $key='1810b';//秘钥
    protected $timestamp;//时间戳
    protected $sign;//签名
    public function __construct()
    {
        //验证签名
        $this->isSign();
    }

    //验证签名
    public function isSign(){
//        dd(request()->all());
        //接收一些必须参数
        $this->randomnum=request()->all('randomnum');
        $this->timestamp=request()->all('timestamp');
        $this->sign=request()->all('sign');
        //验证参数是否为空
        if($this->randomnum=='' || $this->timestamp=='' || $this->sign==''){
            echo response()->json(400,'missing parameter!');die;
        }
        //拼接成数组
        $signArr=[
            'randomnum'=>$this->randomnum,
            'timestamp'=>$this->timestamp,
            'key'=>$this->key,
        ];
        //接收其他数据
        $all=request()->all();
        unset($all['sign']);
        $signArr=$signArr + $all;
        sort($signArr,SORT_STRING);
        $signStr=implode($signArr);
        $sign=sha1($signStr);
        if($sign!==$this->sign){
            echo response()->json(601,'The signature is not!');die;
        }
    }
}