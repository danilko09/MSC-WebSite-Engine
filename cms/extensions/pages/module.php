<?php

class module_pages extends abs_module implements int_module{

	var $version = "A.0.1";
	private $title = "";

	public function processAction($action,$parameters){
		if(file_exists(__DIR__."/pages/".$action.".html")){
			$this->title = file_get_contents(__DIR__."/pages/".$action.".title");
			return file_get_contents(__DIR__."/pages/".$action.".html");
		}
		return "module \"pages\" catch action \"$action\"";
	}

	public function getPageTitle(){
		return $this->title;
	}

	public static function getModuleDescription(){
		return "The main module, which provides work with static pages";
	}

}
