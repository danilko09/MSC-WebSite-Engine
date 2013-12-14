<?php

	$content .= "<a href='%adress%/index.php/admin'>В административную панель.</a>";
	$db = libs::GetLib("database");
	
	if($adm[3] == ""){
		
		$menus = $db->getAll("menus","title",true);
		
		$content .= "<br/>Выберите меню для редактирования.<br/><br/>";
		
		foreach($menus as $num=>$menu){
		
			$content .= ($num+1).".<a href='%adress%/index.php/admin/menus/view/".$menu['id']."'>".$menu['title']."</a>[<a href='%adress%/index.php/admin/menus/remmen/".$menu['id']."'>Удалить</a>]<br/>";
		
		}
		
		$content .= "<br/><a href='%adress%/index.php/admin/menus/new/'>Создать новое меню.</a><br/><br/>";
	
	}elseif($adm[3] == "view"){

		$content .= " | <a href='%adress%/index.php/admin/menus'>К списку меню.</a><br/>Вы можете попробавть перейти по ссылкам и посмотреть куда они ведут.<br/>В круглых скобках выводится группа, которой отображается этот пункт меню(если там стоит all, то ссылка выводится всем)<br/>В квдратных скобках ссылки на страницу редактирования и на удаление пункта меню.<br/><br/>";
	
		$menu_arr = $db->getAllOnField("menus","id",$adm[4],"id",true);
		$menu = $menu_arr[0];
		
                if($menu['links'] === ""){$content .= "( пусто )<br/>";}
		else{
			$links = explode(",",$menu['links']);
			$i = 1;
			foreach($links as $num => $link){
                                if($link === ""){continue;}
				$link = explode("|",$link);
				$content .= ($i).".<a href='".$link[1]."'>".$link[0]."</a>(".$link[2].") [<a href='%adress%/index.php/admin/menus/edit/".$menu['alias']."/".$num."'>Редактировать</a> | <a href='%adress%/index.php/admin/menus/delelem/".$menu['alias']."/".$num."'>Удалить</a>]<br/>";
				$i++;
			}
		}
		
		$content .= "<br/><a href='%adress%/index.php/admin/menus/addlink/".$adm[4]."'>Добавить элемент меню.</a>";
	
	}elseif($adm[3] == "addlink"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>К списку меню.</a> | <a href='%adress%/index.php/admin/menus/view/".$adm[4]."'>К списку элементов.</a>";
		
		$menu_arr = $db->getAllOnField("menus","id",$adm[4],"id",true);
		$menu = $menu_arr[0];
		
		$content .= "<br/>Добавление нового элемента меню.";
		
		$arr['menu_id'] = $adm[4];
		$content .= libs::GetLib("templates")->getRTmpl("admin\add_link",$arr);
	
	}elseif($adm[3] == "savlink"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>К списку меню.</a> | <a href='%adress%/index.php/admin/menus/view/".$adm[4]."'>К списку элементов.</a>";
		
		$menu_arr = $db->getAllOnField("menus","id",$adm[4],"id",true);
		$menu = $menu_arr[0];
		
		$lnk = str_replace(config::site_url,"%adress%",filter_input(INPUT_POST,'link'));
		
                if(filter_input(INPUT_POST,'num') === "add"){$db->setField("menus","links",filter_input(INPUT_POST,'title')."|".$lnk."|".filter_input(INPUT_POST,'group').",".$menu['links'],"id",$adm[4]);}
		else{
			$mn = explode(",",$menu['links']);
			$i = 0;
			foreach($mn as $num=>$link){
                                if($num != 0){$links .= ",";}
				if(filter_input(INPUT_POST,'num') == $num){
					$links .= filter_input(INPUT_POST,'title')."|".$lnk."|".filter_input(INPUT_POST,'group');
				}else{ $links .= $mn[$i]; }
				$i++;
			}
			$db->setField("menus","links",$links,"id",$adm[4]);
		}
		$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"Пункт меню успешно сохранен."));
	
	}elseif($adm[3] == "edit"){
	
		if($adm[4]!="" && $adm[5]!=""){
		
			$content .= " | <a href='%adress%/index.php/admin/menus'>К списку меню.</a> | <a href='%adress%/index.php/admin/menus/view/".$adm[4]."'>К списку элементов.</a>";
		
			$menu_arr = $db->getAllOnField("menus","alias",$adm[4],"id",true);
			$menu = $menu_arr[0];
			
			$links = explode(",",$menu['links']);
			
			foreach($links as $num=>$link){
			
				if($num == $adm[5]){
					$arr = explode("|",$link);
					$arr['num'] = $num;
					$arr['menu_id'] = $menu['id'];
					$content .= libs::getLib("templates")->getRTmpl("admin/lnk_edit",$arr);
				}
			
			}
		
		}
	
	}elseif($adm[3] == "delelem"){
	
		$menu_arr = $db->getAllOnField("menus","alias",$adm[4],"id",true);
		$menu = $menu_arr[0];	
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>К списку меню.</a> | <a href='%adress%/index.php/admin/menus/view/".$menu['id']."'>К списку элементов.</a>";
		
		$mn = explode(",",$menu['links']);
		$i = 0;
		foreach($mn as $num=>$link){
                        if($adm[5] == $num || $link === ""){$i++;}
			else{
                        if($num != 0){$links .= ",".$mn[$i];}
                        else{$links .= $mn[$i];}
			$i++;
			}
		}
		$db->setField("menus","links",$links,"alias",$adm[4]);
		$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"Пункт меню успешно удален."));
	
	}elseif($adm[3] === "new"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>К списку меню.</a>";
                if(filter_input(INPUT_POST,'alias') == null){$content .= libs::GetLib("templates")->getTmpl("admin/new_menu");}
		else{
		
			if(!$db->isExists("menus","alias",filter_input(INPUT_POST,'alias'))){
				$db->insert("menus",array("alias"=>filter_input(INPUT_POST,'alias'),"title"=>filter_input(INPUT_POST,'title')));
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"Меню '".filter_input(INPUT_POST,'title')."' успешно создано."));
                        }else{$content .= libs::GetLib("templates")->getRTmpl("error",array("message"=>"Меню с алиасом '".filter_input(INPUT_TYPE,'alias')."' уже существет, поменяйте алиас."));}
		}
		
	
	}elseif($adm[3] === "remmen"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>К списку меню.</a>";
		
		if($db->isExists("menus","id",$adm[4])){
		
			$db->deleteOnId("menus",$adm[4]);
			$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"Меню с id ".$adm[4]." успешно удалено."));
		
                }else{$content .= libs::GetLib("templates")->getRTmpl("error",array("message"=>"Меню с id ".$adm[4]." не найдено."));}
		
        }else{$content .= "<br/>Ошибка";}

?>