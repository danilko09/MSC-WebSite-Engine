<?php

// `admin`
$admin = array(
  array('id' => '1','alias' => 'blocks','title' => 'Менеджер блоков','group' => 'Основные'),
  array('id' => '2','alias' => 'scripts','title' => 'Менеджер скриптов','group' => 'Основные'),
  array('id' => '3','alias' => 'pages','title' => 'Менеджер страниц','group' => 'Основные'),
  array('id' => '4','alias' => 'menus','title' => 'Менеджер меню','group' => 'Основные'),
  array('id' => '5','alias' => 'about','title' => 'О системе','group' => 'Основные')
);

// `lib`
$lib = array(
  array('id' => '2','title' => 'testing','file' => 'testing')
);

// `menus`
$menus = array(
  array('id' => '1','alias' => 'main','title' => 'Главное меню','links' => 'На главную|%adress%/index.php/|all,Админка|%adress%/index.php/admin|all')
);

$pages = array(
  array('id' => '1','alias' => 'home','title' => 'Добро пожаловать','content' => '<p>Установка CMS "MSC: WebSite Engine A.2.0 Preview 2" успешно завершена !</p>
<p>Это главная страница сайта, для её редактирования вам нужно использовать менеджер страниц в административной панели.</p>
<p>&nbsp;</p>
<p>Главное меню сайта:</p>
<p><content type=\'menu\' name=\'main\'/></p>
<p>&nbsp;</p>'),
  array('id' => '2','alias' => 'registration','title' => 'Регистрация','content' => '<p><content type=\'script\' name=\'auth\' action=\'GetRegister\'/></p>'),
  array('id' => '3','alias' => 'auth','title' => 'Авторизация','content' => '<p><content type=\'script\' name=\'auth\' action=\'GetAuth\'/></p>'),
  array('id' => '4','alias' => 'logout','title' => 'Выйти','content' => '<p><content type=\'script\' name=\'auth\' action=\'DoLogout\'/></p>'),
  );

// `scripts`
$scripts = array(
  array('id' => '1','title' => 'авторизация\\регистрация','alias' => 'auth','file' => 'users'),
  );

// `server`
$server = array(
  array('id' => '1','param' => 'launcher','value' => '13'),
  array('id' => '2','param' => 'build','value' => '1')
);

$import = array(
	'admin'=>$admin,
	'lib'=>$lib,
	'menus'=>$menus,
	'pages'=>$pages,
	'scripts'=>$scripts,
	'server'=>$server
);
