<?php

//���������� AJAX ��� CMS MSC WebSite Engine
//������ ������ ���������� ������� GET(���� POST, �� ��������� ������� � URL)
//������ �������:
//		type: ��� ������ (position | system | block | scrirpt | menu)
//		��������� ��������� �� ���� (���������� ���� <content /> ��� ��������� ���������)

//������ �������

session_start();

//��������� �������	
if(!is_file("cms/config.php")) exit;	
include("cms/config.php");

//����������� "������������"	
if(!is_file("cms/lib.php")) exit;	
include("cms/lib.php");

//��������� ������������ �������� � �������� ��� �������
echo libs::GetLib("templates_types")->getContentByTag($_GET);

//���������� ������
exit;

?>