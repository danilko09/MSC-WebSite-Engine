<?php

include "config.php";

if(!is_dir($client_dir)){mkdir($client_dir);}
if(!is_dir($cache_dir)) {mkdir($cache_dir); }

if(!MT_ZIP_mode){
switch(filter_input(INPUT_GET,"act")){
    case "bsize": echo $block_size; break;// block size
    case "tsize": echo calculateTotalSize(); break;// total size
    case "fsize": echo filter_input(INPUT_GET,"file") != null && file_exists($client_dir."/".str_replace("..","",filter_input(INPUT_GET,"file"))) ? filesize($client_dir."/".str_replace("..","",filter_input(INPUT_GET,"file"))) : "0"; break;// file size
    case "gtree": echo getList(); break;// get files tree-list
    case "gblock": echo getFileBlock(filter_input(INPUT_GET,"file"),filter_input(INPUT_GET,"block")); break;// get block
    default: echo "err"; break;// error: unknown action
}
}else{
switch(filter_input(INPUT_GET,"act")){
    case "bsize": echo $block_size; break;// block size
    case "tsize": echo filesize(getCWD()."/".$MT_ZIP_file); break;// total size
    case "fsize": echo $MT_ZIP_file != null && file_exists(getCWD()."/".str_replace("..","",$MT_ZIP_file)) ? filesize(getCWD()."/".str_replace("..","",$MT_ZIP_file)) : "0"; break;// file size
    case "gblock": echo getFileBlock($MT_ZIP_file,filter_input(INPUT_GET,"block")); break;// get block
    default: echo "err"; break;// error: unknown action
}
}
function getList($dir = null){
    global $client_dir;
    if($dir == null){$d = $client_dir;}else{$d = $dir;}
    $count1 = ""; 
    $dh = opendir( $d ); 
    while( ( $files = readdir( $dh ) ) !== false ) 
     { 
      if ( $files != "." && $files != ".." ) 
       { 
        $path = $d . "/" . $files; 
        if( is_dir($path) ) 
         {  $count1 .= getList($path);} 
        elseif( is_file( $path ) ) 
         {  $count1 .= str_replace($client_dir,"",$path."<br/>\r\n");  } 
       } 
     } 
    closedir($dh); 
    return $count1;
}

function calculateTotalSize(){
    global $client_dir;
    if(!file_exists($client_dir) || !is_dir($client_dir)){mkdir($client_dir); return 0;}
    return dirsize($client_dir);
}

function dirsize( $d ) { 
  $count1 = 0; 
  $dh = opendir( $d ); 
  while( ( $files = readdir( $dh ) ) !== false ) 
   { 
    if ( $files != "." && $files != ".." ) 
     { 
      $path = $d . "/" . $files; 
      if( is_dir( $path ) ) 
       {  $count1 += dirsize( $path , $count1 );  } 
      elseif(is_file( $path )) 
       {  $count1 += filesize($path);  } 
     } 
   } 
  closedir($dh); 
  return $count1; 
}

function getFileBlock($file,$block){
    global $cache_enabled,$cache_dir,$client_dir,$block_size,$MT_ZIP_mode;
    if($cache_enabled && is_file($cache_dir."/".str_replace("..","",$file)."/".$block)){return file_get_contents($cache_dir."/".str_replace("..","",$file)."/".$block);}
    
    if(!$MT_ZIP_mode){$f = fopen($client_dir."/".str_replace("..","",$file), "rb");}
    else{$f = fopen(getCWD()."/".$file, "rb");}
    if($block > 0){fread($f,$block_size * $block);}
    $data = fread($f,$block_size);
    fclose($f);
        
    checkPath($cache_dir."/".str_replace("..","",$file)."/".$block);
    $f = fopen($cache_dir."/".str_replace("..","",$file)."/".$block,"w");
    fwrite($f, $data);
    fclose($f);
    return $data;
}

function checkPath($path){
    if(!file_exists(substr($path,0,strrpos($path,"/")))){
        checkPath(substr($path,0,strrpos($path,"/")));
        mkdir(substr($path,0,strrpos($path,"/")));
    }
}