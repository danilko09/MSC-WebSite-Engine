<?php
	$ech = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$ech = str_replace("/setup", "", $ech);
	$content = "��������� ���������.<br/>��� ����������� �������������� ����������� ��������������.<br/><br/><a href='$ech'>�� ����</a> | <a href='".$ech."index.php/admin'>� ���������������� ������.</a>";
	//����� ������ ���� �������� ���������� setup, �� ���� ������ ����� �������� � ������
	$_SESSION = Array();
	
	$finish = true;