<?php

namespace Controller\Template\Square;

require 'Square.php';

use Controller\Template\Square as Square;

class PriceTag extends Square
{
  /**
   * Controller for price tag design
   * @param array $post contains 
   * 
   * design_template
   * 
   * 
   */
  public function price_tag($post, $margin = 40)
  {
    $default_image = $post['design_template'];
    $new_image_path = $post['new_image_path'];
    $asset_link = '../assets/images/templates/circleprice/addon/circle.png';

    //set cordinate of logo
    $cord = $post['pos'] ?? 'tl';

    //hold instance of Watermark class
    $watermark = $this->water_mark();

    // upload logo and product
    $logo_product_link = $this->create_image()->logo_and_product_upload($post['product_details'], $post['logo_details']);

    // crop image
    $product_image = $this->image_dimension()->crop($logo_product_link['product']);

    //add logo to image
    $prepped_image1 = $watermark->add_logo_to_image( $product_image, $logo_product_link['logo'], $cord, $margin);
    // imagescale()

    //get image dimension
    $image_dimension = $watermark->get_image_dimension();

    /**
     * @var int price tag y coordinate
     */
    $py = ($image_dimension['height'] / 2) - 70;


    //set cordinate of asset
    $asset_cord = ['x' => $margin, 'y' => $py];


    //copy asset into a new path
    $new_asset = $this->create_image()->create_blank_image($asset_link, $new_image_path);


    //add asset to image
    $prepped_image2 = $watermark->add_logo_to_image($prepped_image1, $new_asset, $asset_cord);


    //get logo dimension
    $asset_dimension = $watermark->get_logo_dimension();


    //set image details
    $image_array = [
      'new_image_path' => $prepped_image2,
    ];


    //set font array
    $font_size = 20;
    $font_array = [
      'px' => $margin,
      'py' =>  $image_dimension['height'] - ($margin + $font_size),
      'size'=> $font_size,
      'file' => $post['font'],
      'width' => 50,
      'color' => [255,255,255]
    ];

    //set font array for contact
    $footer = $post['contact'];
    $this->write_to_footer($prepped_image2, $image_array, $footer, $font_array);
    
    //set font array for price
    $font_array = [
      'px' => $margin + 50,
      'py' => $py + ($asset_dimension['height'] / 2),
      'size' => 40,
      'file' => $post['font'],
      'angle' => 12
    ];
    
    return $this->text_to_image($prepped_image2, $image_array, $post['price'], $font_array);

    // SET THE NEW DESIGN TO A NEW PATH
    // $new_image_path = $this->create_image()->create_blank_image($default_image, $post['new_image_path']);
  }
}
