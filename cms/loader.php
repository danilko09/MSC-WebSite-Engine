<?php

	$db = new DataBase();
	$templates = new templates();
	include("lib.php");

	$debug = "";
	if(!is_dir("scripts")) debug("<font color='red'>�������� ����� 'scripts' � �������� ����� �����.</font>");
	if(!is_dir("tmpl")) debug("<font color='red'>�������� ����� 'tmpl' � �������� ����� �����.</font>");
	
	function debug($content){
		global $debug;
		if(config::debug){$debug .= "<br/>".$content."<br/>\r\n";}
	}
	
	
	
?>
