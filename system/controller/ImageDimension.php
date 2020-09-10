<?php

namespace Controller\Common;

require_once 'Common.php';

use Controller\Common;

/**
 * Class for setting the width of image
 */
class ImageDimension
{
    const DEFAULT_IMAGE_WIDTH = 500;
    const DEFAULT_IMAGE_HEIGHT = 500;
    public $create_image;


    /**
     * Create an instance for CreateImage
     * 
     * @return CreateImage  
     */
    public function create_image()
    {
        $this->create_image = new CreateImage;
        return $this->create_image;
    }
    /**
     * Crop image into a square
     * @param string $img_path 
     * @param int $length 
     * @param int|null $scale
     * @return resource
     * 
     */
    public function crop($img_path, $height, $width)
    {
        $create_image = $this->create_image();
        $im = $create_image->create_image_resource($img_path);
        // set scale to height assuming image is square
        $scale = $height;

        // set scale if image is rectangle
        if ($width > $height) {
            $scale = $height < 500 ? 500 : null;
            $im = imagescale($im, $scale);
        } elseif ($height > $width) {
            $scale = $width < 500 ? 500 : null;
            $im = imagescale($im, $scale);
        }

        //scale up the image
        // if ($scale) {
        //     $im = imagescale($im, $scale);
        // }

        $resource = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $scale, 'height' => $scale]);
        imagepng($resource, $img_path);
        imagedestroy($resource);
        return $img_path;
    }
    /**
     * Resize image email
     * @param mixed $source_image_path
     * @param int $width
     * @param int $height
     * @return bool
     */
    public function resize_image($source_image_path, $width = null, $height = null, $transparency = true)
    {
        if ($width == null)
            $width = self::DEFAULT_IMAGE_WIDTH;
        if ($height == null)
            $height = self::DEFAULT_IMAGE_HEIGHT;

        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);

        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }

        if ($source_gd_image === false) {
            return false;
        }


        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = $width / $height;
        if ($source_image_width <= $width && $source_image_height <= $height) {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) ($height * $source_aspect_ratio);
            $thumbnail_image_height = $height;
        } else {
            $thumbnail_image_width = $width;
            $thumbnail_image_height = (int) ($width / $source_aspect_ratio);
        }

        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

        if ($transparency == true) {
            imagealphablending($thumbnail_gd_image, false);
            imagesavealpha($thumbnail_gd_image, true);
            $transparency = imagecolorallocatealpha($thumbnail_gd_image, 255, 255, 255, 0);
            imagefilledrectangle($thumbnail_gd_image, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $transparency);
        }

        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

        switch ($source_image_type) {
            case IMAGETYPE_JPEG:
                imagejpeg($thumbnail_gd_image, $source_image_path, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumbnail_gd_image, $source_image_path, 0);
                break;
            case IMAGETYPE_GIF:
                imagegif($thumbnail_gd_image, $source_image_path);
                break;
        }

        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);

        return true;
    }
}
