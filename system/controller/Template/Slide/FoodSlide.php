<?php

namespace Controller\Template\Slide;


use Controller\Template\Square as Square;

class FoodSlide extends Square
{
    /**
     * The relative link to image path
     * @var REL_LINK
     */
    const REL_LINK = '../../';
    /**
     * 
     */
    public function process($post)
    {
        if (!isset($_SESSION['saved_logo']))
            $this->upload_logo($_FILES['logo']);

        switch ($post['section']) {
            case 'front':
                return $this->front_section($post);
                break;
            case 'content':
                // return $this->content_section($post);
                break;
            case 'back':
                // return $this->back_section($post);
                break;
            default:
                # code...
                break;
        }
    }
    private function upload_logo($data)
    {
        $_SESSION['saved_logo']  = $this->create_image()->logo_upload($data, 100, self::REL_LINK);
        return $_SESSION['saved_logo'];
    }
    private function duplicate($source, $dst, $ext = 'png')
    {
        return $this->create_image()->create_blank_image($source, $dst, $ext);
    }
    /**
     * Design front section
     * @param array $post The details from food slide form page
     * @return string $render_path
     */
    private function front_section(array $post)
    {
        // $logo_link = $this->create_image()->create_blank_image(self::REL_LINK . self::ROOT_IMG_PATH . '/' . $_SESSION['saved_logo'], $post['new_image_path']);
        $logo_link = $this->duplicate(self::REL_LINK . self::ROOT_IMG_PATH . '/' . $_SESSION['saved_logo'], $post['new_image_path']);
        //Duplicate front design
        $front = $this->create_image()->create_blank_image($post['front_image'], $post['new_image_path']);

        $source = $this->water_mark()->add_logo_to_image($front, self::REL_LINK . self::ROOT_IMG_PATH . '/' . $logo_link, 'bl');

        $title = mb_strtoupper($post['title']);

        $font_array = [
            'px' => 70,
            'py' => 140,
            'file' => $post['font'],
            'color' => [255, 255, 255],
            'size' => 80,
            'line_height' => 150
        ];

        $image_array = [
            'new_image_path' => $front
        ];

        return $this->text_to_image($source, $image_array, $title, $font_array);
        // return $source;
    }
    /**
     * Design content section
     * @param array $post The details from food slide form page
     * @return string $render_path
     */
    private function content_section(array $post)
    {

        $content = $this->duplicate($post['default_image'], $post['new_image_path']);
        $logo_link = $this->duplicate(self::REL_LINK . self::ROOT_IMG_PATH . '/' . $_SESSION['saved_logo'], $post['new_image_path']);
        $source = $this->water_mark()->add_logo_to_image($content, self::REL_LINK . self::ROOT_IMG_PATH . '/' . $logo_link, 'bl');
        $font_array = [
            'px' => 70,
            'py' => 140,
            'file' => $post['font'],
            'color' => [255, 255, 255],
            'size' => 50,
            'line_height' => 150
        ];

        $image_array = [
            'new_image_path' => $content
        ];
        $string = mb_strtoupper($post['title']);

        return $this->text_to_image($source, $image_array, $string, $font_array);
    }
    /**
     * Add text on image
     * @param array $post
     * keys include
     *  string      'default_image' - This is a link to the template image
     * 
     *  string      'text' - Text for design 
     * 
     *  string      'new_image_path' - (link)
     * 
     *  string      'font' - (link)
     * 
     *  string      'font_size' - [optional]
     * 
     *  string      'font_angle' - [optional]
     * 
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
