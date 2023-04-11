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

// Define the API endpoint to insert data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Select the database and collection to insert into
    $db = $mongoClient->selectDatabase("storage_data");
    $collection = $db->selectCollection("riders");

    // Insert the document into the collection
    $result = $collection->insertOne($data);

    // Check if the insertion was successful and send response accordingly
    if ($result->getInsertedCount() == 1) {
        $response = array("success" => true, "message" => "Document inserted successfully.");
    } else {
        $response = array("success" => false, "message" => "Failed to insert document. Error: " . $result->getWriteErrors()[0]["errmsg"]);
    }

    // Send the response back to the client
    echo json_encode($response);
}
?>
