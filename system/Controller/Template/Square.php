<?php

namespace Controller\Template;

use Model\Font as font;
use Controller\Common\Color as color;
use Controller\Constant as constant;
use Controller\Common\Assets as assets;
use Controller\Common\ImageDimension as imageDimension;
use Controller\Common\CreateImage;
use Controller\Template\Watermark as watermark;
class Square
{
    const ROOT_IMG_PATH = 'assets/images';
    public $model;
    public $color;
    public $imageDimension;
    public $createImage;

    public function __construct()
    {
        $this->font = new font;
        $this->color = new color;
        $this->imageDimension = new imageDimension;
        $this->createImage = assets::createImage();
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
    public function createImage()
    {
        return $this->createImage;
    }
    public function water_mark()
    {
        $this->watermark = new watermark();
        
        return $this->watermark;
    }
    public function imageDimension(){
        return $this->imageDimension;
    }
    /**
     * Add text on image
     * @param string $new_link
     * @param array $imageArray
     *  keys include
     * 
     *  string  'newImagePath' - The final path of the image
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
     * @return string $newImagePath
     */
    public function write_to_footer($new_link, $imageArray, $footer, $font_array)
    {
        $font_array['size'] = isset($font_array['size']) ? $font_array['size'] : 20;
        $font_array['px'] = isset($font_array['px']) ? $font_array['px'] : 700;
        $font_array['py'] = isset($font_array['py']) ? $font_array['py'] : 850;
        $font_array['width'] = isset($font_array['width']) ? $font_array['width'] : 20;
        $font_array['line_height'] = isset($font_array['line_height']) ? $font_array['line_height'] : 60;
        //Get font from DB

        $font = $this->font->get_font();

        $font_array['file'] =  constant::rootDir().$font['montserrat'];

      
       return $this->text_to_image($new_link, $imageArray, trim($footer), $font_array);
    }
    /**
     * Wrap text into a specified dimesion
     * 
     * @param string $new_link (The default image to work with)
     * 
     * @param array $imageArray
     *  keys include
     * 
     *  string  'newImagePath' - The final path of the image  (or The folder to store the image)
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
     * @return string $newImagePath
     */
    public function text_to_image($new_link, $imageArray, $text, $font_array): string
    {
        // SORT IMAGE ARRAY
        $image = imagecreatefrompng($new_link);
        $newImagePath = $imageArray['newImagePath'];
       
        $image_width =  isset($imageArray['width']) ? $imageArray['width'] : $this->imageDimension::DEFAULT_IMAGE_WIDTH;

        // SORT FONT ARRAY
        // get_color array
        if (isset($font_array['color'])) {
            $font_col = imagecolorallocate($image, $font_array['color'][0], $font_array['color'][1], $font_array['color'][2]);
        } else {
            // $color = $this->font->get_color($image);
            $color = color::get_color($image);
        }

        // set color
        $font_color = $font_col ?? $color['black'];
        $font_size = isset($font_array['size']) ? $font_array['size'] : 60;
        $font_angle = isset($font_array['angle']) ? $font_array['angle'] : 0;
        $line_height = isset($font_array['line_height']) ? $font_array['line_height'] : 120;
        $text_width = isset($font_array['width']) ? $font_array['width'] : 11;
     

        //CREAT IMAGE WITH CUSTOM BACKGROUND COLOR
        // echo $px;
        if(isset($imageArray['background']) ){
            $background = isset($imageArray['background']) ? $imageArray['background'] : ['255', '255', '255'];
            $background = imagecolorallocate($image, $background[0], $background[1], $background[2]);
            imagefill($image, 0, 0, $background);
        }
     

        // BREAK TEXT INTO NEW LINE

        $text = wordwrap($text, $text_width, "\n", false);
        $lines = explode("\n", $text);
       $py = null;
        foreach ($lines as $line) {
          $bbox =  imagettfbbox($font_size, $font_angle, $font_array['file'],trim($line));
          $px =  isset($imageArray['width']) ?$bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2):$font_array['px'];
          if(!$py){
              $py = isset($font_array['py']) ? $font_array['py'] + $font_size : $px + $font_size;
          }
        
            // $col = $color[$font_color];
            // $_SESSION['debug'] = $font_color;
            imagettftext($image, $font_size, $font_angle, $px, $py, $font_color, $font_array['file'], trim($line));
            $py += $line_height;
        }

        imagepng($image, $newImagePath);
        imagedestroy($image);
     
        return $newImagePath;
    }
}
