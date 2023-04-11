<?php
// Define the data to insert
$data = array(
    
        'order_id' => 'ORD-123456',
        'user_id' => 'USR-789012',
        'warehouse_id' => 'WH-345678',
        'rider_id' => 'RDR-901234',
        'fridge_id' => 'FRG-567890',
        'operator_id' => 'OPE-234567',
        'order_date' => '2023-04-04 15:30:00',
        'delivery_address' => '123 Main St, Anytown, USA 12345',
        'delivery_instructions' => 'Leave at the front door.',
        'payment_method' => 'Credit Card',
        'payment_status' => 'Completed',
        'order_status' => 'Delivered',
        'total_amount' => '$50.00',
        'delivery_fee' => '$5.00',
        'delivery_time_estimate' => '30 minutes',
        'actual_delivery_time' => '2023-04-04 16:00:00',
        'order_items' => '{
        "item_1": {
        "product_id": "PROD-123",
        "quantity": 2,
        "price": "$20.00",
        "description": "Product 1"
        },
        "item_2": {
        "product_id": "PROD-456",
        "quantity": 1,
        "price": "$10.00",
        "description": "Product 2"
        }
        }',
        'promotion_id' => 'PROMO-789',
        'discount_amount' => '$5.00',
        'customer_rating' => '4.5 stars',
        'refund_status' => 'Pending',
        'refund_reason' => 'Wrong item delivered',
        'order_edit_history' => '{
        "event_1": {
        "timestamp": "2023-04-04 15:35:00",
        "message": "Address updated to 456 Oak St."
        },
        "event_2": {
        "timestamp": "2023-04-04 15:45:00",
        "message": "Item 2 removed from order."
        }
        }',
        'pickup_time' => '2023-04-04 15:45:00',
        'drop_time' => '2023-04-04 16:00:00',
        'delivery_distance' => '3 miles'
        );

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/orders/api_key/insert_user_with_api_key.php');
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
