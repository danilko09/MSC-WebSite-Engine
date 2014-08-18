<?php

	$db = new DataBase();
	$templates = new templates();
	include("lib.php");

	$debug = "";
	if(!is_dir("scripts")) debug("<font color='red'>Создайте папку 'scripts' в корневой папке сайта.</font>");
	if(!is_dir("tmpl")) debug("<font color='red'>Создайте папку 'tmpl' в корневой папке сайта.</font>");
	
	function debug($content){
		global $debug;
		if(config::debug){$debug .= "<br/>".$content."<br/>\r\n";}
	}
	
	
	
?>
