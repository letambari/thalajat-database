<?php
// Load the MongoDB library
require '../../vendor/autoload.php';

// Set headers to allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// Create a MongoDB client instance
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection for user access
$dbAccess = $mongoClient->selectDatabase("storage_data");
$collectionAccess = $dbAccess->selectCollection("user_access");

// Check for API key
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;

if (!$apiKey) {
    // Return error for missing API key
    returnError('Missing API key', 400);
}

// Find the document with the given API key
$accessFilter = ['api_key' => $apiKey];
$accessDocument = $collectionAccess->findOne($accessFilter);

if (!$accessDocument) {
    // Return error for unauthorized access
    returnError('Unauthorized access', 401);
}

// Check if the permission field exists and is equal to 1 or 2
$permission = $accessDocument['permission'] ?? null;

if (!in_array($permission, [1, 2])) {
    // User does not have full access
    returnError('Unauthorized access or invalid permission level', 401);
}

// Define the API endpoint to insert data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Validate the data
    $validationRules = [
        'promotion_id' => [
            'required' => true,
            'integer' => true,
        ],
        'promotion_name' => [
            'required' => true,
            'string' => true,
            'max_length' => 255,
        ],
        'promotion_type' => [
            'required' => true,
            'in' => ['percentage', 'fixed'],
            'string' => true,
        ],
        'discount_value' => [
            'required' => true,
            'numeric' => true,
            'min' => 0,
        ],
        'start_date' => [
            'required' => true,
            'date_format' => 'Y-m-d',
        ],
        'end_date' => [
            'required' => true,
            'date_format' => 'Y-m-d',
        ],
        'min_purchase_amount' => [
            'required' => true,
            'numeric' => true,
            'min' => 0,
        ],
        'max_discount_amount' => [
            'numeric' => true,
            'nullable' => true,
            'min' => 0,
        ],
        'eligible_products' => [
            'array' => true,
        ],
        'excluded_products' => [
            'array' => true,
        ],
        'customer_segment' => [
            'required' => true,
            'in' => ['all_customers', 'high-spender', 'loyal_customer'],
        ],
        'promotion_channel' => [
            'required' => true,
            'in' => ['email', 'social_media', 'website'],
        ],
        'coupon_code' => [
            'required' => true,
            'string' => true,
            'max_length' => 255,
        ],
        'redemption_limit' => [
            'required' => true,
            'integer' => true,
            'min' => 0,
        ],
        'redemption_count' => [
            'required' => true,
            'integer' => true,
            'min' => 0,
        ],
        'status' => [
            'required' => true,
            'in' => ['active', 'inactive'],
        ],
        'created_at' => [
            'required' => true,
            'date_format' => 'Y-m-d\TH:i:s\Z',
        ],
        'updated_at' => [
            'required' => true,
            'date_format' => 'Y-m-d\TH:i:s\Z',
        ],
        "discount_amount" => [
            "required" => true,
            "numeric" => true,
        ],
        "payment_method" => [
            "required" => true,
            "in" => ["credit_card", "paypal", "bank_transfer"],
        ],
        "shipping_method" => [
            "required" => true,
            "in" => ["express_shipping", "standard_shipping", "pickup"],
        ],
        "user_agent" => [
            "required" => true,
            "string" => true,
        ],
        "language_preference" => [
            "required" => true,
            "string" => true,
        ],
        "customer_segment" => [
            "required" => true,
            "in" => ["high-spender", "regular", "low-spender"],
        ],
        "campaign_id" => [
            "required" => true,
            "integer" => true,
        ],
        "campaign_channel" => [
            "required" => true,
            "in" => ["email", "social_media", "paid_search"],
        ],
        "campaign_effectiveness" => [
            "required" => true,
            "numeric" => true,
        ],
        "user_behavior_flow" => [
            "required" => true,
            "string" => true,
        ],
        "custom_dimensions" => [
            "array" => true,
        ],
        "abandoned_cart" => [
            "required" => true,
            "boolean" => true,
        ],
        "cart_recovery_status" => [
            "required" => true,
            "in" => ["sent_email", "called_customer", "automated_sms"],
        ],
        "video_id" => [
            "required" => true,
            "integer" => true,
        ],
        "video_playback_position" => [
            "required" => true,
            "integer" => true,
        ],
        "video_engagement_rate" => [
            "required" => true,
            "numeric" => true,
        ],
        "form_id" => [
            "required" => true,
            "integer" => true,
        ],
        "form_completion_rate" => [
            "required" => true,
            "numeric" => true,
        ],
        "error_message" => [
            "required" => true,
            "string" => true,
        ],
        "customer_lifetime_value" => [
            "required" => true,
            "numeric" => true,
        ],
        "churn_probability" => [
            "required" => true,
            "numeric" => true,
        ],
        "conversion_rate" => [
            "required" => true,
            "numeric" => true,
        ],
        "bounce_rate" => [
            "required" => true,
            "numeric" => true,
        ],
        "average_session_duration" => [
            "required" => true,
            "integer" => true,
        ],
        "exit_rate" => [
            "required" => true,
            "numeric" => true,
        ],
        "new_vs_returning_users" => [
            "array" => true,
        ],
        "time_since_last_visit" => [
            "required" => true,
            "string" => true,
        ],
        "traffic_source" => [
            "array" => true,
        ],
        "clickthrough_rate" => [
            "array" => true,
        ],
        "impressions" => [
            "array" => true,
        ],
        "cost_per_click" => [
            "array" => true,
        ],
        "average_order_value" => [
            "array" => true,
        ],
        "repeat_purchase_rate" => [
            "array" => true,
        ],
        "items_per_order" => [
            "array" => true,
        ],
        "revenue_per_user" => [
            "array" => true,
        ],
        "cost_per_acquisition" => [
            "array" => true,
        ],
        "referral_count" => [
            "array" => true,
        ],
        "social_shares" => [
            "array" => true,
        ],
        "email_open_rate" => [
            "array" => true,
        ],
        "email_click_rate" => [
            "array" => true,
        ],
        "unsubscribe_rate" => [
            "array" => true,
        ],
        "push_notification_opt_in_rate" => [
            "numeric" => true,
        ],
        "push_notification_open_rate" => [
            "numeric" => true,
        ],
        "app_installations" => [
            "integer" => true,
        ],
        "app_uninstallations" => [
            "integer" => true,
        ],
        "app_rating" => [
            "numeric" => true,
        ],
        "page_load_time" => [
            "numeric" => true,
        ],
        "navigation_path" => [
            "array" => true,
        ],
        "conversion_funnel_steps" => [
            "array" => true,
        ],
        "abandonment_rate" => [
            "numeric" => true,
        ],
        "revenue_per_visit" => [
            "numeric" => true,
        ],
        "cart_to_detail_rate" => [
            "numeric" => true,
        ],
        "product_views_per_session" => [
            "integer" => true,
        ],
        "wishlist_additions" => [
            "integer" => true,
        ],
        
        
    ];

    // Validate the data
    $errors = validateData($requestData, $validationRules);

    // Check if there are validation errors
    if (!empty($errors)) {
        returnError('Invalid input data', $errors);
        exit();
    }

    // Select the database and collection to insert into
    $db = $mongoClient->selectDatabase("storage_data");
    $collection = $db->selectCollection("analytics");

    // Insert the document into the collection
    $result = $collection->insertOne($requestData);

    // Check if the insertion was successful and send response accordingly
    if ($result->getInsertedCount() == 1) {
        returnResponse('Document inserted successfully');
    } else {
        returnError('Failed to insert document', 500);
    }
}

// Data is valid, continue processing

/**
 * Validate the data against the provided rules.
 *
 * @param array $data The data to be validated.
 * @param array $rules The validation rules.
 * @return array The validation errors, if any.
 */
function validateDateFormat($dateString, $format)
{
    $dateTime = DateTime::createFromFormat($format, $dateString);
    return $dateTime && $dateTime->format($format) === $dateString;
}

function validateData($data, $rules)
{
    $errors = [];

    foreach ($rules as $field => $fieldRules) {
        foreach ($fieldRules as $rule => $value) {
            switch ($rule) {
                case 'required':
                    if ($value && !isset($data[$field])) {
                        $errors[$field][] = 'The field is required.';
                    }
                    break;
                case 'integer':
                    if (isset($data[$field]) && !is_numeric($data[$field])) {
                        $errors[$field][] = 'The field must be an integer.';
                    }
                    break;
                case 'numeric':
                    if (isset($data[$field]) && !is_numeric($data[$field])) {
                        $errors[$field][] = 'The field must be numeric.';
                    }
                    break;
                case 'string':
                    if (isset($data[$field]) && !is_string($data[$field])) {
                        $errors[$field][] = 'The field must be a string.';
                    }
                    break;
                case 'max_length':
                    if (isset($data[$field]) && strlen($data[$field]) > $value) {
                        $errors[$field][] = 'The field length exceeds the maximum allowed length.';
                    }
                    break;
                case 'min':
                    if (isset($data[$field]) && $data[$field] < $value) {
                        $errors[$field][] = 'The field value is below the minimum allowed value.';
                    }
                    break;
                case 'in':
                    if (isset($data[$field]) && !in_array($data[$field], $value)) {
                        $errors[$field][] = 'The field value is not allowed.';
                    }
                    break;
                case 'date_format':
                    if (isset($data[$field]) && !validateDateFormat($data[$field], $value)) {
                        $errors[$field][] = 'The field does not match the required date format.';
                    }
                    break;
                case 'array':
                    if (isset($data[$field]) && !is_array($data[$field])) {
                        $errors[$field][] = 'The field must be an array.';
                    }
                    break;
                case 'nullable':
                    if (isset($data[$field]) && is_null($data[$field])) {
                        unset($data[$field]);
                    }
                    break;
                // Add validation rules for other data types if needed
            }
        }
    }

    return $errors;
}

/**
 * Return an error response with the specified message and HTTP status code.
 *
 * @param string $message The error message.
 * @param int $statusCode The HTTP status code for the error response.
 */
function returnError($message, $statusCode)
{
    $response = [
        'error' => $message,
    ];

    http_response_code($statusCode);

    echo json_encode($response);
    exit();
}

/**
 * Return a success response with the specified message.
 *
 * @param string $message The success message.
 */
function returnResponse($message)
{
    $response = [
        'message' => $message,
    ];

    http_response_code(200);

    echo json_encode($response);
    exit();
}
?>
