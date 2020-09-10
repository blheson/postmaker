<?php

namespace Controller\Template\Square;

require 'Square.php';

use Controller\Template\Square as square;

class PriceTag extends square
{
  /**
   * Controller for price tag design
   * @param array $post contains 
   * 
   * design_template
   * 
   * 
   */
  public function price_tag($post, $margin = 70)
  {
    $default_image = $post['design_template'];
    $new_image_path = $post['new_image_path'];
    $asset_link = '../assets/images/templates/circleprice/addon/circle.png';

    //set cordinate of logo
    $cord = $post['pos'] ?? 'tl';

    //hold instance of Watermark class
    $watermark = $this->water_mark();

    // upload amd add logo to image
    $prepped_image1 = $watermark->logo_on_product($post['product_details'], $post['logo_details'], $cord, 'square');

    // imagescale()

    //get image dimension
    $image_dimension = $watermark->get_image_dimension();


    /**
     * @var int price tag y coordinate
     */
    $py = ($image_dimension['height'] / 2) - 90;


    //set cordinate of asset
    $asset_cord = ['x' => $margin - 30, 'y' => $py];

    //copy asset into a new path
    $new_asset = $this->create_image()->create_blank_image($asset_link, $new_image_path);


    //add asset to imag
    $prepped_image2 = $watermark->add_logo_to_image($prepped_image1, $new_asset, $asset_cord);


    //get logo dimension
    $asset_dimension = $watermark->get_logo_dimension();

    //set image details
    $image_array = [
      'new_image_path' => $prepped_image2,
    ];

    //set font array
    $font_array = [
      'px' => $margin,
      'py' =>  $image_dimension['height'] - $margin,
      'file' => $post['font'],
      'width' => 50
    ];


    //set font array for contact
    $footer = $post['contact'];
    $this->write_to_footer($prepped_image2, $image_array, $footer, $font_array);


    //set font array for price
    $font_array = [
      'px' => $margin + 20,
      'py' => $py + ($asset_dimension['height'] / 2),
      'size' => 50,
      'file' => $post['font'],
      'angle' => 12
    ];
    return $this->text_to_image($prepped_image2, $image_array, $post['price'], $font_array);

    // //SET THE NEW DESIGN TO A NEW PATH
    // $new_image_path = $this->create_image()->create_blank_image($default_image, $post['new_image_path']);
  }
}
