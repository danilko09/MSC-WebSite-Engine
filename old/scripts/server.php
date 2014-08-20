<?php

function getRconPage(){
	
	if($_SESSION['rconConsole'] == "") $_SESSION['rconConsole'] = "Добро пожаловать в RCON консоль.<br/><br/>Для подключения к серверу введите команду:<br/>connect [IP сервера] [порт RCON] [пароль RCON]<br/>";
	
	if($_POST['command'] != ""){
	
		$_SESSION['rconConsole'] .= "<br/>[".date("h:i")."] ".$_POST['command']."<br/>";
		
		$cmd = explode(" ", $_POST['command']);
		
		if($_POST['command'] == "cls"){
		
			$_SESSION['rconConsole'] = "Консоль была очищена.<br/>";
		
		}elseif($cmd['0'] == "connect"){
			
			$_SESSION['rcon_server'] = $cmd[1];
			$_SESSION['rconPort'] = $cmd[2];
			$_SESSION['rconPass'] = $cmd[3];
			
			include_once("rcon/mcraftrcon.class.php");
			$rcon = new MinecraftRcon;
			
			try{
				$rcon = new MinecraftRcon;
				$stat = $rcon->Connect( $_SESSION['rcon_server'], $_SESSION['rconPort'], $_SESSION['rconPass']);
				$_SESSION['rconConsole'] .= "<br/>----------------------------------------<br/>Соединение с сервером...<br/>Соединение установленно.<br/>Результат запроса:<font color='green'>$stat</font><br/>----------------------------------------<br/>";
				$_SESSION['rconConnected'] = 1;
			}catch(MinecraftRconException $e){
				$_SESSION['rconConnected'] = "";
				$_SESSION['rconConsole'] .= "<br/>----------------------------------------<br/>Соединение с сервером...<br/>Не удалось соедениться: <font color='red'>".$rcon->getErr()."</font><br/>----------------------------------------<br/>";
			}
		
		}elseif($cmd['0'] == "reset"){
		
			$_SESSION['rconConsole'] = "Добро пожаловать в RCON консоль.<br/><br/>Для подключения к серверу введите команду:<br/>connect [IP сервера] [порт RCON] [пароль RCON]<br/>";
		
		}elseif($_SESSION['rconConnected'] != 1){
		
			$_SESSION['rconConsole'] .= "<br/><br/><font color='red'>Нет соединения с сервером.</font><br/>Для подключения к серверу введите команду:<br/>connect [IP сервера] [порт RCON] [пароль RCON]<br/>";
		
		}else{
		
			include_once("rcon/mcraftrcon.class.php");
			
			try
			{
				$rcon = new MinecraftRcon;
				$rcon->Connect( $_SESSION['rcon_server'], $_SESSION['rconPort'], $_SESSION['rconPass']);
				
				$command = trim($_POST['command']);
				$command = str_replace(array("\r\n", "\n", "\r"),'', $command);
				$command = preg_replace('| +|', ' ', $command);
				 
				 
				$str = trim(htmlspecialchars($rcon->Command($command), ENT_QUOTES ));

				$str = str_replace(array("\r\n", "\n", "\r"),'<br>', $str);

				if (!strncmp($command,'say',3) and strlen($str) > 2) $str = substr($str, 2);
				if (!strncmp(substr($str, 2),'Usage',5)) $str = substr($str, 2);
				 
				$str = str_replace(chr(167)."e", "</font><font color='yellow'>", $str);
				$str = str_replace(chr(167)."f", "</font><font color='white'>", $str);
				$str = str_replace(chr(167)."7", "</font><font color='wheat'>", $str);
				$str = str_replace(chr(167)."6", "</font><font color='gold'>", $str);
				$str = str_replace(chr(167)."0", "</font><font color='black'>", $str);
				$str = str_replace(chr(167)."1", "</font><font color='blue'>", $str);
				$str = str_replace(chr(167)."a", "</font><font color='#00f00'>", $str);
				
				$str = str_replace(array(chr(167)), '', $str); 
				
				$_SESSION['rconConsole'] .= "<br/>".$str."<br/>";

			}
			catch( MinecraftRconException $e )
			{
				$_SESSION['rconConsole'] .= "<br/><font color='wheat'>".$e->getMessage( )."</font><br/>"; 
			}

			$rcon->Disconnect( );
		
		}
		
	}
	
	return "<div style='background-color: black; overflow:auto; height: 400px; width: 100%; border: ridge 10px darkblue; color: wheat' id='console'>".$_SESSION['rconConsole']."</div><br/><form method='post'><input type='text' style='width: 70%;' name='command'><input type='submit' value='отправить'></form><script>document.getElementById('console').scrollTop = document.getElementById('console').scrollHeight</script>";

}

function getSrvStat(){

	global $db;
	
	include_once("rcon/mcraftrcon.class.php");
	
	$repl = array();
	
	$srv = $db->getAllOnField("rcon", "option", "stat", true,true);
	
	try
			{
				$rcon = new MinecraftRcon;
				$rcon->Connect( $srv[0]['vl1'], $srv[0]['vl2'], $srv[0]['vl3']);
				
				$command = "list";
				 
				 
				$str = trim(htmlspecialchars($rcon->Command($command), ENT_QUOTES ));

				$str = str_replace(array("\r\n", "\n", "\r"),'<br>', $str);
				
				$repl['rpl'] .= "<br/>".$str."<br/>";
				if($str != "" || $str == "Server offline") $repl['status'] .= "Работает";
				else $repl['status'] .= "Выключен";

			}
			catch( MinecraftRconException $e )
			{
				$repl['status'] .= "<br/><font color='wheat'>".$e->getMessage( )."</font><br/>"; 
			}

			$rcon->Disconnect( );

	return "<center>".templates::getTmpl("srvStat",$repl)."</center>";

}

?>