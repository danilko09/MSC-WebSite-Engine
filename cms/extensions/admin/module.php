<?php

	$title = " | Административная панель";
	$navigator = "<a href='%adress%/index.php/admin'>Административная панель</a>";

	if($_SESSION['auth'] === "1") $perm = $db->getField("users", "group", "login", $_SESSION['login']);
	else redirect(config::site_url."/index.php/user/login");

	if($perm != "admin") redirect(config::site_url);

	if($pg_pars === "" || $pg_pars == null){

		$menu = $db->getAll("admin_main_menu",true,true);
		$menu_count = $db->getLastID("admin_main_menu");
		
		$contents = $db->getAll("admin_contents",true,true);
		
			
		for($m = 0;$m <= $menu_count;$m++){
			
			$cat_cont = "";
			$cat_ids = explode(",",$menu[$m]['content_ids']);
			
			for($c = 0;$c < count($cat_ids)+1;$c++){
			
				$cont_id = $cat_ids[$c] - 1;
				$cat_cont_arr = $contents[$cont_id];
				if(is_array($cat_cont_arr)) $cat_cont .= templates::getTmpl("admin/cat_cont",$cat_cont_arr);
							
			}
			
			$cont['title'] = $menu[$m]['title'];
			$cont['content'] = $cat_cont;
			if($menu[$m]['title'] != "")$site .= templates::getTmpl("admin/category",$cont);
		}

	}
	elseif(is_file("admin/".$pg[3].".php")) include("admin/".$pg[3].".php");
	else $site = "<center><h1>404 ADMIN ERROR</h1></center>";

	$page = templates::addScripts($page);
	
	$page = str_replace("%content%", "<div class='post'>".$navigator."<br/>".$site."</div>", $page);

	//debug("На этой странице отключены все дополнительные скрипты, т.к. их работа может привести к ошибке.");
?>