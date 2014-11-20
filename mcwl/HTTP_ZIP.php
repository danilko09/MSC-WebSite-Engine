<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'HTTP_ZIP_CONFIG.php';
header("Content-Length: ".filesize(getcwd()."/client/".$file));
$need = filesize(getcwd()."/client/".$file);
$f = fopen(getcwd()."/client/".$file,"r");
while(!feof($f)){
    if($need > 1024){
        echo fread($f, 1024);
    }else{
        echo fread($f, 1);
    }
}
fclose($f);