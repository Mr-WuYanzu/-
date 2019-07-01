<?php
use libs\Request;
use libs\Response;

function request(){
    static $request;
    if($request instanceof Request){
        return $request;
    }
    $request=new Request();
    return $request;
}

function response(){
    static $response;
    if($response instanceof Response){
        return $response;
    }
    $response=new Response();
    return $response;
}