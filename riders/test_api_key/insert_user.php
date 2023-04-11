<?php
// Define the data to insert
$data = array(
    
    "rider_id"=> 12345,
    "first_name"=> "John",
    "last_name"=> "Doe",
    "email"=> "johndoe@email.com",
    "phone_number"=> "555-123-4567",
    "vehicle_type"=> "bike",
    "vehicle_registration"=> "ABC-123",
    "license_number"=> "123456789",
    "availability_status"=> True,
    "current_location"=> "{
        'latitude': 37.7749,
        'longitude': -122.4194
    }",
    "start_date"=> "2022-01-01",
    "end_date"=> "None",
    "total_deliveries"=> 50,
    "performance_rating"=> 4.8,
    "last_activity"=> "2023-04-03 15=>30=>00",
    "emergency_contact"=> "{
        'name': 'Jane Doe',
        'phone_number'=> '555-987-6543',
        'relationship'=> 'Spouse'
    }",
    "insurance"=> "{
        'provider'=> 'Geico',
        'policy_number'=> '1234567890',
        'expiration_date'=> '2022-12-31'
    }",
    "training_completion_date"=> "2022-02-01",
    "background_check"=> "{
        'status'=> 'Completed',
        'date'=> '2022-01-15'
    }",
    "preferred_working_hours"=> "9am - 5pm",
    "delivery_zones"=> "{
        'Zone A', 'Zone B'
    }",
    "equipment"=> "{
        'Helmet', 'Delivery bag'
    }",
    "language_skills"=> "{
        'English', 'Spanish'
    }",
    "notes"=> "Has exceptional customer service skills.",
    "hourly_rate"=> 15.0,
    "commission_rate"=> 0.25,
    "total_earnings"=> 1250.0,
    "average_delivery_time"=> "30 minutes",
    "on_time_delivery_rate"=> "90%",
    "late_delivery_count"=> 5,
    "customer_rating_average"=> 4.5,
    "customer_compliments"=> 10,
    "customer_complaints"=> 2,
    "uniform_size"=> "Medium",
    "bank_name"=> "Chase Bank",
    "training_completion_date"=> "2022-03-30",
    "background_check_status"=> "completed",
    "background_check_date"=> "2022-03-25",
    "preferred_working_hours"=> "{'9am-5pm', '5pm-11pm'}",
    "delivery_zones"=> "{
        'Downtown', 'East End', 'West End'
        }",
    "equipment"=> "{'Helmet', 'Delivery Bag', 'GPS device'}",
    "language_skills"=> "{'English', 'Spanish', 'French'}",
    "notes"=> "Has a valid driver's license and own transportation.",
    "hourly_rate"=> 20.0,
    "commission_rate"=> 2.5,
    "total_earnings"=> 450.0,
    "average_delivery_time"=> "30 minutes",
    "on_time_delivery_rate"=> 0.9,
    "late_delivery_count"=> 2,
    "customer_rating_average"=> 4.8,
    "customer_compliments"=> 10,
    "customer_complaints"=> 1,
    "uniform_size"=> "Medium",
    "bank_name"=> "Chase",
    "bank_account_number"=> "1234567890",
    "bank_routing_number"=> "111000614",
    "tax_id"=> "123-45-6789",
    "work_status"=> "Citizen",
    "contract_type"=> "Full-time",
    "contract_start_date"=> "2022-03-01",
    "contract_end_date"=> "2022-09-01",
    "probation_period_end_date"=> "2022-04-01",
    "days_off"=> "{'Saturday', 'Sunday'}",
    "vacation_days_used"=> 2,
    "vacation_days_remaining"=> 8,
    "sick_days_used"=> 1,
    "sick_days_remaining"=> 4,
    "overtime_hours"=> 10,
    "bonus_earned"=> 50.0,
    "warnings_received"=> 0,
    "last_warning_date"=> null,
    "termination_reason"=> null,
    "reference_check_status"=> "completed",
    "medical_certificate_status"=> "valid",
    "medical_certificate_expiration_date"=> "2023-02-01",
    "health_conditions"=> "Allergic to peanuts",
    "emergency_procedures"=> "Epinephrine auto-injector on hand",
    "preferred_communication_method"=> "Phone",
    "scheduling_preferences"=> "Available to work any shift",
    "performance_review_date"=> "2022-09-01",
    "performance_review_notes"=> "Exceeds expectations in customer service.",
    "recognition_awards"=> "{'Employee of the Month'}",
    "skills_and_certifications"=> "{'Drivers License'}"

   
        );

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/riders/api_key/insert_user_with_api_key.php');
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
