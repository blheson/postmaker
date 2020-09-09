<?php

namespace Controller\Template\Square;

require 'Image.php';

use Controller\Common\Image as image;

class PriceTag extends image
{
  /**
   * Controller for price tag design
   * @param array $post contains 
   * 
   * design_template
   * 
   * 
   */
  public function price_tag($post)
  {
    $default_image = $post['design_template'];
    // $this->dnd($post);


    //set cordinate of logo
    $cord = $post['pos'] ?? 'tl';

    //set cordinate of asset
    $asset_cord = ['x' => 20, 'y' => 450];



    $prepped_image1 = $this->water_mark()->logo_on_product($post['product_details'], $post['logo_details'], $cord);

    $prepped_image2 = $this->water_mark()->add_logo_to_image($prepped_image1, '../assets/images/templates/circleprice/addon/circle.png', $asset_cord);

    $image_array = [
      'new_image_path' => $prepped_image2,
    ];
    //set font array
    $font_array = [
      'px' => 20,
      'file' => $post['font']
    ];
    $footer = $post['contact'];

    $this->write_to_footer($prepped_image2, $image_array, $footer, $font_array);
$dim = $this->water_mark()->get_image_dimension();
    return $this->text_to_image($prepped_image2, $image_array, $post['price'], $font_array);

    // //SET THE NEW DESIGN TO A NEW PATH
    // $new_image_path = $this->create_image()->create_blank_image($default_image, $post['new_image_path']);
  }
}
