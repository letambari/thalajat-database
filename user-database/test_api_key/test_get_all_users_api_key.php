<?php

$curl = curl_init();
$id = '642ce137904f2d2681012838';
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/thalajat-database/user-database/api_key/get_all_users_api_key.php',
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
