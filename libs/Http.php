<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/5
 * Time: 18:51
 */

namespace libs;


class Http
{
    //发送get请求
    public function getCurl($url){
        $options = [
            CURLOPT_URL => $url,
        ];
//        dd($this->isHttps($url));
        if($this->isHttps($url)){
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        $this->curl($options);

    }
    //发送post请求
    public function postCurl($url,$data=[],$upload=false,$header=[]){
        $upload || $data=http_build_query($data);
//        dd($data);
        $options=[
            CURLOPT_URL =>$url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ];
        if($header){
            $options[CURLOPT_HTTPHEADER]=$header;
        }
        if($this->isHttps($url)){
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        $this->curl($options);
    }
    //基本的curl请求
    public static function curl($options=[]){
        $option=[
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ];
        //初始化
        $ch=curl_init();
        //设置参数
        curl_setopt_array($ch,$options);
        //发送请求
        $res = curl_exec($ch);
        if(!$res){
            dd(curl_error($ch));
        }
        //关闭，释放缓存
        curl_close($ch);
        return $res;
    }
    //判断是否是https请求
    public function isHttps($url){
        if(strpos($url,"https://")!==false){
            return true;
        }
        return false;
    }
}