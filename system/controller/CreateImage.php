<?php

namespace Controller\Common;

use Controller\Common\ImageDimension;

/**
 * Class for creating image and resource
 */
class CreateImage
{
    public function __construct()
    {
        require_once 'ImageDimension.php';
        $this->image_dimension = new ImageDimension;
    }
    /**
     * Image checker
     * @param mixed $data
     * @param array $root_dir 
     * @param array $link
     * @return array
     */
    public function check_image($data, $link)
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
        $image_data = $this->check_image($file, $link);
        if (is_array($image_data)) {
            $tmp_loc = $image_data['tmp_loc'];
            $upload_path = $image_data['upload_path'];
            $db_path = $image_data['db_path'];
        } else {
            return $image_data;
        }

        if (move_uploaded_file($tmp_loc, $upload_path)) {
            if ($dimension == null) {
                $this->image_dimension->resize_image($upload_path, true, true);
            } else {
                $this->image_dimension->resize_image($upload_path, $dimension['width'], $dimension['height']);
            }
            return $db_path;
        } else {
            $_SESSION['error'] = 'The image could not be saved';
            return $db_path;
        }
    }
    /**
     * Add text on image
     * @param string $base_image_path
     * @param string $new_image_link
     * @param string $ext
     * 
     * @return string $new_image_path
     */
    public function create_blank_image($base_image_path, $new_image_link, $ext = 'png'): string
    {
        $new_name = "postmaker_" . md5(rand(1, 10)) . "." . $ext;
        $new_image_path = $new_image_link . $new_name;
        copy($base_image_path, $new_image_path);
        return $new_image_path;
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
