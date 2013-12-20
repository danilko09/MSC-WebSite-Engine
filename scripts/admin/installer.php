<?php

//Установщик расширений | менеджер пакетов
//Ну что ж попробую описать примерный функционал, который обязательно будет
//1. Установка пакетов на подобии Linux`ов (например apt-get install someprogram)
//Здесь просто будет писаться алиас репозитория, откуда будет идти установка
//2. Отслеживание зависимостей скрипта(тоже походит на подход дистров Linux)
//3. Поиск и установка обновлений из репозиториев (хм, опять в Linux распространено)
//4. Создание своих сборок-скриптов(тоже есть в некоторых дистрах)
// Эта функция позволит создавать свои дистрибутивы CMS с подборкой скриптов, библиотек и патчей, удобно для бэкапов (будет возможность добавить дамп ДБ)
// Возможно позже в установщик CMS будет вшито развертывание из пользовательского дистрибутива.
//5. Миграция дистрибутива.
//  Ну это упрощает поиск нужных скриптов, да и позволяет выцарапать только нужные скрипты из дистрибутива
//6. Обновление "на лету".
//  Позволяет оперативно переходить на более новые версии скриптов, без закрытия сайта на техобслуживание
//Ну и последняя, оч полезная плюшечка
//7. Обновление исходников в репозитории с текущего сайта
// Удобно при разработке на сайте, допустим у вас на локальной машине стоит веб-сервер с CMS или имеется какой-либо сервер в сети
// и вам нужно выложить обновление скрипта и не охото копаться в папках, тогда просто открываем этот раздел и сливаем нужные файлы в репозиторий
// Поддержка будет только MSC репозиториев, которые будут максимально адаптироваться под работу с данной CMS

// Ну а теперь код :)

    $content = "<h5>Установщик расширений</h5><br/>";
    
    if($adm[3] == ""){
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
    }elseif($adm[3] == "install" && $adm[4] == "catalog" && $adm[5] != ""){
        $info = catalog_search($adm[5]);
        $content .= "Вы уверены что хотите установить именно это расширение ?<br/>"
                 . $info['title']." (".$info['alias']." | ".$info['latest_version'].")<br/>".$info['repo']."<br/>".$info['description']
                 . "<a href='%adress%/index.php/install/catalog'>";    
    }else{$content .= "ошибка 404<br/>Возможно данная функция пока не доделана и отключена.";}


function catalog_search($alias){
    return array("alias"=>$alias,"title"=>"Название скрипта","description"=>"Описание","latest_version"=>"1.0","repo"=>"http://somesite/msc-repo/");
}
