<?php
namespace Controller\Template\Square;

require 'Square.php';

use Controller\Template\Square as square;

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
     *  string      'default_image' - This is a link to the template image
     *  string      'text' - Text for design 
     *  string      'new_image_path' - (link)
     *  string      'font' - (link)
     *  string      'font_size' - [optional]
     *  string      'font_angle' - [optional]
     * @return string $new_link
     */

    public function add_data_on_blank_image($post): string
    {
        if (strlen($post['text']) < 1) {
            $_SESSION['error'] = "Please put in a text";
            return false;
        }

        $default_image = $post['default_image'];

        //SET THE NEW DESIGN TO A NEW PATH
        $new_image_path = $this->create_image()->create_blank_image($default_image, $post['new_image_path']);


        //SORT IMAGE ARRAY     

        $image_array['new_image_path'] = $new_image_path;

        //check if background colour is set
        if (isset($post['background'])) {
            $image_array['background'] = $this->color->convert_hex_to_rgb(preg_replace('|#|', '', $post['background']));
        }


        //check if image width is set
        if (isset($post['width']))
            $image_array['width'] = $image_array['width'];

        //SORT FONT ARRAY

        $font_array['px']    = isset($post['px']) ? (int)$post['px'] : 130;
        if (isset($post['py']))
            $font_array['py']     = $post['py'];
        if (isset($post['color']))
            $font_array['color']     = $this->color->convert_hex_to_rgb(preg_replace('|#|', '', $post['color']));;

        if (isset($post['size']))
            $font_array['size']     = $post['size'];

        if (isset($post['angle']))
            $font_array['angle']     = $post['angle'];

        if (isset($post['line_height']))
            $font_array['line_height']     = $post['line_height'];

        $font_array['file'] = $post['font'];

        //STRING  
        $string = $post['text'];

        //IMAGE RESOURCE
        // $im     = imagecreatefrompng($default_image);
        $new_link = $this->text_to_image($default_image, $image_array, $string, $font_array);
        if ($post['footer'] != '') {
            unset($font_array['px']);
            $this->write_to_footer($new_link, $image_array, $post['footer'], $font_array);
        }
        return $new_link;
    }

}
