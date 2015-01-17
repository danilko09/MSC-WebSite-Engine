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
            return 'other';
        }
    }
    
    private static function showMain(){
        if((isset(self::$config['admin']) && count(self::$config) == 1) || (!isset(self::$config) && count(self::$config) == 0)){
            return WSE_ENGINE::translate('NO_ELEMENTS', 'admin');
        }
        $list = array();
        foreach(self::$config as $alias => $vars){
            if($alias == 'admin'){continue;}
            $vars['category'] = WSE_ENGINE::translate($vars['category'],$vars['file']);
            $vars['title'] = WSE_ENGINE::translate($vars['title'],$vars['file']);
            $list[$vars['category']] .= WSE_ENGINE::getRTmpl('admin/list_element', $vars);
        }
        $content = '';
        foreach($list as $category => $elements){
            $content .= WSE_ENGINE::getRTmpl('admin/list_category', array('title'=>$category,'content'=>$elements))
        }
    }
    
    private static function loadINI(){
        if(is_array(self::$config)){return;}
        if(is_file(__DIR__.DIRECTORY_SEPARATOR.'admin.ini')){
            self::$config = parse_ini_file(__DIR__.DIRECTORY_SEPARATOR.'admin.ini', true);
        }else{
            self::$config = array();
        }
    }
    
}
