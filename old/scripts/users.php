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
			debug("Произошла ошибка, обратитесь к администрации сайта (доп. информация $errstr ($errno))<br>\n");
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
				$_SESSION['userMessage'] = "Добро пожаловать, ".$_SESSION['login'];
				redirect(config::site_url."/");
			
			}else $content .= "Не верный логин/пароль.<br/>[script_userLoginForm]";

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
			debug("Произошла ошибка, обратитесь к администрации сайта (доп. информация $errstr ($errno))<br>\n");
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
				$_SESSION['userMessage'] = "Спасибо за регистрацию.";
				redirect(config::site_url."/");
			
			}elseif($ans_bl[1][0] === "already"){
				$content .= "<div style='background-color: green; border-radius: 5px; border: solid 5px yellowgreen; color: white; padding: 10px;'>Этот логин занят или вы уже зарегистрированны, попробуйте авторизироваться.<br/></br>[script_userLoginForm]<br/>*Если вы уже регистрировались на сайтах с такимзначком <img src='http://sites.msc.16mb.com/msc_man.png' /> то вы уже здесь зарегистрированны. [script_userRegForm]</div>";
			}else debug("Произошла ошибка, обратитесь к администрации (доп. информация: сервер msc.16mb.com вернул не верный ответ.)");
		}
	
	}
	elseif($_SESSION['auth'] != "1") $content .= "Вы не авторизированны!<br/>[script_userLoginPage]";
	else redirect(config::site_url."/");
	
	return "<div class='post'><h1 class='title'><a href=''>Личный кабинет</a></h1><p>".$content."</p></div>";

}

function getLoginPage(){

	return "<br/><center><table><tr><td><form action='%adress%/index.php/user/login' method='POST'>Логин<img src='http://sites.msc.16mb.com/msc_man.png'/>: <input type='text' name='login'/><br/>Пароль: <input type='password' name='pass'/><br/><input type='submit' value='Войти'/></form></td>
	<td><form action='%adress%/index.php/user/registration' method='POST'>Логин<img src='http://sites.msc.16mb.com/msc_man.png'/>: <input type='text' name='login'/><br/>Пароль: <input type='password' name='pass'/><br/><input type='submit' value='Регистрация'/></form></td></tr></table></center>";

}

function getRegForm(){

	return "<center><br/><form action='%adress%/index.php/user/registration' method='POST'><table style='color: white;'><tr><td>Логин<img src='http://sites.msc.16mb.com/msc_man.png'/>:</td><td> <input type='text' name='login'/><br/></td></tr><tr><td>Пароль:</td><td> <input type='password' name='pass'/><br/></td></tr></table><input type='submit' value='Регистрация'/></form></center>";

}

function getLoginForm(){

	return "<center><form action='%adress%/index.php/user/login' method='POST'><table style='color: white;'><tr><td>Логин<img src='http://sites.msc.16mb.com/msc_man.png'/>:</td><td><input type='text' name='login' style='width: 90%;'/><br/></td></tr><tr><td>Пароль:</td><td><input type='password' name='pass' style='width: 90%;'/><br/></td></tr></table><input alt='Для регистрации оставте поля пустыми.' type='submit' value='Войти | Регистрация'/></form></center>";

}

function getBlock(){

	if(isset($_SESSION['auth']) && $_SESSION['auth'] === "1")return "<center>Привет, ".$_SESSION['login']."!<br /><br />
<script>
function getUploader(){
	var username = '".$_SESSION['login']."';
	var url = '".config::site_url."';
	document.getElementById('uploader').innerHTML = \"<form method='post' enctype='multipart/form-data' action='http://msc.16mb.com/players/skins/uploader.php?url=\"+url+\"&domain=\"+document.domain+\"&user=\"+username+\"'> Выберите файл скина: <input type='file' name='UserFile'> <input type='submit' value='Отправить'> </form>  <a onclick=\\\"hideUploader();\\\">Скрыть</a>\";
};
function hideUploader(){
	document.getElementById(\"uploader\").innerHTML = \"<a onclick=\\\"getUploader();\\\">Сменить скин</a>\";
};
</script>

Твой скин выглядит так:<br /><br />
<img src=\"http://sites.msc.16mb.com/auth/skins/skin2d.php?player=".$_SESSION['login']."\" /><br /><br />
<div id=\"uploader\"><a href='%adress%/index.php/skins'>Сменить скин</a></div>
<form action=\"%adress%/index.php/user/logout\" method=\"post\">
<input type=\"hidden\" name=\"action\" value=\"logout\" />
<input type=\"submit\" style=\"margin-top: 10px;\" value=\"Выйти\" />
</form>
</center>
";
else return "[script_userLoginForm]";

}

function getSkinsPage(){

	if($_SESSION['auth']){
	
		$ret = "<div class='post'>
Твой скин выглядит так:<br /><br />
<img src=\"http://sites.msc.16mb.com/auth/skins/skin2d.php?player=".$_SESSION['login']."\" /><br /><br />
<div id=\"uploader\"><form method='post' enctype='multipart/form-data' action='http://sites.msc.16mb.com/auth/skins/uploader.php?user=".$_SESSION['login']."'> Выберите файл скина: <input type='file' name='UserFile'> <input type='submit' value='Отправить'> </form></div>
</div>
";
	
	}
	else $ret = "[script_userLoginForm]";
	
	return $ret;

}

?>
