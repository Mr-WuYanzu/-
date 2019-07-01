<?php
require_once ('response\Response.php');
var_dump($_POST);die;
$arr=[
    'name'=>'lisi',
    'age'=>19,
    'sex'=>'男',
    'child'=>[
        'child1'=>[
            'name'=>'liwu',
            'age'=>3,
        ],
        'child2'=>[
            'name'=>'liliu',
            'age'=>2
        ]
    ]
];
// var_dump(simplexml_load_string(response\Response::dataAll(200,'ok',$arr)));
//$json=json_encode($arr);
//var_dump(\response\Response::dataAll(300,'ojbk',$arr));
////$json=json_decode($json,true);
////var_dump($json);
    $xml = '<?xml version="1.0" encoding="utf-8" ?>
                <root>
                    <code>200</code>
                    <message>ok</message>
                    <name>张三</name>
                </root>
    ';
//    $xml=simplexml_load_string($xml);
$xmlreader=new XMLReader();
$xmlreader->XML($xml);
//$data=[];
while ($xmlreader->read()){
    if($xmlreader->nodeType==\XMLReader::ELEMENT){
        $nodename=$xmlreader->name;
    }
    if($xmlreader->nodeType==\XMLReader::TEXT && !empty($nodename)){
        $data[$nodename]=$xmlreader->value;
    }
}
//var_dump($data);
$domxml=new DOMDocument();
$domxml->loadXML($xml);
$data['name']=$domxml->getElementsByTagName('name')[0]->nodeValue;
var_dump($data);