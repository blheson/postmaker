<?php
//    header("Content-type: image/png");

use claviska\SimpleImage;

$sourceImageType="assets/images/card-greeting.png";
// function getImage(){
//     $string = $_GET['text'];
//     $im     = imagecreatefrompng($sourceImageType);
//     $orange = imagecolorallocate($im, 220, 210, 60);
//     $px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
//     imagestring($im, 3, $px, 9, $string, $orange);
//     $image = imagepng($im);
//     imagedestroy($im);
//     return $image;
// } 
// $newImage = getImage();
include_once "system/vendor/claviska/simpleimage/src/claviska/SimpleImage.php";
$image = new SimpleImage($sourceImageType);
var_dump($image);
?>
<main>
<div>
    <img src="<?=$newImage;?>" alt="image">
</div>
</main>
