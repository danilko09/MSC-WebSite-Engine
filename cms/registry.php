<?php

final class registry{
    
    public static function get($key){
        
        if(!is_dir(__DIR__."/registry")){mkdir(__DIR__."/registry");}
        $index = __DIR__."/registry/".str_replace(".","/",$key)."/index";
        if(is_file($index)){
            return file_get_contents($index);
        }else{
            return null;
        }
        
    }
    
    public static function set($key,$val){
        
        if(!is_dir(__DIR__."/registry")){mkdir(__DIR__."/registry");}
        self::make_writable($key);
        $index = __DIR__."/registry/".str_replace(".","/",$key)."/index";
        if(is_array($val)){
            file_put_contents($index, serialize($val));
        }elseif(is_string($val)){
            file_put_contents($index, $val);
        }
        
    }
    
    private static function make_writable($key){
        
        $arr = explode(".",$key.".");
        $prefix = __DIR__."/registry/";
        
        for($i = 0; $i < count($arr)-1; $i++){
            
            if(!is_dir($prefix.$arr[$i])){mkdir($prefix.$arr[$i]);}
            $prefix .= $arr[$i]."/";
            
        }
        
    }
    
}