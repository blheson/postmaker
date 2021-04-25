<?php

namespace Controller\Common;

use Controller\Common;
use Controller\Common\Assets as assets;
/**
 * Class for setting the width of image
 */
class ImageDimension
{
    const DEFAULT_IMAGE_WIDTH = 1000;
    const DEFAULT_IMAGE_HEIGHT = 1000;
    public $createImage;


    /**
     * Create an instance for CreateImage
     * 
     * @return CreateImage  
     */
    public function createImage()
    {
        return assets::createImage();
    }
    /**
     * Crop image into a squarea
     * @param string $img_path 
     * @param int $length 
     * @param int|null $scale
     * @return resource
     * 
     */
    public function crop($img_path, $default = self::DEFAULT_IMAGE_WIDTH)
    {

        list($width, $height) = getimagesize($img_path);
        $aspect_ratio = $this->aspect($width, $height);
        if ($width == $height)
            return $img_path;
        // list($logo_width, $logo_height) = getimagesize($logo);
        // $this->setLogoDimension($logo_width, $logo_height);
        $createImage = $this->createImage();


        // set scale to height assuming image is square
        $scale = $height;

        // set scale if image is rectangle
        //Also check if width and height is less than @var $default
        if (($width < $default || $height < $default) && $width != $height) {
            $im = $createImage->createImageResource($img_path);
            if ($width > $height) {

                $scale = $height < $default ? $default : $height;
                $im = imagescale($im, -1, $scale);
            } elseif ($height > $width) {

                $scale = $width < $default ? $default : $width;
                $im = imagescale($im, $scale);
            }
        }


        if (($width > $default && $height > $default) && $width != $height) {
            if ($width < $height) {
                $this->resize_image($img_path, $default, 1000 / $aspect_ratio);
            } elseif ($height < $width) {

                $this->resize_image($img_path, 1000 * $aspect_ratio);
            }
            $im = $createImage->createImageResource($img_path);
        }


        $resource = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $default, 'height' => $default]);


        imagepng($resource, $img_path);

        imagedestroy($im);
        // imagedestroy($target_layer);
        return $img_path;
    }
    private function aspect($width, $height)
    {
        return $width / $height;
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
