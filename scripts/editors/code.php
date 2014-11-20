<?php

class editors_code{

	public static function GetField($name,$code){
		return '
		
		
<link rel="stylesheet" href="%adress%/scripts/editors/codemirror/mode/../lib/codemirror.css">
<script src="%adress%/scripts/editors/codemirror/mode/../lib/codemirror.js"></script>
<script src="%adress%/scripts/editors/codemirror/mode/../addon/edit/matchbrackets.js"></script>
<script src="%adress%/scripts/editors/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="%adress%/scripts/editors/codemirror/mode/xml/xml.js"></script>
<script src="%adress%/scripts/editors/codemirror/mode/javascript/javascript.js"></script>
<script src="%adress%/scripts/editors/codemirror/mode/css/css.js"></script>
<script src="%adress%/scripts/editors/codemirror/mode/clike/clike.js"></script>
<script src="%adress%/scripts/editors/codemirror/mode/php/php.js"></script>

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