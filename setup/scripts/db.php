<?php

// `admin`
$admin = array(
  array('id' => '1','alias' => 'blocks','title' => '�������� ������','group' => '��������'),
  array('id' => '2','alias' => 'scripts','title' => '�������� ��������','group' => '��������'),
  array('id' => '3','alias' => 'pages','title' => '�������� �������','group' => '��������'),
  array('id' => '4','alias' => 'menus','title' => '�������� ����','group' => '��������'),
  array('id' => '5','alias' => 'about','title' => '� �������','group' => '��������')
);

// `lib`
$lib = array(
  array('id' => '2','title' => 'testing','file' => 'testing')
);

// `menus`
$menus = array(
  array('id' => '1','alias' => 'main','title' => '������� ����','links' => '�� �������|%adress%/index.php/|all,�������|%adress%/index.php/admin|all')
);

$pages = array(
  array('id' => '1','alias' => 'home','title' => '����� ����������','content' => '<p>��������� CMS "MSC: WebSite Engine A.2.0 Preview 2" ������� ��������� !</p>
<p>��� ������� �������� �����, ��� � �������������� ��� ����� ������������ �������� ������� � ���������������� ������.</p>
<p>&nbsp;</p>
<p>������� ���� �����:</p>
<p><content type=\'menu\' name=\'main\'/></p>
<p>&nbsp;</p>'),
  array('id' => '2','alias' => 'registration','title' => '�����������','content' => '<p><content type=\'script\' name=\'auth\' action=\'GetRegister\'/></p>'),
  array('id' => '3','alias' => 'auth','title' => '�����������','content' => '<p><content type=\'script\' name=\'auth\' action=\'GetAuth\'/></p>'),
  array('id' => '4','alias' => 'logout','title' => '�����','content' => '<p><content type=\'script\' name=\'auth\' action=\'DoLogout\'/></p>'),
  );

// `scripts`
$scripts = array(
  array('id' => '1','title' => '�����������\\�����������','alias' => 'auth','file' => 'users'),
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
