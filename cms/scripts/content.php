<?php

final class content{
    
    private static $page = null;
    private static $bind_p = [];
    
    private static function detectPage(){
        $pages = is_file(__DIR__.DIRECTORY_SEPARATOR.'pages.json') ? json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'pages.json'),true) : [];
        $page = (count(WSE_ENGINE::getURI()) > 0) ? rtrim(implode('/',WSE_ENGINE::getURI()),'/') : 'home';
        if($page == ""){$page = "home";}
        if(!isset($pages[$page])){
            foreach(self::$bind_p as $p => $pgw){
                if(strpos('/'.$page, $p) === 0 && WSE_ENGINE::checkScript($pgw) && method_exists($pgw, "bindPage")){
                    self::$page = $pgw::bindPage();
                    return;
                }
            }
            self::$page = [
                "code" => "404",
                "title" => WSE_ENGINE::translate("404_TITLE", "content"),
                "content" => WSE_ENGINE::translate("404_CONTENT", "content")
            ];
        }else{
            self::$page = $pages[$page];
        }
    }
    
    public static function autoload(){
        WSE_ENGINE::RegisterTagHandler("page", "content");
    }
    
    public static function handleTag($tag){
        if(self::$page == null){self::detectPage();}
        return isset(self::$page[$tag['name']]) ? self::$page[$tag['name']] : "";
    }
    
    public static function bindAddressBase($uri,$page_worker){
        self::$bind_p[$uri] = $page_worker;
    }
    
}