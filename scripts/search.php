<?php
class search{
function getSearchBlock(){

//return "<center><form action='%adress%/index.php/search' method='post'><input type='text' name='find'/><br/><input type='submit' value='Найти'/></form></center>";
return '<form class="searchform" method="post" action="%adress%/index.php/search"><input type="text" name="find" value="Напиши и нажми энетер" onfocus="this.value=\'\'" onblur="this.value=\'Напиши и нажми энтер\'"></form>';


}

function getSearchPage(){

	$db = libs::GetLib("database");

	$finded = $db->search("news", $_POST['find'], array("title", "short","full"));
	
	if(!is_array($finded)) $find = "Ничего не найдено.";
	
	else{
	
		$i = 1;
	
		foreach($finded as $k=>$v){
		
			if($v['title'] == "") continue;
			
			$find .= "<div style='border: dotted 5px yellowgreen; border-radius: 15px; padding: 10px; margin: 10px;'>".$i.".<a href='%adress%/index.php/news?id=".$v['id']."'>".$v['title']."</a><br/><p>".$v['short']."</p><p class='tags'>Количество совпадений: ".$v['relevant']."</p></div>";
			$i++;
		
		}
	
	}

	return "<div class='post'><form action='%adress%/index.php/search' method='post'><input type='text' value='".$_POST['find']."' name='find'/><input type='submit' value='Найти'/></form><br/><br/>".$find."</div>";

}
}

?>