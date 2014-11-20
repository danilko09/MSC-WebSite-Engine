<?php

	$content .= "<a href='%adress%/index.php/admin'>� ���������������� ������.</a>";
	$db = libs::GetLib("database");
	
	if($adm[3] == ""){
		
		$menus = $db->getAll("menus","title",true);
		
		$content .= "<br/>�������� ���� ��� ��������������.<br/><br/>";
		
		foreach($menus as $num=>$menu){
		
			$content .= ($num+1).".<a href='%adress%/index.php/admin/menus/view/".$menu['id']."'>".$menu['title']."</a>[<a href='%adress%/index.php/admin/menus/remmen/".$menu['id']."'>�������</a>]<br/>";
		
		}
		
		$content .= "<br/><a href='%adress%/index.php/admin/menus/new/'>������� ����� ����.</a><br/><br/>";
	
	}elseif($adm[3] == "view"){

		$content .= " | <a href='%adress%/index.php/admin/menus'>� ������ ����.</a><br/>�� ������ ���������� ������� �� ������� � ���������� ���� ��� �����.<br/>� ������� ������� ��������� ������, ������� ������������ ���� ����� ����(���� ��� ����� all, �� ������ ��������� ����)<br/>� ��������� ������� ������ �� �������� �������������� � �� �������� ������ ����.<br/><br/>";
	
		$menu = $db->getAllOnField("menus","id",$adm[4],"id",true);
		$menu = $menu[0];
		
		if($menu['links'] === "")$content .= "( ����� )<br/>";
		else{
			$links = explode(",",$menu['links']);
			$i = 1;
			foreach($links as $num => $link){
				if($link === "") continue;
				$link = explode("|",$link);
				$content .= ($i).".<a href='".$link[1]."'>".$link[0]."</a>(".$link[2].") [<a href='%adress%/index.php/admin/menus/edit/".$menu['alias']."/".$num."'>�������������</a> | <a href='%adress%/index.php/admin/menus/delelem/".$menu['alias']."/".$num."'>�������</a>]<br/>";
				$i++;
			}
		}
		
		$content .= "<br/><a href='%adress%/index.php/admin/menus/addlink/".$adm[4]."'>�������� ������� ����.</a>";
	
	}elseif($adm[3] == "addlink"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>� ������ ����.</a> | <a href='%adress%/index.php/admin/menus/view/".$adm[4]."'>� ������ ���������.</a>";
		
		$menu = $db->getAllOnField("menus","id",$adm[4],"id",true);
		$menu = $menu[0];
		
		$content .= "<br/>���������� ������ �������� ����.";
		
		$arr['menu_id'] = $adm[4];
		$content .= templates::getRTmpl("admin\add_link",$arr);
	
	}elseif($adm[3] == "savlink"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>� ������ ����.</a> | <a href='%adress%/index.php/admin/menus/view/".$adm[4]."'>� ������ ���������.</a>";
		
		$menu = $db->getAllOnField("menus","id",$adm[4],"id",true);
		$menu = $menu[0];
		
		$lnk = str_replace(config::site_url,"%adress%",$_POST['link']);
		
		if($_POST['num'] === "add") $db->setField("menus","links",$_POST['title']."|".$lnk."|".$_POST['group'].",".$menu['links'],"id",$adm[4]);
		else{
			$mn = explode(",",$menu['links']);
			$i = 0;
			foreach($mn as $num=>$link){
				if($num != 0) $links .= ",";
				if($_POST['num'] == $num){
					$links .= $_POST['title']."|".$lnk."|".$_POST['group'];
				}else{
					$links .= $mn[$i];
				}
				$i++;
			}
			$db->setField("menus","links",$links,"id",$adm[4]);
		}
		$content .= templates::getRTmpl("success",array("message"=>"����� ���� ������� ��������."));
	
	}elseif($adm[3] == "edit"){
	
		if($adm[4]!="" && $adm[5]!=""){
		
			$content .= " | <a href='%adress%/index.php/admin/menus'>� ������ ����.</a> | <a href='%adress%/index.php/admin/menus/view/".$adm[4]."'>� ������ ���������.</a>";
		
			$menu = $db->getAllOnField("menus","alias",$adm[4],"id",true);
			$menu = $menu[0];
			
			$links = explode(",",$menu['links']);
			
			foreach($links as $num=>$link){
			
				if($num == $adm[5]){
					$arr = explode("|",$link);
					$arr['num'] = $num;
					$arr['menu_id'] = $menu['id'];
					$content .= templates::getRTmpl("admin/lnk_edit",$arr);
				}
			
			}
		
		}
	
	}elseif($adm[3] == "delelem"){
	
		$menu = $db->getAllOnField("menus","alias",$adm[4],"id",true);
		$menu = $menu[0];	
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>� ������ ����.</a> | <a href='%adress%/index.php/admin/menus/view/".$menu['id']."'>� ������ ���������.</a>";
		
		$mn = explode(",",$menu['links']);
		$i = 0;
		foreach($mn as $num=>$link){
			if($adm[5] == $num || $link === "") $i++;
			else{
			if($num != 0) $links .= ",".$mn[$i];
			else $links .= $mn[$i];
			$i++;
			}
		}
		$db->setField("menus","links",$links,"alias",$adm[4]);
		$content .= templates::getRTmpl("success",array("message"=>"����� ���� ������� ������."));
	
	}elseif($adm[3] === "new"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>� ������ ����.</a>";
		if($_POST['alias'] == null) $content .= templates::getTmpl("admin/new_menu");
		else{
		
			if(!$db->isExists("menus","alias",$_POST['alias'])){
				$db->insert("menus",array("alias"=>$_POST['alias'],"title"=>$_POST['title']));
				$content .= templates::getRTmpl("success",array("message"=>"���� '".$_POST['title']."' ������� �������."));
			}else $content .= templates::getRTmpl("error",array("message"=>"���� � ������� '".$_POST['alias']."' ��� ���������, ��������� �����."));
		}
		
	
	}elseif($adm[3] === "remmen"){
	
		$content .= " | <a href='%adress%/index.php/admin/menus'>� ������ ����.</a>";
		
		if($db->isExists("menus","id",$adm[4])){
		
			$db->deleteOnId("menus",$adm[4]);
			$content .= templates::getRTmpl("success",array("message"=>"���� � id ".$adm[4]." ������� �������."));
		
		}else $content .= templates::getRTmpl("error",array("message"=>"���� � id ".$adm[4]." �� �������."));
		
	}else $content .= "<br/>������";

?>