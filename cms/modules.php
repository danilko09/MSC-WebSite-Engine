<?php

interface int_module{//interface for modules have all base functions that can be called by cms

//system
	public function processAction($action, $parameters);//module action (url parser)
	public static function processInsertion($insertion);//<!--mod_name type="block" name="block_name"-->
//page
	public function getPageTitle();
	public function getPageDescription();
	public function getPageKeyWords();
//module
	public static function getModuleName();
	public static function getModuleDescription();
	public static function getModuleVersion(); 

}

abstract class abs_module implements int_module{//anti bug :-)

//system
	public function processAction($action, $parameters){ return "undefined"; }
	public static function processInsertion($insertion){ return "undefined"; }
//page
	public function getPageTitle(){ return "undefined"; }
	public function getPageDescription(){ return ""; }
	public function getPageKeyWords(){ return ""; }
//module
	public static function getModuleName(){ return "undefined"; }
	public static function getModuleDescription(){ return "undefined"; }
	public static function getModuleVersion(){ return "undefined"; }

}
