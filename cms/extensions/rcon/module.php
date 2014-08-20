<?php

class module_rcon{

	var $version = "A.0.1";

	public function __construct(){
		//cms::regIns("rcon_log");
	}

	public function processAction($action){
		return "module \"rcon\" catch action \"$action\""
		."<h1>RCON консоль</h1>[script_rconConsole]";
	}

	public function processInsertion($insertion){
		return "rcon module insertion detected";
	}

	public function getTitle(){
		return "RCON консоль";
	}

	public function getDescription(){
		return "The main module, which provides work with static pages";
	}

}
