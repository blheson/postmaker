<?php

namespace Controller\Common;

use Controller\Common\ImageDimension;
use Controller\Common\Assets as assets;

/**
 * Class for creating image and resource
 */
class CreateImage
{
    const REL_LINK = '../';
    // public static $imageDimension;
    /**
     * @return ImageDimension
     */
    public function getImageDimension()
    {
        return assets::imageDimension();
    }
    /**
     * The relative part of the render link
     * @param string $rel
     */
    public function get_upload_link($rel = self::REL_LINK)
    {
        $link = [];
        $link['short'] = 'render/';
        $link['long'] = $rel . 'assets/images/' . $link['short'];
        return $link;
    }
    /**
     * Upload Logo
     * @param array $logo HTTP upload for logo file
     * @param array $logo_width Default is 200
     * @param string $rel the relative part of the render link
     * @return string $logo_link
     */
    public function logo_upload($logo, $logo_height = 120, $rel = self::REL_LINK)
    {
         
        $link = $this->get_upload_link($rel);
        if (isset($logo) && $logo['size'] > 0) {
            if ($logo['error'] > 0) {
                $_SESSION['postmakerError'] = 'upload a valid logo';
                return false;
            } else {
                $logo_link = $this->uploadImage($logo, $link, ['width' => null, 'height' => $logo_height]);
                if (!$logo_link) {
                    $_SESSION['postmakerError'] = "Logo not successfully uploaded";
                    return false;
                }
            }
        } else {
            $logo_link = 'Logo certificate not successfully uploaded';
            throw new \Exception("No image was uploaded");
            return false;
        }
        return $logo_link;
    }
    /**
     * Logo and product upload
     * @param array $image HTTP upload for image file
     * @param array $logo HTTP upload for logo file
     * @param array $logo_width Default is 200
     * @return string link to Water mark image
     */
    public function logo_and_product_upload($image, $logo, $logo_height = 120)
    {
        $link = $this->get_upload_link();

        //Process Product Image
        if (isset($image) && $image['size'] > 0) {
            if ($image['error'] > 0) {
                $_SESSION['postmakerError'] = 'Upload a valid picture';
                return false;
            } else {
                $product_image = $this->uploadImage($image, $link);
                if (!$product_image) {
                    $_SESSION['postmakerError'] = "Image not successfully uploaded";
                    return false;
                }
            }
        } else {
            $product_image = 'no image';
            throw new \Exception("No image was uploaded");
            return false;
        }
        //upload logo and process logo
        $logo_link = $this->logo_upload($logo, $logo_height);

        // if (isset($logo) && $logo['size'] > 0) {
        //     if ($logo['error'] > 0) {
        //         $_SESSION['postmakerError'] = 'upload a valid logo';
        //         return false;
        //     } else {
        //         $logo_link = $this->uploadImage($logo, $link, ['width' => null, 'height' => $logo_height]);
        //         if (!$logo_link) {
        //             $_SESSION['postmakerError'] = "Logo not successfully uploaded";
        //             return false;
        //         }
        //     }
        // } else {
        //     $logo_link = 'Logo certificate not successfully uploaded';
        //     throw new \Exception("No image was uploaded");
        //     return false;
        // }

        return ['product' => '../assets/images/' . $product_image, 'logo' => '../assets/images/' . $logo_link];
    }
    /**
     * Image checker
     * @param mixed $data
     * @param array $root_dir 
     * @param array $link
     * @return array
     */
    public function checkImage($data, $link)
    {
        $allowed = array('png', 'jpg', 'jpeg', 'gif', 'webp');

        $paths = pathinfo($data["name"]);
        $file_ext = strtolower($paths['extension']);
        $mime = explode('/', $data['type']);
        $mime_type = $mime[0];
        $mime_ext = $mime[1];
        $tmp_loc = $data['tmp_name'];
        $file_size = $data['size'];
        $file_name = md5(rand(1, 10)) . "." . $file_ext;
        //  $upload_name = substr($file_name,0,1).'/'.$file_name;
        $upload_name = $file_name;

        $upload_path = $link['long'] . $upload_name;


        // $upload_path = $root_dir.$link['long'];
        $db_path =  $link['short'] . $upload_name;


        if ($mime_type != 'image') {
            $_SESSION['postmakerError'] = 'The file must be an image';
            return false;
        }
        // strtolower
        if (!in_array($file_ext, $allowed)) {
            $_SESSION['postmakerError'] = 'The file extension must be a png, jpg, jpeg, gif';
          
            
            return false;
        }
 
        if ($file_size > 10000000) {
            $_SESSION['postmakerError'] = 'The file size must be under 10MB';
            return false;
        }
        if ($file_ext != $mime_ext && ($mime_ext == 'jpeg' && $file_ext != 'jpg')) {
            $_SESSION['postmakerError'] = 'The file extension does not match the original file';
            return false;
        }

        return ['tmp_loc' => $tmp_loc, 'upload_path' => $upload_path, 'db_path' => $db_path];
    }
    /**
     * Upload image to server
     * @param array $file index of uploaded file
     * @param string $link Link to upload image
     * @param array  $dimension The height and width of the image
     *  indexes /n are width and height
     * @return string Path to the uploaded image
     */
    public function uploadImage($file, $link, $dimension = null)
    {

        $image_data = $this->checkImage($file, $link);
   
        if (!is_array($image_data)) {
         
            return false;
        }

        $tmp_loc = $image_data['tmp_loc'];
        $upload_path = $image_data['upload_path'];
        $db_path = $image_data['db_path'];
        if (move_uploaded_file($tmp_loc, $upload_path)) {
            $check = $dimension == null;
            $width =  $check ? true : $dimension['width'];
            $height = $check  ? true : $dimension['height'];
            $this->getImageDimension()->resize_image($upload_path, $width, $height);
            return $db_path;
        } else {
            $_SESSION['postmakerError'] = 'The image could not be saved';
            return false;
        }
    }
    /**
     * Add text on image
     * @param string $baseImagePath
     * @param string $newImageLink folder to save new file
     * @param string $ext
     * 
     * @return string $newImagePath
     */
    public function createBlankImage($baseImagePath, $newImageLink, $ext = 'png'): string
    {
       
        $new_name = "postmaker_" . sha1(time()) . "." . $ext;
    
        $newImagePath = $newImageLink . $new_name;
        copy($baseImagePath, $newImagePath);

        return $newImagePath;
    }
    /**
     * Create Image Resource
     * 
     * @param string $file - Path to file
     * 
     * @return resource $im
     */
    public function createImageResource(string $file)
    {
 
        $image_detail = getimagesize($file);
        //CHECK THE IMAGE TYPE
        switch ($image_detail[2]) {
            case IMAGETYPE_GIF:
                $im = imagecreatefromgif($file);
                break;
            case IMAGETYPE_JPEG:
                $im = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($file);

                break;
            default:
                throw new \Exception('File is not an image', 1);
        }
        return $im;
    }
}
