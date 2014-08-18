<?php

function getNewsPage(){

	global $db;
	global $pg_pars;
	
	if($pg_pars === "add"){
	
		$ret.= "<h1 class='title'><a href=''>Добавление новости</a></h1>";
		
		if($_SESSION['auth'] === "1"){
		
			$perm = $db->getField("users", "group", "login", $_SESSION['login']);
			
			if($perm === "admin"){
			
				if($_POST['save'] != null){
					
					$arr['title'] = $_POST['name'];
					$arr['short'] = $_POST['short'];
					$arr['full'] = $_POST['full'];
					$arr['date'] = date("Y-m-d");
					
					$db->insert("news",$arr);
				
				}
				else $ret .= templates::getTmpl("news/add",Array());
				
			}else{
				
				$ret .= "<br/>У вас нет доступа к этой странице.<br/>";
				
			}
		
		}
		else{
		
			$ret .= "<div style='background-color: darkgreen; padding: 10px; margin: 10px; border-radius: 10px;'><div style='background-color: greenYellow; padding: 10px; margin: 00px; border-radius: 10px;'>Для добавления новости необходимо пройти авторизацию.</div><br/>".getLoginForm()."</div>";
		
		}

	
	}else{
	
		$art = $db->getElementOnID("news", $pg_pars);
		$ret = $art['full'];
	
	}
	
	return "<div class='post'>".$ret."</div>";
	
}

function getNewsBlock(){

	global $db;
	$return = "";
	$news = $db->getAll("news", true, true);
	
	if(isset($_SESSION['auth']) && $_SESSION['auth'] === "1") $return .= "<a href='%adress%/index.php/news/add' style='float: right; margin: 10px 15px 0 0;'>Добавить новость</a>";
	
	$return .= "<ul>";
	
	foreach($news as $id => $cont){
	
		$return .= "<li>".templates::getTmpl("miniArticle", $cont)."</li>";
	
	}
	
	$return .= "</ul>";
	
	return $return;

}

function getArchiveBlock(){

	global $db;
	
	$news = $db->getAll("news", true, true);
	$dt = array();
	foreach($news as $key => $val){
	
		foreach($val as $k=>$v){
		
			if($k == "date"){
			
				$date = explode("-", $v);
				$index = $date[1]."-".$date[0];
				if(isset($dt[$index])){$dt[$index]++;}
				else{$dt[$index] = 1;} 
			
			}
		
		}
	
	}
	$ret = "";
	foreach($dt as $d => $n){
	
		$dm = $d;
		$df = explode("-", $dm);
		$dm = $df[1]."-".$df[0];
		
		$d = dateMonth($d);
		
		$ret = "<a href='%adress%/index.php/archive/$dm'>".$d."($n)</a><br/>".$ret;
	
	}
	
	return "<center>".$ret."</center>";

}

function getArchivePage(){
	
	global $db;
	global $pg_pars;
	
	if($pg_pars === "") return "<h1>Архив новостей</h1><div>Не верный запрос.</div>";
	
	$df = explode("-", $pg_pars);
	$dt = $df[1]."-".$df[0];
	$cont .= "Новости на период \"".dateMonth($dt)."\".";
	
	$cnt = $db->search("news", $pg_pars, array("date"));
	if(!is_array($cnt)) $cont .= "<br/>Ничего не найдено.";
	else{
		
		foreach($cnt as $pt => $art){
			if($art['title'] != "") $cont .= $i.templates::getTmpl("miniArticle", $art);
		}
	
	}
	
	return "<h1>Архив новостей</h1><div>".$cont."</div>";

}

function dateMonth($d){

	$d = str_replace("01-", "Янаварь ", $d);
	$d = str_replace("02-", "Февраль ", $d);
	$d = str_replace("03-", "Март ", $d);
	$d = str_replace("04-", "Апрель", $d);
	$d = str_replace("05-", "Май ", $d);
	$d = str_replace("06-", "Июнь ", $d);
	$d = str_replace("07-", "Июль ", $d);
	$d = str_replace("08-", "Август ", $d);
	$d = str_replace("09-", "Сентябрь ", $d);
	$d = str_replace("10-", "Октябрь ", $d);
	$d = str_replace("11-", "Ноябрь ", $d);
	$d = str_replace("12-", "Декабрь ", $d);

	return $d;
	
}
?>
