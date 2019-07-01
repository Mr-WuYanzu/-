<?php
namespace controllers;
use controllers\RestfulController;
use model\StudentModel;

class StudentController extends RestfulController{
	public function student(){
		dd(1);
		$model = new StudentModel;
		$data = $model->query("select * from __TABLE__");
		if($data){
			header('HTTP/1.1 200 ok');
			exit(json_encode($data));
		}else{
			header('HTTP/1.1 500 Internal Server Error');
		}
		
	}
	public function store(){
		$data = $_POST;
		
	}
	public function edit(){

	}
	public function destroy(){
		
	}
}