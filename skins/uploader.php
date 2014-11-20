<?php

$path=GetCWD()."/skins"; 

if(empty($_FILES['UserFile']['tmp_name'])){ 

}
else 
{ 
	
   if($_FILES['UserFile']['type'] != "image/png"){
	
	}
	else
	
	{
	 copy($_FILES['UserFile']['tmp_name'],$path.chr(47).$_GET['user'].".png");
	}
}

//echo $_GET['user'];
header("Location: ".$_SERVER["HTTP_REFERER"]);
?>