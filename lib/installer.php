<?php

class installer {
    
    public function ifc($alias){
        $arr = $this->fic($alias);
        $arr['return'] = "Заглушка установщика, установка не была выполнена.";
        return $arr;
    }
    
    public function ifr($alias,$repo){
        echo "$alias from $repo";
    }
    
    public function fic($alias){
        return array("alias"=>$alias,
            "title"=>"Тут название :)",
            "description"=>"На данной странице должны быть параметры найденного скрипта, но пока эта функция не готова и сработала заглушка.",
            "latest_version"=>"1.0",
            "repo"=>"http://somesite/msc-repo/"
            );
    }
    
    public function fbr($repo){
        echo "fbr: `$repo` in jail :)";
    }
    
}

?>
