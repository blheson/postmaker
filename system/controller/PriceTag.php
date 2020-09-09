<?php
namespace Controller\Template\Square;

require 'Image.php';

use Controller\Common\Image as image;

class PriceTag extends image{
        /**
     * Controller for price tag design
     */
    public function price_tag($post){
        $default_image = $post['design_template'];
        $product = $_FILES['product'];
        $link = [];
        $link['short'] = 'render/';
        $link['long'] = '../assets/images/' . $link['short'];

        //set cordinate of logo
        $cord = $post['pos'] ?? 'tl';
      $prepped_image1 = $this->water_mark()->logo_on_product($cord,$post['product_details'], $post['logo_details']);
      $prepped_image2 = $this->water_mark()->logo_on_product($cord,$post['product_details'], $post['logo_details']);


        // //SET THE NEW DESIGN TO A NEW PATH
        // $new_image_path = $this->create_image()->create_blank_image($default_image, $post['new_image_path']);
    }
}