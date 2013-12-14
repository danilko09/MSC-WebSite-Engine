<?php
	if($_POST['act'] != "site_activation") $content .= "<br/><br/><form method='post'>Регистрация главного администратора.<br/>Введите учетные данные главного администратора сайта.<input type='hidden' name='act' value='site_activation'/><br/>Логин: <input type='text' name='login'/><br/>Пароль: <input type='password' name='pass'/></br><input type='submit' value='' style='
    text-decoration: none;
    background: url(%adress%/setup/tmpl/gotovo.png) no-repeat;
    height: 47px;
    width: 150px;
    border: 0;
' /></form>";
	else{
		$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
		$ech = str_replace("/setup/", "", $ech);
				
				include("../config.php");
				include("../lib/database.php");
				
				$usr = array();
				$usr['group'] = "admin";
				$usr['login'] = $_POST['login'];
				$usr['password'] = $_POST['pass'];
				
				$db = new DataBase();
				$db->insert("users",$usr);
				
				$_SESSION['userMessage'] = "Добро пожаловать,".$log_bl[1][0].".<br/>Установка CMS завершена, теперь вы можете работать с сайтом.<br/>Пожалуйста авторизируйтесь.";
				$_SESSION['auth'] == "0";
				$content .= "Установка MSC Web Site Engine завершена, осталось только нажать на кнопку \"Запустить сайт\" и можно начинать работу с сайтом.<br/><br/><form method='POST'><input type='hidden' name='act' value='setup_off'><input type='submit' value='Запустить сайт'></form>";
				next_stage();
			
			
	}
?>