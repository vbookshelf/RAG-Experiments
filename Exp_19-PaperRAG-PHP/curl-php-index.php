<?php

$my_query = "Quantum computing"; // Set your message here
$top_k = 10; // Set your desired top_k value here

$weaviateEndpoint = 'https://my-sandbox1-1486fdzz.weaviate.network/v1/graphql';

$weaviateApiKey = "f7reHvnG4wjI4GjX5DKAhOdOdNnLGDK1Ps53"; // Set your Weaviate API key here
$openaiApiKey = "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"; // Set your OpenAI API key here
$cohereApiKey = "Zi5oLLOtPd0Q0oHUhJLhXB9rRx4Sfgui8000Ke0n"; // Set your Cohere API key here

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

$wcs_response_json = json_decode($response, true);


if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

//echo $response;

 
$title_text = $wcs_response_json['data']['Get']['ARXIV_100SAMPLE_VDB'][0]['title'];

echo $title_text;


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