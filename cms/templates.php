<?php
//��������� ������ ��������
    $self = "http://".filter_input(INPUT_SERVER,'SERVER_NAME').filter_input(INPUT_SERVER,'PHP_SELF');
    if($ajax != true){$self = str_replace(config::site_url."/index.php","",$self);}
    else{$self = str_replace(config::site_url."/ajax.php","",$self);}
//�������� �� ��������� �������� (������� ��� ��������)
    $adm = explode("/", $self);
    if($self == "" || $self == "/"){$page = "home";}
    elseif($adm[1] == "admin"){$page = "admin";}
    else{$page = "no_sys";}
//��������� ������� �� ������� $_GET
    if(filter_input(INPUT_GET,'tmpl') != ""){libs::GetLib("templates")->template_name = filter_input(INPUT_GET,'tmpl');}
    else{libs::GetLib("templates")->template_name = "default";}
//��������� ��������� ��������
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

                    $content_db[0]['title'] = "�������� ������";
                    $content = "�������� �� ������ \"%adress%$self\" �� �������.<br/><br/>��� ������: 404.";

            }else{

                    $content_db[0]['title'] = $cur_page['title'];
                    $content = $cur_page['content'];

            }

    }

    libs::GetLib("templates_types")->setPageVar("page_title",$content_db[0]['title']);
    libs::GetLib("templates")->SiteOut($content);

?>