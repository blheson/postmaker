<?php
// Create a 200 x 200 image
// $canvas = imagecreatetruecolor(200, 200);

// // Allocate colors
// $pink = imagecolorallocate($canvas, 255, 105, 180);
// $white = imagecolorallocate($canvas, 255, 255, 255);
// $green = imagecolorallocate($canvas, 132, 135, 28);
// $white = imagecolorallocate($canvas, 255, 255, 255);
// $white = imagecolorallocate($canvas, 0, 0, 0);
// imagefill($canvas,0,0,$pink);
// imagestring($canvas,12,10,10,'This is a bell', $white);
// // Draw three rectangles each with its own color
// // imagerectangle($canvas, 50, 50, 150, 150, $pink);
// // imagerectangle($canvas, 45, 60, 120, 100, $white);
// // imagerectangle($canvas, 100, 120, 75, 160, $green);
// // imagerectangle($canvas, 0, 180, 198, 198, $white);
// imagefilledrectangle($canvas, 0, 180, 199, 199, $white);

// // Output and free from memory
// header('Content-Type: image/jpeg');

// imagejpeg($canvas);
// imagedestroy($canvas);

$newImagePath = 'C:/xampp/htdocs/postmaker/assets/images/render/postmaker_1679091c5a880faf6fb5e6087eb1b2dc.png';
 
echo substr($newImagePath,strpos($newImagePath,'/assets/images/'));