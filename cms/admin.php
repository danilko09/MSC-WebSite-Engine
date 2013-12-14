<?php

	$content_db[0]['title'] = "Административная панель";
	$content = "";
	
	
	if(libs::LoadLib("users")){
		$auth = libs::GetLib("users")->IsAuthorized();
                
		if($auth != "1"){
                libs::GetLib("users")->TryLogin();
                $auth = libs::GetLib("users")->IsAuthorized();
                }
                
		$login = libs::GetLib("users")->GetLogin();
		$group = libs::GetLib("users")->GetGroup();
	}else{
		$auth = "no_lib";
	}

	if($auth == "1" && $group == "admin"){
	
		if($adm[2] == ""){
                    
                $menu = libs::GetLib("database")->getAll("admin","group", true);
	
			foreach($menu as $num=>$link){
			
				$links[$link['group']] .= "<a href='%adress%/index.php/admin/".$link['alias']."'>".$link['title']."</a><br/>";
			
			}
			
			foreach($links as $group=>$cnt){
			
				$content .= str_replace("[title]", $group,str_replace("[content]",$cnt,libs::GetLib("templates")->GetTmpl("admin/post")));
			
			}
			
		}else{
		
                        if(is_file("scripts/admin/".$adm[2].".php")){include_once("scripts/admin/".$adm[2].".php");}
                        else{$content = "<div><h5>Ошибка 404 | <a href='%adress%/index.php/admin'>в административную панель</a></h5>Не найден файл '%adress%/scripts/admin/".$adm[2].".php'.<br/><br/>Возможно вы попали на эту страницу случайно набрав не верный адрес страницы, или расширение, к которому принадлежит этот файл, было удалнео.</div>";}
		
		}
			
	}elseif($auth == "1" && $group != "admin"){
		$content .= "<div class='warning-box'>Вы не состоите в группе 'администраторы' и не можете получить доступ к этой странице.</div>";
	}elseif($auth == "0" || $auth == null){
	
		$content .= "<div class='warning-box'>Вы не авторизированны.</div><div style='width: 150px;'><p>".libs::GetLib("users")->GetLoginForm()."</p></div>";
	
	}elseif($auth == "no_lib"){
		$content = "<div class='warning-box'>Не найдена библиотека 'users', она используется для авторизации посетителей сайта, без нее доступ к административной панели не возможен.</div>";
	}
?>