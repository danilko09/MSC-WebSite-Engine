<?php
	//print_r($_POST);
	//print_r($_SESSION);
	if($_SESSION['db']['act'] == null){
	
		if($_POST['db_server'] == ""){
		$tmpl = getTmpl("db_param");
		$content .= $tmpl;	
		}else{
		
			$ech = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$ech = str_replace("/setup/", "", $ech);
			
			if(!mysql_connect($_POST['db_server'], $_POST['db_user'], $_POST['db_pass']) || !mysql_select_db($_POST['db_name'])){
			
				$content .= "��������� ������: ".mysql_error();
				$content .= "<h2>���������������� ��</h2><br/><form method='POST'>������ ��� ������: <input type='text'name='db_server'/><br/>��� ������������: <input type='text' name='db_user'/><br/>������: <input type='password' name='db_pass'/><br/>���� ������: <input type='text' name='db_name'/><br/>������� ������: <input type='text' name='db_prefix'/><br/><h3>��������� �����</h3>�������� �����: <input type='text' name='site_name'/><br/><br/><input type='submit' value='����������'/><input type='hidden' name='act' value='db_conf'/></form>";
			
			}else{
				if(is_dir("../cms") && is_file("../config.php")){
				$conf = file_get_contents("../config.php");
				$conf = "<?php

class Config{

	const db_pref = \"".$_POST['db_prefix']."\";
	const db_host = \"".$_POST['db_server']."\";
	const db_user = \"".$_POST['db_user']."\";
	const db_pass = \"".$_POST['db_pass']."\";
	const db_name = \"".$_POST['db_name']."\";
	const site_url = \"".$ech."\";
	const site_name = \"".$_POST['site_name']."\";

}

?>";
				$file = fopen("../config.php", "w");
				fwrite($file, $conf);
				fclose($file);
				//$content .= "������ ������������ ������������ �������?<br/><br/><form method='POST'><input type='hidden' name='act' value='db_import' /><input type='submit' name='yes' value='��'/><input type='submit' name='no' value='���, ������������ ������������'></form><br/><br/><font color='red'>��������!</font> ��� ��������� ������ ������������ ������ ����� ���� �������� ��������� ������, ��������, ����� ���������� ������� � ������� � �������������. �� CMS ����� �� ������������ ������� �������, ������� ������������� ������� ��������� ����� � �������� ������, � ����� ������������ ��������� �����.<br/><br/>���� ���� ������ ����� ��� CMS ��������������� � ������ ���, �� ���������� �������� ������ ������, ����� CMS ������������� ���� ��������� � ���� ������.<br/><br/><font color='red'>����������.</font> CMS ������� ������ ����������� ��� � ������ �������.";
				$_SESSION['db']['act'] = "import";
				$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
				$ech = str_replace("/setup/", "", $ech);
				header("Location: $ech/setup");
				}else $content .= "�� ���������� ����� 'CMS' � �������� ����� ����� ��� ���� 'config.php'.<br/><br/>��������� �� ������ ����� ������ ����� MSC Web Site Engine � �������� ��������";
				
			}
				
		}
		
	}else{
	
		if($_POST['act'] != "db_import"){
		$content .= "������ ������������ ������������ �������?<br/><br/><form method='POST'><input type='hidden' name='act' value='db_import' /><input type='submit' name='yes' value=' ' style='text-decoration: none; background: url(%adress%/setup/tmpl/da.png) no-repeat; height: 47px; width: 150px; border: 0;'/><input type='submit' name='no' value=' ' style='    text-decoration: none;   background: url(%adress%/setup/tmpl/no.png) no-repeat;    height: 47px;    width: 150px;   border: 0;'></form><br/><br/><font color='red'>��������!</font> ��� ��������� ������ ������������ ������ ����� ���� �������� ��������� ������, ��������, ����� ���������� ������� � ������� � �������������. �� CMS ����� �� ������������ ������� �������, ������� ������������� ������� ��������� ����� � �������� ������, � ����� ������������ ��������� �����.<br/><br/>���� ���� ������ ����� ��� CMS ��������������� � ������ ���, �� ���������� �������� ������ ������, ����� CMS ������������� ���� ��������� � ���� ������.<br/><br/><font color='red'>����������.</font> CMS ������� ������ ����������� ��� � ������ �������.";
		}
		else{
			if($_POST['yes'] != null){
			include("../config.php");
			$sql = file_get_contents("scripts/db.sql");
			$sql = str_replace("prefix_", config::db_pref, $sql);
			
			$mysql = new mysqli(config::db_host, config::db_user, config::db_pass, config::db_name);
			$q = explode(";", $sql);
			foreach($q as $i=>$query){
			$mysql->query($query);
			}
			require("scripts/db.php");
			include("../lib/database.php");
			$db = new database();
			foreach($import as $table=>$tc){
				foreach($tc as $num=>$arr) $db->insert($table,$arr);
			}
			$mysql->close();
			$_SESSION['message'] = "��� ����������� ������ ���� �������������.";
			}else $_SESSION['message'] .= "�� ���������� �� ������� ����������� ��������.";
			next_stage();			
		}
		
	}
	
?>