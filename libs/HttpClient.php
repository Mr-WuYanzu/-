<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/11
 * Time: 18:39
 */

namespace libs;


class HttpClient
{
    //使用fopen
    public function fopenHttp($url,$data=''){
        $opts=[];
        if($data!==''){
            if(is_array($data)){
                $data=http_build_query($data);
                $opts=[
                    'http'=>[
                      'method'=>"POST",
                      'header'=>"Accept-language: zh-CN,zh;q=0.9\r\n".
                                "Content-Type: Application/x-www-form-urlencoded\r\n".
                                "Content-Length: ".strlen($data)."\r\n",
                      'content'=>$data,
                    ],
                ];
            }
        }
        $contxt=stream_context_create($opts);
        //访问资源
        $fp = fopen($url,'r',false,$contxt);
        //读取结果
        $content=stream_get_contents($fp);
        return $content;
    }
    //使用file
    public function fileHttp($url='',$data=''){
        $opts=[];
        if($data!==''){
            if(is_array($data)){
                $data=http_build_query($data);
                $opts=[
                    'http'=>[
                            'method'=>'POST',
                            'header'=>"Accept-language: zh-CN,zh;q=0.9\r\n".
                                        "Content-Type: application/x-www-form-urlencoded\r\n".
                                        "Content-Length: ".strlen($data),
                            'content'=>$data
                        ]
                ];
            }
        }
        $contxt=stream_context_create($opts);
        //连接资源
        $file=file($url,0,$contxt);
        $content=implode("\r\n",$file);
        return $content;
    }
    //使用file_get_contents
    public function fileGetHttp($url,$data=''){
        $opts=[];
        if($data!==''){
            if(is_array($data)){
                $data=http_build_query($data);
                $opts=[
                    'http'=>[
                        'method'=>'POST',
                        'header'=>"Accept-language: zh-CN,zh;q=0.9\r\n".
                            "Content-Type: application/x-www-form-urlencoded\r\n".
                            "Content-Length: ".strlen($data),
                        'content'=>$data
                    ]
                ];
            }
        }
        $contxt=stream_context_create($opts);
        //连接资源
        $content=file_get_contents($url,false,$contxt);
        return $content;
    }
    //使用fsockopen
    public function fsockHttp($url,$data='',$upload=false,$port=80){
        $parse=parse_url($url);
        $path=$parse['path'] ?? '/';
        if(isset($parse['query'])){
            $path .= '?'.$parse['query'];
        }
        $method='GET';
        $fp=fsockopen($parse['host'],$port,$errno,$errstr,30);
        //编写http报文
        $httpStr = "GET ".$path." HTTP/1.1\r\n";
        if($data){
            $method="POST";
            $upload || ($data = is_array($data) ? http_build_query($data) : $data);
            $httpStr = "POST ".$path." HTTP/1.1\r\n";
            $httpStr .= $upload ? "Content-Type: multipart/form-data; boundary=--ABC\r\n" :"Content-Type: Application/x-www-form-urlencoded\r\n";
            $httpStr .= "Content-Length: ".strlen($data)."\r\n";
        }

        $httpStr .= "Host: ".$parse['host']."\r\n";
        $httpStr .= "Accept: */*\r\n\r\n";
        if($method=='POST'){
            $httpStr .= $data;
        }
//dd($httpStr);
        fwrite($fp,$httpStr);
        $content=stream_get_contents($fp);
        return self::parseHttp($content);
    }
    //使用stream_socket_client
    public function socketHttp($url,$data='',$port=80){
        $parse=parse_url($url);
        $path=$parse['path']??"/";
        if(isset($parse['query'])){
            $path .= '?'.$parse['query'];
        }
        $fp = stream_socket_client("tcp://".$parse['host'].':'.$port, $errno, $errstr, 30);
        if(!$fp){
            //跑出异常
        }
        $method='GET';
        $httpStr="GET ".$path." HTTP/1.1\r\n";
        if(is_array($data) && $data!=''){
            $method="POST";
            $data=http_build_query($data);
            $httpStr='POST '.$path." HTTP/1.1\r\n";
            $httpStr .= "Content-Type: Application/x-www-form-urlencoded\r\n";
            $httpStr .= "Content-Length: ".strlen($data)."\r\n";
        }
        $httpStr .= "Host: ".$parse['host']."\r\n";
        $httpStr .= "Accept: */*\r\n";
        $httpStr .= "\r\n";
        if($method=='POST'){
            $httpStr .= $data;
        }
        dd($httpStr);
        fwrite($fp,$httpStr);
        $contents=stream_get_contents($fp);
        return self::parseHttp($contents);
    }
    //解析http响应报文
    public static function parseHttp($content=''){
//        var_dump($content);
        //分割报文头和报文主体
        list($http_header,$http_body)=explode("\r\n\r\n",$content);
        //获得起始行
        $http_header=explode("\r\n",$http_header);
        //获得http协议  状态码   状态码描述
        list($scheme,$code,$codeInfo)=explode(' ',$http_header[0]);
        unset($http_header[0]);
        $headers=[];
        foreach($http_header as $v){
            list($key,$val)=explode(' ',$v);
            $headers[$key]=$val;
        }
        $httpBody="";
        if(isset($headers['Transfer-Encoding:'])){
            while($http_body){
                $arr=explode("\r\n",$http_body,2);
                $num=intval($arr[0],16);
                $httpBody .= substr($arr[1],0,$num);
                $http_body = substr($arr[1],$num+2);
            }
        }
        return ['status'=>[$scheme,$code,$codeInfo],'header'=>$http_header,'body'=>$httpBody];
    }
}