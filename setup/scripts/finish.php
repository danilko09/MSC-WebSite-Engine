<?php
	$ech = filter_input(INPUT_SERVER,'HTTP_ORIGIN').filter_input(INPUT_SERVER,'REQUEST_URI');
	$ech = str_replace("/setup", "", $ech);
	$content = "��������� ���������.<br/>��� ����������� �������������� �����������.<br/><br/><a href='$ech'>�� ����</a> | <a href='".$ech."index.php/admin'>� ���������������� ������.</a>";
	$_SESSION = Array();//��������� ������, ����-�� ����-������ ������� ����������
	
	$finish = true;
?>