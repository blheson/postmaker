<?php

namespace Controller;

class Constant
{
    public static function rootDir($path=null)
    {
       $path =  $path??$_SERVER['SCRIPT_FILENAME'];
        return dirname(dirname($path));
    }
    public static function root_img_path()
    {
        return 'assets/images';
    }
}