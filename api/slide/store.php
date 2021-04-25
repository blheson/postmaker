<?php

header('Content-Type: application/json');
$dir = '../../';
 
 
include $dir . "system/initiate.php";

use Controller\Template\Slide\FoodSlide as foodslide;
use Controller\Helper as helper;

$slide = new foodslide();
if (!isset($_REQUEST['section'])) {
    http_response_code('401');
    echo json_encode(['error' => true, 'message' => 'Bad Request']);
    die();
}

$imageLink = $slide->process($_REQUEST);
if (is_null($imageLink)) {
    echo json_encode(['error' => true, 'message' => $_SESSION['postmakerError']]);
    unset($_SESSION['postmakerError']);
    die();
}
$imageLink = helper::parseLink($imageLink);

echo json_encode(['error' => false, 'message' => $imageLink]);
