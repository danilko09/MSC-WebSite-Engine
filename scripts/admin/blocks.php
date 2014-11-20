<?php

	$content = "<h5>�������� ������</h5><a href='%adress%/index.php/admin'>�� ������� �������� ������ ����������.</a>";
	$db = new database();
	
	if($adm[3] == ""){
	
		$blocks = $db->getAll("blocks","id",true);
		$content .= "<br/>�������� ����, ������� ������ ��������������� ��� �������.<br/><br/>";
		
		foreach($blocks as $num => $block){
		
			$content .= ($num+1)."."."<a href='%adress%/index.php/admin/blocks/edit/".$block['id']."'>".$block['title']."</a> (<a href='%adress%/index.php/admin/blocks/del/".$block['id']."'>�������</a>)<br/>";
		
		}
		$content .= "<br/><a href='%adress%/index.php/admin/blocks/create'>������� ����� ����</a><br/><a href='%adress%/index.php/admin/blocks/pos'>��������� ������� ������.</a>";
	}elseif($adm[3] == "edit"){
	
		$block = $db->GetElementOnID("blocks", $adm[4]);
		$content .= " | <a href='%adress%/index.php/admin/blocks'>� ������ ������.</a>";
		
		templates::SetPageTmpl("single");
		
		if(scripts::checkScript("editors_code")){
		
			$arr['id'] = $block['id'];
			$arr['code'] = editors_code::GetField("code",$block['code']);
			$arr['title'] = $block['title'];
			$arr['alias'] = $block['alias'];
			$arr['position'] = $block['position'];
		
                        var_dump($block['code']);
                        
			$arr['method'] = "get";
			$arr['action'] = "%adress%/index.php/admin/blocks/save/".$block['id'];
			
			$content .= templates::getRTmpl("admin/block_edit",$arr);
		
			//$content .= "<br/><div class='sidebox'><form method='get' action='%adress%/index.php/admin/blocks/save/".$block['id']."'><input type='submit' value='���������'/><br/>�������� �����(������������ � ��������� �����): <input type='text' /><br/>����� �����(������������ � ���� content � �������� ��������� name):<input type='text' /><br/>������� �����(�������� name �� ���� ������ ��� ����� �������): <input type='text' />��� �����:".libs::GetLib("editors/code")->GetField("code",$block['code'])."</form></div><div style='height: 20px;'></div>";
					
		}else $content .= "<div class='warning-box'>�� ������� ���������� ����������� ��������� 'editors_code'.<br/>���������� ��������������� ���������� ��� �������������� ������.</div>";
	
	}elseif($adm[3] == "create"){
	
	$content .= " | <a href='%adress%/index.php/admin/blocks'>� ������ ������.</a>";
		
		templates::SetPageTmpl("single");
		
		if(libs::LoadLib("editors/code")){
		
			$arr['id'] = $db->getLastID("blocks")+1;
			$arr['code'] = libs::GetLib("editors/code")->GetField("code","");
			$arr['title'] = "";
			$arr['alias'] = "";
			$arr['position'] = "sidebar";
			
			$arr['method'] = "get";
			$arr['action'] = "%adress%/index.php/admin/blocks/save/".$arr['id'];
			
			$content .= templates::getRTmpl("admin/block_edit",$arr);	
			
		}else $content .= "<div class='warning-box'>�� ������� ���������� ����������� ��������� 'lib/editors/visual'.<br/>���������� ��������������� ���������� ��� �������������� ������.</div>";
	
	}elseif($adm[3] == "pos"){
	
		$content .= " | <a href='%adress%/index.php/admin/blocks'>� ������ ������.</a>";
		
		if($adm[4] == ""){
		
		$content .= "<br/>�������� �������, � ������� �� ������ �������� ������� ������(������ ������� ������).<br/><br/>";
		
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
			$content .= " | <a href='%adress%/index.php/admin/blocks/pos'>� ������ �������.</a>";
			$blocks = $db->getAllOnField("blocks","position",$adm[4],"title",true);
			$content .= "<br/>� ������ ���� ��������� �����, � � ��������� ���� ����� �������� ����� ��� ������� �����������.<br/><br/><form action='%adress%/index.php/admin/blocks/savepos/' method='get'>";
			foreach($blocks as $num=>$block){
				$content .= ($num+1).".".$block['title']." <input type='text' name='".$block['alias']."' value='".$block['order']."' style='width: 30px;'/><br/>";
			}
			$content .= "<input type='submit' value='���������'/></form>";
		}
	
	
	}elseif($adm[3] == "savepos"){
	
		$content .= " | <a href='%adress%/index.php/admin/blocks'>� ������ ������.</a>";
		$content .= " | <a href='%adress%/index.php/admin/blocks/pos'>� ������ �������.</a>";
		
		foreach($_GET as $alias=>$order){
			$db->setField("blocks","order",$order,"alias",$alias);
		}
		$content .= templates::GetRTmpl("success",array("message"=>"������� ������ ���������."));
	
	}elseif($adm[3] == "del"){
		
		$content .= " | <a href='%adress%/index.php/admin/blocks'>� ������ ������.</a>";
		$ex = $db->isExists("blocks","id",$adm[4]);
		if($ex){
			$title = $db->GetField("blocks","title","id",$adm[4]);
			$db->DeleteOnID("blocks",$adm[4]);
			$content .= templates::GetRTmpl("success",array("message"=>"���� '$title' ������."));
		}else $content .= templates::GetRTmpl("error",array("message"=>"���� � ����� id �� ������."));
		
	}elseif($adm[3] == "save"){
	
		$content .= " | <a href='%adress%/index.php/admin/blocks'>� ������ ������.</a>";
	
		if($_GET['code'] != "" && $adm[4] != "" && (new database())->isExists("blocks","id",$adm[4])){
		
			$code = str_replace($url_base,"%adress%",$_GET['code']);
			$code = str_replace("\\\\","\\",$code);
			$code = str_replace("\\\"","\"",$code);
			$code = str_replace("\\'","'",$code);
		
                        $db = new database();
                        
			$db->setField("blocks","code",$code,"id",$adm[4]);
			$db->setField("blocks","title",$_GET['title'],"id",$adm[4]);
			$db->setField("blocks","alias",$_GET['alias'],"id",$adm[4]);
			$db->setField("blocks","position",$_GET['position'],"id",$adm[4]);
			
			$content .= templates::GetRTmpl("success", array('message'=>'���� "'.$_GET['title'].'" ������� ��������.'));
		
		}elseif($_GET['code'] != "" && $adm[4] != "" && !libs::GetLib("database")->isExists("blocks","id",$adm[4])){
		
			$code = str_replace(config::site_url,"%adress%",$_GET['code']);
			$code = str_replace("\\\\","\\",$code);
			$code = str_replace("\\\"","\"",$code);
			$code = str_replace("\\'","'",$code);
			
			$block['code'] = $code;
			$block['title'] = $_GET['title'];
			$block['alias'] = $_GET['alias'];
			$block['position'] = $_GET['position'];
			
			libs::GetLib("database")->insert("blocks",$block);
			
			$content .= templates::GetRTmpl("success", array('message'=>'���� "'.$_GET['title'].'" ������� ��������.'));
		
		}else{
			
			$content .= templates::GetRTmpl("error", array('message'=>'��� ���������� ��������� ������.'));
			
		}
	
	}else $content .= "<br/><br/><div class='warning-box'>�� ��������� ��� �������� � �������.</div>";

?>