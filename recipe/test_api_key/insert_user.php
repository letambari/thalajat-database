<?php
// Define the data to insert
$data = array(
    
    "title" => "Recipe Title",
    "link" => "Recipe Link",
    "main_image" => "Recipe Main Image",
    "description" => "Recipe Description",
    "ingredients" =>"{
        'Ingredient 1',
        'Ingredient 2',
        'Ingredient 3',
        'Ingredient 4',
        'Ingredient 5',
        'Ingredient 6',
        'Ingredient 7'
    }",
    "utensils" => "{
        'Utensil 1',
        'Utensil 2',
        'Utensil 3',
        'Utensil 4'
    }",
    "nutrition" => "Nutrition per Serving",
    "tags" => "{
        'Tag 1',
        'Tag 2',
        'Tag 3'
    }",
    "steps" => "{
        '{
            'description' => 'Step 1',
            'image_link' => 'Step 1 image Link'
        }',
        '{
            'description' => 'Step 2',
            'image_link' => 'Step 2 image Link'
        }',
        '{
            'description' => 'Step 3',
            'image_link' => 'Step 3 image Link'
        }',
        '{
            'description' => 'Step 4',
            'image_link' => 'Step 4 image Link'
        }',
        '{
            'description' => 'Step 5',
            'image_link' => 'Step 5 image Link'
        }',
        '{
            'description' => 'Step 6',
            'image_link' => 'Step 6 image Link'
        }',
        '{
            'description' => 'Step 7',
            'image_link' => 'Step 7 image Link'
        }',
        '{
            'description' => 'Step 8',
            'image_link' => 'Step 8 image Link'
        }',
        '{
            'description' => 'Step 9',
            'image_link' => 'Step 9 image Link'
        }',
        '{
            'description' => 'Step 10',
            'image_link' => 'Step 10 image Link'
        }',
        '{
            'description' => 'Step 11',
            'image_link' => 'Step 11 image Link'
        }',
        '{
            'description' => 'Step 12',
            'image_link' => 'Step 12 image Link'
        }',
        '{
            'description' => 'Step 13',
            'image_link' => 'Step 13 image Link'
        }',
        '{
            'description' => 'Step 14',
            'image_link' => 'Step 14 image Link'
        }',
    }",
    "categories" => "{
        'Category 1',
        'Category 2'
    }",
    "difficulty" => "Difficulty Level",
    "preparation_time" => "Preparation Time",
    "baking_time" => "Baking Time",
    "resting_time" => "Resting Time"

   
        );

// Encode the data into JSON format
$jsonData = json_encode($data);

// Set up the cURL request
$ch = curl_init('http://localhost/mongo-site/recipe/api_key/insert_user_with_api_key.php');
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
