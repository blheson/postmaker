<?php
namespace Controller\Template\Square;

use Controller\Template\Square as square;
use Controller\Constant as constant;

class SquareImage extends square
{
    /**
     * Get image
     */

    public function get_image($post)
    {
        $source_image_type = $post['link'];
        $string = $post['text'];
        $im     = imagecreatefrompng($source_image_type);
        $orange = imagecolorallocate($im, 220, 210, 60);
        $px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
        imagestring($im, 3, $px, 9, $string, $orange);
        // $this->createBlankImage();
        $image  = imagepng($im);
        imagedestroy($im);
        return $image;
    }
    /**
     * Add text on image
     * @param array $post
     * keys include
     *  string      'defaultImage' - This is a link to the template image
     *  string      'text' - Text for design 
     *  string      'newImagePath' - (link)
     *  string      'font' - (link)
     *  string      'font_size' - [optional]
     *  string      'font_angle' - [optional]
     * @return string $new_link
     */

    public function add_data_on_blank_image($post): string
    {
        if (strlen($post['text']) < 1) {
            $_SESSION['postmakerError'] = "Please put in a text";
            return false;
        }

        $defaultImage = $post['defaultImage'];

        //SET THE NEW DESIGN TO A NEW PATH
        $newImagePath = $this->createImage()->createBlankImage($defaultImage, $post['newImagePath']);


        //SORT IMAGE ARRAY     

        $imageArray['newImagePath'] = $newImagePath;

        //check if background colour is set
        if (isset($post['background'])) {
            $imageArray['background'] = $this->color->convertHexToRgb(preg_replace('|#|', '', $post['background']));
        }


        //check if image width is set
        if (isset($post['width']))
            $imageArray['width'] = $imageArray['width'];

        //SORT FONT ARRAY

        $font_array['px']    = isset($post['px']) ? (int)$post['px'] : 130;
        if (isset($post['py']))
            $font_array['py']     = $post['py'];
        if (isset($post['color']))
            $font_array['color']     = $this->color->convertHexToRgb(preg_replace('|#|', '', $post['color']));;

        if (isset($post['size']))
            $font_array['size']     = $post['size'];

        if (isset($post['angle']))
            $font_array['angle']     = $post['angle'];

        if (isset($post['line_height']))
            $font_array['line_height']     = $post['line_height'];

        $font_array['file'] = constant::rootDir().$post['font'];

        //STRING  
        $string = $post['text'];

        //IMAGE RESOURCE
        // $im     = imagecreatefrompng($defaultImage);
        $new_link = $this->text_to_image($defaultImage, $imageArray, $string, $font_array);
        if ($post['footer'] != '') {
            unset($font_array['px']);
            $this->write_to_footer($new_link, $imageArray, $post['footer'], $font_array);
        }
        return $new_link;
    }

}
