<?php

define("MSC_WSE_CORE_VERSION", '4');
define("WSE_INCLUDE", true);
define("WSE_DEBUG", true);

define("WSE_CMS_DIR", __DIR__.DIRECTORY_SEPARATOR."cms");
define("WSE_TMPL_DIR", WSE_CMS_DIR.DIRECTORY_SEPARATOR."templates");
define("WSE_SCRIPTS_DIR", WSE_CMS_DIR.DIRECTORY_SEPARATOR."scripts");
define("WSE_TRANSLATE_DIR", WSE_CMS_DIR.DIRECTORY_SEPARATOR."locales");

if(WSE_DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

final class WSE_ENGINE{

    //URI(L)
    private static $url = null;
    private static $uri = null;
    private static $url_base = null;
    private static $index = null;

    public static function getURL(){

        if(self::$url == null){
            self::genURLI();
        }
        return self::$url;
    }

    public static function getURI(){

        if(self::$uri == null){
            self::genURLI();
        }
        return self::$uri;
    }

    public static function getURL_BASE(){

        if(self::$url_base == null){
            self::genURLI();
        }
        return self::$url_base;
    }

    public static function getIndex(){
        if(self::$index == null){
            self::genURLI();
        }
        return self::$index;
    }
    
    private static function genURLI(){

        self::$url = "http://".filter_input(INPUT_SERVER, "HTTP_HOST").filter_input(INPUT_SERVER, "PHP_SELF");
        $url = explode("index.php", self::$url);
        self::$url_base = trim($url[0], '/');
        if(isset($url[1])){
            self::$uri = explode("/", $url[1]);
            array_shift(self::$uri);
        }else{
            self::$uri[0] = "";
        }
        self::$index = str_replace($url[1],'',"http://".filter_input(INPUT_SERVER, "HTTP_HOST").filter_input(INPUT_SERVER,'REQUEST_URI'));
    }

    //Инициализация

    public static function start(){

        session_start();

        if(self::$uri == null){
            self::genURLI();
        }

        if(filter_input(INPUT_GET, 'ajax') == '1'){
            self::ajax();
            exit;
        }

        if(isset(self::getConfig()['setup']['do']) && self::getConfig()['setup']['do'] == "1"){
            self::setup();
            self::printTimings();
            exit;
        }

        if(isset(self::$uri[0]) && self::$uri[0] == "admin"){
            self::admin();
            self::printTimings();
            exit;
        }

        self::normal();
        self::printTimings();
    }

    //Обычный режим

    private static $pages_cache = null;

    private static function normal(){

        self::autoload();
        echo self::PrepearHTML(self::getTmpl("index"));
        
    }

    //Режим администрирования

    private static function admin(){
        
        self::setTemplate(self::$config['main']['admin_style']); self::autoload();
        echo str_replace("<content/>",self::getContentByTag(['type'=>'script','name'=>'admin','action'=>'showpage']),self::PrepearHTML(self::getTmpl("admin")));
        
    }

    //Режим Ajax

    private static function ajax(){
        
        self::autoload();
        echo self::GetContentByTag(filter_input_array(INPUT_GET));

    }

    //Скрипты

    private static $all_scripts_cache = null;

    public static function checkScript($alias){

        $allInfo = self::getAllScriptsInfo();
        if(!isset($allInfo[$alias])){ return false; }
        
        $info = $allInfo[$alias];
        if(!(isset($info['file']) || isset($info['a_file']))){
            return false;
        }

        if(isset($info['file']) && !is_file(WSE_SCRIPTS_DIR.DIRECTORY_SEPARATOR.$info['file'].".php")){
            return false;
        }elseif(isset($info['file'])){
            include_once WSE_SCRIPTS_DIR.DIRECTORY_SEPARATOR.$info['file'].".php";
            if(!class_exists($alias)){ return false; }
        }
        if(isset($info['a_file']) && !is_file(WSE_SCRIPTS_DIR.DIRECTORY_SEPARATOR.$info['a_file'].".php")){
            return false;
        }elseif(isset($info['a_file'])){
            include_once WSE_SCRIPTS_DIR.DIRECTORY_SEPARATOR.$info['a_file'].".php";
            if(!class_exists("admin_".$alias)){ return false; }
        }
        return true;
    }

    public static function getScriptInfo($alias){

        if(self::checkScript($alias)){
            $info = self::getAllScriptsInfo();
            return $info[$alias];
        }else{
            return false;
        }
    }

    public static function getAllScriptsInfo(){

        if(self::$all_scripts_cache == null){
            self::$all_scripts_cache = is_file(WSE_CMS_DIR.DIRECTORY_SEPARATOR."scripts.ini") ? parse_ini_file(WSE_CMS_DIR.DIRECTORY_SEPARATOR."scripts.ini", true) : array();
        }
        return self::$all_scripts_cache;
    }

    private static function autoload(){

        self::$autoload = microtime();
        $scripts = self::getAllScriptsInfo();
        if(!is_array($scripts)){
            return;
        }
        foreach($scripts as $alias => $info){
            if(!isset($info['autoload']) || $info['autoload'] != 1 || $info['autoload'] != "1"){
                continue;
            }
            if(!self::checkScript($alias)){
                continue;
            }
            include_once WSE_SCRIPTS_DIR.DIRECTORY_SEPARATOR.$info['file'].".php";
            if(class_exists($alias) && method_exists($alias, "autoload")){
                $alias::autoload();
            }
        }
        self::$autoload = (round((microtime() - self::$autoload) * 1000));
    }

    //Конфиг

    private static $config = null;

    public static function getConfig(){

        if(self::$config == null){
            self::$config = is_file(WSE_CMS_DIR.DIRECTORY_SEPARATOR."config.ini") ? parse_ini_file(WSE_CMS_DIR.DIRECTORY_SEPARATOR."config.ini", true) : array();
        }

        return self::$config;
    }

    //Локализации

    private static $locales_cache = null;

    public static function translate($mark, $script = null, $locale = null){

        if(self::$locales_cache == null){
            self::$locales_cache = parse_ini_file(WSE_TRANSLATE_DIR.DIRECTORY_SEPARATOR."main.ini", true);
        }

        $data = self::$locales_cache;
        if($script !== null && file_exists(WSE_TRANSLATE_DIR.DIRECTORY_SEPARATOR.$script.".ini")){
            foreach(parse_ini_file(WSE_TRANSLATE_DIR.DIRECTORY_SEPARATOR.$script.".ini", true) as $lang => $vars){
                $data[$lang] = isset($data[$lang]) ? $vars + $data[$lang] : $vars;
            }
        }

        if($locale === null){
            $locale = self::$config['main']['locale'];
        }
        
        if(isset($data[$locale][$mark])){
            return $data[$locale][$mark];
        }
        if(isset($data[self::$config['main']['def_locale']][$mark])){
            return $data[self::$config['main']['def_locale']][$mark];
        }

        return $mark;
    }

    //Шаблонизация

    private static $template_name = null;
    private static $types;
    
    public static function GetContentByTag($tag){

        $return = "";
        if(isset($tag['type']) && $tag['type'] == "system"){
            $return = self::getSystemVar($tag['name']);
        }elseif(isset($tag['type']) && $tag['type'] == "script"){
            if(self::checkScript($tag['name']) && method_exists($tag['name'], $tag['action'])){
                $cl = $tag['name'];
                $return = $cl::$tag['action']($tag);
            }elseif(WSE_DEBUG){
                $return = "Can't handle script tag '".($tag['name'])."'";
            }
        }else{
            $return = self::getRegistredTypeBy($tag);
        }
        return $return;
    }

    public static function RegisterTagHandler($tag_type, $handler){

        self::$types[$tag_type] = $handler;
    }

    private static function getSystemVar($var){

        if($var == "title"){
            return self::$config['main']['site_name'];
        }
        return $var;
    }

    private static function getRegistredTypeBy($tag){

        $code = isset($tag['type']) ? self::$types[$tag['type']] : " ";
        if(self::checkScript($code) && method_exists($code, "handleTag")){
            return $code::handleTag($tag);
        }elseif(WSE_DEBUG){
            return "Can't handle tag type '".(isset($tag['type']) ? $tag['type'] : ' ')."', reason: no method in class '".$code."'";
        }else{
            return "";
        }
    }

    public static function setTemplate($name){
        
        if(is_dir(WSE_TMPL_DIR.DIRECTORY_SEPARATOR.$name)){
            self::$template_name = $name;
        }
    }

    public static function PrepearHTML($text){

        $text_reply = "";
        preg_match_all("|<content (.*)/>|U", $text, $text_reply, PREG_SPLIT_NO_EMPTY);
        foreach($text_reply[1] as $num => $args){
            $arr = explode(" ", $args);
            foreach($arr as $value){

                $tag_tmp = explode("=", $value);
                if(isset($tag_tmp[1])){
                    $tag[$tag_tmp[0]] = str_replace("\"", "", str_replace("'", "", $tag_tmp[1]));
                }else{
                    $tag[$tag_tmp[0]] = true;
                }
            }
            $text = str_replace($text_reply[0][$num], self::GetContentByTag($tag), $text);
            $tag = array();
        }
        $ret = str_replace("%index%",self::$index,str_replace("%base%",self::$url_base,str_replace("%url%",self::$url,str_replace("%tmpl_root%","%base%/cms/templates/".self::$template_name,$text))));
        if(count($text_reply[0]) > 0){
            $ret = self::PrepearHTML($ret);
        }
        return $ret;
    }

    public static function isTmpl($name){

        if(filter_input(INPUT_GET, 'tmpl') == null && (!isset($_SESSION['tmpl']) || $_SESSION['tmpl'] == null) && self::$template_name == null){
            self::$template_name = self::$config['main']['def_tmpl'];
        }elseif(filter_input(INPUT_GET, 'tmpl') != null){
            self::$template_name = filter_input(INPUT_GET, 'tmpl');
            $_SESSION['tmpl'] = filter_input(INPUT_GET, 'tmpl');
        }else{
            self::$template_name = $_SESSION['tmpl'];
        }

        if(is_file(WSE_TMPL_DIR.DIRECTORY_SEPARATOR.self::$template_name.DIRECTORY_SEPARATOR.$name.".html")){
            return "t";
        }elseif(is_file(WSE_TMPL_DIR.DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR.$name.".html")){
            return "s";
        }else{
            return false;
        }
    }
    
    public static function getTmpl($name){
        
        if(filter_input(INPUT_GET, 'tmpl') == null && (!isset($_SESSION['tmpl']) || $_SESSION['tmpl'] == null) && self::$template_name == null){
            self::$template_name = self::$config['main']['def_tmpl'];
        }elseif(filter_input(INPUT_GET, 'tmpl') != null){
            self::$template_name = filter_input(INPUT_GET, 'tmpl');
            $_SESSION['tmpl'] = filter_input(INPUT_GET, 'tmpl');
        }else{
            self::$template_name = isset($_SESSION['tmpl']) ? $_SESSION['tmpl'] : self::$template_name;
        }
        
        if(is_file(WSE_TMPL_DIR.DIRECTORY_SEPARATOR.self::$template_name.DIRECTORY_SEPARATOR.$name.".html")){
            return file_get_contents(WSE_TMPL_DIR.DIRECTORY_SEPARATOR.self::$template_name.DIRECTORY_SEPARATOR.$name.".html");
        }elseif(is_file(WSE_TMPL_DIR.DIRECTORY_SEPARATOR.self::$template_name.DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR.$name.".html")){
            return file_get_contents(WSE_TMPL_DIR.DIRECTORY_SEPARATOR.self::$template_name.DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR.$name.".html");
        }elseif(is_file(WSE_TMPL_DIR.DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR.$name.".html")){
            return file_get_contents(WSE_TMPL_DIR.DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR.$name.".html");
        }else{
            return str_replace("[file]", "%tmpl_root%/".$name.".html", self::translate("TMPL_NOT_FOUND"));
        }

    }

    public static function getRTmpl($name, $arr){
        
        $return = self::getTmpl($name);

        if($return == str_replace("[file]", "%tmpl_root%/".$name.".html", self::translate("TMPL_NOT_FOUND"))){
            $return = "<p>Во вроемя вывода шаблона возникла ошибка, но вам было передано следующее:</p>";
            foreach($arr as $code => $val){
                $return .= "<p>$code: <br/>$val</p>";
            }
            return "<div>".$return."</div>";
        }else{
            foreach($arr as $code => $val){
                $return = str_replace("[".$code."]", $val, $return);
            }
            return $return;
        }
        
    }
    
    //Вывод таймингов
    
    private static $autoload = 0;
    
    private static function printTimings(){
        
        if(WSE_DEBUG){
            
            echo "<!-- page generation time: ".(round((microtime() - WSE_START_MICROTIME) * 1000))."ms\r\n"
                ." scripts autoload time: ".self::$autoload."ms"
                ."-->";
            
        }
        
    }

}

define("WSE_START_MICROTIME", microtime());
WSE_ENGINE::start();