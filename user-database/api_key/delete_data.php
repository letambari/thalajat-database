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
$collection = $mongo->storage_data->users;


// Check if the permission field exists and is equal to 1
if (isset($document_access['permission']) && $document_access['permission'] === 1 || $document_access['permission'] === 2) {
  // User has full access
 
  
// Check if request method is DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  
  // Get ID from URL parameter
  if(!isset($_GET['id'])){
    echo "data did not come";
    exit();
  }
 
  $id = $_GET['id'];
  
  // Construct filter
  $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
  
  // Delete document from collection
  $result = $collection->deleteOne($filter);


//   $success_status = $result->getDeletedCount();
//  echo $nice = json_encode($success_status);

//  if($nice == 1){
//     echo "ID is deleted";
//  } else {
//     echo "not deleted";
//  }
// //echo $success_status;
//    exit();
  // Check if delete was successful
  if ($result->getDeletedCount() === 1) {

    // Return success message
    echo "Deleted Successfully";
  } else {
    header("HTTP/1.1 204 No Content");
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
  echo json_encode(array('error' => 'Unauthorized access'));
  exit();
}

?>
