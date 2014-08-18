<?php

session_start();

switch($_POST['act']){
	case "":
		$content .= "Предстоящие шаги:<br/><br/>1.Выбор компонентов для установки<br/>2.Проверка наличия необходимых компонентов<br/>3.Загрузка недостающих компонентов<br/>4.Настройка базы данных<br/>5.Импорт данных в БД<br/><br/><form method='POST'><input type='hidden' name='act' value='comp1'/><input type='submit' value='Начать установку!' /></form>";
	break;
	
	case "comp1":
		$content .= "В данной версии установщика нет возможности установки дополнительных компонентов, приносимсвои извинения.<form method='POST'><input type='hidden' name='act' value='db_conf'/><input type='submit' value='Перейти к следующему шагу' /></form>";
	break;
	
	case "comp2":
		$content .= "В данной версии установщика нет возможности установки дополнительных компонентов, приносимсвои извинения.<form method='POST'><input type='hidden' name='act' value='db_conf'/><input type='submit' value='Перейти к следующему шагу' /></form>";
	break;
	
	case "comp3":
		$content .= "В данной версии установщика нет возможности установки дополнительных компонентов, приносимсвои извинения.<form method='POST'><input type='hidden' name='act' value='db_conf'/><input type='submit' value='Перейти к следующему шагу' /></form>";
	break;
	
	case "db_conf":
		if($_POST['db_server'] == ""){
		$content .= "<h2>Конфигурирование БД</h2><br/><form method='POST'>Сервер баз данных*: <input type='text'name='db_server'/><br/>Имя пользователя*: <input type='text' name='db_user'/><br/>Пароль: <input type='password' name='db_pass'/><br/>База данных*: <input type='text' name='db_name'/><br/>Префикс таблиц: <input type='text' name='db_prefix'/><br/><h3>Настройки сайта</h3>Название сайта: <input type='text' name='site_name'/><br/><br/><input type='submit' value='Продолжить'/><input type='hidden' name='act' value='db_conf'/></form>Примечание. Символом '*' помечены поля, которые обязательны для заполнения.";	
		}else{
		
			$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
			$ech = str_replace("/setup/", "", $ech);
			
			if(!mysql_connect($_POST['db_server'], $_POST['db_user'], $_POST['db_pass']) || !mysql_select_db($_POST['db_name'])){
			
				$content .= "Произошла ошибка: ".mysql_error();
				$content .= "<h2>Конфигурирование БД</h2><br/><form method='POST'>Сервер баз данных: <input type='text'name='db_server'/><br/>Имя пользователя: <input type='text' name='db_user'/><br/>Пароль: <input type='password' name='db_pass'/><br/>База данных: <input type='text' name='db_name'/><br/>Префикс таблиц: <input type='text' name='db_prefix'/><br/><h3>Настройки сайта</h3>Название сайта: <input type='text' name='site_name'/><br/><br/><input type='submit' value='Продолжить'/><input type='hidden' name='act' value='db_conf'/></form>";
			
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
				$content .= "Хотите перезаписать существующие таблицы?<br/><br/><form method='POST'><input type='hidden' name='act' value='db_import' /><input type='submit' name='yes' value='Да'/><input type='submit' name='no' value='Нет, использовать существующие'></form><br/><br/><font color='red'>Внимание!</font> При включении замены существующих таблиц могут быть потеряны некоторые данные, например, может очиститься таблица с данными о пользователях. Но CMS может не поддерживать текущие таблицы, поэтому рекомендуется сделать резервную копию и включить замену, а затем восстановить резервную копию.<br/><br/>Если база данных пуста или CMS устанавливается в первый раз, то необходимо включить замену таблиц, чтобы CMS импортировала свои настройки в базу данных.<br/><br/><font color='red'>Примечание.</font> CMS заменит только необходимые для её работы таблицы.";
				}else $content .= "Не существует папка 'CMS' в корневой папке сайта или файл 'config.php' в этой папке.<br/><br/>Загрузите на сервер сайта другую копию MSC Web Site Engine и обновите страницу";
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
		
		$content .= "Все необходимые данные были импортированы.<br/><br/>Если вы уже зарегистрированны в системе сайтов MSC, то вам осталось лишь ввести свой персональный код пользователя и подтвердить привязку сайта в личном кабинете.(если это необходимо)<form method='post'><input type='hidden' name='act' value='site_activation'/><input type='text' name='uid'/><input type='submit' value='Готово!' /></form>";
		
		}
		else $content .= "Вы отказались от импорта стандартных настроек.<br/><br/>Если вы уже зарегистрированны в системе сайтов MSC, то вам осталось лишь ввести свой персональный код пользователя и подтвердить привязку сайта в личном кабинете.(если это необходимо)<form method='post'><input type='hidden' name='act' value='site_activation'/><br/><br/>Ваш персональный код: <input type='text' name='uid'/><input type='submit' value='Готово!' /></form>";
		
	break;
	
	case "site_activation":
		
		$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
		$ech = str_replace("/setup/", "", $ech);
		
		$fp = fsockopen ("sites.msc.16mb.com", 80, $errno, $errstr, 30);
		if (!$fp) {
			debug("Произошла ошибка, обратитесь к администрации сайта (доп. информация $errstr ($errno))<br>\n");
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
				$_SESSION['userMessage'] = "Добро пожаловать.<br/>Установка CMS завершена, теперь вы можете работать с сайтом.<br/>Пожалуйста авторизируйтесь.";
				$_SESSION['auth'] == "0";
				$content .= "Установка MSC Web Site Engine завершена, осталось только нажать на кнопку \"Запустить сайт\" и можно начинать работу с сайтом.<br/><br/><form method='POST'><input type='hidden' name='act' value='setup_off'><input type='submit' value='Запустить сайт'></form>";
			
			}else $content .= "Пользователя с таким персональным кодом не существует.<br/><br/>Попробуйте снова.<br/><br/><form method='post'><input type='hidden' name='act' value='site_activation'/><br/><br/>Ваш персональный код: <input type='text' name='uid'/><input type='submit' value='Готово!' /></form>";

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

echo "<html><body><div style='height: 80%; width: 80%; position: fixed; left:10%; top:10%; border-radius: 10px; border: dotted 5px black; padding: 10px;'><center><h2>Установка <font color='#00DD00'>MSC</font> <font color='blue'>W</font>eb <font color='#EEE044'>S</font>ite <font color='red'>E</font>ngine</h2><br/><br/><br/><font style='font-size: 130%'>$content</font></center></div><div style='float: right; width: 10%; height: 80%;'></div></body></html>";
?>