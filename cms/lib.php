<?php
//Подгразка основных библиотек
//Проверка папок с библиотеками
    if(!is_dir("lib")){ echo "Отсутствует директория 'lib' в корневой папке сайта, продолжение работы CMS без этой директории не возможно."; exit;}
//Подключение основных библиотек

    libs::LoadLib("database");
    libs::LoadLib("templates");
    libs::LoadLib("templates_types");
//Загрузка библиотек по списку из БД
    libs::LoadLibsDB();

    class libs {

            public function LoadLib($lib_name){

                    global $libr;
                    $lib = str_replace("\\","_",str_replace("/","_",$lib_name));

                    if(!is_dir("lib")){ echo "Отсутствует директория 'lib' в корневой папке сайта."; return false;}
                    if(!is_file("lib/".$lib_name.".php")){return false;}

                    include_once("lib/".$lib_name.".php");
                    if(class_exists($lib)){$libr[$lib] = new $lib();}
                    else{echo "\n\rОшибка в файле 'lib/".$lib_name.".php', не существует класс '".$lib."'.";}
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