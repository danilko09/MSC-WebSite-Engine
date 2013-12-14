<?php
	session_start();
    if(filter_input(INPUT_GET,'restart') == 1){$_SESSION = null; echo "<h1><font color='red'>Установщик переключен на 1 шаг. Перейдите по адресу, куда вы устанавливаете CMS.</font></h1>";}
	
	$finish = false;
	include("config.php");
	
	
	//Подключение скриптов из очереди
	
    if($_SESSION['pos'] === "" || $_SESSION['pos'] === null){$_SESSION['pos'] = 0;}
	
	if($scripts[$_SESSION['pos']]!=null)include("scripts/".$scripts[$_SESSION['pos']].".php");
    else{$content = "Во время установки возникла ошибка.<br/>Пожалуйста, свяжитесь с разработчиком проекта и сообщите об этом.";}
	
	//Подгрузка шаблона
        
    $ech = filter_input(INPUT_SERVER,'HTTP_ORIGIN').filter_input(INPUT_SERVER,'REQUEST_URI');
	$ech = str_replace("/setup/", "", $ech);
        
	$site = str_replace("%adress%",$ech,str_replace("%content%",$message.$content,file_get_contents("tmpl.html")));
	echo $site;
    if($finish){RemoveDir("../setup");}
	
	//Вспомогательные функции
	
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
					
                                        if (is_dir($tmpPath)){RemoveDir($tmpPath);}// если папка 
					else{if(file_exists($tmpPath)){unlink($tmpPath);}/*удаляем файл*/}
				}
			}closedir($dirHandle);
			
			// удаляем текущую папку
			if(file_exists($path)){rmdir($path);}
		}
	}

?>