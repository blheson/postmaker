<?php
//    header("Content-type: image/png");

// use claviska\SimpleImage;

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
// include_once "system/vendor/claviska/simpleimage/src/claviska/SimpleImage.php";
// $image = new SimpleImage($sourceImageType);
// var_dump($image);
if (isset($_GET['see'])) {
    $sourceImageType = "assets/images/1.jpg"; #~
    $new_source = "assets/images/2.jpg";#~
    copy($sourceImageType, $new_source); 
    $im     = imagecreatefromjpeg($new_source);
    // $im     = imagecreatefromjpeg($new_source);

    $new_res = imagescale($im,1000);
  
    $r = imagecrop($im,['x'=>0,'y'=>0,'width'=>510,'height'=>510]);
    // imagecopyresized($new, $im, 0, 0, 0, 0, 1000, 1000, 907, 510);
    imagejpeg($r, $new_source);   
}
?>
<main>
    <?php
    if (isset($_GET['see'])) :
    ?>
        <div>
            <img src="<?= $new_source; ?>" alt="image" width="400px">
        </div>
    <?php
    endif;
    ?>
    <a href="?see">Load</a>
    <br>
    <a href="test.php">back</a>
</main>