<?php
	session_start();
    if(filter_input(INPUT_GET,'restart') == 1){$_SESSION = null; echo "<h1><font color='red'>���������� ���������� �� 1 ���. ��������� �� ������, ���� �� �������������� CMS.</font></h1>";}
	
	$finish = false;
	include("config.php");
	
	
	//����������� �������� �� �������
	
    if($_SESSION['pos'] === "" || $_SESSION['pos'] === null){$_SESSION['pos'] = 0;}
	
	if($scripts[$_SESSION['pos']]!=null)include("scripts/".$scripts[$_SESSION['pos']].".php");
    else{$content = "�� ����� ��������� �������� ������.<br/>����������, ��������� � ������������� ������� � �������� �� ����.";}
	
	//��������� �������
        
    $ech = filter_input(INPUT_SERVER,'HTTP_ORIGIN').filter_input(INPUT_SERVER,'REQUEST_URI');
	$ech = str_replace("/setup/", "", $ech);
        
	$site = str_replace("%adress%",$ech,str_replace("%content%",$message.$content,file_get_contents("tmpl.html")));
	echo $site;
    if($finish){RemoveDir("../setup");}
	
	//��������������� �������
	
	function next_stage(){
	
	
		$ech = filter_input(INPUT_SERVER,'HTTP_ORIGIN').filter_input(INPUT_SERVER,'REQUEST_URI');
		$ech = str_replace("/setup/", "", $ech);
		
		$_SESSION['pos']++;
		header("Location: $ech/setup");
	
	}
	
	function getTmpl($file){
		return file_get_contents("tmpl/".$file.".html");
	}
	
	function debug($err){
		echo $err;
	}
	
	function RemoveDir($path)
	{
		if(file_exists($path) && is_dir($path)){$dirHandle = opendir($path);
			while (false !== ($file = readdir($dirHandle))){
				if ($file!='.' && $file!='..'){
					$tmpPath=$path.'/'.$file;chmod($tmpPath, 0777);
					
                                        if (is_dir($tmpPath)){RemoveDir($tmpPath);}// ���� ����� 
					else{if(file_exists($tmpPath)){unlink($tmpPath);}/*������� ����*/}
				}
			}closedir($dirHandle);
			
			// ������� ������� �����
			if(file_exists($path)){rmdir($path);}
		}
	}

?>