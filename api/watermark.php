<?php

header('Content-Type: application/json');

$dir = '../';
include $dir . "system/initiate.php";
 
use Controller\Template\Watermark as watermark;
use Controller\Helper as helper;

$watermark = new watermark();

 
if (!isset($_REQUEST['type'])) {
    http_response_code('401');
    echo json_encode(['error' => true, 'message' => 'Bad Request']);
    exit();
}
$image_link = $watermark->logoOnProduct($_FILES['file'], $_FILES['logo'], $_REQUEST['logoPosition'], 100);
 
if (is_null($image_link)) {
    echo json_encode(['error' => true, 'message' => $_SESSION['postmakerError']]);
    unset($_SESSION['postmakerError']);
    exit();
}
$image_link = helper::parseLink($image_link);

echo json_encode(['error' => false, 'message' => $image_link]);
