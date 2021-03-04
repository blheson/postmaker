<?php

header('Content-Type: application/json');
if (!isset($_REQUEST['section'])){
    http_response_code('401');
    echo json_encode(['status'=>401,'message'=>'Bad Request']);
}
echo json_encode($_REQUEST);
echo json_encode($_FILES['logo']);

