<?php

namespace Controller;

class Constant
{
    const APP_NAME = 'postmaker';
    public static function rootDir($path=null)
    {
       $path =  $path??$_SERVER['SCRIPT_FILENAME'];
       $end = (int)(strpos($path,self::APP_NAME)+9);
 
    return substr($path,0,$end);
        // return dirname(dirname($path));
    }
    public static function rootImgPath()
    {
        return 'assets/images';
    }
}