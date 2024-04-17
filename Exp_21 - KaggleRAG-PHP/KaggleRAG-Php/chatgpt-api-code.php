<?php
session_start();

include "name_config.php";




$weaviateEndpoint = 'https://my-sandbox1-1486fdzz.weaviate.network/v1/graphql';

$weaviateApiKey = "YOUR_WCS_API_KEY"; // Set your Weaviate API key here
$openaiApiKey = "YOUR_OPENAI_API_KEY"; // Set your OpenAI API key here
$cohereApiKey = "YOUR_OPENAI_API_KEY"; // Set your Cohere API key here

$top_k = 30; 
$num_results_to_display = 5;



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
				prepared_text
                _additional {
                    distance
                    rerank(
                        property: "prepared_text"
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



//-------------------------------
// OPENAI API REQUEST


$model_type = "gpt-3.5-turbo-0125"; 
$url = 'https://api.openai.com/v1/chat/completions';

$max_tokens = 1000;
$temperature = 0;
$presence_penalty = 0; 
$frequency_penalty = 0;


// Note that the chatbot does not have memory.


// Add the first 5 abstracts to a list
$num_results = 5;
	
// Define an empty array
$context_list = [];

for ($i = 0; $i < $num_results; $i++) {
	
	$prepared_text = $wcs_response_json['data']['Get'][$vdb_name][$i]['prepared_text'];
	
    $context_list[] = $prepared_text;

}


$system_setup_message = <<<EOT
You are a helpful Kaggle assistant.
EOT;
	
// Create a messages list
$_SESSION['message_history'] = array();


// Append the system role to the messages list.
// This will included in every message that get's submitted
$_SESSION['message_history'][] = array("role" => "system", "content" => $system_setup_message);


// Convert $context_list into a string
$context_string = implode("\n", $context_list);


$prompt_with_context = <<<EOT
Text containing information: 
{$context_string}
Question: {$my_query}

Extract the answer to the question from the text provided. 
If the text doesn't contain the answer, 
	reply that the answer is not available.
Answer at the level that a high school student would understand.
Format you output using paragraph tags (<p></p>).
Separate sentences into paragraphs to improve readability.
Respond as if you are part of the Kaggle team.
EOT;



// Append the user's message to the messages list.
	// Remember that system role is already in the messages list.
$_SESSION['message_history'][] = array("role" => "user", "content" => $prompt_with_context);



	$headers = array(
	    "Authorization: Bearer {$openaiApiKey}",
	    "Content-Type: application/json"
	);
	
	
	// Define data
	$data = array();
	$data["model"] = $model_type;
	$data["messages"] = $_SESSION['message_history'];
	$data["max_tokens"] = $max_tokens;
	$data["temperature"] = $temperature;
	$data["presence_penalty"] = $presence_penalty;
	$data["frequency_penalty"] = $frequency_penalty;
	
	
	
	// init curl
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($curl);
	
	if (curl_errno($curl)) {
	    echo 'Error:' . curl_error($curl);
	} else {
		
	    $generatedText = json_decode($result, true);
		//echo $generatedText;
		//print_r($generatedText['choices'][0]['message']['content']);
		
		$message = $generatedText['choices'][0]['message']['content'];
		
	}
			
// End of OpenAi API Call
//-------------------------------

		
//$message = 'Search completed.';


// Display a message on the page
// *** This is what we need to process on the index.php page ***
$response = array('success' => true, 'chat_text' => $message, 'wcs_response' => $wcs_response_json, 'num_results_to_display' => $num_results_to_display);

//$response = array('message_history' => $_SESSION['message_history'], 'chat_text' => $message);
echo json_encode($response);



	
}

?>