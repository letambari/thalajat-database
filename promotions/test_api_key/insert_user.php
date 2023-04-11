<?php
// Define the data to insert
$data = array(
    
    "promotion_id"=> 1234,
    "promotion_name"=> "Spring Sale",
    "promotion_type"=> "percentage",
    "discount_value"=> 15,
    "start_date"=> "2023-04-01",
    "end_date"=> "2023-04-30",
    "min_purchase_amount"=> 50,
    "max_discount_amount"=> 20,
    "eligible_products"=> "{
      234, 567, 890
    }",
    "excluded_products"=> "{
      345
    }",
    "customer_segment"=> "new_customers",
    "promotion_channel"=> "email",
    "coupon_code"=> "SPRING15",
    "redemption_limit"=> 1000,
    "redemption_count"=> 250,
    "status"=> "active",
    "created_at"=> "2023-03-25 10=>00=>00",
    "updated_at"=> "2023-03-31 16=>30=>00"
        );

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/promotions/api_key/insert_user_with_api_key.php');
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
