<?php

	$title .= " | �������� ��������";
	addNavi("�������� ��������","%adress%/index.php/admin/scripts");
	
	//print_r($pg);
	
	if($pg[4] == null || $pg[4] == ""){
	
		$site .= "<br/>��� �������������� ������� �������� ��� �������� �� ������.<br/>";
		
		$sc = $db->getAll("scripts", true, true);
		
		foreach($sc as $n=>$script){
		
			$site .= "<br/>".($n+1).". <a href='%adress%/index.php/admin/scripts/script/".$script['id']."'>".$script['title']."</a>";
		
		}
	
	}elseif($pg[4] == "script"){
		
		if($_POST['save']) echo "save";
		
		$sc = $db->getElementOnID("scripts", $pg[5]);
		
		addNavi($sc['title'],"%adress%/index.php/admin/scripts/".$pg[5]);
		
		$file = join('',file("scripts/".$sc['file']));
		
		$script = $file;
		
		$site .= "<form method='POST'><br/><center><input type='submit' name='save' value='���������' /></center>ID: ".$sc['id']."<br/>��������: <input type='text' name='title' value='".$sc['title']."' /><br/>����: <input type='text' name='file' value='".$sc['file']."'><br/>���������� �������:<br/><textarea name='php' style='width: 100%; height: 300px'>$script</textarea><input type='hidden' name='scripth' value='$scripth' /></form>";
		
		//echo "<br/>";
		//print_r($mathes[0]);
		//echo "<br/>";
		
		//print_r($sc);
		//print_r($_POST);
	}

?>