<?php
require '../../vendor/autoload.php';

// Connect to MongoDB client
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
    // Return error for connection timeout
    http_response_code(503);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Failed to connect to MongoDB'));
    exit();
}

// Select database and collection
$database = $mongoClient->selectDatabase("storage_data");
$collection = $database->selectCollection("analytics");

// Get the user's API key from the request headers
if (!isset($_SERVER['HTTP_X_API_KEY'])) {
    // Return error for missing API key
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Missing API key'));
    exit();
}

$user_api_key = filter_input(INPUT_SERVER, 'HTTP_X_API_KEY', FILTER_SANITIZE_STRING);

// Check if the user has access to this API
$access_collection = $database->selectCollection("user_access");
$access_filter = ['api_key' => $user_api_key];
$access_document = $access_collection->findOne($access_filter);

if (!$access_document) {
    // Return error for unauthorized access
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized access'));
    exit();
}




// Check if the permission field exists and is equal to 1
if (isset($access_document['permission']) && $access_document['permission'] === 1 || $access_document['permission'] === 2 || $access_document['permission'] === 3 || $access_document['permission'] === 4) {
   

    // Define the API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if ID parameter is set and valid
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-fA-F]{24}$/")));
    if (!$id) {
        // Return error for invalid ID parameter
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid ID parameter'));
        exit();
    }
    // Construct filter
    $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
    // Find the document with the given ID
    try {
        $document = $collection->findOne($filter);
        if (!$document) {
            // Return error for document not found
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Document not found'));
            exit();
        }
        // Output the document as JSON
        header('Content-Type: application/json');
        echo json_encode($document);
    } catch (MongoDB\Driver\Exception\Exception $e) {
        // Return error for database error
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
        exit();
    }
} else {
    // Return error for unsupported HTTP method
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unsupported HTTP method'));
    exit();
}
    
    } else {
        // User does not have full access
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Unauthorized access'));
        exit();
      }
    ?>
