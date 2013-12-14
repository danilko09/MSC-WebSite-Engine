<?php

class users{

	public function IsAuthorized(){
	
                if($_SESSION['auth'] == "1" && $_SESSION['login'] != null && $_SESSION['login'] != ""){return "1";}
                else{return "0";}
	
	}
	
	public function GetLogin(){
	
		return $_SESSION['login'];
	
	}
	public function GetGroup(){
		
                if($_SESSION['login'] == "" || $_SESSION['login'] == null){return "guest";}		
                else{return libs::GetLib("database")->GetField("users","group","login",$_SESSION['login']);}
		
	}
	
	public function GetLoginForm(){
	
		return libs::GetLib("templates")->GetRTmpl("auth/login-form",array('method'=>'POST'));
	
	}
	
	public function GetRegisterForm(){
		return libs::GetLib("templates")->GetRTmpl("auth/register-form",array('method'=>'POST'));
	}
	
	public function TryLogin(){
	
            $login = filter_input(INPUT_POST, 'login');
            $pass  = filter_input(INPUT_POST, 'password');

            if($login != "" && $login != "" && libs::GetLib("database")->getField("users", "password", "login", $login) == $pass){$ok = true;}
            else{$ok = false;}
         
            if($ok){
                    $_SESSION['login'] = $login;
                    $_SESSION['auth'] = true;
                    $_SESSION['group'] = "user";
                    return true;
            }else{$_SESSION['auth'] = "0";}
	
	}
	
	public function VerifiLogin($login,$pass){
            
                if($login != "" && $login != "" && libs::GetLib("database")->getField("users", "password", "login", $login) == $pass){$ok = true;}
                else{$ok = false;}
		
		if($ok){
			$_SESSION['login'] = filter_input(INPUT_POST,'login');
			$_SESSION['auth'] = true;
			$_SESSION['group'] = "user";
			return true;
                }else{return false;}
	}
	
	public function TryRegister(){
	
            $login = filter_input(INPUT_POST, 'login');
            $pass  = filter_input(INPUT_POST, 'password');
            
            if($login != "" && $login != "" && libs::GetLib("database")->getField("users", "password", "login", $login) == $pass){$ok = true;}else{$ok = false;}
		
            if($ok == "true"){return "Логин занят.";}
            elseif(filter_input(INPUT_POST,'mail') != ""){
                    $user['login'] = $login; $user['password'] = $pass;
                    $user['mail'] = filter_input(INPUT_POST,'mail');
                    $user['group'] = "registred";
                    libs::GetLib("database")->insert("users",$user); return "ok";
            }
		
	
	}
	
	public function LogOut(){
		unset($_SESSION['auth']);
		unset($_SESSION['login']);
	}

}

?>





