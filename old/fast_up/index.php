<?php

session_start();

switch($_POST['act']){
	
	case "":
		if($_POST['db_server'] == ""){
		$content .= "<h2>���������������� ��</h2><br/><form method='POST'>������ ��� ������*: <input type='text'name='db_server'/><br/>��� ������������*: <input type='text' name='db_user'/><br/>������: <input type='password' name='db_pass'/><br/>���� ������*: <input type='text' name='db_name'/><br/>������� ������: <input type='text' name='db_prefix'/><br/><h3>��������� �����</h3>�������� �����: <input type='text' name='site_name'/><br/><br/><input type='submit' value='����������'/><input type='hidden' name='act' value=''/></form>����������. �������� '*' �������� ����, ������� ����������� ��� ����������.";	
		}else{
		
			$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
			$ech = str_replace("/fast_up/", "", $ech);
			
			if(!mysql_connect($_POST['db_server'], $_POST['db_user'], $_POST['db_pass']) || !mysql_select_db($_POST['db_name'])){
			
				$content .= "��������� ������: ".mysql_error();
				$content .= "<h2>���������������� ��</h2><br/><form method='POST'>������ ��� ������: <input type='text'name='db_server'/><br/>��� ������������: <input type='text' name='db_user'/><br/>������: <input type='password' name='db_pass'/><br/>���� ������: <input type='text' name='db_name'/><br/>������� ������: <input type='text' name='db_prefix'/><br/><h3>��������� �����</h3>�������� �����: <input type='text' name='site_name'/><br/><br/><input type='submit' value='����������'/><input type='hidden' name='act' value=''/></form>";
			
			}else{
				if(is_dir("../cms") && is_file("../cms/config.php")){
				$conf = file_get_contents("../cms/config.php");
				$conf = "<?php

class Config{

	const db_pref = \"".$_POST['db_prefix']."\";
	const db_host = \"".$_POST['db_server']."\";
	const db_user = \"".$_POST['db_user']."\";
	const db_pass = \"".$_POST['db_pass']."\";
	const db_name = \"".$_POST['db_name']."\";
	const site_url = \"".$ech."\";
	const debug = true;
	const site_name = \"".$_POST['site_name']."\";

}

?>";
				$file = fopen("../cms/config.php", "w");
				fwrite($file, $conf);
				fclose($file);
				$content .= "���� �������� �����������, ��� ���������� ������������ ��������������� ����� ������� ��� ����� � ���� ����.<form method='POST'><input type='text' name='login' /><input type='hidden' name='act' value='site_activation'><input type='submit' value='������!'></form>��������! ����� ������ ��������� � ����� ������� �� ����� <a href='http://sites.msc.16mb.com/'>sites.msc.16mb.com</a>, ����� �� �� ������� ����� �� ���� � � ���������������� ������.";
				}else $content .= "�� ���������� ����� 'CMS' � �������� ����� ����� ��� ���� 'config.php' � ���� �����.<br/><br/>��������� �� ������ ����� ������ ����� MSC Web Site Engine � �������� ��������";
			}
				
		}
	break;
	
	case "site_activation":
		
			include("../cms/database.php");
			include("../cms/config.php");
			
			$db = new DataBase();
			
			$usr['login'] = $_POST['login'];
			$usr['group'] = "admin";
			
			$db->insert("users",$usr);
		
				$_SESSION['userMessage'] = "����� ����������.<br/>��������� CMS ���������, ������ �� ������ �������� � ������.<br/>���������� ���������������.";
				$_SESSION['auth'] == "0";
				$content .= "��������� MSC Web Site Engine ���������.<br/><br/><a href='".config::site_url."'>�� ����</a><br/><br/>��������! ��� ������ ������ ����� ���������� ������� ���������� 'fast_up' �� ����� �����.";
			
			
		
	break;
	
}

echo "<html><body><div style='height: 80%; width: 80%; position: fixed; left:10%; top:10%; border-radius: 10px; border: dotted 5px black; padding: 10px;'><center><h2>������� ��������� <font color='#00DD00'>MSC</font> <font color='blue'>W</font>eb <font color='#EEE044'>S</font>ite <font color='red'>E</font>ngine</h2><br/><br/><br/><font style='font-size: 130%'>$content</font></center></div><div style='float: right; width: 10%; height: 80%;'></div></body></html>";
?>