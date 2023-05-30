<?php

require '../../vendor/autoload.php';

// Connect to MongoDB client
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Select database and collection
$database = $mongoClient->selectDatabase("storage_data");
$collection = $database->selectCollection("categories");

// Get the user's API key from the request headers
if (!isset($_SERVER['HTTP_X_API_KEY'])) {
    // Return error for missing API key
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Missing API key'));
    exit();
}

$user_api_key = $_SERVER['HTTP_X_API_KEY'];

// Check if the user has access to this API
$access_collection = $database->selectCollection("user_access");
$access_filter = ['api_key' => $user_api_key];
$access_document = $access_collection->findOne($access_filter);

if (!$access_document) {
    // Return error for unauthorized access
    header('HTTP/1.1 401 Unauthorized');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized access'));
    exit();
}

// Check if the permission field exists and is equal to 1
if (isset($access_document['permission']) && ($access_document['permission'] === 1 || $access_document['permission'] === 2 || $access_document['permission'] === 3 || $access_document['permission'] === 4)) {

    // Define the API endpoint
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Validate and sanitize the ID parameter
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            try {
                $objectId = new MongoDB\BSON\ObjectId($id);
            } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
                // Return error for invalid ID parameter
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json');
                echo json_encode(array('error' => 'Invalid ID parameter'));
                exit();
            }

            // Construct filter with the validated ID parameter
            $filter = ['_id' => $objectId];

            // Find the document with the given ID
            $document = $collection->findOne($filter);

            if ($document) {
                // Output the document as JSON
                header('Content-Type: application/json');
                echo json_encode($document);
            } else {
                // Return error for document not found
                header('HTTP/1.1 404 Not Found');
                header('Content-Type: application/json');
                echo json_encode(array('error' => 'Document not found'));
            }
        } else {
            // Return error for missing ID parameter
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Missing ID parameter'));
        }
    } else {
        // Return error for unsupported HTTP method
        header('HTTP/1.1 405 Method Not Allowed');
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Unsupported HTTP method'));
    }
} else {
    // User does not have full access
    header('HTTP/1.1 401 Unauthorized');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized access'));
    exit();
}
?>
