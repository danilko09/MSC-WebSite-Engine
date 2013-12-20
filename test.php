<?php

    CreateTable("test", array('lola'=>'text not null'));

        function CreateTable($title,$structure){
            $task = "CREATE TABLE `$title` ( `id` INT(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`));";
            FOREACH($structure AS $name => $params){
                $task .= " ALTER TABLE `$title` add `$name` $params;";
            }
            echo $task;
        }
?>