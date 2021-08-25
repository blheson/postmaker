<?php

namespace Controller\Template;

use Controller\Template\Square as Square;
use Controller\Common\Assets as assets;
use Controller\Constant as constant;

class PriceTag extends Square
{
  /**
   * Controller for price tag design
   * @param array $post contains newImagePath, designTemplate, pos,logo_details,product_details
   * @param int $margin
   * designTemplate
   */
  public function priceTag($post, $margin = 50)
  {
    $defaultImage = $post['designTemplate'];
    $newImagePath = $post['newImagePath'];
    $asset_link = '../assets/images/templates/circleprice/addon/circle.png';

    //set cordinate of logo
    $cord = $post['pos'] ?? 'tl';

    //hold instance of Watermark class
    $watermark = $this->water_mark();

    // upload logo and product
    $logo_product_link = $this->createImage()->logo_and_product_upload($post['product_details'], $post['logo_details'], 90);

    // crop image
    $product_image = $this->imageDimension()->crop($logo_product_link['product']);
  

    //add logo to image
    $prepped_image1 = $watermark->addLogoToImage($product_image, $logo_product_link['logo'], $cord, $margin);


    //get image dimension
    $imageDimension = $watermark->getImageDimension();

    /**
     * @var int price tag y coordinate
     */
    $py = ($imageDimension['height'] / 2) - 70;


    //set cordinate of asset
    $asset_cord = ['x' => $margin, 'y' => $py];

    //add footer rectangle to design
    assets::create_rectangle(
      $prepped_image1,
      [
        0,
        $imageDimension['width'],
        ($imageDimension['height'] - 80),
        $imageDimension['height']
      ],
      [
        0, 0, 0
      ]
    );

    //copy asset into a new path
    $new_asset = $this->createImage()->createBlankImage($asset_link, $newImagePath);


    //add asset to image
    $prepped_image2 = $watermark->addLogoToImage($prepped_image1, $new_asset, $asset_cord);


    //get logo dimension
    $asset_dimension = $watermark->get_logo_dimension();


    //set image details
    $imageArray = [
      'newImagePath' => $prepped_image2,
    ];


    //set font array
    $font_size = 20;
    $font_array = [
      'px' => $margin,
      'py' =>  $imageDimension['height'] - ($margin + $font_size),
      'size' => $font_size,
      'file' => constant::rootDir().$post['font'],
      'width' => 50,
      'color' => [255, 255, 255]
    ];

    //set font array for contact
    $footer = $post['contact'];
    $this->write_to_footer($prepped_image2, $imageArray, $footer, $font_array);

    //set font array for price
    $font_array = [
      'px' => $margin + 50,
      'py' => $py + ($asset_dimension['height'] / 2),
      'size' => 40,
      'file' => constant::rootDir().$post['font'],
      'angle' => 12
    ];

    return $this->text_to_image($prepped_image2, $imageArray, $post['price'], $font_array);

    // SET THE NEW DESIGN TO A NEW PATH
    // $newImagePath = $this->createImage()->createBlankImage($defaultImage, $post['newImagePath']);
  }
}
