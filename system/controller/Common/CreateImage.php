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
                $_SESSION['error'] = 'upload a valid logo';
                return false;
            } else {
                $logo_link = $this->upload_image($logo, $link, ['width' => null, 'height' => $logo_height]);
                if (!$logo_link) {
                    $_SESSION['error'] = "Logo not successfully uploaded";
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
                $_SESSION['error'] = 'Upload a valid picture';
                return false;
            } else {
                $product_image = $this->upload_image($image, $link);
                if (!$product_image) {
                    $_SESSION['error'] = "Image not successfully uploaded";
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
        //         $_SESSION['error'] = 'upload a valid logo';
        //         return false;
        //     } else {
        //         $logo_link = $this->upload_image($logo, $link, ['width' => null, 'height' => $logo_height]);
        //         if (!$logo_link) {
        //             $_SESSION['error'] = "Logo not successfully uploaded";
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
        $file_ext = $paths['extension'];
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
            $_SESSION['error'] = 'The file must be an image';
            return 'The file must be an image';
        }
        if (!in_array($file_ext, $allowed)) {
            $_SESSION['error'] = 'The file extension must be a png, jpg, jpeg, gif';
            return 'The file extension must be a png, jpg, jpeg, gif';
        }
        if ($file_size > 10000000) {
            $_SESSION['error'] = 'The file size must be under 10MB';
            return 'The file size must be under 15MB';
        }
        if ($file_ext != $mime_ext && ($mime_ext == 'jpeg' && $file_ext != 'jpg')) {
            $_SESSION['error'] = 'The file extension does not match the original file';
            return 'The file extension does not match the original file';
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
    public function upload_image($file, $link, $dimension = null)
    {

        $image_data = $this->checkImage($file, $link);
        if (is_array($image_data)) {
            $tmp_loc = $image_data['tmp_loc'];
            $upload_path = $image_data['upload_path'];
            $db_path = $image_data['db_path'];
        } else {
            return $image_data;
        }

        if (move_uploaded_file($tmp_loc, $upload_path)) {
            $check = $dimension == null;
            $width =  $check ? true : $dimension['width'];
            $height = $check  ? true : $dimension['height'];
            $this->getImageDimension()->resize_image($upload_path, $width, $height);
            return $db_path;
        } else {
            $_SESSION['error'] = 'The image could not be saved';
            return $db_path;
        }
    }
    /**
     * Add text on image
     * @param string $base_image_path
     * @param string $new_image_link folder to save new file
     * @param string $ext
     * 
     * @return string $newImagePath
     */
    public function createBlankImage($base_image_path, $new_image_link, $ext = 'png'): string
    {
       
        $new_name = "postmaker_" . sha1(time()) . "." . $ext;
    
        $newImagePath = $new_image_link . $new_name;
        copy($base_image_path, $newImagePath);

        return $newImagePath;
    }
    /**
     * Create Image Resource
     * 
     * @param string $file - Path to file
     * 
     * @return resource $im
     */
    public function create_image_resource(string $file)
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
