<?php

	class auth{
	
		public function GetRegister(){
			
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
					$return = "�� ��� ���������������";	
                                }elseif(libs::getlib("users")->TryRegister()=="ok"){$return = "����������� �������� �������.<br/><a href='%adress%'>������� �� ������� �������� �����.</a>";}
                                else{$return = libs::getlib("users")->GetRegisterForm();}
				
                        }else{$return = libs::getlib("templates")->getRTmpl("error",array("message"=>"�� ������� ��������� ���������� 'users'"));}
			
			return $return;
			
		}
		
		public function GetAuth(){
			
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
					$return = "�� ��� ���������������";	
				}elseif(libs::getlib("users")->TryLogin()){$return .= "����������� �������� ������� | <a href='%adress%'>�� ����</a>";}
                                else{$return = libs::getlib("users")->GetLoginForm();}
				
				
                        }else{$return = libs::getlib("templates")->getRTmpl("error",array("message"=>"�� ������� ��������� ���������� 'users'"));}
			return $return;
		
		}
		
		public function DoLogout(){
		
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
						libs::getlib("users")->logOut();
						$return = "���� :(<br/><a href='%adress%'>������� �� ������� �������� �����.</a>";
                                }else{$return = "��������� �� �������� ����������� ��� �����, �� �������� ����������� ��� �����������.";}
				
                        }else{$return = libs::getlib("templates")->getRTmpl("error",array("message"=>"�� ������� ��������� ���������� 'users'"));}
			return $return;
			
		}
	
	}

?>



