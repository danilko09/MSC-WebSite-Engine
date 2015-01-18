<?php

class skins{
    
    private static $place = 'skins';
    
    public static function getHeadLink($par){
        return "%base%/".self::$place."/skin2d.php?mode=head&player=".$par['username'];
    }
    
}