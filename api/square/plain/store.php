<?php
// header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$dir = '../../../';
include $dir . "system/initiate.php";

use Controller\Helper as helper;
use Controller\Template\Square\SquareImage as square;

$squareImage = new square();

if (!isset($_REQUEST['text'])) {
    http_response_code('401');
    echo json_encode(['error' => true, 'message' => 'Bad Request']);
    exit();
}

if (isset($_REQUEST['text']))
    $imageLink = $squareImage->addDataOnBlankImage($_REQUEST);
if (is_null($imageLink)) {
    echo json_encode(['error' => true, 'message' => $_SESSION['postmakerError']]);
    unset($_SESSION['postmakerError']);
    exit();
}
$imageLink = helper::parseLink($imageLink);

echo json_encode(['error' => false, 'message' => $imageLink]);
