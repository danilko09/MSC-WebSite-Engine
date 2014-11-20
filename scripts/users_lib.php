<?php

final class users{

	public static function IsAuthorized(){
                return (isset($_SESSION['auth']) && $_SESSION['auth'] == "1" && $_SESSION['login'] != null && $_SESSION['login'] != "") ? "1" : "0";
	}
	
	public static function GetLogin(){	
		return isset($_SESSION['login']) ? $_SESSION['login'] : null;
	}
	public static function GetGroup(){
                return (!isset($_SESSION['login']) || $_SESSION['login'] == "" || $_SESSION['login'] == null) ? "guest" 
                : (new database())->GetField("users","group","login",$_SESSION['login']);		
	}
	
	public static function GetLoginForm(){
	
		return str_replace("[method]", "POST", templates::GetTmpl("auth/login-form"));
	
	}
	
	public static function GetRegisterForm(){
		return str_replace("[method]", "POST", templates::GetTmpl("auth/register-form"));
	}
	
	public static function TryLogin(){
            $ok = (filter_input(INPUT_POST,'password') != null && filter_input(INPUT_POST,'login') != null && (new database())->getField("users", "password", "login", filter_input(INPUT_POST,'login')) == filter_input(INPUT_POST,'password'));
	    if($ok){
                $_SESSION['login'] = filter_input(INPUT_POST,'login');
                $_SESSION['auth'] = true;
                $_SESSION['group'] = "user";
                return true;
            }else{$_SESSION['auth'] = "0";}
        }
	
	public static function VerifiLogin($login,$pass){
		$ok = (filter_input(INPUT_POST,'password') != null && $login != "" && $login != "" && (new database())->getField("users", "password", "login", $login) == $pass);
                if($ok){
			$_SESSION['login'] = $login;
			$_SESSION['auth'] = true;
			$_SESSION['group'] = "user";
		}
                return $ok;
	}
	
	public static function TryRegister(){
	
		$ok = (filter_input(INPUT_POST,'login') != "" && filter_input(INPUT_POST,'login') != "" && (new database())->getField("users", "password", "login", filter_input(INPUT_POST,'login')) == filter_input(INPUT_POST,'password'));
		
                if($ok){return "Логин занят.";}
		elseif(filter_input(INPUT_POST,'mail') != null){
			$user['login'] = filter_input(INPUT_POST,'login');
			$user['password'] = filter_input(INPUT_POST,'password');
			$user['mail'] = filter_input(INPUT_POST,'mail');
			$user['group'] = "registred";
			(new database())->insert("users",$user);
			return "ok";
		}
		
	
	}
	
	public static function LogOut(){
		unset($_SESSION['auth']);
		unset($_SESSION['login']);
	}

}