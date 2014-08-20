<?php

class module_menus extends abs_module implements int_module {
	
	public static function processInsertion($ins){

		global $db;

		if(isset($ins) && $db->isExists("menus", "title", $ins)){
	
			$menu_id = $db->getField("menus", "id", "title", $ins);
			if(!is_array($menu_id)) $menu = $db->getElementOnID("menus", $menu_id);
		
			$menus['urls'] = explode(",", $menu['urls']);
			$menus['links'] = explode(",", $menu['links']);
			$repl = "";
			foreach($menus['urls'] as $num=>$lnk){
		
				$repl .= "<li><a href='".$lnk."'>".$menus['links'][$num]."</a></li>\r\n";
		
			}
		
			return "<ul>".$repl."</ul>";
	
		}elseif($ins != "" && $ins != null){
			debug("Ìåíþ '".$ins."' íå íàéäåî.");		
		}
	}

}
