<?php

	$libs = $db->getAll("lib",true,true);
	$f = $db->getLastId("lib");
	
	
	if(is_dir("lib")){
	for($i = 0;$i<=$f;$i++){
		
		if($db->isExists("lib","id",$i)){
			
			$lib = $db->getElementOnID("lib",$i);
			
			if($lib['enabled'] === "1"){
			
				require_once("../lib/".$lib['file']);
			
			}
			
		}
		
	}
	}else print("Папка LIB не существует, подключение библиотек не возможно");

?>