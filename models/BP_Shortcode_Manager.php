<?php 

class BP_Shortcode_Manager {
	private static $instance;

	private function __construct(){

	}

	public static function init(){
		if(null == self::$instance){
			self::$instance = new self;
		}

		return self::$instance;
	}


	public function register_shortcodes(){

	}

	private function citrix_registration(){}

}