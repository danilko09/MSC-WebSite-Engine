<?php

	$content = "<h5>�������� ��������</h5><a href='%adress%/index.php/admin'>�� ������� �������� ������ ����������.</a>";
	$db = libs::GetLib("database");
	
	if($adm[3] == ""){
	
		$scripts = $db->getAll("scripts","id",true);
		$content .= "<br/>������� ������ � ������ � �������� ��������, ������� ������ ���������.<br/>��� ������� �� �������� �������, ������ �������� �� ������ ��������.<br/><br/>";
		$content .= "�������:<br/>";
		foreach($scripts as $num => $script){
		
			$link['code'] = "%adress%/index.php/admin/scripts/edit/code/".$script['id'];
			$link['delete'] = "%adress%/index.php/admin/scripts/delete/".$script['id'];
			
			$content .= ($num+1)."."."<a href='%adress%/index.php/admin/scripts/edit/properties/".$script['id']."'>".$script['title']."</a> [<a href='".$link['code']."'>������������� ���</a> | <a href='".$link['delete']."'>�������</a>]<br/>";
		
		}
		
                $dir = opendir('lib');
                $content .= "<br/>����������: <br/>";
                $f = 1;
                while($d = readdir($dir)){
                    if($d != "." && $d != ".." && !is_dir("lib/".$d)){
                        
                        $a = explode('.', $d);
                        $content .= $f.".<a href='%adress%/index.php/admin/scripts/libs/edit/".$d."'>".$a[0]."</a> [<a href='%adress%/index.php/admin/scripts/libs/edit/".$d."'>������������� ���</a> | <a href='%adress%/index.php/admin/scripts/libs/del/".$d."'>�������</a>]<br/>";
                        $f++;

                    }
		}
		
                
		$content .= "<div><br/><a href='%adress%/index.php/admin/scripts/register'>���������������� ����� ������.</a><br/></div>";
		
	}
        elseif($adm[3] == "edit"){
	
		$content .= " | <a href='%adress%/index.php/admin/scripts/'>� ������� ��������</a>";
	
		switch($adm[4]){
		
			case "code":
			
				libs::GetLib("templates")->SetPageTmpl("single");
				
				if($adm[5] != "" && $adm[5] != null && $db->isExists("scripts", "id", $adm[5])){

					$file = $db->GetFieldOnID("scripts",$adm[5],"file");
                                        if(is_file("scripts/".$file.".php")){$scode = file_get_contents("scripts/".$file.".php");}
                                        else{$scode = "<?php \r\n\r\nclass ".$db->GetFieldOnID("scripts",$adm[5],"alias")."{\r\n\r\n}\r\n\r\n?>";}
                                        if(libs::LoadLib("editors/code")){$content .= libs::getLib("templates")->getRTmpl("admin/script_edit_code",array("code"=>libs::GetLib("editors/code")->GetField("code",$scode),"method"=>"post","action"=>"%adress%/index.php/admin/scripts/save/code/".$adm[5]));}
                                        else{$content .= libs::getLib("templates")->getRTmpl("error",array("message"=>"�� ������� ��������� ���������� ��������� ����."));}
					

				}else{
					$content .= libs::getLib("templates")->getRTmpl("error",array("message"=>"����������� �� ������ id ��� id �� ������������� �������."));
				}
				
			break;
			
			/*case "entries":
				
				if($adm[5] != "add" && $adm[5] != "del" && $adm[5] != "" && $db->isExists("scripts", "id", $adm[5])){
				
					$script = $db->getAllOnField("scripts","id",$adm[5],"id",true);
					
					$script_e = $db->getAllOnField("scripts_entries","script_alias",$script[0]['alias'],"id",true);
					
					$ent = explode(",", $script[0]['entry_points']);
					
					foreach($ent as $a=>$entry){
					
						$e = explode("|",$entry);
						$real[$e[0]] = "1";
					
					}
						
					$act['n'] = 1;
					$unact['n'] = 1;
					$deffect['n'] = 1;
					
					if(is_array($script_e)){
						foreach($script_e as $a=>$value){
						
							if($real[$value['entry_title']] == "1"){
							
								$act['code'] .= ($act['n']).".".$value['entry_title']." [<a href='%adress%/index.php/admin/scripts/edit/entries/del/".$value['id']."'>���������</a>]<br/>";
								$acr['n']++;
								$real[$value['entry_title']] = "0";
							
							}elseif($real[$value['entry_title']] == "0"){
								$deffect['code'] .= $deffect['n'].".".$value['entry_title']." (����� �������� ������� �����)[<a href='%adress%/index.php/admin/scripts/edit/entries/del/".$value['id']."'>�������</a>]<br/>";
								$deffect['n']++;
							}else{
								$deffect['code'] .= $deffect['n'].".".$value['entry_title']." (��������� �� ����������� ������� �����)[<a href='%adress%/index.php/admin/scripts/edit/entries/del/".$value['id']."'>�������</a>]<br/>";
								$deffect['n']++;
							}
						
						}
					}
					
					foreach($real as $code=>$num){
					
						if($num == "1"){
							$unact['code'] .= $unact['n'].".".$code." [<a href='%adress%/index.php/admin/scripts/edit/entries/add/".$script[0]['alias']."/".$code."'>������������</a>]<br/>";
						}
					
					}
					
					if($act['code'] == "") $act['code'] = "[��� �������� ������� �����]";
					if($unact['code'] == "") $unact['code'] = "[��� ���������� ������� �����]";
					if($deffect['code'] == "") $deffect['code'] = "[��� ���������� ������� �����]";
					
					$arr['active'] = $act['code'];
					$arr['unactive'] = $unact['code'];
					$arr['deffect'] = $deffect['code'];
				
					$content .= libs::GetLib("templates")->getRTmpl("admin/script_edit_entries",$arr);
					
				}elseif($adm[5] == "add"){
				
					$script = $db->getAllOnField("scripts","alias",$adm[6],"id",true);
					
					if($db->isExists("scripts_entries","entry_title",$adm[7])){
						$content .= libs::GetLib("templates")->getRTmpl("error",array("message"=>"������ ������� ����� ��� �������.<br/>���� �� ��������� ��� ��������� ��� ��������� �� �������� ������� �����, ��, ��������, ��� ������� ����� ��� ������������ ������ ��������."));
					}else{
						$db->insert("scripts_entries",array("entry_title"=>$adm[7],"script_alias"=>$adm[6]));
						$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"������� ����� ������� ������������."));
					}
				
				}elseif($adm[5] == "del"){
				
					if($db->isExists("scripts_entries","id",$adm[6])){
						$db->deleteOnID("scripts_entries",$adm[6]);
						$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"������� ����� ������� ���������."));
					}else{
						$content .= libs::GetLib("templates")->getRTmpl("error",array("message"=>"������ ������� ����� ��� �� ������� ��� ���� �������."));
					}
				
				}else{
					$content .= libs::getLib("templates")->getRTmpl("error",array("message"=>"����������� �� ������ id ��� id �� ������������� �������."));
				}
				
			break;*/
			
			case "properties":
				
				if($adm[5] != "" && $adm[5] != null && $db->isExists("scripts", "id", $adm[5])){
					$sc = $db->GetAllOnField("scripts","id",$adm[5],"id",true);
					unset($sc[0]['entry_points']);
					$sc[0]['method'] = "post";
					$sc[0]['action'] = "%adress%/index.php/admin/scripts/save/properties/".$adm[5];
					
					$content .= libs::getLib("templates")->getRTmpl("admin/script_edit",$sc[0]);
				}else{
					$content .= libs::getLib("templates")->getRTmpl("error",array("message"=>"����������� �� ������ id ��� id �� ������������� �������."));
				}
				
			break;
			
			default:
				$content .= libs::GetLib("templates")->getRTmpl("error",array("message"=>"�� ������ �������� �������������� �������."));
			break;
		
		}
	
	}elseif($adm[3] == "save"){
	
	
		$content .= " | <a href='%adress%/index.php/admin/scripts/'>� ������� ��������</a>";
	
		switch($adm[4]){
		
			case "code":
				$script = $db->getAllOnField("scripts","id",$adm[5],"id",true);
				
				$code = filter_input(INPUT_POST,'code');
				
				$file = fopen("scripts/".$script[0]['file'].".php", "w");
				fwrite($file, $code);
				fclose($file);
				
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"��� ������� '".$script[0]['title']."' ������� ���������."));			
			break;
                    
                        case "lib_code":
				
                                $class_arr = explode(".",$adm[5]);
                                $class = $class_arr[0];
                                
				$code = filter_input(INPUT_POST,"code");
				
				$file = fopen("lib/".$adm[5], "w");
				fwrite($file, $code);
				fclose($file);
				
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"��� ���������� '".$class."' ������� ���������."));			
			break;
			
			case "properties":
				$script = $db->getAllOnField("scripts","id",$adm[5],"id",true);
				$db->setField("scripts","title",filter_input(INPUT_POST,'title'),"id",$adm[5]);
				$db->setField("scripts","alias",filter_input(INPUT_POST,'alias'),"id",$adm[5]);
				$db->setField("scripts","file",filter_input(INPUT_POST,'file'),"id",$adm[5]);
				
				$content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"��������� ������� '".$script[0]['title']."' ������� ���������."));			
			break;
		
			default:
				$content .= libs::getLib("templates")->getRTmpl("error",array("message"=> "�� ������ ��������."));
			break;
		
		}
	
	}
        elseif($adm[3] == "delete"){
	
		$content .= " | <a href='%adress%/index.php/admin/scripts/'>� ������� ��������</a>";	

		if($db->isExists("scripts","id",$adm[4])){
		
			$title = $db->GetField("scripts","title","id",$adm[4]);
                        if(is_file("scripts/".$db->GetField("scripts","file","id",$adm[4]).".php")){unlink("scripts/".$db->GetField("scripts","file","id",$adm[4]).".php");}
			$db->DeleteOnID("scripts",$adm[4]);
			$content .= libs::GetLib("templates")->GetRTmpl("success",array("message"=>"������ '$title' ������."));

                }else{$content .= libs::GetLib("templates")->GetRTmpl("error",array("message"=>"������ � ����� id �� ������."));}
			
	
	}
        elseif($adm[3] == "register"){
	
		$content .= " | <a href='%adress%/index.php/admin/scripts/'>� ������� ��������</a>";
		
		if(filter_input(INPUT_POST,'alias') == null || filter_input(INPUT_POST,'file') == null){
			$content .= libs::GetLib("templates")->GetTmpl("admin/reg_script");
		}else{
                        if(filter_input(INPUT_POST,'title') == null){$title = "undefined | ".filter_input(INPUT_POST,'alias');}
                        else{$title = filter_input(INPUT_POST,'title');}
			$alias = filter_input(INPUT_POST,'alias');
			$file = filter_input(INPUT_POST,'file');
			
			if(!$db->isExists("scripts","alias",$alias)) {$db->insert("scripts",array("alias"=>$alias,"title"=>$title,"file"=>$file)); $content .= libs::GetLib("templates")->getRTmpl("success",array("message"=>"������ '".$title."' ������� ���������������."));} 
                        else{$content .= libs::GetLib("templates")->getRTmpl("error",array("message"=>"������ � ������� '".$alias."' ��� ��������������� �����."));}
		}
	
	}
        elseif($adm[3] == "libs"){
            $content .= " | <a href='%adress%/index.php/admin/scripts/'>� ������ ��������.</a>";
        
            if($adm[4] == "edit"){
                libs::GetLib("templates")->SetPageTmpl("single");
				
                if($adm[5] != "" && $adm[5] != null){

                        $class_arr = explode('.',$adm[5]);
                        $class = $class_arr[0];
                        if(is_file("lib/".$adm[5])){$scode = file_get_contents("lib/".$adm[5]);}
                        else{$scode = "<?php \r\n\r\nclass ".$class."{\r\n\r\n}\r\n\r\n?>";}
                        if(libs::LoadLib("editors/code")){$content .= libs::getLib("templates")->getRTmpl("admin/script_edit_code",array("code"=>libs::GetLib("editors/code")->GetField("code",$scode),"method"=>"post","action"=>"%adress%/index.php/admin/scripts/save/lib_code/".$adm[5]));}
                        else{$content .= libs::getLib("templates")->getRTmpl("error",array("message"=>"�� ������� ��������� ���������� ��������� ����."));}


                }else{$content .= libs::getLib("templates")->getRTmpl("error",array("message"=>"����������� �� ������ id ��� id �� ������������� �������."));}
            }else{$content .= libs::GetLib("templates")->getRTmpl("error",array("message"=>"����������� ������� ��� ���������."));}
            
        }
        else{$content .= " | <a href='%adress%/index.php/admin/scripts/'>� ������� ��������</a><br/><br/><div class='warning-box'>�� ��������� ��� �������� �� ���������.</div>";}

?>