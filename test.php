<?php
// Этот файл не должен был попасть на git, но раз уж попал, то обхясню что это
// В общем примерно так будет создаваться новая таблица в установщике расширений

    CreateTable("test", array('lola'=>'text not null'));

        function CreateTable($title,$structure){
            $task = "CREATE TABLE `$title` ( `id` INT(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`));";
            FOREACH($structure AS $name => $params){
                $task .= " ALTER TABLE `$title` add `$name` $params;";
            }
            echo $task;
        }
?>