<?php

	$sc = libs::GetLib("database")->getAll("scripts","id",true);
	foreach($sc as $num=>$script){
	
		$scripts .= ($num+1).".".$script['title']."<br/>";
	
	}

	$admins = "";
	$i1 = 1;
	$dir_ad = opendir("scripts/admin");
	while($d = readdir($dir_ad)){
		if($d != "." && $d != ".." && !is_dir($d)){
		
			$n = explode(".",$d);
			$admins .= $i1.".".$n[0]."<br/>";
			$i1++;
			
		}
	}
	closedir($dir_ad);
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
	
	<h2>� �������</h2>
	<div><a href='%adress%/index.php/admin'>� ���������������� ������.</a><br/><br/></div>
	<div><p>
	�� ����������� CMS \"MSC: WebSite Engine\"<br/>
	������ ���� �������: 2.0<br/>
	������ �������: A.2.0 CC(alpha 2.0 clear core | 8.12.2013)<br/>
	���������� � ������: � ������ ����� ������������ ������ ���� CMS + �������� ���������� � ������ �����������/�����������.
	<br/><br/>
	������������� ������� �����:<br/>
	$scripts<br/>
	������������� ������� ���������������� ������:<br/>
	$admins<br/>
	������������� ����������:<br/>
	$libs
	</p></div>
	
	";

?>