<?php

$curl = curl_init();
$id = '6451257404d858a20405d604';
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/mongo-site/recipe/api_key/get_single_users_with_api_key.php?id='.$id.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'X-API-Key: DECODE-ANIMATOR'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
