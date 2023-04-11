<?php
// Define the data to insert
$data = array(
  "product_id" => 1234,
  "product_name" => "Organic Avocado",
  "product_description" => "Fresh, ripe avocados picked from organic farms",
  "product_image" => "https://example.com/images/organic_avocado.jpg",
  "category_id" => 5,
  "brand_id" => 12,
  "price" => 2.99,
  "weight_or_volume" => "1 lb",
  "unit_of_measure" => "pounds",
  "shelf_life" => 7,
  "barcode" => "012345678912",
  "is_active" => true,
  "creation_date" => "2022-03-15",
  "last_update" => "2022-03-20",
  "sku" => "ORG-AV-001",
  "nutrition_info" => array(
    "calories" => 160,
    "fat" => 15,
    "protein" => 2,
    "carbohydrates" => 9,
    "fiber" => 7
  ),
  "ingredients" => array(
    "organic avocado"
  ),
  "allergens" => array(
    "none"
  ),
  "storage_instructions" => "Store at room temperature until ripe, then refrigerate",
  "expiration_date" => "2022-03-30",
  "origin" => "Mexico",
  "is_vegan" => true,
  "is_gluten_free" => true,
  "is_organic" => true,
  "manufacturer" => "Organic Farms Inc.",
  "supplier_id" => 23,
  "wholesale_price" => 1.99,
  "tax_rate" => 0.06,
  "discount" => 0.1,
  "is_halal" => false,
  "is_kosher" => true,
  "package_type" => "plastic",
  "package_size" => array(
    "length" => 5,
    "width" => 3,
    "height" => 2
  ),
  "featured" => false,
  "eco_friendly" => true,
  "certifications" => array(
    "Organic",
    "Non-GMO",
    "Fair Trade"
  ),
  "additional_notes" => "Avocados may vary in size and ripeness",
  "Product_weight_warehouse_1" => 30.5,
  "Product_weight_warehouse_2" => 25.2,
  "Product_weight_warehouse_3" => 28.7
);

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/product-database/api_key/insert_user_with_api_key.php');
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
