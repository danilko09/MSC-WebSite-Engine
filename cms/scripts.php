<?php

final class scripts{//класс для загрузки скриптов и получения информации о них
    
    private static $all_scripts_cache = null;//кеш
    
    public static function checkScript($alias){//проверяет корректность установки скрипта
       
        if(!isset(self::getAllScriptsInfo()[$alias])){return false;}
        $info = self::getAllScriptsInfo()[$alias];
        if(!(isset($info['file']) || isset($info['a_file']))){return false;}
                
        if(isset($info['file']) && !is_file("scripts/".$info['file'].".php")){return false;}
        elseif(isset($info['file'])){
            include_once "scripts/".$info['file'].".php";
            if(!class_exists($alias)){return false;}
        }
          echo $alias.'<br/>';
        if(isset($info['a_file']) && !is_file("scripts/".$info['a_file'].".php")){return false;}
        elseif(isset($info['a_file'])){
            include_once "scripts/".$info['a_file'].".php";
            if(!class_exists("admin_".$alias)){return false;}
        }
        return true;
    }
    
    public static function getScriptInfo($alias){//выдает массив с мета-информацией о скрипте
        return self::checkScript($alias) ? self::getAllScriptsInfo()[$alias] : false;
    }
    
    public static function getAllScriptsInfo(){//возвращает метаинформацию о всех скриптах (в том числе не рабочих)
        if(self::$all_scripts_cache == null){self::$all_scripts_cache = parse_ini_file("cms/scripts.ini",true);}
        return self::$all_scripts_cache;
    }
    
    public static function makeAutoload(){//автозагрузка скриптов
        $scripts = self::getAllScriptsInfo();
        foreach($scripts as $alias => $info){
            if(!isset($info['autoload']) || $info['autoload'] != 1 || $info['autoload'] != "1"){continue;}
            if(!checkScript($alias)){continue;}
            include_once 'scripts/'.$info['file'].".php";
            if(class_exists($alias) && method_exists($alias, "autoload")){$alias::autoload();}
        }
    }
    
}


$sql = mysql_connect($db_host.':'.$db_port,$db_user,$db_pass) or die('Ошибка подключения к БД: '.mysql_error()); // Подключение к базе с пользователями
mysql_select_db($db_database,$sql);



class Config{

	const db_pref = "dle_";
	const db_host = "localhost";
	const db_user = "web";
	const db_pass = "web12345";
	const db_name = "web";
	const debug = true;
	const site_name = "Local testing site";

}