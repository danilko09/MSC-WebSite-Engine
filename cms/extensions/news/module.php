<?php

class module_news extends abs_module implements int_module{

	public function processAction($action,$params){

		switch($action){

			case "main": return $this->getNewsListBlock(); break;
			case "add": return $this->getAddPage(); breal;

		}

	}

	public static function processInsertion($ins){
		switch($ins){
			case "block":  return self::getNewsListBlock(); break;
		}
	}

	private function getNewsPage($params){

	global $db;

	var_dump($params);
	if(isset($params['add'])){
	
		

	
	}else{
	
		$art = $db->getElementOnID("news", $pg_pars);
		$ret = $art['full'];
	
	}
	
	return "<div class='post'>lalka".$ret."</div>";

}

private static function getNewsListBlock(){

	global $db;
	$return = "";
	$news = $db->getAll("news", true, true);
	
	if(isset($_SESSION['auth']) && $_SESSION['auth'] === "1") $return .= "<a href='%adress%/news/add' style='float: right; margin: 10px 15px 0 0;'>Äîáàâèòü íîâîñòü</a>";
	
	$return .= "<ul>";
	
	foreach($news as $id => $cont){
	
		$return .= "<li>".templates::getTmpl("miniArticle", $cont)."</li>";
	
	}
	
	$return .= "</ul>";
	
	return $return;
}

private function getAddPage(){

	$ret.= "<h1 class='title'><a href=''>Äîáàâëåíèå íîâîñòè</a></h1>";
		
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
				
				$ret .= "<br/>Ó âàñ íåò äîñòóïà ê ýòîé ñòðàíèöå.<br/>";
				
			}
		
		}
		else{
		
			$ret .= "<div style='background-color: darkgreen; padding: 10px; margin: 10px; border-radius: 10px;'><div style='background-color: greenYellow; padding: 10px; margin: 00px; border-radius: 10px;'>Äëÿ äîáàâëåíèÿ íîâîñòè íåîáõîäèìî ïðîéòè àâòîðèçàöèþ.</div><br/>[users_loginForm]</div>";
		
		}

	return $ret;

}

}
