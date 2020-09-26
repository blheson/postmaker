<?php

namespace Controller\Template;

require_once 'Common.php';
require_once (dirname(__DIR__)) . DS . 'Model' . DS . 'Model.php';
require_once 'Watermark.php';

use Model\Model as Model;
use Controller\Common\Color as color;
use Controller\Common\ImageDimension as imagedimension;
use Controller\Common\CreateImage;
use Controller\Template\Square\Watermark as watermark;
class Square
{
    const ROOT_IMG_PATH = 'assets/images';
    public $model;
    public $color;
    public $image_dimension;
    public $create_image;

    public function __construct()
    {
        $this->model = new Model;
        $this->color = new color;
        $this->image_dimension = new imagedimension;
        $this->create_image = new CreateImage;
    }
    /** 
     * Pretty print data
     * @param mixed $data
     * 
     */
    public function dnd($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
    public function create_image()
    {
        return $this->create_image;
    }
    public function water_mark()
    {
        $this->watermark = new watermark();
        
        return $this->watermark;
    }
    public function image_dimension(){
        return $this->image_dimension;
    }
    /**
     * Add text on image
     * @param string $new_link
     * @param array $image_array
     *  keys include
     * 
     *  string  'new_image_path' - The final path of the image
     * 
     *  array   'background' - [optional] The array of the red, green and blue component of the color Default is [255,255,255]
     * 
     *  int     'width' - [optional] The width of the image
     * 
     * @param string $footer
     * 
     * @param array $font_array 
     * 
     *  keys include
     * 
     *  int     'file' - The size of the font.
     * 
     *  int     'size' - [optional] The size of the font. Default is 20
     * 
     *  int     'width' - [optional] The width that the text will occupy on the design. Default is 11
     * 
     *  float   'angle' - [optional] The angle of the font. Default is 0
     * 
     *  float   'line_height' - [optional] The line_height of the font. Default is 60
     * 
     *  float   'px' - The x coordinate of the first character of the font.
     * 
     *  float   'py' - [optional] The y coordinate of the first character of the font.
     * @return string $new_image_path
     */
    public function write_to_footer($new_link, $image_array, $footer, $font_array)
    {
        $font_array['size'] = isset($font_array['size']) ? $font_array['size'] : 20;
        $font_array['px'] = isset($font_array['px']) ? $font_array['px'] : 700;
        $font_array['py'] = isset($font_array['py']) ? $font_array['py'] : 850;
        $font_array['width'] = isset($font_array['width']) ? $font_array['width'] : 20;
        $font_array['line_height'] = isset($font_array['line_height']) ? $font_array['line_height'] : 60;
        //Get font from DB

        $font = $this->model->get_font();

        $font_array['file'] = $font['montserrat'];

      
       return $this->text_to_image($new_link, $image_array, trim($footer), $font_array);
    }
    /**
     * Wrap text into a specified dimesion
     * 
     * @param string $new_link (The default image to work with)
     * 
     * @param array $image_array
     *  keys include
     * 
     *  string  'new_image_path' - The final path of the image  (or The folder to store the image)
     * 
     *  array   'background' - [optional] The array of the red, green and blue component of the color Default is [255,255,255]
     * 
     *  int     'width' - [optional] The width of the image
     * 
     * @param string $text
     * 
     * @param array $font_array 
     * 
     *  keys include
     * 
     *  int     'file' - The size of the font.
     * 
     *  int     'color' - The color of the font. E.g [255,255,255]
     * 
     *  int     'size' - [optional] The size of the font. Default is 60
     * 
     *  int     'width' - [optional] The width that the text will occupy on the design. Default is 11
     * 
     *  float   'angle' - [optional] The angle of the font. Default is 0
     * 
     *  float   'line_height' - [optional] The line_height of the font. Default is 120
     * 
     *  float   'px' - The x coordinate of the first character of the font.
     * 
     *  float   'py' - [optional] The y coordinate of the first character of the font.
     * 
     * @return string $new_image_path
     */
    public function text_to_image($new_link, $image_array, $text, $font_array): string
    {
        // SORT IMAGE ARRAY
        $image = imagecreatefrompng($new_link);
        $new_image_path = $image_array['new_image_path'];
       
        $image_width =  isset($image_array['width']) ? $image_array['width'] : $this->image_dimension::DEFAULT_IMAGE_WIDTH;

        // SORT FONT ARRAY
        // get_color array
        if (isset($font_array['color'])) {
            $font_col = imagecolorallocate($image, $font_array['color'][0], $font_array['color'][1], $font_array['color'][2]);
        } else {
            // $color = $this->model->get_color($image);
            $color = color::get_color($image);
        }

        // set color
        $font_color = $font_col ?? $color['black'];
        $font_size = isset($font_array['size']) ? $font_array['size'] : 60;
        $font_angle = isset($font_array['angle']) ? $font_array['angle'] : 0;
        $line_height = isset($font_array['line_height']) ? $font_array['line_height'] : 120;
        $text_width = isset($font_array['width']) ? $font_array['width'] : 11;
        $px = $font_array['px'];
        $py = isset($font_array['py']) ? $font_array['py'] + $font_size : $px + $font_size;

        //CREAT IMAGE WITH CUSTOM BACKGROUND COLOR
        // echo $px;
        if(isset($image_array['background']) ){
            $background = isset($image_array['background']) ? $image_array['background'] : ['255', '255', '255'];
            $background = imagecolorallocate($image, $background[0], $background[1], $background[2]);
            imagefill($image, 0, 0, $background);
        }
     

        // BREAK TEXT INTO NEW LINE

        $text = wordwrap($text, $text_width, "\n", false);
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            // $col = $color[$font_color];
            // $_SESSION['debug'] = $font_color;
            imagettftext($image, $font_size, $font_angle, $px, $py, $font_color, $font_array['file'], trim($line));
            $py += $line_height;
        }

        imagepng($image, $new_image_path);
        imagedestroy($image);
        return $new_image_path;
    }
}
