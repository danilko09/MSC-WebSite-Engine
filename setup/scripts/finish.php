<?php
	$ech = filter_input(INPUT_SERVER,'HTTP_ORIGIN').filter_input(INPUT_SERVER,'REQUEST_URI');
	$ech = str_replace("/setup", "", $ech);
	$content = "Установка завершена.<br/>Все необходимые расширениябыли установлены.<br/><br/><a href='$ech'>На сайт</a> | <a href='".$ech."index.php/admin'>В административную панель.</a>";
	$_SESSION = Array();//Обнуление сессии, мало-ли чего-нибудь лишнего записалось
	
	$finish = true;
?>