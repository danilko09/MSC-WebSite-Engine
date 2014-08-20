<?php
addNavi("�������� ������", "%adress%/index.php/admin/blocks");
$title .= " | �������� ������";

if($pg[4] == null || $pg[4] == ""){
$bl = $db->getAll("blocks", true, true);
$blocks = null;

foreach($bl as $id=>$block){

	$blocks .= ($id+1).". <a href='%adress%/index.php/admin/blocks/".$block['id']."'>".trim($block['title'])."</a><br/>";
	
}

	$site .= "<div><p>��� �������������� ����� �������� ��� �������� �� ������<br/></br></p>$blocks<p><a href='%adress%/index.php/admin/blocks/add'>�������� ����� ����</a></p></div>";
}elseif($pg[4] == "add"){

	addNavi("�������� ����","%adress%/index.php/admin/blocks/add");

	if($_POST['act'] != "save"){
		
		$id = $db->getLastID("blocks");
		
		$site .= "<h3>���������� ������ �����</h3><br/><form method='post'><input type='hidden' name='act' value='save'>ID: ".$id."<br/>��������: <input type='text' name='title' />  <input type='submit' value='���������'><br/><br/>���������: <select name='pos' size=1><option value='' selected>������</option><option value='l'>�����</option><option value='r'>������</option></select></br><textarea name='content' style='margin-top: 10px; width: 100%; height: 300px;'></textarea><br/>�������� class: <input type='text' name='class'/><br/>�������� ID: <input type='text' name='id' /></form>";
	
	}else{
	
		if($db->isExists("blocks", "title", $_POST['title'])){
			$_SESSION['userMessage'] = "���� � ����� ��������� ��� ����������.";
			redirect(config::site_url."/index.php/admin/blocks/add");
		}else{
		
			$cnt = str_replace("\\\"", "\"", $_POST['content']);
			$block["title"] = $_POST['title'];
			$block["content"] = $cnt;
			$block["css_class"] = $_POST['css_class'];
			$block["css_id"] = $_POST['css_id'];
			$block["pos"] = $_POST['pos'];
			
			$db->insert("blocks", $block);
			$_SESSION['userMessage'] = "���� ".$_POST['title']." ��������!";
			
			redirect(config::site_url."/index.php/admin/blocks");

		}
	
	}

}else{

	if($_POST['act'] != "save"){
				
		$bl = $db->getElementOnID("blocks", $pg[4]);
		
		addNavi($bl['title'],"%adress%/index.php/admin/blocks/".$pg[4]);
		
		$pos[$bl['pos']] = "selected";
		
		$site .= "<br/><form method='post'><input type='hidden' name='act' value='save'>ID: ".$pg[4]."<br/>��������: <input type='text' value='".$bl['title']."' name='title' />  <input type='submit' value='���������'><br/><br/>���������: <select name='pos' size=1><option value='' ".$pos[''].">������</option><option value='l' ".$pos['l'].">�����</option><option value='r' ".$pos['r'].">������</option></select></br><textarea name='content' style='margin-top: 10px; width: 100%; height: 300px;'>".$bl['content']."</textarea><br/>�������� class: <input type='text' value='".$bl['css_class']."' name='class'/><br/>�������� ID: <input type='text' value='".$bl['css_id']."' name='id' /></form>";
	
	}else{
	
		$cnt = str_replace("\\\"", "\"", $_POST['content']);
		$cnt = str_replace(config::site_url,"%adress%", $cnt);
		$db->setFieldOnID("blocks", $pg[4], "title", $_POST['title']);
		$db->setFieldOnID("blocks", $pg[4], "content", "$cnt");
		$db->setFieldOnID("blocks", $pg[4], "css_class", $_POST['css_class']);
		$db->setFieldOnID("blocks", $pg[4], "css_id", $_POST['css_id']);
		$db->setFieldOnID("blocks", $pg[4], "pos", $_POST['pos']);
		
		$_SESSION['userMessage'] = "���� ".$_POST['title']." ��������!";
		
		redirect(config::site_url."/index.php/admin/blocks");
	
	}
}

?>