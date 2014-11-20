<?php

$news = array(
    array("title"=>"Пробная новость 1","content"=>"Содержимое пробной новости"),
    array("title"=>"Пробная новость 2","content"=>"Содержимое пробной новости"),
    array("title"=>"Пробная новость 3","content"=>"Содержимое пробной новости"),
    array("title"=>"Пробная новость 4","content"=>"Содержимое пробной новости")
);
$ret = "";
foreach($news as $post){

    ob_start();
    eval("?><h3><?=\$post['title'];?></h3><p><?=\$post['content'];?></p><a href=\"#\">Подробнее</a>");
    $ret .= ob_get_clean();//str_replace("'","&#8242;",str_replace('"',"&#8243;",ob_get_clean()));

}

?>
<script>
parent.document.getElementById("news").innerHTML = '<div style="position: inherit; height: inherit; overflow:auto" onmousedown="canmove = false;" onmouseup="canmove = true;"><?=$ret?></div>';
</script>