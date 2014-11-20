<?php
//ini_set('memory_limit', '-1');//освобождаем память :-)
$timing = microtime();//сохраняем время начала генерации
//включение вывода отладочных сообщений
error_reporting(E_ALL);
ini_set('display_errors', 1);
//начало сессии
session_start();
//определение констант
define("MSC_WSE_CORE_VERSION","3.1");
define("MSC_WSE_ENGINE_VERION","A.3.1");
define("MSC_WSE_ENGINE_VERSION_DESC", "alpha 3.1 | 05.11.2014");

//Запуск установщика
if (is_dir('setup') && is_file('setup/index.php')) {header('Location: setup');}
//Проверка существования папки CMS	
if(!is_dir("cms")){die("Отсутствует директория 'CMS'.");} 
//Подгрузка конфига	
if(!is_file("cms/config.php")){exit;}	
include("cms/config.php");
//Подгрузка локализаций
if(!is_file("cms/translate.php")){exit;}	
include("cms/translate.php");
//Подключение "библиотекаря"	
//if(!is_file("cms/lib.php")){exit;}	
//include("cms/lib.php");
//Загрузка системного менеджера скриптов
if(!is_file("cms/scripts.php")){exit;}	
include("cms/scripts.php");
scripts::makeAutoload();
//Подгрузка сценария для парсера шаблонов
if (!is_file("cms/templates.php")){exit;}
include("cms/templates.php");
//выводим время генерации страницы
echo "<!-- ".(round((microtime() - $timing)*1000))."ms -->";