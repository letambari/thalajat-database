<?php
// Define the data to insert
$data = array(
    'fridge_Order_id' => '12365',
    'fridge_purchased' => false,
    'fridge_purchase_date' => '2022-01-01',
    'fridge_warranty_enddate' => '2024-01-01',
    'fridge_longatuide' => '123.456',
    'fridge_latitude' => '12.345',
    'fridge_user_map_pin_lon' => '123.456',
    'fridge_user_map_pin_lat' => '12.345',
    'ip_address' => '192.168.0.1',
    'fridge_id' => 'fridge-123',
    'fridge_credits' => 100,
    'user_id' => 'user-123',
    'language' => 'English',
    'country' => 'USA',
    'first_name' => 'Kendrick',
    'last_name' => 'Lamar',
    'email' => 'destiny@example.com',
    'password' => 'mysecretpassword',
    'phone_number' => '1234567890',
    'house_type' => 'Apartment',
    'address' => '123 Main St, Anytown USA',
    'registration_date' => '2022-01-01',
    'profile_image' => 'https://example.com/profiles/johndoe.jpg',
    'payment_method' => 'card',
    'payment_details' => 'encrypted payment details'
);

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/user-database/api_key/insert_user_with_api_key.php');
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
