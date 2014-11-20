<?php

	//��������� �������� ���������
	
	//�������� ����� � ������������
	if(!is_dir("lib")){ echo "����������� ���������� 'lib' � �������� ����� �����, ����������� ������ CMS ��� ���� ���������� �� ��������."; exit;}
	
	//����������� �������� ���������
	
	libs::LoadLib("database");
	//libs::LoadLib("templates");
	libs::LoadLib("templates_types");
	
	libs::LoadLibsDB();
	
	class libs {
		
		public static function LoadLib($lib_name){
		
			global $libr;
			$lib = str_replace("\\","_",str_replace("/","_",$lib_name));
			
			if(!is_dir("lib")){ echo "����������� ���������� 'lib' � �������� ����� �����."; return false;}
			if(!is_file("lib/".$lib_name.".php")) return false;
			
			include_once("lib/".$lib_name.".php");
			$libr[$lib] = new $lib();
			//echo $lib_name.";";
			return true;
		
		}
		
		public static function LoadLibsDB(){
		
			if(!libs::LoadLib("database")) return false;
			//echo "lol";
			$db = new DataBase();
			
			$libs = $db->getAll("lib",true,true);
			
			//print_r($libs);
			
			if(empty($libs)) return false;
			
			foreach($libs as $id=>$element){
			
				libs::LoadLib($element['file']);
			
			}
			
			return true;
		
		}
		
		public static function GetLib($lib_name){
		
			global $libr;
			$lib = str_replace("\\","_",str_replace("/","_",$lib_name));
			
			return $libr[$lib];
		
		}
			
	}

?>