<?php

header('Content-Type: application/json');
$dir = dirname(dirname(__DIR__));
 
include $dir . DIRECTORY_SEPARATOR."system/initiate.php";


use Controller\Helper as helper;

if (!isset($_REQUEST['src'])) {
    http_response_code('401');
    echo json_encode(['error' => true, 'message' => 'Bad Request']);
    die();
}

$result = helper::deleteImage($_REQUEST['src']);
if ($result) {
    echo json_encode(['error' => false, 'message' => 'Image was deleted successfully']);
 
    die();
}
 
echo json_encode(['error' => true, 'message' => 'Image was not deleted']);