<?php

	$sc = scripts::getAllScriptsInfo();
        $num = 1;$a_num = 1;
        $scripts = "";$admins = "";
        foreach($sc as $script){
            if(isset($script['title']) && isset($script['file'])){
                if(!file_exists("scripts/".$script['file'].".php")){$script['title'] .= " | Не удалось найти файл";}
                $scripts .= ($num++).".".$script['title']."<br/>";
            }
            if(isset($script['a_title']) && isset($script['a_file'])){
                if(!file_exists("scripts/".$script['a_file'].".php")){$script['a_title'] .= " | Не удалось найти файл";}
                $admins .= ($a_num++).".".$script['a_title']."<br/>";
            }
	}
	
	$libs = "";
	$i = 1;
	$dir = opendir("lib");
	while($d = readdir($dir)){
		if($d != "." && $d != ".." && !is_dir($d)){
		
			$n = explode(".",$d);
			$libs .= $i.".".$n[0]."<br/>";
			$i++;
			
		}
	}
	
	$content .= "
	
	<h2>О системе</h2>
	<div><a href='%adress%/index.php/admin'>В административную панель.</a><br/><br/></div>
	<div><p>
	Вы пользуетесь CMS \"MSC: WebSite Engine\"<br/>
	Версия ядра системы: ".MSC_WSE_CORE_VERSION."<br/>
	Версия системы: ".MSC_WSE_ENGINE_VERION." (".MSC_WSE_ENGINE_VERSION_DESC.")<br/><br/>
	Установленные скрипты сайта:<br/>
	$scripts<br/>
	Установленные скрипты административной панели:<br/>
	$admins<br/>
	Установленные библиотеки:<br/>
	$libs
	</p></div>
	
	";

?>