<?php

class news{

	public function getNewsPage(){

		$db = (new database());
		$pg_pars = isset($_GET['action']) ? $_GET['action'] : null;
		
		if($pg_pars === "add"){
		
			$ret.= "<h1 class='title'><a href=''>���������� �������</a></h1>";
			
			if(libs::GetLib("users")->IsAuthorized() == "1"){
			
			libs::loadLib("users");
				
				if(libs::GetLib("users")->GetGroup() == "admin"){
				
					if($_POST['save'] != null){
						
						$arr['title'] = $_POST['name'];
						$arr['short'] = $_POST['short'];
						$arr['full'] = $_POST['full'];
						$arr['date'] = date("Y-m-d");
						
						$db->insert("news",$arr);
						$ret .= templates::getTmpl("news/saved");
					
					}
					else $ret .= templates::getTmpl("news/add");
					
				}else{
					
					$ret .= "<br/>� ��� ��� ������� � ���� ��������.<br/>";
					
				}
			
			}
			else{
			
				$ret .= "<div>��� ���������� ������� ���������� ����������������.</div>";
			
			}

		
		}else{
		
			$art = $db->getElementOnID("news", $_GET['id']);
			$ret = $art['full'];
		
		}
		
		return "<div class='post'>".$ret."</div>";
		
	}

	public function getNewsBlock(){

		$db = (new database());
		$news = $db->getAll("news", true, true);
		$return = "";
                
		if(isset($_SESSION['auth']) && $_SESSION['auth'] === "1") $return .= "<a href='%adress%/index.php/news/add' style='float: right; margin: 10px 15px 0 0;'>�������� �������</a>";
		
		$return .= "<ul>";
		
		foreach($news as $id => $cont){
		
			$return .= "<li>".templates::getRTmpl("miniArticle", $cont)."</li>";
		
		}
		
		$return .= "</ul>";
		
		return $return;

	}

	public function getArchiveBlock(){

		$db = (new database());
		$news = $db->getAll("news", true, true);
		$dt = array();
                
                foreach($news as $key => $val){
		
			foreach($val as $k=>$v){
			
				if($k == "date"){
				
					$date = explode("-", $v);
                                        isset($dt[$date[1]."-".$date[0]]) ? $dt[$date[1]."-".$date[0]]++ : $dt[$date[1]."-".$date[0]] = 1;
				
				}
			
			}
		
		}
		$ret = "";
		foreach($dt as $d => $n){
		
			$dm = $d;
			$df = explode("-", $dm);
			$dm = $df[1]."-".$df[0];
			
			$d = dateMonth($d);
			
			$ret = "<a href='%adress%/index.php/archive?period=$dm'>".$d."($n)</a><br/>".$ret;
		
		}
		
		return "<div><p><center>".$ret."</center></p></div>";

	}

	public function getArchivePage(){
		
		$db = (new database());
		$pg_pars = $_GET['period'];
		
		if($pg_pars === "" || $pg_pars === null) return "<h1>����� ��������</h1><div><content type='script' name='ArchiveBlock'/></div>";
		
		$df = explode("-", $pg_pars);
		$dt = $df[1]."-".$df[0];
		$cont .= "������� �� ������ \"".dateMonth($dt)."\".";
		
		$cnt = $db->search("news", $pg_pars, array("date"));
		if(!is_array($cnt)) $cont .= "<br/>������ �� �������.";
		else{
			
			foreach($cnt as $pt => $art){
				if($art['title'] != "") $cont .= $i.templates::getRTmpl("miniArticle", $art);
			}
		
		}
		
		return "<h1>����� ��������</h1><div>".$cont."</div>";

	} 

}

function dateMonth($d){

		$d = str_replace("01-", "������� ", $d);
		$d = str_replace("02-", "������� ", $d);
		$d = str_replace("03-", "���� ", $d);
		$d = str_replace("04-", "������", $d);
		$d = str_replace("05-", "��� ", $d);
		$d = str_replace("06-", "���� ", $d);
		$d = str_replace("07-", "���� ", $d);
		$d = str_replace("08-", "������ ", $d);
		$d = str_replace("09-", "�������� ", $d);
		$d = str_replace("10-", "������� ", $d);
		$d = str_replace("11-", "������ ", $d);
		$d = str_replace("12-", "������� ", $d);

		return $d;
		
}

?>

