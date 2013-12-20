<?php

// Менеджер блоков
// Ну собтвенно создание/редактирование/удаление блоков.

	$content = "<h5>Редактор блоков</h5><a href='%adress%/index.php/admin'>На главную страницу панели управления.</a>";
	$db = libs::GetLib("database");
	
	if($adm[3] == ""){
	
		$blocks = $db->getAll("blocks","id",true);
		$content .= "<br/>Выберите блок, который хотите отредактировать или удалить.<br/><br/>";
		
		foreach($blocks as $num => $block){
		
			$content .= ($num+1)."."."<a href='%adress%/index.php/admin/blocks/edit/".$block['id']."'>".$block['title']."</a> (<a href='%adress%/index.php/admin/blocks/del/".$block['id']."'>Удалить</a>)<br/>";
		
		}
		$content .= "<br/><a href='%adress%/index.php/admin/blocks/create'>Создать новый блок</a><br/><a href='%adress%/index.php/admin/blocks/pos'>Настройка позиций блоков.</a>";
	}elseif($adm[3] == "edit"){
	
		$block = $db->GetElementOnID("blocks", $adm[4]);
		$content .= " | <a href='%adress%/index.php/admin/blocks'>К списку блоков.</a>";
		
		libs::GetLib("templates")->SetPageTmpl("single");
		
		if(libs::LoadLib("editors/code")){
		
			$arr['id'] = $block['id'];
			$arr['code'] = libs::GetLib("editors/code")->GetField("code",$block['code']);
			$arr['title'] = $block['title'];
			$arr['alias'] = $block['alias'];
			$arr['position'] = $block['position'];
			
			$arr['method'] = "get";
			$arr['action'] = "%adress%/index.php/admin/blocks/save/".$block['id'];
			
			$content .= libs::GetLib("templates")->getRTmpl("admin/block_edit",$arr);
		
			//$content .= "<br/><div class='sidebox'><form method='get' action='%adress%/index.php/admin/blocks/save/".$block['id']."'><input type='submit' value='Сохранить'/><br/>Название блока(отображается в заголовке блока): <input type='text' /><br/>Алиас блока(используется в теге content в качестве параметра name):<input type='text' /><br/>Позиция блока(параметр name из тега позици или алиас позиции): <input type='text' />Код блока:".libs::GetLib("editors/code")->GetField("code",$block['code'])."</form></div><div style='height: 20px;'></div>";
					
                }else{$content .= "<div class='warning-box'>Не найдена библиотека визуального редактора 'lib/editors/visual'.<br/>Установите соответствующую библиотеку для редактирования блоков.</div>";}
	
	}elseif($adm[3] == "create"){
	
	$content .= " | <a href='%adress%/index.php/admin/blocks'>К списку блоков.</a>";
		
		libs::GetLib("templates")->SetPageTmpl("single");
		
		if(libs::LoadLib("editors/code")){
		
			$arr['id'] = $db->getLastID("blocks")+1;
			$arr['code'] = libs::GetLib("editors/code")->GetField("code","");
			$arr['title'] = "";
			$arr['alias'] = "";
			$arr['position'] = "sidebar";
			
			$arr['method'] = "get";
			$arr['action'] = "%adress%/index.php/admin/blocks/save/".$arr['id'];
			
			$content .= libs::GetLib("templates")->getRTmpl("admin/block_edit",$arr);	
			
                }else{$content .= "<div class='warning-box'>Не найдена библиотека визуального редактора 'lib/editors/visual'.<br/>Установите соответствующую библиотеку для редактирования блоков.</div>";}
	
	}elseif($adm[3] == "pos"){
	
		$content .= " | <a href='%adress%/index.php/admin/blocks'>К списку блоков.</a>";
		
		if($adm[4] == ""){
		
		$content .= "<br/>Выберите позицию, в которой вы хотите изменить порядок блоков(пустые позиции скрыты).<br/><br/>";
		
		$blocks = $db->getAll("blocks","position",true);
		
		foreach($blocks as $num => $block){
		
			$positions[$block['position']]++;
		
		}
		
		$i = 1;
		
		foreach($positions as $name=>$count){
			$content .= $i.".<a href='%adress%/index.php/admin/blocks/pos/".$name."'>".$name."</a>(".$count.")";
			$i++;
		}
		
		}else{
			$content .= " | <a href='%adress%/index.php/admin/blocks/pos'>К списку позиций.</a>";
			$blocks = $db->getAllOnField("blocks","position",$adm[4],"title",true);
			$content .= "<br/>В списке ниже находятся блоки, а в текстовом поле после названия блока его порядок отображения.<br/><br/><form action='%adress%/index.php/admin/blocks/savepos/' method='get'>";
			foreach($blocks as $num=>$block){
				$content .= ($num+1).".".$block['title']." <input type='text' name='".$block['id']."' value='".$block['order']."' style='width: 30px;'/><br/>";
			}
			$content .= "<input type='submit' value='Сохранить'/></form>";
		}
	
	
	}elseif($adm[3] == "savepos"){
	
		$content .= " | <a href='%adress%/index.php/admin/blocks'>К списку блоков.</a>";
		$content .= " | <a href='%adress%/index.php/admin/blocks/pos'>К списку позиций.</a>";
		
		foreach(filter_input_array(INPUT_GET) as $alias=>$order){
			$db->setField("blocks","order",$order,"id",$alias);
		}
		$content .= libs::GetLib("templates")->GetRTmpl("success",array("message"=>"Позиции блоков сохранены."));
	
	}elseif($adm[3] == "del"){
		
		$content .= " | <a href='%adress%/index.php/admin/blocks'>К списку блоков.</a>";
		$ex = $db->isExists("blocks","id",$adm[4]);
		if($ex){
			$title = $db->GetField("blocks","title","id",$adm[4]);
			$db->DeleteOnID("blocks",$adm[4]);
			$content .= libs::GetLib("templates")->GetRTmpl("success",array("message"=>"Блок '$title' удален."));
                }else{$content .= libs::GetLib("templates")->GetRTmpl("error",array("message"=>"Блок с таким id не найден."));}
		
	}elseif($adm[3] == "save"){
	
		$content .= " | <a href='%adress%/index.php/admin/blocks'>К списку блоков.</a>";
	
		if(filter_input(INPUT_GET,'code') != "" && $adm[4] != "" && libs::GetLib("database")->isExists("blocks","id",$adm[4])){
		
			$code = str_replace(config::site_url,"%adress%",filter_input(INPUT_GET,'code'));
		
			libs::GetLib("database")->setField("blocks","code",$code,"id",$adm[4]);
			libs::GetLib("database")->setField("blocks","title",filter_input(INPUT_GET,'title'),"id",$adm[4]);
			libs::GetLib("database")->setField("blocks","alias",filter_input(INPUT_GET,'alias'),"id",$adm[4]);
			libs::GetLib("database")->setField("blocks","position",filter_input(INPUT_GET,'position'),"id",$adm[4]);
			
			$content .= libs::GetLib("templates")->GetRTmpl("success", array('message'=>'Блок "'.filter_input(INPUT_GET,'title').'" успешно сохранен.'));
		
		}elseif(filter_input(INPUT_GET,'code') != "" && $adm[4] != "" && !libs::GetLib("database")->isExists("blocks","id",$adm[4])){
		
			$code = str_replace(config::site_url,"%adress%",filter_input(INPUT_GET,'code'));
			
			$block['code'] = $code;
			$block['title'] = filter_input(INPUT_GET,'title');
			$block['alias'] = filter_input(INPUT_GET,'alias');
			$block['position'] = filter_input(INPUT_GET,'position');
			
			libs::GetLib("database")->insert("blocks",$block);
			
			$content .= libs::GetLib("templates")->GetRTmpl("success", array('message'=>'Блок "'.filter_input(INPUT_GET,'title').'" успешно сохранен.'));
		
		}else{
			
			$content .= libs::GetLib("templates")->GetRTmpl("error", array('message'=>'При сохранении произошла ошибка.'));
			
		}
	
        }else{$content .= "<br/><br/><div class='warning-box'>Не известный тип операции с блоками.</div>";}

?>