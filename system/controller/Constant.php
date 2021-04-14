<?php

namespace Controller;

class Constant
{
    public static function rootDir($path)
    {
        return dirname(dirname($path));
    }
    public static function root_img_path()
    {
        return 'assets/images';
    }
}