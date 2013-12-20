<?php

// Визуальный редактор текста
// Так же как и редактор кода создан для удобства и стандартизации обращения.
// Используйте для редактирования текстов (используется например в новостной ленте).
// Ну я думаю с tinymce все уже когда-то сталкивались, так вот - это его "адаптер" под данную CMS.

class editors_visual{

	public function GetField($name,$code){
	
		return "
		
<script type='text/javascript' src='%adress%/lib/editors/tinymce/js/tinymce/tinymce.min.js'></script>
<script type='text/javascript'>
tinymce.init({
    selector: 'textarea',
	 });
</script>

<script>
tinymce.init({
    selector: 'textarea#".$name."'
 }); 
</script>

<textarea id='".$name."' name='".$name."'>".$code."</textarea>
	
		";
	
	}

}

?>