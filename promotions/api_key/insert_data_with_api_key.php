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
      'promotion_id' => ['required' => true, 'integer' => true],
      'promotion_name' => ['required' => true, 'string' => true],
      'promotion_type' => ['required' => true, 'string' => true],
      'discount_value' => ['required' => true, 'integer' => true],
      'start_date' => ['required' => true, 'date_format' => 'Y-m-d'],
      'end_date' => ['required' => true, 'date_format' => 'Y-m-d'],
      'min_purchase_amount' => ['required' => true, 'integer' => true],
      'max_discount_amount' => ['required' => true, 'integer' => true],
      'eligible_products' => ['array' => true],
      'excluded_products' => ['array' => true],
      'customer_segment' => ['required' => true, 'string' => true],
      'promotion_channel' => ['required' => true, 'string' => true],
      'coupon_code' => ['required' => true, 'string' => true],
      'redemption_limit' => ['required' => true, 'integer' => true],
      'redemption_count' => ['required' => true, 'integer' => true],
      'status' => ['required' => true, 'string' => true],
      'created_at' => ['required' => true, 'date_format' => 'Y-m-d H:i:s'],
      'updated_at' => ['required' => true, 'date_format' => 'Y-m-d H:i:s'],
        
        
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
