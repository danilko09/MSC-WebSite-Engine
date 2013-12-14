<?php

//Реализация AJAX для CMS MSC WebSite Engine
//Запрос должен посылаться методом GET(либо POST, но параметры указать в URL)
//Формат запроса:
//		type: тип данных (position | system | block | scrirpt | menu)
//		остальные параметры по типу (аналогично тегу <content /> все остальные параметры)

//начало скрипта
session_start();
//Подгрузка конфига	
if(!is_file("cms/config.php")){exit;}	
include("cms/config.php");
//Подключение "библиотекаря"	
if(!is_file("cms/lib.php")){exit;}	
include("cms/lib.php");
//Получение необходимого контента и отправка его клиенту
$arr = filter_input_array(INPUT_GET);
if($arr['type'] == "content"){$ajax = true; include("cms/templates.php");}
else{echo libs::GetLib("templates_types")->getContentByTag($arr);}

?>