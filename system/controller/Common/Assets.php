<?php

namespace Controller\Common;
use Controller\Common;

/**
 * Class for shapes
 */
class Assets
{
    const DEFAULT_IMAGE_WIDTH = 1000;
    const DEFAULT_IMAGE_HEIGHT = 1000;
    public static $create_image;
    /**
     * Create an instance for CreateImage
     * 
     * @return CreateImage  
     */
    public static function create_image()
    {
        return new CreateImage;
    }
    /**
     * Create a rectangle
     * @param string $path The path to image to add rectangle to 
     * @param array $dimension The four cardinal points the rectangle will fill
     * @param array $color The RGB array of color (e.g 255,255,255 for white) 
     * @return resource
     * 
     */
    public static function create_rectangle($path, $dimension, $color)
    {
        list($x1, $x2, $y1, $y2) = $dimension;
        $create_image = self::create_image();
        $im = $create_image->create_image_resource($path);
        $col = Color::get_color($im, $color);
        imagefilledrectangle($im, $x1, $y1, $x2, $y2, $col);
        imagepng($im, $path);
        imagedestroy($im);
        unset($create_image);
        return $path;
    }
}
