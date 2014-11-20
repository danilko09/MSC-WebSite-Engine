<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

	session_start();
	
	$finish = false;
	
	//������� ��������
	
	$scripts['0'] = "welcome";
	$scripts['1'] = "database";
	$scripts['2'] = "admin";
	$scripts['3'] = "components";
	$scripts['4'] = "finish";
	
	//����������� �������� �� �������
	
        if(!isset($_SESSION['pos']) || $_SESSION['pos']  === "" || $_SESSION['pos'] === null){$_SESSION['pos'] = 0;}
	$message = "";$content = "";
	include("scripts/".$scripts[$_SESSION['pos']].".php");
	
	//��������� �������
	
	$site = file_get_contents("tmpl.html");
	$site = str_replace("%content%",$message.$content,$site);
	$ech = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$ech = str_replace("/setup/", "", $ech);
	$site = str_replace("%adress%",$ech,$site);
	echo $site;
	if($finish) RemoveDir("../setup");
	
	//��������������� �������
	
	function next_stage(){
	
	
		$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
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
	{global $message;
		if(file_exists($path) && is_dir($path))
		{
			$dirHandle = opendir($path);
			while (false !== ($file = readdir($dirHandle))) 
			{
				if ($file!='.' && $file!='..')
				{
					$tmpPath=$path.'/'.$file;
					if(!chmod($tmpPath, 0777)){
                                            $messages .= "<br/>�� ������� ������� ����� �� ���� '".$tmpPath."'";
                                        }
					if (is_dir($tmpPath))
					{  // ���� �����
						RemoveDir($tmpPath);
					} 
					else 
					{ 
						if(file_exists($tmpPath))
						{
							// ������� ���� 
                                                        if(!unlink($tmpPath)){$message .= "<br/>�� ������� ������� ���� '".$tmpPath."'.<br/>��������� ������� ���������, ������� ����� 'setup' �� �����.";return;}
                                                        
						}
					}
				}
			}
			closedir($dirHandle);
			
			// ������� ������� �����
			if(file_exists($path))
			{
				rmdir($path);
			}
		}
	}

?>