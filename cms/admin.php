<?php
$title = "Административная панель";
admin_panel::auth();
$content = admin_panel::makeContent();

final class admin_panel {

    private static $auth = "0";
    private static $login = "";
    private static $group = "guest";
    private static $message = "";
    
    public static function auth(){
        if(scripts::checkScript("users")){//Авторизация
            self::$auth = users::IsAuthorized();
            if(self::$auth != "1"){ users::TryLogin(); self::$auth = users::IsAuthorized();}
            self::$login = users::GetLogin();
            self::$group = users::GetGroup();
        }else{
            self::$auth = "no_lib";
        }
    }
    
    public static function makeContent(){   
        $content = "";
        
        if(self::$auth == "1" && self::$group == "admin"){//выбор и вывод содержимого страницы
            $content = self::main();
        }elseif(self::$auth == "1" && self::$group != "admin"){//если не админ
                self::$message .= "<div class='warning-box'>Вы не состоите в группе 'администраторы' и не можете получить доступ к этой странице.</div>";
        }elseif(self::$auth == "0" || self::$auth == null){//если не авторизирован
                self::$message .= "<div class='warning-box'>Вы не авторизированны.</div><div style='width: 150px;'><p>".users::GetLoginForm()."</p></div>";
        }elseif(self::$auth == "no_lib"){//если нет библиотеки
                self::$message = "<div class='warning-box'>Не найдена библиотека 'users', она используется для авторизации посетителей сайта, без нее доступ к административной панели не возможен.</div>";
        }
        return (self::$message != "") ? self::$message.$content : $content;
    }
    private static function main(){
        global $adm;$content = "";
        
        var_dump(scripts::checkScript($adm[2]));
        if(!isset($adm[2]) || $adm[2] == ""){//меню в админке
        $links = self::genMenu();
        foreach($links as $group=>$cnt){
            $content .= str_replace("[title]", $group,str_replace("[content]",$cnt,templates::GetTmpl("admin/post")));
        }
    }elseif(scripts::checkScript($adm[2])){
    
        $tmp = "admin_".$adm[2];
        $content = $tmp::genContent();
        
    }else{//404 или какая-то страница в админке
        
            $content = "<div><h5>Ошибка 404 | <a href='%adress%/index.php/admin'>в административную панель</a></h5>Не найден файл '%adress%/scripts/admin/".$adm[2].".php'.<br/><br/>Возможно вы попали на эту страницу случайно набрав не верный адрес страницы, или расширение, к которому принадлежит этот файл, было удалнео.</div>";
        
    }
    return $content;
    }
    
    private static function genMenu(){
        $links = array();
        $sc = scripts::getAllScriptsInfo();
        foreach($sc as $alias=>$info){
            if(!isset($info['a_title']) || !isset($info['a_category'])){continue;}
            if(isset($links[$info['a_category']])){
                $links[$info['a_category']] .= "<a href='%adress%/index.php/admin/".$alias."'>".$info['a_title']."</a><br/>";
            }else{
            $links[$info['a_category']] = "<a href='%adress%/index.php/admin/".$alias."'>".$info['a_title']."</a><br/>";
            }            
        }
        return $links;
    }
    
}
