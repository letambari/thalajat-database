<?php
// Define the data to insert
$data = array(
  
        "promotion_id"=> 1234,
        "promotion_name"=> "Spring Sale",
        "promotion_type"=> "percentage",
        "discount_value"=> 20,
        "start_date"=> "2023-04-01",
        "end_date"=> "2023-04-30",
        "min_purchase_amount"=> 50.00,
        "max_discount_amount"=> null,
        "eligible_products"=> "{
            101, 102, 103
        }",
        "excluded_products"=> "{
            
        }",
        "customer_segment"=> "all_customers",
        "promotion_channel"=> "email",
        "coupon_code"=> "SPRING20",
        "redemption_limit"=> 1000,
        "redemption_count"=> 500,
        "status"=> "active",
        "created_at"=> "2023-03-31T14=>30=>00Z",
        "updated_at"=> "2023-04-02T09=>15=>00Z",
        "discount_amount"=> 10.50,
    "payment_method"=> "credit_card",
    "shipping_method"=> "express_shipping",
    "user_agent"=> "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36",
    "language_preference"=> "en",
    "customer_segment"=> "high-spender",
    "campaign_id"=> 1234,
    "campaign_channel"=> "email",
    "campaign_effectiveness"=> 0.05,
    "user_behavior_flow"=> "homepage > product page > add to cart > checkout",
    "custom_dimensions"=> '{
        "dimension1"=> "value1", "dimension2"=> "value2"
    }',
    "abandoned_cart"=> false,
    "cart_recovery_status"=> "sent_email",
    "video_id"=> 5678,
    "video_playback_position"=> 120,
    "video_engagement_rate"=> 0.75,
    "form_id"=> 9012,
    "form_completion_rate"=> 0.8,
    "error_message"=> "Please enter a valid email address",
    "customer_lifetime_value"=> 5000,
    "churn_probability"=> 0.2,
    "conversion_rate"=> 0.05,
    "bounce_rate"=> 0.6,
    "average_session_duration"=> 120,
    "exit_rate"=> 0.7,
    "new_vs_returning_users"=> '{
        "new"=> 500, "returning"=> 1000
    }',
    "time_since_last_visit"=> "3 days",
    "traffic_source"=> '{
        "organic_search"=> 500,
        "paid_search"=> 750,
        "referral"=> 300,
        "social"=> 400,
        "direct"=> 1000
    }',
    "clickthrough_rate"=> '{
        "link_1"=> 0.1,
        "link_2"=> 0.05,
        "link_3"=> 0.03
    }',
    "impressions"=> '{
        "ad_1"=> 10000,
        "ad_2"=> 20000,
        "ad_3"=> 5000
    }',
    "cost_per_click"=> '{
        "ad_campaign_1"=> 0.5,
        "ad_campaign_2"=> 0.3,
        "ad_campaign_3"=> 0.8
    }',
    "average_order_value"=> '{
        "Q1_2023"=> 200,
        "Q2_2023"=> 220,
        "Q3_2023"=> 240
    }',
    "repeat_purchase_rate"=> '{
        "Q1_2023"=> 0.2,
        "Q2_2023"=> 0.25,
        "Q3_2023"=> 0.3
    }',
    "items_per_order"=> '{
        "Q1_2023"=> 2.5,
        "Q2_2023"=> 3,
        "Q3_2023"=> 3.5
    }',
    "revenue_per_user"=> '{
        "Q1_2023"=> 400,
        "Q2_2023"=> 450,
        "Q3_2023"=> 500
    }',
    "cost_per_acquisition"=> '{
        "marketing_channel_1"=> 100,
        "marketing_channel_2"=> 120,
        "marketing_channel_3"=> 150
    }',
    "referral_count"=> '{
        "user_1"=> 3,
        "user_2"=> 1,
        "user_3"=> 5
    }',
    "social_shares"=> '{
        "content_1"=> 1000,
        "content_2"=> 2000,
        "content_3"=> 500
    }',
    "email_open_rate"=> '{
        "email_campaign_1"=> 0.3,
        "email_campaign_2"=> 0.25,
        "email_campaign_3"=> 0.2
    }',
    "email_click_rate"=> '{
        "email_campaign_1"=> 0.05,
        "email_campaign_2"=> 0.03,
        "email_campaign_3"=> 0.02
    }',
    "unsubscribe_rate"=> '{
        "email_campaign_1"=> 0.1,
        "email_campaign_2"=> 0.15,
        "email_campaign_3"=> 0.12
    }',
    
    "push_notification_opt_in_rate"=> 0.25,
    "push_notification_open_rate"=> 0.4,
    "app_installations"=> 1000,
    "app_uninstallations"=> 50,
    "app_rating"=> 4.2,
    "page_load_time"=> 2.5,
    "navigation_path"=> '{
        "home", "products", "product details", "add to cart", "checkout"
    }',
    "conversion_funnel_steps"=> '{
        "viewed product", "added to cart", "entered shipping information", "completed purchase"
    }',
    "abandonment_rate"=> 0.6,
    "revenue_per_visit"=> 10.5,
    "cart_to_detail_rate"=> 0.4,
    "product_views_per_session"=> 8,
    "wishlist_additions"=> 50

    
        );

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/analytics/api_key/insert_user_with_api_key.php');
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
