<?php
addNavi("Редактор блоков", "%adress%/index.php/admin/blocks");
$title .= " | Редактор блоков";

if($pg[4] == null || $pg[4] == ""){
$bl = $db->getAll("blocks", true, true);
$blocks = null;

foreach($bl as $id=>$block){

	$blocks .= ($id+1).". <a href='%adress%/index.php/admin/blocks/".$block['id']."'>".trim($block['title'])."</a><br/>";
	
}

	$site .= "<div><p>Для редактирования блока выберите его название из списка<br/></br></p>$blocks<p><a href='%adress%/index.php/admin/blocks/add'>Добавить новый блок</a></p></div>";
}elseif($pg[4] == "add"){

	addNavi("Добавить блок","%adress%/index.php/admin/blocks/add");

	if($_POST['act'] != "save"){
		
		$id = $db->getLastID("blocks");
		
		$site .= "<h3>Добавление нового блока</h3><br/><form method='post'><input type='hidden' name='act' value='save'>ID: ".$id."<br/>Название: <input type='text' name='title' />  <input type='submit' value='сохранить'><br/><br/>Положение: <select name='pos' size=1><option value='' selected>Скрыть</option><option value='l'>Слева</option><option value='r'>Справа</option></select></br><textarea name='content' style='margin-top: 10px; width: 100%; height: 300px;'></textarea><br/>Параметр class: <input type='text' name='class'/><br/>Параметр ID: <input type='text' name='id' /></form>";
	
	}else{
	
		if($db->isExists("blocks", "title", $_POST['title'])){
			$_SESSION['userMessage'] = "Блок с таким названием уже существует.";
			redirect(config::site_url."/index.php/admin/blocks/add");
		}else{
		
			$cnt = str_replace("\\\"", "\"", $_POST['content']);
			$block["title"] = $_POST['title'];
			$block["content"] = $cnt;
			$block["css_class"] = $_POST['css_class'];
			$block["css_id"] = $_POST['css_id'];
			$block["pos"] = $_POST['pos'];
			
			$db->insert("blocks", $block);
			$_SESSION['userMessage'] = "Блок ".$_POST['title']." сохранен!";
			
			redirect(config::site_url."/index.php/admin/blocks");

		}
	
	}

}else{

	if($_POST['act'] != "save"){
				
		$bl = $db->getElementOnID("blocks", $pg[4]);
		
		addNavi($bl['title'],"%adress%/index.php/admin/blocks/".$pg[4]);
		
		$pos[$bl['pos']] = "selected";
		
		$site .= "<br/><form method='post'><input type='hidden' name='act' value='save'>ID: ".$pg[4]."<br/>Название: <input type='text' value='".$bl['title']."' name='title' />  <input type='submit' value='сохранить'><br/><br/>Положение: <select name='pos' size=1><option value='' ".$pos[''].">Скрыть</option><option value='l' ".$pos['l'].">Слева</option><option value='r' ".$pos['r'].">Справа</option></select></br><textarea name='content' style='margin-top: 10px; width: 100%; height: 300px;'>".$bl['content']."</textarea><br/>Параметр class: <input type='text' value='".$bl['css_class']."' name='class'/><br/>Параметр ID: <input type='text' value='".$bl['css_id']."' name='id' /></form>";
	
	}else{
	
		$cnt = str_replace("\\\"", "\"", $_POST['content']);
		$cnt = str_replace(config::site_url,"%adress%", $cnt);
		$db->setFieldOnID("blocks", $pg[4], "title", $_POST['title']);
		$db->setFieldOnID("blocks", $pg[4], "content", "$cnt");
		$db->setFieldOnID("blocks", $pg[4], "css_class", $_POST['css_class']);
		$db->setFieldOnID("blocks", $pg[4], "css_id", $_POST['css_id']);
		$db->setFieldOnID("blocks", $pg[4], "pos", $_POST['pos']);
		
		$_SESSION['userMessage'] = "Блок ".$_POST['title']." сохранен!";
		
		redirect(config::site_url."/index.php/admin/blocks");
	
	}
}

?>