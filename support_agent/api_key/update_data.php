<?php

// Load the MongoDB library
require '../../vendor/autoload.php';

// Set headers to allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// Create a MongoDB client instance
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Select the database and collection for user access
$db_access = $mongoClient->selectDatabase("storage_data");
$collection_access = $db_access->selectCollection("user_access");

// Check for API key
if (!isset($_SERVER['HTTP_X_API_KEY'])) {
    // Return error for missing API key
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Missing API key'));
    exit();
}

// Find the document with the given API key
$filter = ['api_key' => $_SERVER['HTTP_X_API_KEY']];
$document_access = $collection_access->findOne($filter);

if (!$document_access) {
    // Return error for unauthorized access
    header('HTTP/1.1 401 Unauthorized');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized access'));
    exit();
}


// Set up MongoDB connection
$mongo = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongo->storage_data->support_agent;


// Check if the permission field exists and is equal to 1, 2, 3, or 4
if (isset($document_access['permission']) && ($document_access['permission'] === 1)) {
    // User has full access

    // Check if request method is PATCH
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {

        // Get ID from URL parameter
        if (!isset($_GET['id'])) {
            http_response_code(400); // Set response status code to 400 Bad Request
            echo json_encode(array("message" => "Missing ID parameter."));
            exit();
        }

        // Validate and sanitize the ID parameter
        $id = $_GET['id'];
        try {
            $objectId = new MongoDB\BSON\ObjectId($id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            // Return error for invalid ID parameter
            http_response_code(400); // Set response status code to 400 Bad Request
            echo json_encode(array("message" => "Invalid ID parameter."));
            exit();
        }

        // Construct filter
        $filter = ['_id' => $objectId];

        // Decode PATCH data from request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate and sanitize the PATCH data if required

        // Construct update document from PATCH data
        $update = ['$set' => $data];

        // Update document in collection
        $result = $collection->updateOne($filter, $update);

        // Check if update was successful
        if ($result->getModifiedCount() === 1) {
            // Return success message with updated document
            $updatedDocument = $collection->findOne($filter);
            http_response_code(200); // Set response status code to 200 OK
            echo json_encode($updatedDocument);
        } else {
            // Return error message
            http_response_code(404); // Set response status code to 404 Not Found
            echo json_encode(array("message" => "Document not found."));
        }
    } else {
        // Return error message for unsupported method
        http_response_code(405); // Set response status code to 405 Method Not Allowed
        echo json_encode(array("message" => "Method not allowed."));
    }
} else {
    // User does not have full access
    header('HTTP/1.1 401 Unauthorized');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized access or invalid permission level'));
    exit();
}
?>
