<?php

function getAdminBar(){

	global $db;
	if(!isset($_SESSION['login'])){return;}
	$gr = $db->getField("users", "group", "login", $_SESSION['login']);
	
	if($gr == "admin") return "<a href='%adress%/index.php/admin'>���������������� ������</a> | <a href='%adress%/index.php/rcon'>RCON</a>";

}

?>
