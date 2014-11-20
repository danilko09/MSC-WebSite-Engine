<?php

// `admin`
$admin = array(
  array('id' => '1','alias' => 'blocks','title' => '�������� ������','group' => '��������'),
  array('id' => '2','alias' => 'scripts','title' => '�������� ��������','group' => '��������'),
  array('id' => '3','alias' => 'pages','title' => '�������� �������','group' => '��������'),
  array('id' => '4','alias' => 'menus','title' => '�������� ����','group' => '��������'),
  array('id' => '5','alias' => 'about','title' => '� �������','group' => '��������')
);

// `blocks`
$blocks = array(
  array('id' => '1','alias' => 'news','title' => '�������','position' => 'sidebar','order' => '3','code' => '<content type=\'script\' name=\'news\' action=\'getNewsBlock\'/>
'),
  array('id' => '2','alias' => 'news_search','title' => '����� �� �����','position' => 'sidebar','order' => '0','code' => '<content type=\'script\' name=\'search\' action=\'getSearchBlock\'/>


'),
  array('id' => '3','alias' => '','title' => '�����','position' => 'sidebar','order' => '0','code' => '<content type=\'script\' name=\'news\' action=\'getArchiveBlock\'/>')
);

// `lib`
$lib = array(
  array('id' => '1','title' => 'testing','file' => 'testing')
);

// `menus`
$menus = array(
  array('id' => '1','alias' => 'main','title' => '������� ����','links' => '�� �������|%adress%/index.php/|all,�������|%adress%/index.php/admin|all')
);

// `news`
$news = array(
  array('id' => '1','title' => 'hello','category' => 'default','date' => '2013-11-01','short' => 'hello short','full' => 'hello full'),
  array('id' => '2','title' => '����','category' => 'default','date' => '2013-11-01','short' => '�������� �������','full' => '������ ���������� �������� �������')
);

// `server`
$server = array(
  array('id' => '1','param' => 'launcher','value' => '13'),
  array('id' => '2','param' => 'build','value' => '1')
);

$import = array(
	'blocks'=>$blocks,
	'lib'=>$lib,
	'menus'=>$menus,
	'news'=>$news,
	'server'=>$server
);
