<?php
 
$ref = $_REQUEST['stripeToken'];

$headers = array('Content-type: application/x-www-form-urlencoded','Authorization: Bearer sk_test_51IxHxkG8oNMh0OfzsVK0WEPFtbD4b45rYazYObtiJDZjdYRA2VcWdgb2mgudl2LykrkZI7IVf2akt0J29mHtFiaS00QgM7sXBe');

$fields = array(
    'amount' =>  $_REQUEST['amount'],
    'currency' =>  $_REQUEST['currency'],
    'description' =>  $_REQUEST['description'],
    'source' => $ref
);

// var_dump($fields);
$handle = curl_init();
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
curl_setopt($handle, CURLOPT_URL, 'https://api.stripe.com/v1/charges');
curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($handle);
$statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
curl_close($handle);
echo $result;
// $json = json_decode($result);
// echo $json;