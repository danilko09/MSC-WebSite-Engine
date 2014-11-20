<?php
include_once 'lib/templates_types.php';
include_once 'lib/database.php';
//include_once 'lib/users.php';
class templates{

        private static $blocks;
        public static $file_name = "index";
        public static $template_name = "default";
        public static $out = "0";
        public static $parse_content = "1";

        public static function SiteOut($content,$adress){

                if (self::$parse_content === "1") {$content = self::PrepearHTML($content);}
            
                $db = new DataBase();
                self::$blocks = $db->getAll("blocks",true,true);
                self::$out = "1";

                if (!is_dir("tmpl")) { die("Не существует папка 'tmpl', вывод страницы не возможен."); }

                if (self::$template_name == "" || !is_dir('tmpl/' . self::$template_name)) { self::$template_name = "default"; }

                if (!is_dir('tmpl/' . self::$template_name) && !is_dir('tmpl/default')) { die('Не существует папки текущего шаблона и файлов стандартного шаблона.'); }

                if (!is_file("tmpl/" . self::$template_name . "/" . self::$file_name . ".html")) { die('Не удалось найти основной файл шаблона, вывод страницы не возможен.'); }
                $tmpl = file_get_contents("tmpl/".self::$template_name."/".self::$file_name.".html");
                if (self::$parse_content === "1") { $tmpl = str_replace("<content/>", $content, $tmpl); }
                $tmpl = str_replace("%tmpl_root%",$adress."/tmpl/".self::$template_name,$tmpl);
                $tmpl = self::PrepearHTML($tmpl);
                if (self::$parse_content === "0") { $tmpl = str_replace("<content/>", $content, $tmpl); }
                $tmpl = str_replace("%adress%",$adress,$tmpl);
                echo $tmpl;

        }

        public static function PrepearHTML($text){

                preg_match_all("|<content (.*)/>|U", $text, $text_reply, PREG_SPLIT_NO_EMPTY);
                foreach($text_reply[1] as $num=>$args){

                        //echo $num;
                        $arr = explode(" ", $args);
                        //print_r($arr);
                        foreach($arr as $name=>$value){

                                $tag_tmp = explode("=",$value);
                                if(isset($tag_tmp[1])){
                                $tag[$tag_tmp[0]] = str_replace("\"","",str_replace("'","",$tag_tmp[1]));
                                }else{$tag[$tag_tmp[0]] = true;}
                        }
                        //echo "<br/>".$tag['name'].":".$tag['type']."<br/>\r\n";
                        //echo $text_reply[0][$num]."<hr/>";
                        $text = str_replace($text_reply[0][$num],templates_types::GetContentByTag($tag),$text);
                        $tag = array();
                }

                if(count($text_reply[0]) > 0){$text = self::PrepearHTML($text);}
                return $text;
        }

        public static function SetPageTmpl($name){

                if(self::$out == "0"){
                        if(is_file("tmpl/".self::$template_name."/".$name.".html"))self::$file_name = $name;
elseif (is_file("tmpl/default/" . $name . ".html")) {
        self::$template_name = "default";
        self::$file_name = $name;
    } else {
        return false;
    }
}else{return false;}

        }


        public static function getTmpl($name){

            if (is_file("tmpl/" . self::$template_name . "/" . $name . ".html")) {
                $return = file_get_contents("tmpl/" . self::$template_name . "/" . $name . ".html");
            } elseif (is_file("tmpl/scripts/" . $name . ".html")) {
                $return = file_get_contents("tmpl/scripts/" . $name . ".html");
            } else {
                $return = str_replace("[file]", "%tmpl_root%/" . $name . ".html", translate::get("TMPL_NOT_FOUND"));
            }

            return $return;

        }

        public static function getRTmpl($name,$arr){

                $return = self::getTmpl($name);

                if($return == str_replace("[file]", "%tmpl_root%/".$name.".html",translate::get("TMPL_NOT_FOUND"))){

                        $return = "<div><p>Во вроемя вывода шаблона возникла ошибка, но вам было передано следующее:</p>";

                        foreach( $arr as $code=>$val ){

                        $return .= "<p>$code: <br/>$val</p>";

                        }

                        $return .= "</div>";

                        return $return;

                }else{

                        foreach($arr as $code=>$val){

                                $return = str_replace("[".$code."]",$val,$return);

                        }

                        return $return;

                }

        }

}


//Получение алиаса страницы
$tmp = explode("index.php","http://".filter_input(INPUT_SERVER,"HTTP_HOST").filter_input(INPUT_SERVER,"PHP_SELF"));
$url_base = trim($tmp[0],'/');
$self = $tmp[1];
//Проверка на системную страницу (админка)
$adm = explode("/", $self);
//Установка шаблона из GET параметров
if(filter_input(INPUT_GET,'tmpl') == null && (!isset($_SESSION['tmpl']) || $_SESSION['tmpl'] == null)){
    templates::$template_name = "default";
}elseif(filter_input(INPUT_GET,'tmpl') != null){
    templates::$template_name = filter_input(INPUT_GET,'tmpl');
    $_SESSION['tmpl'] = filter_input(INPUT_GET,'tmpl');
}else{
    templates::$template_name = $_SESSION['tmpl'];
}
//Вызываем админку, если запрос идет на нее, иначе выдаем страничку
if(isset($adm[1]) && $adm[1] == "admin"){
    templates::$parse_content='0';
    require_once("cms/admin.php");
}else{
    $cur_page = "";
    $pages = parse_ini_file("cms/pages.ini", true);
    $page = ($self == "" || $self == "/") ? "home" : trim($self,"/");
    if(!isset($pages[$page])){
        $title = "Возникла ошибка";
        $content = "Страница по адресу \"%adress%$self\" не найднеа.<br/><br/>Код ошибки: 404.";
    }else{
        $title = isset($pages[$page]['title']) ? file_get_contents("cms/pages/".$pages[$page]['title'].".html") : "";
        $content = file_get_contents("cms/pages/".$pages[$page]['content'].".html");
    }
}
templates_types::setPageVar("page_title",$title);
templates::SiteOut($content,$url_base);