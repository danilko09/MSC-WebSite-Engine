<?php

	if(!isset($_POST['act']) || $_POST['act'] != "next")$content = "Предстоящие шаги:<br/><br/>1.Настройка базы данных<br/>2.Назначение администратора<br/>3.Установка необходимых компонентов<br/>4.Завершение установки<br/><br/><form method='POST' action='#'><input type='hidden' name='act' value='next'/><input type='submit' value='' style='
    text-decoration: none;
    background: url(%adress%/setup/tmpl/begin.png) no-repeat;
    height: 47px;
    width: 150px;
    border: 0;
' /></form>";
	else	next_stage();
?>