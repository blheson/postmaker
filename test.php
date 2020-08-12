<?php
   header("Content-type: image/png");
   $sourceImageType="assets/images/card-greeting.png";
function getImage(){
    $string = $_GET['text'];
    $im     = imagecreatefrompng($sourceImageType);
    $orange = imagecolorallocate($im, 220, 210, 60);
    $px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
    imagestring($im, 3, $px, 9, $string, $orange);
    $image = imagepng($im);
    imagedestroy($im);
    return $image;
} 
$newImage = getImage();
?>
<main>
<div>
    <img src="<?=$newImage;?>" alt="image">
</div>
</main>
