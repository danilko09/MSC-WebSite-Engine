<?php

//Установщик расширений | менеджер пакетов
//Ну что ж попробую описать примерный функционал, который обязательно будет
//1. Установка пакетов на подобии Linux`ов (например apt-get install someprogram)
//Здесь просто будет писаться алиас репозитория, откуда будет идти установка
//2. Отслеживание зависимостей скрипта(тоже походит на подход дистров Linux)
//3. Поиск и установка обновлений из репозиториев (хм, опять в Linux распространено)

// Ну а теперь код :)

    $content = "<h5>Установщик расширений</h5><br/>";
    
    if(!libs::LoadLib("installer")){
            $content .= "Дистрибутив CMS поврежден, дальнейшая работа скрипта не возможна.";
    }elseif($adm[3] == ""){
        $content .= "Здесь вы можете выбрать одну из нужных функций:<br/>"
                . "<a href='%adress%/index.php/admin/installer/install/catalog'>Установка из каталога</a><br/>"
                . "<a href='%adress%/index.php/admin/installer/install/repo'>Установка из репозитория</a><br/>"
                . "<a href='%adress%/index.php/admin/installer/repo'>Управление репозиториями</a><br/>"
                . "<a hreh='%adress%/index.php/admin/installer/update/catalog'>Обновление из каталога</a></br>"
                . "<a href='%adress%/index.php/admin/installer/update/repo'>Обновление из репозитория</a><br/>"
                . "<br/><p>Установка(обновление) из каталога отличается от установки(обновления) из репозитория только тем, что данные о репозитории и последней версии берутся из каталога, а не репозитория.<br/>"
                . "В данной версии установщика поддерживаются только MSC-репозитории.</p>";
    }elseif($adm[3] == "install" && $adm[4] == ""){
        $content .= "Вы в разделе установки, пожалуйсто выберите тип установки:<br/>"
                . "<a href='%adress%/index.php/admin/installer/install/catalog'>Установка из каталога</a><br/>"
                . "<a href='%adress%/index.php/admin/installer/install/repo'>Установка из репозитория</a><br/>";
    }elseif($adm[3] == "install" && $adm[4] == "catalog" && $adm[5] == ""){
        $content .= "Введите алиас расширения, которое хотите установить из каталога.<br/><form method='get'>"
                . "<input type='text' id='ex' name='ex'/>"
                . "</form><br/>"
                . "<center><a href='' class='button' style='width: 300px;' id='ex_link'>Установить!</a></center>"
                . "<script>"
                . "setInterval(ex_toster,500);"
                . "var buf = '';"
                . "function ex_toster(){"
                . "if(document.getElementById('ex').value != buf){"
                . "buf = document.getElementById('ex').value;"
                . "document.getElementById('ex_link').href = '%adress%/index.php/admin/installer/install/catalog/'+document.getElementById('ex').value;"
                . "}"
                . "}"
                . "</script>";
    }elseif($adm[3] == "install" && $adm[4] == "catalog" && $adm[5] != "" && $adm[6] != "go"){
        $info = libs::GetLib("installer")->fic($adm[5]);
        $content .= "Вы уверены что хотите установить именно это расширение ?<br/><br/>"
                 . $info['title']." (".$info['alias']." | ".$info['latest_version'].")<br/>".$info['repo']."<br/>".$info['description']
                 . "<br/><a href='%adress%/index.php/admin/installer/install/catalog/".$adm[5]."/go'>Начать установку</a><br/><br/>"
                 . "<p>Перед внесением изменений рекоммендуется сделать бекап сайта (как данных в БД, так и файлов на сервере ).<br/>Это связанно с возможностью возникновения ошибок во время установки.<br/>(процесс установки\обновления не контролируется системой, все действия производятся автоматическим скриптом, который поставляется из репозитория)</p>";    
    }elseif($adm[3] == "install" && $adm[4] == "catalog" && $adm[5] != "" && $adm[6] == "go"){
            $ins = libs::GetLib("installer");
            $ret_arr = $ins->ifc($adm[5]);
            
            if($ret_arr['return'] == ""){$content .= "Установка завершена.";}
            else{$content .= $ret_arr['return'];}
    }else{$content .= "ошибка 404<br/>Возможно данная функция пока не доделана и отключена.";}

