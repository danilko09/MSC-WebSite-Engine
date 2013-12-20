<?php

// Менеджер страниц
// Имеет зависимость от визуального редактора текста и кода
// Пользоваться можно, есть какие-либо пожелания/замечания/подсказки, обращайтесь к автору CMS, хотя вроде нормально работает и более-менее удобно

	$content = "<h5>Менеждер страниц</h5><a href='%adress%/index.php/admin'>На главную страницу панели управления.</a>";
	$db = libs::GetLib("database");
	
	switch($adm[3]){
	
		case "":
		
			$content .= "<br/><br/>Для перехода на страницу найдите в списке название нужной страницы и перейдите по ссылке.<br/>Для редактирования страницы найдите название нужной страницы и в этой-же строчке нажмите по ссылке 'Редактировать'.<br/><br/>";
		
			$pages = $db->getAll("pages","id",true);
			
			foreach( $pages as $num=>$page ){
			
				$content .= ($num+1).".<a href='%adress%/index.php/".$page['alias']."'>".$page['title']."</a> [<a href='%adress%/index.php/admin/pages/edit/".$page['id']."'>Редактировать</a> | <a href='%adress%/index.php/admin/pages/edit-code/".$page['id']."'>Редактировать HTML-код</a> | <a href='%adress%/index.php/admin/pages/delete/".$page['id']."'>Удалить</a>]<br/>";
			
			}
			
			$content .= "<br/><a href='%adress%/index.php/admin/pages/add'>Создать новую страницу</a>";
		
		break;
		
		case "edit":
		
			$page_arr = $db->getAllOnField("pages","id",$adm[4],"id",true);
			$page = $page_arr[0];
			$page['action'] = "%adress%/index.php/admin/pages/save/".$page['id'];$page['method'] = "post";
			$content .= " | <a href='%adress%/index.php/admin/pages/'>К списку страниц</a>";
			
			preg_match_all("|<content (.*)/>|U", $page['content'], $text_reply, PREG_SPLIT_NO_EMPTY);
                        foreach($text_reply[0] as $num=>$tag){$page['content'] = str_replace($tag,"[content ".$text_reply[1][$num]."]",$page['content']);}
				
			
				
				//echo "<br/>".$tag['name'].":".$tag['type']."<br/>\r\n";
				//echo $text_reply[0][$num]."<hr/>";
				$text = str_replace($text_reply[0][$num],libs::GetLib("templates_types")->GetContentByTag($tag),$text);
			
                        if(libs::LoadLib("editors/visual")){$page['edit'] = libs::GetLib("editors/visual")->GetField("code",$page['content']);}
                        else{$page['edit'] .= "Редактирование в визуальном редакторе не доступно, т.к. не удалось загрузить библиотеку 'editors/visual'<br/><textarea name='code'>".$page['content']."</textarea>";}
			
			$content .= libs::GetLib("templates")->getRTmpl("admin/page_edit",$page);
		
		break;
		
                case "edit-code":
		
			$page_arr = $db->getAllOnField("pages","id",$adm[4],"id",true);
			$page = $page_arr[0];
			$page['action'] = "%adress%/index.php/admin/pages/save/".$page['id'];$page['method'] = "post";
			$content .= " | <a href='%adress%/index.php/admin/pages/'>К списку страниц</a>";
			
			preg_match_all("|<content (.*)/>|U", $page['content'], $text_reply, PREG_SPLIT_NO_EMPTY);
                        foreach($text_reply[0] as $num=>$tag){$page['content'] = str_replace($tag,"[content ".$text_reply[1][$num]."]",$page['content']);}
				
			
				
				//echo "<br/>".$tag['name'].":".$tag['type']."<br/>\r\n";
				//echo $text_reply[0][$num]."<hr/>";
				$text = str_replace($text_reply[0][$num],libs::GetLib("templates_types")->GetContentByTag($tag),$text);
			
                        if(libs::LoadLib("editors/code")){$page['edit'] = libs::GetLib("editors/code")->GetField("code",$page['content']);}
                        else{$page['edit'] .= "Редактирование в редакторе кода не доступно, т.к. не удалось загрузить библиотеку 'editors/code'<br/><textarea name='code'>".$page['content']."</textarea>";}
			
			$content .= libs::GetLib("templates")->getRTmpl("admin/page_edit",$page);
		
		break;
		
                
		case "save":
		
			$code = str_replace(config::site_url,"%adress%",filter_input(INPUT_POST,'code'));
			
			preg_match_all("|\[content (.*)\]|U", $code, $text_reply, PREG_SPLIT_NO_EMPTY);
                        foreach($text_reply[0] as $num=>$tag){$code = str_replace($tag,"<content ".$text_reply[1][$num]."/>",$code);}
			
			if($db->isExists("pages","id",$adm[4])){
				$db->setField("pages", "title", filter_input(INPUT_POST,'title'), "id", $adm[4]);
				$db->setField("pages", "alias", filter_input(INPUT_POST,'alias'), "id", $adm[4]);
				$db->setField("pages","content",$code          , "id", $adm[4]);
				
				$content .= " | <a href='%adress%/index.php/admin/pages'>К списку страниц.</a>";
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"Страница '".filter_input(INPUT_POST,'title')."' успешно сохранена."));
			}else{
				$page = array("alias"=>filter_input(INPUT_POST,'alias'),"title"=>filter_input(INPUT_POST,'title'),"content"=>$code);
				$db->insert("pages",$page);
				
				$content .= " | <a href='%adress%/index.php/admin/pages'>К списку страниц.</a>";
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"Страница '".filter_input(INPUT_POST,'title')."' успешно сохранена."));
			
			}
			
		break;
		
		case "delete":
		
			$ex = $db->isExists("pages","id",$adm[4]);
			if($ex){
				$title = $db->GetField("pages","title","id",$adm[4]);
				$db->DeleteOnID("pages",$adm[4]);
				$content .= libs::GetLib("templates")->GetRTmpl("success",array("message"=>"Страница '$title' удалена."));
                        }else{$content .= libs::GetLib("templates")->GetRTmpl("error",array("message"=>"Страница с таким id не найдена."));}
			
		break;
		
		case "add":
		
			$content .= " | <a href='%adress%/index.php/admin/pages'>К списку страниц.</a>";
			
			
			$page = array("content"=>"","title"=>"","alias"=>"");
			$page['action'] = "%adress%/index.php/admin/pages/save/".$page['id'];$page['method'] = "post";
			
                        if(libs::LoadLib("editors/visual")){$page['edit'] = libs::GetLib("editors/visual")->GetField("code",$page['content']);}
                        else{$page['edit'] .= "Редактирование в визуальном редакторе не доступно, т.к. не удалось загрузить библиотеку 'editors/visual'<br/><textarea name='code'>".$page['content']."</textarea>";}
			
			$content .= libs::GetLib("templates")->getRTmpl("admin/page_edit",$page);
			
		
		break;
	
		default:
		
			$content .= "<br/>Ошибка";
		
		break;
	
	}
	
?>