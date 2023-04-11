<?php

$id = "643452265924ce22c80009e2";
// specify the API endpoint URL
$url = 'http://localhost/mongo-site/user-database/api_key/update_user.php?id='.$id.'';


// specify the data to be updated


//$url = "https://example.com/api/data/123"; // replace with the URL of the data record you want to update
$data = array(
    'fridge_Order_id' => '8888',
    'fridge_purchased' => true,
    'country' => 'poland'
); // replace with the updated data you want to send

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    'X-API-Key: DECODE-ANIMATOR'
));

$response = curl_exec($curl);

if ($response === false) {
    // handle error
} else {
    // handle success
}

curl_close($curl);
