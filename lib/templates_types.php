<?php

class templates_types{

	private $types;
	private $page;

	public function GetContentByTag($tag){
		if($tag['type'] == null || $tag['type'] == "position"){
                    
			$pos = libs::GetLib("database")->GetAllOnField("blocks", "position", $tag['name'],"order",true);
			
			if(is_array($pos)){
				foreach($pos as $num=>$block){
				
					if($tag['style'] != null && is_file("tmpl/".libs::GetLib("templates")->template_name."/".$tag['style'].".html"))$block_tmpl = file_get_contents("tmpl/".libs::GetLib("templates")->template_name."/".$tag['style'].".html");
                                        elseif(is_file("tmpl/".libs::GetLib("templates")->template_name."/block.html")){$block_tmpl = file_get_contents("tmpl/".libs::GetLib("templates")->template_name."/block.html");}
                                        else{$block_tmpl = 'Ќе удалось найти файл шаблона дл€ блока(нет стандартного шаблона блока и нет за€вленного шаблона блока).';}
				
					$return .= str_replace("[content]",$block['code'],str_replace("[title]",$block['title'],$block_tmpl));
				
				}
			}
		}elseif($tag['type'] == "system"){
		
			$return = $this->getSystemVar($tag['name']);
		
		}elseif($tag['type'] == "block"){			
			
			$block_arr = libs::GetLib("database")->GetAllOnField("blocks", "alias", $tag['name'],true,true);
			$block = $block_arr[0];
			
                        if($tag['style'] != null && is_file("tmpl/".libs::GetLib("templates")->template_name."/".$tag['style'].".html")){$block_tmpl = file_get_contents("tmpl/".libs::GetLib("templates")->template_name."/".$tag['style'].".html");}
                        elseif(is_file("tmpl/".libs::GetLib("templates")->template_name."/block.html")){$block_tmpl = file_get_contents("tmpl/".libs::GetLib("templates")->template_name."/block.html");}
                        else{$block_tmpl = 'Ќе удалось найти файл шаблона дл€ блока(нет стандартного шаблона блока и нет за€вленного шаблона блока).';}
			
			$return = str_replace("[content]",$block['code'],str_replace("[title]",$block['title'],$block_tmpl));
		
		}elseif($tag['type'] == "page"){
		
			$name = $tag['name'];
			$return = $this->page[$name];
		
		}elseif($tag['type'] == "script"){
			
			if(libs::getLib("database")->isExists("scripts","alias",$tag['name'])){
			
				$file = libs::getLib("database")->getField("scripts","file","alias",$tag['name']);
				if(is_file("scripts/".$file.".php")){
					require_once("scripts/".$file.".php");
					if(class_exists($tag['name'])){
					
						$cl = new $tag['name']();
						if(method_exists($tag['name'],$tag['action'])){
							$return = $cl->$tag['action']();
                                                }else{echo "debug: no_function '".$tag['action']."' in '".$tag['name']."'";}
						
                                        }else{echo "debug: no_class ".$tag['name'];}
                                }else{echo "debug: no_file scripts/".$file.".php";}

                        }else{echo "debug: not_registred ".$tag['name'];}
			
		}elseif($tag['type'] == "menu"){
			$menu_arr = libs::GetLib("database")->GetAllOnField("menus", "alias", $tag['name'],"id",true);
			$menu = $menu_arr[0];
			
			$links = explode(",",$menu['links']);
                        if($tag['style'] == "" || $tag['style'] == null){$tag['style'] = "menus/default";}
			
			foreach($links as $num=>$element){
				$link = explode("|",$element);
				if(libs::LoadLib("users")){
					if(libs::GetLib("users")->GetGroup() == $link[2] || $link[2] == "all")$return .= libs::GetLib("templates")->getRTmpl($tag['style'],array("title"=>$link[0],"link"=>$link[1]));//"<a href='".$link[1]."'>".$link[0]."</a>";
                                }else{echo "Ќет библиотеки 'users'.";}
			}
			
		}else{$this->getRegistredTypeBy($tag);}
		return $return;
	}
	
	
	public function RegisterTagHandler($tag_type,$handler){
	
		$this->types[$tag_type] = $handler;
	
	}
	
	private function getSystemVar($var){
		
                        if($var == "title"){return config::site_name;}
			return $var;
		
	}
	
	private function getRegistredTypeBy($tag){
	
		return "»спользуетс€ не зарегестрированный тип вывода данных '$tag'.";
	
	}
	
	public function setPageVar($name,$value){
	
		$this->page[$name] = $value;
	
	}

}