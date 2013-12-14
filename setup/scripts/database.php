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
			
				$content .= "Произошла ошибка: ".mysql_error();
				$content .= "<h2>Конфигурирование БД</h2><br/><form method='POST'>Сервер баз данных: <input type='text'name='db_server'/><br/>Имя пользователя: <input type='text' name='db_user'/><br/>Пароль: <input type='password' name='db_pass'/><br/>База данных: <input type='text' name='db_name'/><br/>Префикс таблиц: <input type='text' name='db_prefix'/><br/><h3>Настройки сайта</h3>Название сайта: <input type='text' name='site_name'/><br/><br/><input type='submit' value='Продолжить'/><input type='hidden' name='act' value='db_conf'/></form>";
			
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
				//$content .= "Хотите перезаписать существующие таблицы?<br/><br/><form method='POST'><input type='hidden' name='act' value='db_import' /><input type='submit' name='yes' value='Да'/><input type='submit' name='no' value='Нет, использовать существующие'></form><br/><br/><font color='red'>Внимание!</font> При включении замены существующих таблиц могут быть потеряны некоторые данные, например, может очиститься таблица с данными о пользователях. Но CMS может не поддерживать текущие таблицы, поэтому рекомендуется сделать резервную копию и включить замену, а затем восстановить резервную копию.<br/><br/>Если база данных пуста или CMS устанавливается в первый раз, то необходимо включить замену таблиц, чтобы CMS импортировала свои настройки в базу данных.<br/><br/><font color='red'>Примечание.</font> CMS заменит только необходимые для её работы таблицы.";
				$_SESSION['db']['act'] = "import";
				$ech = $_SERVER['HTTP_ORIGIN'].$_SERVER['REQUEST_URI'];
				$ech = str_replace("/setup/", "", $ech);
				header("Location: $ech/setup");
				}else $content .= "Не существует папка 'CMS' в корневой папке сайта или файл 'config.php'.<br/><br/>Загрузите на сервер сайта другую копию MSC Web Site Engine и обновите страницу";
				
			}
				
		}
		
	}else{
	
		if($_POST['act'] != "db_import"){
		$content .= "Хотите перезаписать существующие таблицы?<br/><br/><form method='POST'><input type='hidden' name='act' value='db_import' /><input type='submit' name='yes' value=' ' style='text-decoration: none; background: url(%adress%/setup/tmpl/da.png) no-repeat; height: 47px; width: 150px; border: 0;'/><input type='submit' name='no' value=' ' style='    text-decoration: none;   background: url(%adress%/setup/tmpl/no.png) no-repeat;    height: 47px;    width: 150px;   border: 0;'></form><br/><br/><font color='red'>Внимание!</font> При включении замены существующих таблиц могут быть потеряны некоторые данные, например, может очиститься таблица с данными о пользователях. Но CMS может не поддерживать текущие таблицы, поэтому рекомендуется сделать резервную копию и включить замену, а затем восстановить резервную копию.<br/><br/>Если база данных пуста или CMS устанавливается в первый раз, то необходимо включить замену таблиц, чтобы CMS импортировала свои настройки в базу данных.<br/><br/><font color='red'>Примечание.</font> CMS заменит только необходимые для её работы таблицы.";
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
			$_SESSION['message'] = "Все необходимые данные были импортированы.";
			}else $_SESSION['message'] .= "Вы отказались от импорта стандартных настроек.";
			next_stage();			
		}
		
	}
	
?>