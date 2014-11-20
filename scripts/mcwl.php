<?php

class launcher{
    
    public static function showMainPage(){
        $content = "";
        templates::$template_name = "launcher";
        if(scripts::checkScript("users")){//Авторизация
            $auth = users::IsAuthorized();
            if($auth != "1"){ users::TryLogin(); $auth = users::IsAuthorized();}
            $login = users::GetLogin();
        }else{
            $auth = "no_lib";
        }
        if($auth == "1"){//выбор и вывод содержимого страницы
            $content .= templates::GetRTmpl("mcwl/launcher_content",array("username"=>$login));
        }elseif($auth == "0" || $auth == null){//если не авторизирован
            $content .= templates::getRTmpl("error", array("message" => translate::get("NEED_AUTH", "mcwl")))."<div style='width: 150px;'><p>".users::GetLoginForm()."</p></div>";
                //$content .= "<div class='warning-box'>Для получения доступа к игре необходимо ввести свои учетные данные.</div><div style='width: 150px;'><p>".users::GetLoginForm()."</p></div>";
        }elseif($auth == "no_lib"){//если нет библиотеки
            $content .= templates::getRTmpl("error", array("message" => translate::get("NO_USERS", "mcwl")));
                //$content = "<div class='warning-box'>Не найдена библиотека 'users', она используется для авторизации посетителей сайта, без нее лаунчер не может выполнять авторизацию посетителей.</div>";
        }
        return $content;
    }
    
}