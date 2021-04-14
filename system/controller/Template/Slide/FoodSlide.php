<?php

namespace Controller\Template\Slide;


use Controller\Template\Square as Square;
use Controller\Constant as constant;

class FoodSlide extends Square
{
    /**
     * The relative link to image path
     * @var REL_LINK
     */
    const REL_LINK = '../../';

    /**
     * @param array $post
     * 
     */
    public function process($post)
    {

        if (!isset($_SESSION['savedLogo']))
            $this->upload_logo($_FILES['logo']);

        switch ($post['section']) {
            case 'front':
                if ($post['frontImg'] !== 'null')
                   unlink(constant::rootDir().'/'. $post['frontImg']);
                $_SESSION['foodSlide']['front'] = $this->front_section($post);
                return $_SESSION['foodSlide']['front'];
                break;
            case 'content':
                return $this->content_section($post);
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
        $_SESSION['savedLogo']  = $this->create_image()->logo_upload($data, 100, self::REL_LINK);
        return $_SESSION['savedLogo'];
    }
    private function duplicate($source, $dst, $ext = 'png')
    {
        return $this->create_image()->createBlankImage($source, $dst, $ext);
    }
    /**
     * Design front section
     * @param array $post The details from food slide form page
     * @return string $render_path
     */
    private function front_section(array $post)
    {

        // $logo_link = $this->create_image()->createBlankImage(self::REL_LINK . self::ROOT_IMG_PATH . '/' . $_SESSION['savedLogo'], $post['newImagePath']);
        $link = constant::rootDir() . '/';
        $logo_link = $this->duplicate($link . self::ROOT_IMG_PATH . '/' . $_SESSION['savedLogo'], $link . $post['newImagePath']);

        //Duplicate front design
        $front = $this->create_image()->createBlankImage($link . $post['frontImage'], $link . $post['newImagePath']);

        $source = $this->water_mark()->addLogoToImage($front, $logo_link, 'bl');


        $title = mb_strtoupper($post['title']);

        $font_array = [
            'px' => 70,
            'py' => 140,
            'file' => $post['font'],
            'color' => [255, 255, 255],
            'size' => 80,
            'line_height' => 150
        ];

        $imageArray = [
            'newImagePath' => $front
        ];

        return $this->text_to_image($source, $imageArray, $title, $font_array);
        // return $source;
    }
    /**
     * Design content section
     * @param array $post The details from food slide form page
     * @return string $render_path
     */
    private function content_section(array $post)
    {

        $content = $this->duplicate($post['contentImage'], $post['newImagePath']);
        $link = constant::rootDir() . '/';
        $logo_link = $this->duplicate($link. self::ROOT_IMG_PATH . '/' . $_SESSION['savedLogo'], $link.$post['newImagePath']);
        die();
        $source = $this->water_mark()->addLogoToImage($content, $link . self::ROOT_IMG_PATH . '/' . $logo_link, 'bl');
        $font_array = [
            'px' => 70,
            'py' => 140,
            'file' => $post['font'],
            'color' => [255, 255, 255],
            'size' => 50,
            'line_height' => 150
        ];

        $imageArray = [
            'newImagePath' => $content
        ];
        $string = mb_strtoupper($post['title']);

        return $this->text_to_image($source, $imageArray, $string, $font_array);
    }
    /**
     * Add text on image
     * @param array $post
     * keys include
     *  string      'defaultImage' - This is a link to the template image
     * 
     *  string      'text' - Text for design 
     * 
     *  string      'newImagePath' - (link)
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

        $defaultImage = $post['defaultImage'];

        //SET THE NEW DESIGN TO A NEW PATH
        $newImagePath = $this->create_image()->createBlankImage($defaultImage, $post['newImagePath']);


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

        $font_array['file'] = $post['font'];

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
