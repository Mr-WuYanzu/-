<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/5
 * Time: 14:07
 */
namespace libs;
class Response
{
    //自动判断返回什么样的数据
    public static function dataAll($code,$message,$data=[]){
        $type=isset($_GET['farmat'])?$_GET['farmat']:'json';
        switch ($type){
            case 'xml':
                $res=self::xml($code,$message,$data);
                break;
            case 'array':
                $res=self::genData($code,$message,$data);
                break;
            default:
                $res=self::json($code,$message,$data);
        }
        return $res;die;

    }
    //返回数组
    public static function genData($code,$message,$data=[]){
        $arr=[
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        ];
        return $arr;die;
    }
    //返回json数据
    public static function json($code,$message,$data=[]){
        $json = self::genData($code,$message,$data);
        return json_encode($json);die;
    }
    //返回xml格式
    public static function xml($code,$message,$data=[]){
        $dt = self::genData($code,$message,$data);
        $xml = '<?xml version="1.0" encoding="utf-8" ?><root>';
        $xml .= '<code>'.$code.'</code>';
        $xml .= '<message>'.$message.'</message>';
        $xml .= self::createXml($data);
        $xml .= '</root>';
        return $xml;die;

    }
    private static function createXml($data){
        $xml='';
        foreach($data as $k=>$v){
            if(is_array($v)){
                $xml .= '<'.$k.'>';
                $xml .= self::createXml($v);
                $xml .= '</'.$k.'>';
            }else{
                $xml .= '<'.$k.'>'.$v.'</'.$k.'>';
            }
        }
        return $xml;
    }
}