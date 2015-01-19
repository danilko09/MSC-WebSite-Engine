<?php

class skins{
    
    private static $place = 'skins';
    
    public static function getHeadLink($par){
        return "%base%/".self::$place."/skin2d.php?mode=head&player=".$par['username'];
    }
    
    public static function autoload(){
        if(WSE_ENGINE::checkScript("content") && method_exists("content", "bindAddressBase")){
            content::bindAddressBase("/skins","skins");
        }
    }
    
    public static function bindPage(){
        
        return ["title"=>"Тест",'content'=>"test"];
    
    }
    
}