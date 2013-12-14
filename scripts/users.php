<?php

	class auth{
	
		public function GetRegister(){
			
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
					$return = "Вы уже авторизированны";	
                                }elseif(libs::getlib("users")->TryRegister()=="ok"){$return = "Регистрация пройдена успешно.<br/><a href='%adress%'>Перейти на главную страницу сайта.</a>";}
                                else{$return = libs::getlib("users")->GetRegisterForm();}
				
                        }else{$return = libs::getlib("templates")->getRTmpl("error",array("message"=>"Не удалось загрузить библиотеку 'users'"));}
			
			return $return;
			
		}
		
		public function GetAuth(){
			
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
					$return = "Вы уже авторизированны";	
				}elseif(libs::getlib("users")->TryLogin()){$return .= "Авторизация пройдена успешно | <a href='%adress%'>на сайт</a>";}
                                else{$return = libs::getlib("users")->GetLoginForm();}
				
				
                        }else{$return = libs::getlib("templates")->getRTmpl("error",array("message"=>"Не удалось загрузить библиотеку 'users'"));}
			return $return;
		
		}
		
		public function DoLogout(){
		
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
						libs::getlib("users")->logOut();
						$return = "Пока :(<br/><a href='%adress%'>Перейти на главную страницу сайта.</a>";
                                }else{$return = "перейдите на страницу авторизации для входа, на страницу регистрации для регистрации.";}
				
                        }else{$return = libs::getlib("templates")->getRTmpl("error",array("message"=>"Не удалось загрузить библиотеку 'users'"));}
			return $return;
			
		}
	
	}

?>



