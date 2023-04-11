<?php

require '../../vendor/autoload.php';
// Connect to MongoDB client
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Select database and collection
$database = $mongoClient->selectDatabase("storage_data");
$collection = $database->selectCollection("warehouse_and_stocks");


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


// Define the API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all documents from the collection
    $cursor = $collection->find();
    $documents = array();
    foreach ($cursor as $document) {
        $documents[] = $document;
    }
    // Output the documents as JSON
    header('Content-Type: application/json');
    echo json_encode($documents);
} else {
    // Return error for unsupported HTTP method
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unsupported HTTP method'));
}
?>
