<?php

namespace Controller\Template;

use Controller\Common\CreateImage;
use Controller\Common\Assets as assets;
use Controller\Common\ImageDimension;
use Controller\Constant as constant;
use Exception;

class Watermark
{
    public $create_image;
    public $imageDimension;
    public $logo_dimension;
    public $dimension_instance;

    /**
     * Create an instance for CreateImage
     * 
     * @return CreateImage  
     */
    public function create_image()
    {

        return assets::create_image();
    }
    /**
     * Create an instance for CreateImage
     * 
     * @return ImageDimension  
     */
    public function dimension_instance()
    {
        return assets::imageDimension();
    }
    public function addLogoResourceOnImage($dst, $stamp, $cord = null, $margin = 30, $ratio = null)
    {
        // Create stamp image manually from GD
        $stampResource = imagecreatefrompng($stamp);
        list($dst_width, $dst_height) = getimagesize($dst);


        $logo_width = imagesx($stampResource);
        $logo_height = imagesy($stampResource);

        //set image dimension
        $this->setImageDimension($dst_width, $dst_height);


        //set logo dimension

        $this->setLogoDimension($logo_width, $logo_height);


        // SET MARGIN
        if (is_array($cord)) {
            $position = $cord;
        } else {
            $position = $this->getLogoCord($dst_width, $dst_height, $logo_width, $logo_height, $cord, $margin);
        }

        // extract to get $x and $y variable
        extract($position);

        $im = $this->create_image()->create_image_resource($dst);

        imagecopy($im, $stampResource, $x, $y, 0, 0, $logo_width, $logo_height);


        // imagecopyresampled($im, $stamp, $x, $y, 0, 0,$dst_width, $dst_height, $logo_width, $logo_height);



        // Save the image to file and free memory
        imagepng($im, $dst);

        imagedestroy($im);

        return $dst;
    }
    /**
     * Logo on product
     * @param array $image HTTP upload for image file
     * @param array $logo HTTP upload for logo file
     * @param array $logo_width Default is 200
     * @param array $cord
     * @return string link to Water mark image
     */
    public function logo_on_product($image, $logo, $cord, $logo_width = 200, $margin = 30): ?string
    {
        // $link = [];
        // $link['short'] = 'render/';
        // $link['long'] = '../assets/images/' . $link['short'];
        $link = constant::renderLink();
        //Process Product Image
        try {
            if (!isset($image) || $image['size'] < 1 || $image['error'] > 0) {
                $_SESSION['postmakerError'] = 'Upload a valid image';
                return null;
            }
            if (!isset($logo) || $logo['size'] < 1 || $logo['error'] > 0) {
                $_SESSION['postmakerError'] = 'Upload a valid logo';
                return null;
            }

            $product_image = $this->create_image()->upload_image($image, $link);

            if (!$product_image) {

                return null;
            }

            //Process Logo
  

            $logo_link = $this->create_image()->upload_image($logo, $link, ['width' => $logo_width, 'height' => null]);

            if (!$logo_link) {
                return null;
            }
        } catch (Exception $e) {
            $e->getMessage();
        }

        // $link = dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . '/';
        $link= constant::rootDir();
 
        return $this->addLogoToImage($link . '/assets/images/' . $product_image, $link . '/assets/images/' . $logo_link, $cord, $margin);
    }
    /**
     * Add logo to an image
     * 
     * @param string $dst - The picture to watermark
     * 
     * @param string $logo - The stamp or logo used for watermark
     * 
     * @param array|string $cord set the position of logo
     * 
     * @return string $dst
     */
    public function addLogoToImage($dst, $logo, $cord = null, $margin = 30, $ratio = null): string
    {
  

        list($dst_width, $dst_height) = getimagesize($dst);


        list($logo_width, $logo_height) = getimagesize($logo);

        //set image dimension
        $this->setImageDimension($dst_width, $dst_height);


        //set logo dimension

        $this->setLogoDimension($logo_width, $logo_height);


        // SET MARGIN
        if (is_array($cord)) {
            $position = $cord;
        } else {
            $position = $this->getLogoCord($dst_width, $dst_height, $logo_width, $logo_height, $cord, $margin);
        }

        // extract to get $x and $y variable
        extract($position);
 
        $im = $this->create_image()->create_image_resource($dst);

        // Create stamp image manually from GD
        $stamp = imagecreatefrompng($logo);

        unlink($logo);

        imagecopy($im, $stamp, $x, $y, 0, 0, $logo_width, $logo_height);


        // imagecopyresampled($im, $stamp, $x, $y, 0, 0,$dst_width, $dst_height, $logo_width, $logo_height);



        // Save the image to file and free memory
        imagepng($im, $dst);
       
        imagedestroy($im);

        return $dst;
    }

    public function setImageDimension($dst_width, $dst_height)
    {
        $this->imageDimension = ['width' => $dst_width, 'height' => $dst_height];
    }
    public function setLogoDimension($logo_width, $logo_height)
    {
        $this->logo_dimension = ['width' => $logo_width, 'height' => $logo_height];
    }

    public function getImageDimension()
    {
        return $this->imageDimension;
    }

    public function get_logo_dimension()
    {
        return $this->logo_dimension;
    }

    /**
     * Get the coordinate of logo
     * 
     * @param int $dst_width
     * 
     * @param int $dst_height
     * 
     * @param int $logo_width
     * 
     * @param int $logo_height
     * 
     * @param int $margin
     * 
     * @param string $cord
     * 
     * @return array the x and y coordinate of the logo
     * 
     */
    public function getLogoCord($dst_width, $dst_height, $logo_width, $logo_height, $cord, $margin)
    {
        $center_x = ($dst_width / 2) - ($logo_width / 2);
        $m_right_x  = $dst_width - ($logo_height + $margin);
        $m_bottom = $dst_height - ($logo_height + $margin);
        switch ($cord) {
            case "tl":
                $x = $margin;
                $y = $margin;
                break;
            case "tc":
                $x = $center_x;
                $y = $margin;
                break;
            case "tr":
                $x = $m_right_x;
                $y = $margin;
                break;
            case "bl":
                $x = $margin;
                $y = $m_bottom;
                break;
            case "bc":
                $x = $center_x;
                $y = $m_bottom;
                break;
            case "br":
                $x = $m_right_x;
                $y = $m_bottom;
                break;
            default:
                $x = $center_x;
                $y = ($dst_height / 2) - ($logo_height / 2);
                break;
        }
        return ['x' => $x, 'y' => $y];
    }
}
