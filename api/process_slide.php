<?php

header('Content-Type: application/json');
$dir = '../';
include $dir . "system/initiate.php";

use Controller\Template\Slide\FoodSlide as foodslide;
use Controller\Helper as helper;

$slide = new foodslide();
if (!isset($_REQUEST['section'])){
    http_response_code('401');
    echo json_encode(['error'=>'true','message'=>'Bad Request']);
}
// echo json_encode($_REQUEST);
// echo json_encode($_FILES['logo']);

    $image_link = $slide->process($_REQUEST);

    $image_link = helper::parseLink($image_link);
echo json_encode(['error'=>'false','message'=>$image_link]);
