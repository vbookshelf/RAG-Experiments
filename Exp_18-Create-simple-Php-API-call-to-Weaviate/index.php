<?php

// This code will print the response from Weaviate on the top 
// of the page.

$my_query = "Quantum computing"; // Set your query here
$top_k = 10; // Set your desired top_k value here

$weaviateEndpoint = 'https://my-sandbox1-1486fdzz.weaviate.network/v1/graphql';

$weaviateApiKey = "YOUR-API-KEY"; // Set your Weaviate API key here
$openaiApiKey = "YOUR-API-KEY"; // Set your OpenAI API key here
$cohereApiKey = "YOUR-API-KEY"; // Set your Cohere API key here

$requestBody = json_encode([
    'query' => '{
        Get {
            ARXIV_100SAMPLE_VDB (
                hybrid: {
                    query: "' . $my_query  . '"
                    alpha: 0.5
                }
                limit: ' . $top_k . '
            ) {
                arxiv_id
                title
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
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

echo $response;

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Php API Call - Weaviate</title>
</head>

<body>
</body>
</html>