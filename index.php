<?php session_start();
//������ �����������
    if(is_dir('setup') && is_file('setup/index.php')){header('Location: setup');}
//�������� ������������� ����� CMS	
    if(!is_dir("cms")){die("����������� ���������� 'CMS'.");}
//��������� �������	
    if(!is_file("config.php")){exit;}	
    include("config.php");
//����������� "������������"	
    if(!is_file("cms/lib.php")){exit;}	
    include("cms/lib.php");
//��������� �������� ��� ������� ��������
    if(!is_file("cms/templates.php")){exit;}
    include("cms/templates.php");

?>