<?php
//Много ГК. Знаю, перепишу. Специально вынес в отдельный файл.
class admin_content{
    
    public static function main(){
        $uri = WSE_ENGINE::getURI();
        if(!isset($uri[2]) || $uri[2] == null){
            return self::menu();
        }else{
            $pages = is_file(__DIR__.DIRECTORY_SEPARATOR.'pages.json') ? json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'pages.json'),true) : [];
            array_shift($uri);
            array_shift($uri);
            $alias = trim(implode('/',$uri),'/');
            if(isset($pages[$alias]) && filter_input(INPUT_POST,'secret') != 1){
                return WSE_ENGINE::getRTmpl("content/admin/edit", $pages[$alias] + ["alias"=>$alias]);
            }elseif(filter_input(INPUT_POST,'secret') == 1){
                unset($pages[$alias]);
                $page = filter_input_array(INPUT_POST);
                $al = $page['alias'];
                if($page['title'] == ""){$page['title'] = "Без названия";}
                $pages[$al] = ["title"=>$page['title'],"content"=>$page['content']];
                file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'pages.json', json_encode($pages));
                return "Сохранено<br/><a href='".(WSE_ENGINE::getIndex())."/admin'>В админку</a>";
            }elseif($uri[0] == "delete"){
                return self::deleteMenu();
            }elseif($uri[0] == "create"){
                return WSE_ENGINE::getRTmpl("content/admin/edit", ["title"=>"","alias"=>"","content"=>""]);
            }else{
                return "Ошибка!<br/><a href='".(WSE_ENGINE::getIndex())."/admin'>В админку</a>";
            }
        }
    }
    
    private static function menu(){
        $pages = is_file(__DIR__.DIRECTORY_SEPARATOR.'pages.json') ? json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'pages.json'),true) : [];
       // var_dump($pages);
        $text = "";
        foreach($pages as $alias=>$page){
            if($alias == ""){continue;}
            $text .= '<a href="'.rtrim(WSE_ENGINE::getURL(),'/').'/'.ltrim($alias,'/').'" class="list-group-item">'.$page['title'].'<span class="badge">'.$alias.'</span></a>';
        }
        return '
            <ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Редактировать</a></li>
  <li role="presentation"><a href="content/delete">Удалить</a></li>
  <li role="presentation"><a href="content/create">Создать</a></li>
</ul>
<div class="list-group">
'.$text.'
</div>';
    }
    
    public static function deleteMenu(){
        $pages = is_file(__DIR__.DIRECTORY_SEPARATOR.'pages.json') ? json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'pages.json'),true) : [];
        $text = '   <form method="POST">   <table class="table">
        <thead>
          <tr>
            <th>Выбрать</th>
            <th>Название</th>
            <th>Алиас</th>
          </tr>
        </thead>
        <tbody>';
   
        if(is_array(filter_input_array(INPUT_POST))){
                    unset($pages['']);
                    foreach(filter_input_array(INPUT_POST) as $alias=>$val){
                        unset($pages[$alias]);
                    }
                }
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'pages.json', json_encode($pages));
                
        foreach($pages as $alias=>$page){
            if($alias == ""){continue;}
            $text .= '<tr>
            <th scope="row"><input type="checkbox" name="'.$alias.'" id="'.$alias.'"></th>
            <td>'.$page['title'].'</td>
            <td>'.$alias.'</td>
          </tr>';
        }    
                $text = $text.'     </tbody>
      </table><input class="btn btn-primary btn-block" type="submit" value="Удалить"></form>';
                
                return '
            <ul class="nav nav-tabs">
  <li role="presentation"><a href="'.(WSE_ENGINE::getIndex()).'/admin/content">Редактировать</a></li>
  <li role="presentation" class="active"><a href="#">Удалить</a></li>
  <li role="presentation"><a href="'.(WSE_ENGINE::getIndex()).'/admin/content/create">Создать</a></li>
</ul>'.$text;
    }
    
}

