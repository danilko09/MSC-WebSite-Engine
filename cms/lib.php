<?php
//��������� �������� ���������
//�������� ����� � ������������
    if(!is_dir("lib")){ echo "����������� ���������� 'lib' � �������� ����� �����, ����������� ������ CMS ��� ���� ���������� �� ��������."; exit;}
//����������� �������� ���������

    libs::LoadLib("database");
    libs::LoadLib("templates");
    libs::LoadLib("templates_types");
//�������� ��������� �� ������ �� ��
    libs::LoadLibsDB();

    class libs {

            public function LoadLib($lib_name){

                    global $libr;
                    $lib = str_replace("\\","_",str_replace("/","_",$lib_name));

                    if(!is_dir("lib")){ echo "����������� ���������� 'lib' � �������� ����� �����."; return false;}
                    if(!is_file("lib/".$lib_name.".php")){return false;}

                    include_once("lib/".$lib_name.".php");
                    if(class_exists($lib)){$libr[$lib] = new $lib();}
                    else{echo "\n\r������ � ����� 'lib/".$lib_name.".php', �� ���������� ����� '".$lib."'.";}
                    return true;

            }

            public function LoadLibsDB(){

                    if(!libs::LoadLib("database")){return false;}
                    $db = libs::GetLib('database');
                    $libs = $db->getAll("lib",true,true);
                    if(empty($libs)){return false;}
                    foreach($libs as $element){libs::LoadLib($element['file']);}
                    return true;
            }

            public function GetLib($lib_name){
                    global $libr;
                    $lib = str_replace("\\","_",str_replace("/","_",$lib_name));
                    return $libr[$lib];
            }

    }

?>