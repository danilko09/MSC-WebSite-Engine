<?php

	class auth{
	
		public function GetRegister(){
			
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
					$return = "�� ��� ���������������";	
				}elseif(libs::getlib("users")->TryRegister()=="ok") $return = "����������� �������� �������.<br/><a href='%adress%'>������� �� ������� �������� �����.</a>";
				else $return = libs::getlib("users")->GetRegisterForm();
				
			}else $return = templates::getRTmpl("error",array("message"=>"�� ������� ��������� ���������� 'users'"));
			
			return $return;
			
		}
		
		public function GetAuth(){
			
			if(libs::loadlib("users")){
			
				if(libs::getlib("users")->IsAuthorized() == "1"){
					$return = "�� ��� ���������������";	
				}else{
					if(libs::getlib("users")->TryLogin()) $return .= "����������� �������� ������� | <a href='%adress%'>�� ����</a>";
					else $return = libs::getlib("users")->GetLoginForm();
				}
				
			}else $return = templates::getRTmpl("error",array("message"=>"�� ������� ��������� ���������� 'users'"));
			return $return;
		
		}
		
		public function DoLogout(){
		
			if(scripts::checkScript("users")){
			
				if(users::IsAuthorized() == "1"){
                                    users::logOut();
                                    $return = "���� :(<br/><a href='%adress%'>������� �� ������� �������� �����.</a>";
				}else $return = "��������� �� �������� ����������� ��� �����, �� �������� ����������� ��� �����������.";
				
			}else $return = templates::getRTmpl("error",array("message"=>"�� ������� ��������� ���������� 'users'"));
			return $return;
			
		}
	
	}

?>



