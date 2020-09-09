<?php
namespace Model;

class Model {
    public $db;

    public function __construct()
    {
        $this->db=3;
    }
       /**
     * Get font
     * @param Resource $im
     * @return array
     */
    public function get_font()
    {
        $link = 'C:/xampp/htdocs/postmaker/assets/fonts/';
        $font = [
            'montserrat'=>$link.'montserrat.ttf',
            'montserrat_medium'=>$link.'Montserrat-Medium.ttf',
            'montserrat_bold'=>$link.'Montserrat-Bold.ttf',
            'Poppins-Regular.otf'=>$link.'Poppins-Regular.otf',
            'OpenSans-Bold.ttf'=>$link.'OpenSans-Bold.ttf'            
        ];
        return $font;
    }
    /**
     * Get array of color
     * @param Resource $im
     * @param array $color
     * 
     * @return array
     */
    public function get_color($im, $color = null)
    {
        $orange = imagecolorallocate($im, 220, 210, 60);
        $white  = imagecolorallocate($im, 255, 255, 255);
        $black  = imagecolorallocate($im, 0, 0, 0);
        $array = ['orange' => $orange, 'white' => $white, 'black' => $black];
        if ($color != null) {
            return [$color =>$array[$color]];
        }
        return $array;
    }
}