<?php

	$content = "<h5>�������� �������</h5><a href='%adress%/index.php/admin'>�� ������� �������� ������ ����������.</a>";
	$db = libs::GetLib("database");
	
	switch($adm[3]){
	
		case "":
		
			$content .= "<br/><br/>��� �������� �� �������� ������� � ������ �������� ������ �������� � ��������� �� ������.<br/>��� �������������� �������� ������� �������� ������ �������� � � ����-�� ������� ������� �� ������ '�������������'.<br/><br/>";
		
			$pages = $db->getAll("pages","id",true);
			
			foreach( $pages as $num=>$page ){
			
				$content .= ($num+1).".<a href='%adress%/index.php/".$page['alias']."'>".$page['title']."</a> [<a href='%adress%/index.php/admin/pages/edit/".$page['id']."'>�������������</a> | <a href='%adress%/index.php/admin/pages/edit-code/".$page['id']."'>������������� HTML-���</a> | <a href='%adress%/index.php/admin/pages/delete/".$page['id']."'>�������</a>]<br/>";
			
			}
			
			$content .= "<br/><a href='%adress%/index.php/admin/pages/add'>������� ����� ��������</a>";
		
		break;
		
		case "edit":
		
			$page_arr = $db->getAllOnField("pages","id",$adm[4],"id",true);
			$page = $page_arr[0];
			$page['action'] = "%adress%/index.php/admin/pages/save/".$page['id'];$page['method'] = "post";
			$content .= " | <a href='%adress%/index.php/admin/pages/'>� ������ �������</a>";
			
			preg_match_all("|<content (.*)/>|U", $page['content'], $text_reply, PREG_SPLIT_NO_EMPTY);
                        foreach($text_reply[0] as $num=>$tag){$page['content'] = str_replace($tag,"[content ".$text_reply[1][$num]."]",$page['content']);}
				
			
				
				//echo "<br/>".$tag['name'].":".$tag['type']."<br/>\r\n";
				//echo $text_reply[0][$num]."<hr/>";
				$text = str_replace($text_reply[0][$num],libs::GetLib("templates_types")->GetContentByTag($tag),$text);
			
                        if(libs::LoadLib("editors/visual")){$page['edit'] = libs::GetLib("editors/visual")->GetField("code",$page['content']);}
                        else{$page['edit'] .= "�������������� � ���������� ��������� �� ��������, �.�. �� ������� ��������� ���������� 'editors/visual'<br/><textarea name='code'>".$page['content']."</textarea>";}
			
			$content .= libs::GetLib("templates")->getRTmpl("admin/page_edit",$page);
		
		break;
		
                case "edit-code":
		
			$page_arr = $db->getAllOnField("pages","id",$adm[4],"id",true);
			$page = $page_arr[0];
			$page['action'] = "%adress%/index.php/admin/pages/save/".$page['id'];$page['method'] = "post";
			$content .= " | <a href='%adress%/index.php/admin/pages/'>� ������ �������</a>";
			
			preg_match_all("|<content (.*)/>|U", $page['content'], $text_reply, PREG_SPLIT_NO_EMPTY);
                        foreach($text_reply[0] as $num=>$tag){$page['content'] = str_replace($tag,"[content ".$text_reply[1][$num]."]",$page['content']);}
				
			
				
				//echo "<br/>".$tag['name'].":".$tag['type']."<br/>\r\n";
				//echo $text_reply[0][$num]."<hr/>";
				$text = str_replace($text_reply[0][$num],libs::GetLib("templates_types")->GetContentByTag($tag),$text);
			
                        if(libs::LoadLib("editors/code")){$page['edit'] = libs::GetLib("editors/code")->GetField("code",$page['content']);}
                        else{$page['edit'] .= "�������������� � ��������� ���� �� ��������, �.�. �� ������� ��������� ���������� 'editors/code'<br/><textarea name='code'>".$page['content']."</textarea>";}
			
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
				
				$content .= " | <a href='%adress%/index.php/admin/pages'>� ������ �������.</a>";
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"�������� '".filter_input(INPUT_POST,'title')."' ������� ���������."));
			}else{
				$page = array("alias"=>filter_input(INPUT_POST,'alias'),"title"=>filter_input(INPUT_POST,'title'),"content"=>$code);
				$db->insert("pages",$page);
				
				$content .= " | <a href='%adress%/index.php/admin/pages'>� ������ �������.</a>";
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"�������� '".filter_input(INPUT_POST,'title')."' ������� ���������."));
			
			}
			
		break;
		
		case "delete":
		
			$ex = $db->isExists("pages","id",$adm[4]);
			if($ex){
				$title = $db->GetField("pages","title","id",$adm[4]);
				$db->DeleteOnID("pages",$adm[4]);
				$content .= libs::GetLib("templates")->GetRTmpl("success",array("message"=>"�������� '$title' �������."));
                        }else{$content .= libs::GetLib("templates")->GetRTmpl("error",array("message"=>"�������� � ����� id �� �������."));}
			
		break;
		
		case "add":
		
			$content .= " | <a href='%adress%/index.php/admin/pages'>� ������ �������.</a>";
			
			
			$page = array("content"=>"","title"=>"","alias"=>"");
			$page['action'] = "%adress%/index.php/admin/pages/save/".$page['id'];$page['method'] = "post";
			
                        if(libs::LoadLib("editors/visual")){$page['edit'] = libs::GetLib("editors/visual")->GetField("code",$page['content']);}
                        else{$page['edit'] .= "�������������� � ���������� ��������� �� ��������, �.�. �� ������� ��������� ���������� 'editors/visual'<br/><textarea name='code'>".$page['content']."</textarea>";}
			
			$content .= libs::GetLib("templates")->getRTmpl("admin/page_edit",$page);
			
		
		break;
	
		default:
		
			$content .= "<br/>������";
		
		break;
	
	}
	
?>