<?php
	$ech = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$ech = str_replace("/setup", "", $ech);
	$content = "Установка завершена.<br/>Все необходимые расширениябыли установлены автоматичесски.<br/><br/><a href='$ech'>На сайт</a> | <a href='".$ech."index.php/admin'>В административную панель.</a>";
	//Далее должно быть удаление директории setup, но пока просто сброс утановки в начало
	$_SESSION = Array();
	
	$finish = true;