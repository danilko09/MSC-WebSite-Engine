<?php

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