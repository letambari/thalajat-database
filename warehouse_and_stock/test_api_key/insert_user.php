<?php
// Define the data to insert
$data = array(
  'product_id' => 'ABC123',
'stock_level' => 50,
'restock_threshold' => 10,
'last_restock_date' => '2022-03-01',
'warehouse_id' => 'WH001',
'warehouse_type' => 'main',
'warehouse_name' => 'Main Warehouse 1',
'address' => '123 Main St, Anytown, USA',
'latitude' => 37.7749,
'longitude' => -122.4194,
'capacity' => 1000,
'current_stock_level' => 650,
'creation_date' => '2022-02-01',
'last_update' => '2022-04-01',
'is_active' => true,
'temperature_control' => '20 degrees Celsius',
'humidity_control' => '50%',
'temperature_control_fridge' => '-5 degrees Celsius',
'humidity_control_fridge' => '40%',
'temperature_control_freezer' => '-20 degrees Celsius',
'humidity_control_freezer' => '30%',
'incident_log' => [],
'restock_lead_time' => '2 days'
);

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/warehouse_and_stock/api_key/insert_user_with_api_key.php');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'X-API-Key: DECODE-ANIMATOR',
    'Content-Length: ' . strlen($jsonData)),
     
);

// Execute the cURL request and get the response
$response = curl_exec($ch);
curl_close($ch);

// Decode the response from JSON format and print the status message
$decodedResponse = json_decode($response, true);
if ($decodedResponse["success"]) {
    echo "Inserted successfully. Message: " . $decodedResponse["message"];
} else {
    echo "Failed to insert document. Error: " . $decodedResponse["message"];
}
?>
