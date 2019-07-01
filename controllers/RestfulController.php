<?php
namespace controllers;


class RestfulController{
	public function actionIndex(){
		$method = strtolower($_SERVER['REQUEST_METHOD']);
		switch ($method) {
			case 'get':
				return $this->student();
			break;
			case "post":
				return $this->store();
			break;
			case "put":
				return $this->edit();
			break;
			case "delete":
				return $this->destroy();
			break;
			default:
				return request()->json(500,'Internal Server Error',['服务器内部错误']);

		}
	}
}