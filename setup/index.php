<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

	session_start();
	
	$finish = false;
	
	//Очередь скриптов
	
	$scripts['0'] = "welcome";
	$scripts['1'] = "database";
	$scripts['2'] = "admin";
	$scripts['3'] = "components";
	$scripts['4'] = "finish";
	
	//Подключение скриптов из очереди
	
        if(!isset($_SESSION['pos']) || $_SESSION['pos']  === "" || $_SESSION['pos'] === null){$_SESSION['pos'] = 0;}
	$message = "";$content = "";
	include("scripts/".$scripts[$_SESSION['pos']].".php");
	
	//Подгрузка шаблона
	
	$site = file_get_contents("tmpl.html");
	$site = str_replace("%content%",$message.$content,$site);
	$ech = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$ech = str_replace("/setup/", "", $ech);
	$site = str_replace("%adress%",$ech,$site);
	echo $site;
	if($finish) RemoveDir("../setup");
	
	//Вспомогательные функции
	
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
                                            $messages .= "<br/>Не удалось сменить права на файл '".$tmpPath."'";
                                        }
					if (is_dir($tmpPath))
					{  // если папка
						RemoveDir($tmpPath);
					} 
					else 
					{ 
						if(file_exists($tmpPath))
						{
							// удаляем файл 
                                                        if(!unlink($tmpPath)){$message .= "<br/>Не удалось удалить файл '".$tmpPath."'.<br/>Установка системы завершена, удалите папку 'setup' на сайте.";return;}
                                                        
						}
					}
				}
			}
			closedir($dirHandle);
			
			// удаляем текущую папку
			if(file_exists($path))
			{
				rmdir($path);
			}
		}
	}

?>