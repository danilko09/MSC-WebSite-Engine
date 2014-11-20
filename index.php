<?php
//ini_set('memory_limit', '-1');//����������� ������ :-)
$timing = microtime();//��������� ����� ������ ���������
//��������� ������ ���������� ���������
error_reporting(E_ALL);
ini_set('display_errors', 1);
//������ ������
session_start();
//����������� ��������
define("MSC_WSE_CORE_VERSION","3.1");
define("MSC_WSE_ENGINE_VERION","A.3.1");
define("MSC_WSE_ENGINE_VERSION_DESC", "alpha 3.1 | 05.11.2014");

//������ �����������
if (is_dir('setup') && is_file('setup/index.php')) {header('Location: setup');}
//�������� ������������� ����� CMS	
if(!is_dir("cms")){die("����������� ���������� 'CMS'.");} 
//��������� �������	
if(!is_file("cms/config.php")){exit;}	
include("cms/config.php");
//��������� �����������
if(!is_file("cms/translate.php")){exit;}	
include("cms/translate.php");
//����������� "������������"	
//if(!is_file("cms/lib.php")){exit;}	
//include("cms/lib.php");
//�������� ���������� ��������� ��������
if(!is_file("cms/scripts.php")){exit;}	
include("cms/scripts.php");
scripts::makeAutoload();
//��������� �������� ��� ������� ��������
if (!is_file("cms/templates.php")){exit;}
include("cms/templates.php");
//������� ����� ��������� ��������
echo "<!-- ".(round((microtime() - $timing)*1000))."ms -->";