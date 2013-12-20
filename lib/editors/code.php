<?php

// Библиотека редактора кода
// Пока работает только с codemirror, но позже добавлю возможность установки других редакторов
// Создан пока для стандартизации обращения скриптов к редакторам
// Так-что юзайте сию библиотеку, если вам нужен редактор php,js,html

class editors_code{

	public function GetField($name,$code){
		return '
		
		
<link rel="stylesheet" href="%adress%/lib/editors/codemirror/mode/../lib/codemirror.css">
<script src="%adress%/lib/editors/codemirror/mode/../lib/codemirror.js"></script>
<script src="%adress%/lib/editors/codemirror/mode/../addon/edit/matchbrackets.js"></script>
<script src="%adress%/lib/editors/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="%adress%/lib/editors/codemirror/mode/xml/xml.js"></script>
<script src="%adress%/lib/editors/codemirror/mode/javascript/javascript.js"></script>
<script src="%adress%/lib/editors/codemirror/mode/css/css.js"></script>
<script src="%adress%/lib/editors/codemirror/mode/clike/clike.js"></script>
<script src="%adress%/lib/editors/codemirror/mode/php/php.js"></script>

<form><textarea id="'.$name.'" name="'.$name.'">
'.$code.'
</textarea></form>
    <script>
      var editor = CodeMirror.fromTextArea(document.getElementById("'.$name.'"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"
      });
    </script>
		
		';
	}

}

?>