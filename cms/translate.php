<?php

final class translate{
    
    private static $cache = null;
    
    public static function onLoad(){
        //�������� �������� ����������� � �������
        self::$cache = parse_ini_file("cms/locales.ini",true);
    }
    
    public static function get($mark,$script = null,$language = null){
        
        //�������� �����������
        if(self::$cache == null){self::onLoad();}
        $data = self::$cache;
        if($script !== null && file_exists("cms/locales/".$script.".ini")){$data = $data + parse_ini_file("cms/locales/".$script.".ini",true);}
        if($language === null){$language = self::$cache['settings']['current'];}
        
        //����� ����� � ������ �����������
        if(isset($data[$language][$mark])){return $data[$language][$mark];}
        if(isset($data[self::$cache['settings']['default']][$mark])){return $data[self::$cache['settings']['default']][$mark];}

        return $mark;//���� ��� �����������, �� ���������� ����� �������
        
    }
    
    
}