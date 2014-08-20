<?php
//session		
	session_start();
//main function
	function CMS_GET_PAGE(){
//globals
	global $self;
	global $debug;
	global $db;
	global $navigator;
	global $module,$action,$parameters;

//loading tmpl
	$page = file_get_contents("tmpl/".config::def_template."/main.tpl");
//user message
/*	if(isset($_SESSION['userMessage'])){
	$page = str_replace("%message%", "<br/><br/><div style='background-color: green; border-radius: 5px; border: solid 5px yellowgreen; color: white; padding: 10px;'>".$_SESSION['userMessage']."</div>", $page);
	$_SESSION['userMessage'] = "";
	}else $page = str_replace("%message%", "", $page);
*/
//modules
$module_class = "module_".$module;
if(class_exists($module_class)){
$page_module = new $module_class();
if($page_module instanceof int_module){
$content = $page_module->processAction($action,$parameters);
$title = $page_module->getPageTitle();
$meta_desc = $page_module->getPageDescription();
$meta_keys = $page_module->getPageKeyWords();
}
}else{
	$page = str_replace("%content%", "<h2>�������� �� �������.</h2><p>�������� � ������� ".config::site_url.$self." �� �������.</p>", $page);
}
//on page blocks
	$blocks = $db->getAll("blocks", false, false);
	$f = $db->getLastId("blocks");
	$blocks['r'] = "";$blocks['l'] = "";
	for($i = 0; $i < $f; $i++){
	if(!isset($blocks[$i])){continue;}
	if($blocks[$i]['pos'] === "r") $blocks['r'] .= "<li><h2>".$blocks[$i]['title']."</h2><div>".$blocks[$i]['content']."</div></li>";
	elseif($blocks[$i]['pos'] === "l") $blocks['l'] .= "<li id=\"".$blocks[$i]['css_id']."\"><h2>".$blocks[$i]['title']."</h2><div>".$blocks[$i]['content']."</div></li>";
	}
//sidebars with blocks
	$page = str_replace("%sidebar_l%","<ul>".$blocks['l']."</ul>",$page);
	$page = str_replace("%sidebar_r%","<ul>".$blocks['r']."</ul>",$page);
//replacement blocks
	preg_match_all("|\[block_(.*)\]|U", $page, $text_blocks, PREG_SPLIT_NO_EMPTY);
	$l = count($text_blocks, COUNT_RECURSIVE);
	$l = $l / 2;
	for($i=0; $i <= $l; $i++){
	
		if(isset($text_blocks[1][$i]) && $db->isExists("blocks", "title", $text_blocks[1][$i])){
		
			$bl = $db->getField("blocks", "content","title", $text_blocks[1][$i]);
			$page = str_replace($text_blocks[0][$i], $bl, $page);
		
		}elseif(isset($text_blocks[1][$i]) && $text_blocks[1][$i] != ""){
			debug("���� ".$text_blocks[0][$i]." �� ������.");		
		}
		
	}

//adding scripts to page
	$page = templates::addScripts($page,array("title"=>"","content"=>""),"main.tpl");
//admin include	
	if(isset($pg['2']) && $pg['2'] === "admin") include("admin.php");
//adding absolute pathes
	$page = str_replace("%adress%", config::site_url, $page);
//debug out
	if($debug != ""){
	$debug = "<p id='debugBar' style='background-color: lightblue; margin: 10px; padding: 10px; border-radius: 10px'>���������� ���������� (����� ���� ����������� �����):<br/>".$debug."<br/><a href='#' onClick='document.getElementById(\"debugBar\").style.display=\"none\";'>������</a></p>";
	$page = str_replace("%debug%", $debug, $page);
	}else $page = str_replace("%debug%", "", $page);
	eval("?>".$page);
	}
//end of main function	

//// functions ////

//redirect function
	function redirect($to){
	
		header("Location: ".$to);
		exit;
	
	}
//navigation function
	function addNavi($page,$url){
	
		global $navigator;
		$navigator .= " | <a href='$url'>$page</a>";
	
	}
	
	function __autoload($class){

		$pos = strpos($class,"_");
		if($pos){$type = substr($class,0,$pos);$ext = substr($class,$pos+1);}

		switch($type){

			case "module": if(is_file(__DIR__."/extensions/".$ext."/module.php")){include_once __DIR__."/extensions/".$ext."/module.php";} break;

		}
	}
?>
