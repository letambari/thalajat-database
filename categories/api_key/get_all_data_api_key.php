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
    // User has full access

    // Validate and sanitize the page parameter
    $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
    if ($page === false || $page < 1) {
        // Return error for invalid page value
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid page value'));
        exit();
    }

    // Validate and sanitize the limit parameter
    $limit = isset($_GET['limit']) ? filter_var($_GET['limit'], FILTER_VALIDATE_INT) : 10;
    if ($limit === false || $limit < 1) {
        // Return error for invalid limit value
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid limit value'));
        exit();
    }

    // Calculate skip value for pagination
    $skip = ($page - 1) * $limit;

    // Define the API endpoint
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get total document count for pagination
        $totalDocuments = $collection->countDocuments();

        // Get paginated documents from the collection
        $cursor = $collection->find([], ['limit' => $limit, 'skip' => $skip]);
        $documents = iterator_to_array($cursor);

        // Prepare pagination metadata
        $pagination = array(
            'totalDocuments' => $totalDocuments,
            'currentPage' => $page,
            'perPage' => $limit,
            'totalPages' => ceil($totalDocuments / $limit)
        );

        // Output the documents and pagination metadata as JSON
        $response = array(
            'documents' => $documents,
            'pagination' => $pagination
        );

        header('Content-Type: application/json');
        echo json_encode($response);
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
