<?php
namespace libs;

class Log{

	protected static $instance;
	private function __construct(){



	}

	private function __clone(){

	}

	public function log(){
		if(self::$instance instanceof self){
			return new self;	
		}
		return self::$instance;
		
	}

}
