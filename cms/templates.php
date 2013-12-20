<?php

// Управляющий кусочек шаблонизатора
// В разработке. Выбор шаблона будет позже. пока можно потестить с GET запросом.
// На основе URL подбирает контент страницы и выдает его в шаблоне (точнее дергает за ниточки шаблонизатор, чтоб тот это сделал)


//Получение алиаса страницы
    $self = "http://".filter_input(INPUT_SERVER,'SERVER_NAME').filter_input(INPUT_SERVER,'PHP_SELF');
    if($ajax != true){$self = str_replace(config::site_url."/index.php","",$self);}
    else{$self = str_replace(config::site_url."/ajax.php","",$self);}
//Проверка на системную страницу (административная или обычная)
    $adm = explode("/", $self);
    if($self == "" || $self == "/"){$page = "home";}
    elseif($adm[1] == "admin"){$page = "admin";}
    else{$page = "no_sys";}
//Установка шаблона из массива $_GET
    if(filter_input(INPUT_GET,'tmpl') != ""){libs::GetLib("templates")->template_name = filter_input(INPUT_GET,'tmpl');}
    else{libs::GetLib("templates")->template_name = "default";}
//Обработка системной страницы
    if($page == "home"){

            $content_db = libs::GetLib("database")->getAllOnField("pages","alias","home","id", true);
            $content = $content_db[0]['content'];

    }elseif($page == "admin"){
            libs::GetLib("templates")->parse_content='0';
            require_once("cms/admin.php");
    }else{

            $cur_page = "";
            $pages = libs::GetLib("database")->getAll("pages","id", true);

            foreach($pages as $num=>$page){if("/".$page['alias'] == $self){$cur_page = $page;}}

            if($cur_page == ""){

                    $content_db[0]['title'] = "Возникла ошибка";
                    $content = "Страница по адресу \"%adress%$self\" не найднеа.<br/><br/>Код ошибки: 404.";

            }else{

                    $content_db[0]['title'] = $cur_page['title'];
                    $content = $cur_page['content'];

            }

    }

    libs::GetLib("templates_types")->setPageVar("page_title",$content_db[0]['title']);
    libs::GetLib("templates")->SiteOut($content);

?>