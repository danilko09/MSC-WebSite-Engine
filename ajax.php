<?php

//���������� AJAX ��� CMS MSC WebSite Engine
//������ ������ ���������� ������� GET(���� POST, �� ��������� ������� � URL)
//������ �������:
//		type: ��� ������ (position | system | block | scrirpt | menu)
//		��������� ��������� �� ���� (���������� ���� <content /> ��� ��������� ���������)

//������ �������
session_start();
//��������� �������	
if(!is_file("cms/config.php")){exit;}	
include("cms/config.php");
//����������� "������������"	
if(!is_file("cms/lib.php")){exit;}	
include("cms/lib.php");
//��������� ������������ �������� � �������� ��� �������
$arr = filter_input_array(INPUT_GET);
if($arr['type'] == "content"){$ajax = true; include("cms/templates.php");}
else{echo libs::GetLib("templates_types")->getContentByTag($arr);}

?>