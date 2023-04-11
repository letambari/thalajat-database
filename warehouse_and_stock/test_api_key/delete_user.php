<?php
// API endpoint URL
//$url = "http://localhost/mongo-site/delete_user.php/642bc718904f2d2681012824";
$id = '6434676a5924ce22c80009e6';
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/mongo-site/warehouse_and_stock/api_key/delete_user.php?id='.$id.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'X-API-Key: DECODE-ANIMATOR'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
