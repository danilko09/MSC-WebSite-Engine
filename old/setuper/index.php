<?php

session_start();

switch($_POST['act']){
	case "":
		$content .= "����������� ����:<br/><br/>1.����� ����������� ��� ���������<br/>2.�������� ������� ����������� �����������<br/>3.�������� ����������� �����������<br/>4.��������� ���� ������<br/>5.������ ������ � ��<br/><br/><form method='POST'><input type='hidden' name='act' value='comp1'/><input type='submit' value='������ ���������!' /></form>";
	break;
	
	case "comp1":
		$content .= "� ������ ������ ����������� ��� ����������� ��������� �������������� �����������, ������������ ���������.<form method='POST'><input type='hidden' name='act' value='db_conf'/><input type='submit' value='������� � ���������� ����' /></form>";
	break;
	
	case "comp2":
		$content .= "� ������ ������ ����������� ��� ����������� ��������� �������������� �����������, ������������ ���������.<form method='POST'><input type='hidden' name='act' value='db_conf'/><input type='submit' value='������� � ���������� ����' /></form>";
	break;
	
	case "comp3":
		$content .= "� ������ ������ ����������� ��� ����������� ��������� �������������� �����������, ������������ ���������.<form method='POST'><input type='hidden' name='act' value='db_conf'/><input type='submit' value='������� � ���������� ����' /></form>";
	break;
	
	case "db_conf":
		if($_POST['db_server'] == ""){
		$content .= "<h2>���������������� ��</h2><br/><form method='POST'>������ ��� ������*: <input type='text'name='db_server'/><br/>��� ������������*: <input type='text' name='db_user'/><br/>������: <input type='password' name='db_pass'/><br/>���� ������*: <input type='text' name='db_name'/><br/>������� ������: <input type='text' name='db_prefix'/><br/><h3>��������� �����</h3>�������� �����: <input type='text' name='site_name'/><br/><br/><input type='submit' value='����������'/><input type='hidden' name='act' value='db_conf'/></form>����������. �������� '*' �������� ����, ������� ����������� ��� ����������.";	
		}else{
		
			$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
			$ech = str_replace("/setup/", "", $ech);
			
			if(!mysql_connect($_POST['db_server'], $_POST['db_user'], $_POST['db_pass']) || !mysql_select_db($_POST['db_name'])){
			
				$content .= "��������� ������: ".mysql_error();
				$content .= "<h2>���������������� ��</h2><br/><form method='POST'>������ ��� ������: <input type='text'name='db_server'/><br/>��� ������������: <input type='text' name='db_user'/><br/>������: <input type='password' name='db_pass'/><br/>���� ������: <input type='text' name='db_name'/><br/>������� ������: <input type='text' name='db_prefix'/><br/><h3>��������� �����</h3>�������� �����: <input type='text' name='site_name'/><br/><br/><input type='submit' value='����������'/><input type='hidden' name='act' value='db_conf'/></form>";
			
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
				echo $_SERVER['HTTP_ORIGIN'];
				$content .= "������ ������������ ������������ �������?<br/><br/><form method='POST'><input type='hidden' name='act' value='db_import' /><input type='submit' name='yes' value='��'/><input type='submit' name='no' value='���, ������������ ������������'></form><br/><br/><font color='red'>��������!</font> ��� ��������� ������ ������������ ������ ����� ���� �������� ��������� ������, ��������, ����� ���������� ������� � ������� � �������������. �� CMS ����� �� ������������ ������� �������, ������� ������������� ������� ��������� ����� � �������� ������, � ����� ������������ ��������� �����.<br/><br/>���� ���� ������ ����� ��� CMS ��������������� � ������ ���, �� ���������� �������� ������ ������, ����� CMS ������������� ���� ��������� � ���� ������.<br/><br/><font color='red'>����������.</font> CMS ������� ������ ����������� ��� � ������ �������.";
				}else $content .= "�� ���������� ����� 'CMS' � �������� ����� ����� ��� ���� 'config.php' � ���� �����.<br/><br/>��������� �� ������ ����� ������ ����� MSC Web Site Engine � �������� ��������";
			}
				
		}
	break;
	
	case "db_import":
		
		if($_POST['yes'] != null){
		
		include("../cms/config.php");
		$sql = file_get_contents("cms.sql");
		$sql = str_replace("prefix_", config::db_pref, $sql);
		
		$mysql = new mysqli(config::db_host, config::db_user, config::db_pass, config::db_name);
		$mysql->query("SET NAMES 'utf8'");
		$q = explode(";", $sql);
		foreach($q as $i=>$query){
		$mysql->query($query);
		}
		$mysql->close();
		
		$content .= "��� ����������� ������ ���� �������������.<br/><br/>���� �� ��� ����������������� � ������� ������ MSC, �� ��� �������� ���� ������ ���� ������������ ��� ������������ � ����������� �������� ����� � ������ ��������.(���� ��� ����������)<form method='post'><input type='hidden' name='act' value='site_activation'/><input type='text' name='uid'/><input type='submit' value='������!' /></form>";
		
		}
		else $content .= "�� ���������� �� ������� ����������� ��������.<br/><br/>���� �� ��� ����������������� � ������� ������ MSC, �� ��� �������� ���� ������ ���� ������������ ��� ������������ � ����������� �������� ����� � ������ ��������.(���� ��� ����������)<form method='post'><input type='hidden' name='act' value='site_activation'/><br/><br/>��� ������������ ���: <input type='text' name='uid'/><input type='submit' value='������!' /></form>";
		
	break;
	
	case "site_activation":
		
		$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
		$ech = str_replace("/setup/", "", $ech);
		
		$fp = fsockopen ("sites.msc.16mb.com", 80, $errno, $errstr, 30);
		if (!$fp) {
			debug("��������� ������, ���������� � ������������� ����� (���. ���������� $errstr ($errno))<br>\n");
		} else {
			fputs ($fp, "GET /register_site.php?uid=".$_POST['uid']."&url=".$ech." / HTTP/1.0\r\nHost: sites.msc.16mb.com\r\n\r\n");
			while (!feof($fp)) {
				$ans .= fgets ($fp,128);
			}

			preg_match_all("|\[act=(.*)\]|U", $ans, $ans_bl, PREG_SPLIT_NO_EMPTY);
			preg_match_all("|\[login=(.*)\]|U", $ans, $log_bl, PREG_SPLIT_NO_EMPTY);
		
			fclose ($fp);
			
			

			if($ans_bl[1][0] === "ok"){
			
				include("../cms/database.php");
				$db = new DataBase();
				$usr['login'] =  $log_bl[1][0];
				$usr['group'] = "admin";
				$db->insert("users",$usr);
				$_SESSION['userMessage'] = "����� ����������.<br/>��������� CMS ���������, ������ �� ������ �������� � ������.<br/>���������� ���������������.";
				$_SESSION['auth'] == "0";
				$content .= "��������� MSC Web Site Engine ���������, �������� ������ ������ �� ������ \"��������� ����\" � ����� �������� ������ � ������.<br/><br/><form method='POST'><input type='hidden' name='act' value='setup_off'><input type='submit' value='��������� ����'></form>";
			
			}else $content .= "������������ � ����� ������������ ����� �� ����������.<br/><br/>���������� �����.<br/><br/><form method='post'><input type='hidden' name='act' value='site_activation'/><br/><br/>��� ������������ ���: <input type='text' name='uid'/><input type='submit' value='������!' /></form>";

		}
	break;
	
	case "setup_off":
	
		chdir("..");
		$dir = opendir("setup");
		chdir("setup");
		while($d = readdir($dir)){
			if($d != "." && $d != "..") unlink($d);
		}
		closedir($dir);
		chdir("..");
		rmdir("setup");
		$to = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
		$to = str_replace("/setup/", "", $to);
		$content .= "<script>document.location = '".$to."'</script>";
		
	break;
}

echo "<html><body><div style='height: 80%; width: 80%; position: fixed; left:10%; top:10%; border-radius: 10px; border: dotted 5px black; padding: 10px;'><center><h2>��������� <font color='#00DD00'>MSC</font> <font color='blue'>W</font>eb <font color='#EEE044'>S</font>ite <font color='red'>E</font>ngine</h2><br/><br/><br/><font style='font-size: 130%'>$content</font></center></div><div style='float: right; width: 10%; height: 80%;'></div></body></html>";
?>