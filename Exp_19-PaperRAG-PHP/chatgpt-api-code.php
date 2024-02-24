<?php
session_start();

include "name_config.php";




$weaviateEndpoint = 'https://my-sandbox1-1486fdzz.weaviate.network/v1/graphql';

$weaviateApiKey = "YOUR-API-KEY"; // Set your Weaviate API key here
$openaiApiKey = "YOUR-API-KEY"; // Set your OpenAI API key here
$cohereApiKey = "YOUR-API-KEY"; // Set your Cohere API key here

$top_k = 100; 
$num_results_to_display = 20;

$vdb_name = "ARXIV_100SAMPLE_VDB";



// This function cleans and secures the user input
function test_input(&$data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		$data = htmlentities($data);
		
		return $data;
	}




// This code is triggered when the user submits a message
//--------------------------------------------------------

if (isset($_REQUEST["my_message"]) && empty($_REQUEST["robotblock"])) {
	
	
	$my_message = $_REQUEST["my_message"];
	
	// Clean and secure the user's text input
	$my_message = test_input($my_message);
	
	
	/////////////////////////////
	
$my_query = $my_message; // Set your message here


$requestBody = json_encode([
    'query' => '{
        Get {
            '. $vdb_name .' (
                hybrid: {
                    query: "' . $my_query  . '"
                    alpha: 0.5
                }
                limit: ' . $top_k . '
            ) {
                arxiv_id
                title
				cat_text
                abstract
                _additional {
                    distance
                    rerank(
                        property: "abstract"
                        query: "' . $my_query  . '"
                    ) {
                        score
                    }
                }
            }
        }
    }'
]);

$headers = [
    'Authorization: Bearer ' . $weaviateApiKey,
    'Content-Type: application/json',
    'X-OpenAI-Api-Key: ' . $openaiApiKey,
    'X-Cohere-Api-Key: ' . $cohereApiKey
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $weaviateEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

$wcs_response_json = json_decode($response, true);


if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
	
	
	/////////////////////////////


$message = 'Search completed.';


// Display a message on the page
// *** This is what we need to process on the index.php page ***
$response = array('success' => true, 'chat_text' => $message, 'wcs_response' => $wcs_response_json, 'num_results_to_display' => $num_results_to_display);

//$response = array('message_history' => $_SESSION['message_history'], 'chat_text' => $message);
echo json_encode($response);



	
}

?>