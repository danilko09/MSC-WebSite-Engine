<?php

	if(!isset($_POST['act']) || $_POST['act'] != "next")$content = "����������� ����:<br/><br/>1.��������� ���� ������<br/>2.���������� ��������������<br/>3.��������� ����������� �����������<br/>4.���������� ���������<br/><br/><form method='POST' action='#'><input type='hidden' name='act' value='next'/><input type='submit' value='' style='
    text-decoration: none;
    background: url(%adress%/setup/tmpl/begin.png) no-repeat;
    height: 47px;
    width: 150px;
    border: 0;
' /></form>";
	else	next_stage();
?>