<?php

namespace Controller;

final class Constant
{
    const APP_NAME = 'postmaker';
    const ROOT_IMG_PATH = 'assets/images';
    public static function rootDir($path=null)
    {
       $path =  $path??$_SERVER['SCRIPT_FILENAME'];
       $end = (int)(strpos($path,self::APP_NAME)+9);
 
    return substr($path,0,$end);
        // return dirname(dirname($path));
    }
    public static function rootImgPath()
    {
        return self::ROOT_IMG_PATH;
    }
      /**
     * @return string
     */
    public static function renderLink(){
        $link = [];
        $link['short'] = 'render/';
        $link['long'] = '../'. self::rootImgPath() . $link['short'];
        return $link;
    }
    /**
     * Get relative path from URL like https://postmaker.com/path_to_image -> /path_to_image
     * @param string $url 
     * @param string $pattern
     * @return string|false
     */
    public static function relativeImgPath(string $url, string $pattern=self::ROOT_IMG_PATH) {
     return  substr($url,strpos($url,$pattern));
    }
}