<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/17
 * Time: 14:47
 */

namespace controllers;

class FrontController
{
    public function actionIndex(){

        include_once "view/front.html";
    }
     public function actionChunk(){
        include "view/chunk.html";
     }
}