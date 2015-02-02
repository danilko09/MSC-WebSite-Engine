<?php

class admin{
    
    private static $config = null;
    
    public static function showPage(){
        if(!WSE_ENGINE::checkScript('auth')){return WSE_ENGINE::translate('NO_AUTH','admin');}
        if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true){return WSE_ENGINE::translate('NOT_AUTHORISED','admin').'<br/>'.auth::authBlock();}
        self::loadINI();
        if(auth::getGroupByEmail($_SESSION['email']) != 'admin'){
            if(!isset(self::$config['admin']['super']) || self::$config['admin']['super'] != $_SESSION['email']){
                return WSE_ENGINE::translate('NOT_ADMIN','admin');
            }else{
                auth::setGroupByEmail($_SESSION['email'], 'admin');
            }
        }
        $uri = WSE_ENGINE::getURI();
        if(!isset($uri[1]) || $uri[1] == ''){
            return self::showMain();
        }else{
            return self::showOther($uri[1]);
        }
    }
    
    private static function showMain(){
        if((isset(self::$config['admin']) && count(self::$config) == 1) || (!isset(self::$config) && count(self::$config) == 0)){
            return WSE_ENGINE::translate('NO_ELEMENTS', 'admin');
        }
        $list = array();
        foreach(self::$config as $alias => $vars){
            if($alias == 'admin'){continue;}
            $vars['alias'] = $alias;
            $vars['category'] = WSE_ENGINE::translate($vars['category'],$vars['file']);
            $vars['title'] = WSE_ENGINE::translate($vars['title'],$vars['file']);
            if(!isset($list[$vars['category']])){$list[$vars['category']] = "";}
            $list[$vars['category']] .= WSE_ENGINE::getRTmpl('admin/list_element', $vars);
        }
        $content = '';
        foreach($list as $category => $elements){
            $content .= WSE_ENGINE::getRTmpl('admin/list_category', array('title'=>$category,'content'=>$elements));
        }
        return str_replace("%root%",  rtrim(WSE_ENGINE::getURL(),'/'),$content);
    }
    
    private static function loadINI(){
        if(is_array(self::$config)){return;}
        if(is_file(__DIR__.DIRECTORY_SEPARATOR.'admin.ini')){
            self::$config = parse_ini_file(__DIR__.DIRECTORY_SEPARATOR.'admin.ini', true);
        }else{
            self::$config = array();
        }
    }
    
    public static function showOther($alias){
        if($alias == "admin"){return WSE_ENGINE::translate("BAD_FILE", "admin");}
        if(isset(self::$config[$alias])){
            if(isset(self::$config[$alias]['file']) && is_string(self::$config[$alias]['file']) && strlen(self::$config[$alias]['file']) > 0 && is_file(__DIR__.DIRECTORY_SEPARATOR.self::$config[$alias]['file'].'.php')){
                include_once __DIR__.DIRECTORY_SEPARATOR.self::$config[$alias]['file'].'.php';
                if(class_exists(self::$config[$alias]['class']) && method_exists(self::$config[$alias]['class'], self::$config[$alias]['action'])){
                    $class = self::$config[$alias]['class'];
                    $func = self::$config[$alias]['action'];
                    return $class::$func();
                }else{
                    return WSE_ENGINE::translate("BAD_CODE", "admin");
                }
            }else{
                return WSE_ENGINE::translate("BAD_FILE", "admin");
            }
        }else{
            return WSE_ENGINE::translate("NOT_FOUND", "admin");
        }
    }
    
}
