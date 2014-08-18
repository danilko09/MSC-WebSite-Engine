<?php

session_start();

switch($_POST['act']){
	
	case "":
		if($_POST['db_server'] == ""){
		$content .= "<h2>Конфигурирование БД</h2><br/><form method='POST'>Сервер баз данных*: <input type='text'name='db_server'/><br/>Имя пользователя*: <input type='text' name='db_user'/><br/>Пароль: <input type='password' name='db_pass'/><br/>База данных*: <input type='text' name='db_name'/><br/>Префикс таблиц: <input type='text' name='db_prefix'/><br/><h3>Настройки сайта</h3>Название сайта: <input type='text' name='site_name'/><br/><br/><input type='submit' value='Продолжить'/><input type='hidden' name='act' value=''/></form>Примечание. Символом '*' помечены поля, которые обязательны для заполнения.";	
		}else{
		
			$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
			$ech = str_replace("/fast_up/", "", $ech);
			
			if(!mysql_connect($_POST['db_server'], $_POST['db_user'], $_POST['db_pass']) || !mysql_select_db($_POST['db_name'])){
			
				$content .= "Произошла ошибка: ".mysql_error();
				$content .= "<h2>Конфигурирование БД</h2><br/><form method='POST'>Сервер баз данных: <input type='text'name='db_server'/><br/>Имя пользователя: <input type='text' name='db_user'/><br/>Пароль: <input type='password' name='db_pass'/><br/>База данных: <input type='text' name='db_name'/><br/>Префикс таблиц: <input type='text' name='db_prefix'/><br/><h3>Настройки сайта</h3>Название сайта: <input type='text' name='site_name'/><br/><br/><input type='submit' value='Продолжить'/><input type='hidden' name='act' value=''/></form>";
			
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
				$content .= "Файл настроек перезаписан, для назначения пользователя администратором сайта введите его логин в поле ниже.<form method='POST'><input type='text' name='login' /><input type='hidden' name='act' value='site_activation'><input type='submit' value='Готово!'></form>Внимание! Логин должен совпадать с вашим логином на сайте <a href='http://sites.msc.16mb.com/'>sites.msc.16mb.com</a>, иначе Вы не сможете войти на сайт и в административную панель.";
				}else $content .= "Не существует папка 'CMS' в корневой папке сайта или файл 'config.php' в этой папке.<br/><br/>Загрузите на сервер сайта другую копию MSC Web Site Engine и обновите страницу";
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
		
				$_SESSION['userMessage'] = "Добро пожаловать.<br/>Установка CMS завершена, теперь вы можете работать с сайтом.<br/>Пожалуйста авторизируйтесь.";
				$_SESSION['auth'] == "0";
				$content .= "Установка MSC Web Site Engine завершена.<br/><br/><a href='".config::site_url."'>На сайт</a><br/><br/>Внимание! Для защиты вашего сайта необходимо удалить директорию 'fast_up' на вашем сайте.";
			
			
		
	break;
	
}

echo "<html><body><div style='height: 80%; width: 80%; position: fixed; left:10%; top:10%; border-radius: 10px; border: dotted 5px black; padding: 10px;'><center><h2>Быстрая установка <font color='#00DD00'>MSC</font> <font color='blue'>W</font>eb <font color='#EEE044'>S</font>ite <font color='red'>E</font>ngine</h2><br/><br/><br/><font style='font-size: 130%'>$content</font></center></div><div style='float: right; width: 10%; height: 80%;'></div></body></html>";
?>