<?php
    class Watermark{
        public function add_logo_to_image($dst,$source,$cord){
                extract($cord);
                $file = pathinfo($dst);
                
                switch($file['extension']){
                    case 'png':
                        $im = imagecreatefrompng($dst);
                        break;
                    case 'jpeg':
                        $im = imagecreatefromjpeg($dst);
                        break;  
                    case 'gif':
                        $im = imagecreatefromgif($dst);
                        break;                      
                }
            // First we create our stamp image manually from GD
         
                $stamp = imagecreatefrompng($source);

            // Set the margins for the stamp and get the height/width of the stamp image
                $marge_right = $right;
                $marge_bottom = $bottom;
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);
            // Merge the stamp onto our photo with an opacity of 50%

            // imagecopymerge($im, $stamp, 0, 0, 0, 0, imagesx($stamp), imagesy($stamp), 80);
                imagecopy($im, $stamp, 0, 0, 0, 0, imagesx($stamp), imagesy($stamp));


            // Save the image to file and free memory
            imagepng($im, $dir.'assets/images/ll.png');
            imagedestroy($im);
        }
    }
?>