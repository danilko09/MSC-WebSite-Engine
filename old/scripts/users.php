<?php

function getUsersPage(){

	global $pg_pars;
	global $db;
	
	$do = explode(",", $pg_pars);
	if($do[0] === "login" && $_POST['login'] != ""){
	
$filename = "http://sites.msc.16mb.com/login.php?login=".$_POST['login']."&pass=".$_POST['pass']."&site=".config::site_url;
$handle = fopen($filename, "rb");
$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}
fclose($handle);
echo $filename;

		$fp = fsockopen ("sites.msc.16mb.com", 80, $errno, $errstr, 30);
		if (!$fp) {
			debug("��������� ������, ���������� � ������������� ����� (���. ���������� $errstr ($errno))<br>\n");
		} else {
			fputs ($fp, "GET /login.php?login=".$_POST['login']."&pass=".$_POST['pass']."&site=".config::site_url." / HTTP/1.0\r\nHost: sites.msc.16mb.com\r\n\r\n");
			while (!feof($fp)) {
				$ans .= fgets ($fp,128);
			}

			preg_match_all("|\[log=(.*)\]|U", $ans, $ans_bl, PREG_SPLIT_NO_EMPTY);
		
			fclose ($fp);

			if($ans_bl[1][0] === "ok"){
			
				$_SESSION['auth'] = "1";
				$_SESSION['login'] = $_POST['login'];
				$_SESSION['userMessage'] = "����� ����������, ".$_SESSION['login'];
				redirect(config::site_url."/");
			
			}else $content .= "�� ������ �����/������.<br/>[script_userLoginForm]";

		}
	
	}
	elseif($do[0] === "logout"){
	
		unset($_SESSION['auth']);
		unset($_SESSION['login']);
		redirect(config::site_url."/");
	
	}
	elseif($do[0] === "registration" && $_POST['login'] != ""){
	
		$fp = fsockopen ("sites.msc.16mb.com", 80, $errno, $errstr, 30);
		if (!$fp) {
			debug("��������� ������, ���������� � ������������� ����� (���. ���������� $errstr ($errno))<br>\n");
		} else {
			fputs ($fp, "GET /register.php?login=".$_POST['login']."&pass=".$_POST['pass']." / HTTP/1.0\r\nHost: sites.msc.16mb.com\r\n\r\n");
			while (!feof($fp)) {
				$ans .= fgets ($fp,128);
			}

			preg_match_all("|\[reg=(.*)\]|U", $ans, $ans_bl, PREG_SPLIT_NO_EMPTY);
		
			fclose ($fp);

			if($ans_bl[1][0] === "ok"){
			
				$_SESSION['auth'] = "1";
				$_SESSION['login'] = $_POST['login'];
				$_SESSION['userMessage'] = "������� �� �����������.";
				redirect(config::site_url."/");
			
			}elseif($ans_bl[1][0] === "already"){
				$content .= "<div style='background-color: green; border-radius: 5px; border: solid 5px yellowgreen; color: white; padding: 10px;'>���� ����� ����� ��� �� ��� �����������������, ���������� ����������������.<br/></br>[script_userLoginForm]<br/>*���� �� ��� ���������������� �� ������ � ������������ <img src='http://sites.msc.16mb.com/msc_man.png' /> �� �� ��� ����� �����������������. [script_userRegForm]</div>";
			}else debug("��������� ������, ���������� � ������������� (���. ����������: ������ msc.16mb.com ������ �� ������ �����.)");
		}
	
	}
	elseif($_SESSION['auth'] != "1") $content .= "�� �� ���������������!<br/>[script_userLoginPage]";
	else redirect(config::site_url."/");
	
	return "<div class='post'><h1 class='title'><a href=''>������ �������</a></h1><p>".$content."</p></div>";

}

function getLoginPage(){

	return "<br/><center><table><tr><td><form action='%adress%/index.php/user/login' method='POST'>�����<img src='http://sites.msc.16mb.com/msc_man.png'/>: <input type='text' name='login'/><br/>������: <input type='password' name='pass'/><br/><input type='submit' value='�����'/></form></td>
	<td><form action='%adress%/index.php/user/registration' method='POST'>�����<img src='http://sites.msc.16mb.com/msc_man.png'/>: <input type='text' name='login'/><br/>������: <input type='password' name='pass'/><br/><input type='submit' value='�����������'/></form></td></tr></table></center>";

}

function getRegForm(){

	return "<center><br/><form action='%adress%/index.php/user/registration' method='POST'><table style='color: white;'><tr><td>�����<img src='http://sites.msc.16mb.com/msc_man.png'/>:</td><td> <input type='text' name='login'/><br/></td></tr><tr><td>������:</td><td> <input type='password' name='pass'/><br/></td></tr></table><input type='submit' value='�����������'/></form></center>";

}

function getLoginForm(){

	return "<center><form action='%adress%/index.php/user/login' method='POST'><table style='color: white;'><tr><td>�����<img src='http://sites.msc.16mb.com/msc_man.png'/>:</td><td><input type='text' name='login' style='width: 90%;'/><br/></td></tr><tr><td>������:</td><td><input type='password' name='pass' style='width: 90%;'/><br/></td></tr></table><input alt='��� ����������� ������� ���� �������.' type='submit' value='����� | �����������'/></form></center>";

}

function getBlock(){

	if(isset($_SESSION['auth']) && $_SESSION['auth'] === "1")return "<center>������, ".$_SESSION['login']."!<br /><br />
<script>
function getUploader(){
	var username = '".$_SESSION['login']."';
	var url = '".config::site_url."';
	document.getElementById('uploader').innerHTML = \"<form method='post' enctype='multipart/form-data' action='http://msc.16mb.com/players/skins/uploader.php?url=\"+url+\"&domain=\"+document.domain+\"&user=\"+username+\"'> �������� ���� �����: <input type='file' name='UserFile'> <input type='submit' value='���������'> </form>  <a onclick=\\\"hideUploader();\\\">������</a>\";
};
function hideUploader(){
	document.getElementById(\"uploader\").innerHTML = \"<a onclick=\\\"getUploader();\\\">������� ����</a>\";
};
</script>

���� ���� �������� ���:<br /><br />
<img src=\"http://sites.msc.16mb.com/auth/skins/skin2d.php?player=".$_SESSION['login']."\" /><br /><br />
<div id=\"uploader\"><a href='%adress%/index.php/skins'>������� ����</a></div>
<form action=\"%adress%/index.php/user/logout\" method=\"post\">
<input type=\"hidden\" name=\"action\" value=\"logout\" />
<input type=\"submit\" style=\"margin-top: 10px;\" value=\"�����\" />
</form>
</center>
";
else return "[script_userLoginForm]";

}

function getSkinsPage(){

	if($_SESSION['auth']){
	
		$ret = "<div class='post'>
���� ���� �������� ���:<br /><br />
<img src=\"http://sites.msc.16mb.com/auth/skins/skin2d.php?player=".$_SESSION['login']."\" /><br /><br />
<div id=\"uploader\"><form method='post' enctype='multipart/form-data' action='http://sites.msc.16mb.com/auth/skins/uploader.php?user=".$_SESSION['login']."'> �������� ���� �����: <input type='file' name='UserFile'> <input type='submit' value='���������'> </form></div>
</div>
";
	
	}
	else $ret = "[script_userLoginForm]";
	
	return $ret;

}

?>
