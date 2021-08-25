<?php

header('Content-Type: application/json');
$dir = dirname(dirname(__DIR__));
 
include $dir . "system/initiate.php";

use Controller\Template\Slide\FoodSlide as foodslide;
use Controller\Helper as helper;

$slide = new foodslide();
if (!isset($_REQUEST['section'])) {
    http_response_code('401');
    echo json_encode(['error' => true, 'message' => 'Bad Request']);
    exit();
}

$imageLink = $slide->process($_REQUEST);
if (is_null($imageLink)) {
    echo json_encode(['error' => true, 'message' => $_SESSION['postmakerError']]);
    unset($_SESSION['postmakerError']);
    exit();
}
$imageLink = helper::parseLink($imageLink);

echo json_encode(['error' => false, 'message' => $imageLink]);
