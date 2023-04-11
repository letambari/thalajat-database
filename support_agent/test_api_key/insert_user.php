<?php
// Define the data to insert
$data = array(
    
    "support_ticket_id"=> 123456789,
    "user_id"=> 987654321,
    "rider_id"=> null,
    "order_id"=> 456789012,
    "support_agent_id"=> 555444333,
    "date_created"=> "2023-04-01T10=>30=>00Z",
    "date_resolved"=> "2023-04-02T15=>45=>00Z",
    "issue_category"=> "delivery",
    "issue_description"=> "My order was delivered to the wrong address.",
    "priority_level"=> "high",
    "status"=> "resolved",
    "agent_notes"=> "Contacted the delivery person and arranged for the order to be delivered to the correct address.",
    "resolution_details"=> "The order was successfully delivered to the correct address.",
    "customer_feedback"=> "I appreciate the quick resolution of my issue.",
    "support_channel"=> "email",
    "response_time"=> "2 hours",
    "resolution_time"=> "1 day 5 hours 15 minutes",
    "customer_satisfaction_rating"=> 5,
    "escalation_status"=> "not escalated",
    "escalated_to"=> null,
    "follow_up_date"=> "2023-04-10T10=>00=>00Z",
    "follow_up_outcome"=> null,
    "refund_amount"=> 10.99,
    "compensation"=> null,
    "attachments"=> "{
      'https=>//example.com/screenshot.png',
      'https=>//example.com/document.pdf'
    }",
    "internal_feedback"=> "The delivery person may need more training on how to handle deliveries.",
    "related_tickets"=> "{
      987654321,
      234567890
    }",
    "tags"=> "{
      'delivery',
      'resolved'
    }",
    "assigned_group"=> "Customer Support"
 
        );

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/support_agent/api_key/insert_user_with_api_key.php');
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
