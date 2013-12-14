<?php session_start();
//Запуск установщика
    if(is_dir('setup') && is_file('setup/index.php')){header('Location: setup');}
//Проверка существования папки CMS	
    if(!is_dir("cms")){die("Отсутствует директория 'CMS'.");}
//Подгрузка конфига	
    if(!is_file("config.php")){exit;}	
    include("config.php");
//Подключение "библиотекаря"	
    if(!is_file("cms/lib.php")){exit;}	
    include("cms/lib.php");
//Подгрузка сценария для парсера шаблонов
    if(!is_file("cms/templates.php")){exit;}
    include("cms/templates.php");

?>