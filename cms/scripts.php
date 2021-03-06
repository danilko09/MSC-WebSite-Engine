<?php

final class scripts{//����� ��� �������� �������� � ��������� ���������� � ���
    
    private static $all_scripts_cache = null;//���
    
    public static function checkScript($alias){//��������� ������������ ��������� �������
       
        if(!isset(self::getAllScriptsInfo()[$alias])){return false;}
        $info = self::getAllScriptsInfo()[$alias];
        if(!(isset($info['file']) || isset($info['a_file']))){return false;}
                
        if(isset($info['file']) && !is_file("scripts/".$info['file'].".php")){return false;}
        elseif(isset($info['file'])){
            include_once "scripts/".$info['file'].".php";
            if(!class_exists($alias)){return false;}
        }
        if(isset($info['a_file']) && !is_file("scripts/".$info['a_file'].".php")){return false;}
        elseif(isset($info['a_file'])){
            include_once "scripts/".$info['a_file'].".php";
            if(!class_exists("admin_".$alias)){return false;}
        }
        return true;
    }
    
    public static function getScriptInfo($alias){//������ ������ � ����-����������� � �������
        return self::checkScript($alias) ? self::getAllScriptsInfo()[$alias] : false;
    }
    
    public static function getAllScriptsInfo(){//���������� �������������� � ���� �������� (� ��� ����� �� �������)
        if(self::$all_scripts_cache == null){self::$all_scripts_cache = parse_ini_file("cms/scripts.ini",true);}
        return self::$all_scripts_cache;
    }
    
    public static function makeAutoload(){//������������ ��������
        $scripts = self::getAllScriptsInfo();
        foreach($scripts as $alias => $info){
            if(!isset($info['autoload']) || $info['autoload'] != 1 || $info['autoload'] != "1"){continue;}
            if(!checkScript($alias)){continue;}
            include_once 'scripts/'.$info['file'].".php";
            if(class_exists($alias) && method_exists($alias, "autoload")){$alias::autoload();}
        }
    }
    
}

