<?php

class templates_types{

    private static $types;
    private static $page;

    public static function GetContentByTag($tag){
        $return = "";
        if($tag['type'] == null || $tag['type'] == "position"){
            $pos = (new database())->GetAllOnField("blocks", "position", $tag['name'],"order",true);
            if(is_array($pos)){
                foreach($pos as $num=>$block){
                    if(isset($tag['style']) && $tag['style'] != "" && is_file("tmpl/".templates::$template_name."/".$tag['style'].".html")){$block_tmpl = file_get_contents("tmpl/".templates::$template_name."/".$tag['style'].".html");}
                    elseif(is_file("tmpl/".templates::$template_name."/block.html")){$block_tmpl = file_get_contents("tmpl/".templates::$template_name."/block.html");}
                    else{$block_tmpl = 'Ќе удалось найти файл шаблона дл€ блока(нет стандартного шаблона блока и нет за€вленного шаблона блока).';}
                    $return .= str_replace("[content]",$block['code'],str_replace("[title]",$block['title'],$block_tmpl));
                }
            }
        }elseif($tag['type'] == "system"){

                $return = self::getSystemVar($tag['name']);

        }elseif($tag['type'] == "block"){			

                $block = (new database())->GetAllOnField("blocks", "alias", $tag['name'],true,true);
                $block = $block[0];
                if(isset($tag['style']) && $tag['style'] != "" && is_file("tmpl/".templates::$template_name."/".$tag['style'].".html")){$block_tmpl = file_get_contents("tmpl/".templates::$template_name."/".$tag['style'].".html");}
                elseif(is_file("tmpl/".templates::$template_name."/block.html")){$block_tmpl = file_get_contents("tmpl/".templates::$template_name."/block.html");}
                else{$block_tmpl = 'Ќе удалось найти файл шаблона дл€ блока(нет стандартного шаблона блока и нет за€вленного шаблона блока).';}

                $return = str_replace("[content]",$block['code'],str_replace("[title]",$block['title'],$block_tmpl));

        }elseif($tag['type'] == "page"){

                $name = $tag['name'];
                $return = self::$page[$name];

        }elseif($tag['type'] == "script"){
            $scripts = scripts::getAllScriptsInfo();
            if(isset($scripts[$tag['name']])){
                $file = $scripts[$tag['name']]['file'];
                if(is_file("scripts/".$file.".php")){
                        require_once("scripts/".$file.".php");
                        if(class_exists($tag['name'])){
                            $cl = new $tag['name']();
                            if(method_exists($tag['name'],$tag['action'])){
                                    $return = $cl->$tag['action']();
                            }else{echo "debug: no_function ".$tag['action']." in ".$tag['name'];}

                        }else{echo "debug: no_class ".$tag['name'];}
                }else{echo "debug: no_file scripts/".$file.".php";}
            }else{echo "debug: not_registred ".$tag['name'];}
        }elseif($tag['type'] == "menu"){
            $menu = (new DataBase())->GetAllOnField("menus", "alias", $tag['name'],"id",true);
            if($menu == false){$return = "ћеню ".($tag['name'])." не найдено!";}
            $menu = $menu[0];

            $links = explode(",",$menu['links']);
            if(!isset($tag['style']) || $tag['style'] == "" || $tag['style'] == null){$tag['style'] = "menus/default";}

            foreach($links as $num=>$element){
                if($element === ""){continue;}
                $link = explode("|",$element);
                if(scripts::checkScript("users") /*libs::LoadLib("users")*/){
                        if(users::GetGroup() == $link[2] || $link[2] == "all")$return .= templates::getRTmpl($tag['style'],array("title"=>$link[0],"link"=>$link[1]));//"<a href='".$link[1]."'>".$link[0]."</a>";
                }else echo "Ќет библиотеки 'users'.";
            }	
        }else{self::getRegistredTypeBy($tag);}
        return $return;
    }


    public static function RegisterTagHandler($tag_type,$handler){

            self::$types[$tag_type] = $handler;

    }

    private static function getSystemVar($var){

                    if($var == "title"){return config::site_name;}
                    return $var;

    }

    private static function getRegistredTypeBy($tag){

        //заглушка на врем€, позже сделаю
            return "»спользуетс€ не зарегестрированный тип вывода данных.";

    }

    public static function setPageVar($name,$value){

            self::$page[$name] = $value;

    }

}