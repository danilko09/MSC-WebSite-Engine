<?php

class JMC_mon{
    
    private static $config;
    
    public static function onLoad(){
        self::$config = is_file(__DIR__.DIRECTORY_SEPARATOR.'JMC_mon.conf.ser') ? unserialize(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'JMC_mon.conf.ser')) : array();
    }
    
    public static function autoload(){
        self::onLoad();
        WSE_ENGINE::RegisterTagHandler("mon", "JMC_mon");//Возможен конфликт с другими мониторингами
    }
    
    public static function handleTag($tag){
        $tag = [
          "title"=>'server 1',
            "address"=>"92.55.15.4:25565"
        ];
        if(self::$config == null){self::onLoad();}
        $var = isset($tag['address']) ? json_decode(file_get_contents("http://jsonmc.tk/old/?address=92.55.15.4:25565"),true) : null;
        $data = $var == null ? $tag : $var+$tag;
        return isset($var['error']) ? WSE_ENGINE::getRTmpl("jmc_mon/server_error",$data) : WSE_ENGINE::getRTmpl("jmc_mon/server",$data);
    }
    
}
