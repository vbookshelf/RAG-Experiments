
// Config
//-------
// Chat parameters are explained here: https://platform.openai.com/docs/api-reference/chat
// GPT-3-5 specs: https://platform.openai.com/docs/models/gpt-3-5


const bot_name = 'JS App';  	// Give the bot a name
const user_name = 'User';	// Set your chat name 

// *** This url constantly changes depending on the curl request that needs to be sent
const weaviateEndpoint = 'https://my-sandbox1-1486fdzz.weaviate.network/v1/graphql';

const weaviateApiKey = 'YOUR-API-KEY';
const openaiApiKey = 'YOUR-API-KEY';
const cohereApiKey = 'YOUR-API-KEY';



const model_type = "gpt-3.5-turbo-0125"; 
const openai_url = 'https://api.openai.com/v1/chat/completions';

// The max number of tokens to generate in the chat completion.
// I found that if this number is set too high then there will be 
// an undefined response, even if the number is within the model's token limit. 
const max_tokens = 1000; 

// 0 to 2. Higher values like 0.8 will make the output more random, 
// while lower values like 0.2 will make it more focused and deterministic.
// Alter this or top_p but not both.
const temperature = 0.21;

// -2 to 2. Higher values increase the model's likelihood to talk about new topics.
// Reasonable values for the penalty coefficients are around 0.1 to 1.
const presence_penalty = 0; 

// -2 to 2. Higher values decrease the model's likelihood to repeat the same line verbatim.
// Reasonable values for the penalty coefficients are around 0.1 to 1.
const frequency_penalty = 0.5;


// The message history is stored in this variable.
// Storing the message history allows the bot to have context memory.

var message_list;





// Option 1: The user does not load a saved chat
//-----------------------------------------------


// This determines how the bot behaves.
system_setup_message = "Your name is " + bot_name + ". You are a helpful assistant.";



// Create a list with the first item being a dict
message_list = [{"role": "system", "content": system_setup_message}];



// Option 2: The user loads a saved chat (csv file)
//--------------------------------------------------

// The previous chat history will be loaded from the csv file.
// The system_setup_mesaage that defines the bot's behaviour is included in the
// saved chat history.
// The message_list variable is assigned inside the loadChatHistoryFromCsv() function.
// The chat continues from where the chat in the csv file stopped.

const fileInput = document.getElementById("csv-file");

fileInput.addEventListener("change", function(event) {
	
  const file = event.target.files[0];
  
  loadChatHistoryFromCsv(file);
});



async function makeApiRequest(my_message) {
	
		// This scrolls the page up by cicking on a div at the bottom of the page.
		// This shows the user's message.
		// Note that if the click is simlated "on page load" then the cursor 
		// will not autofocus in the form input.
		simulateClick('scroll-page-up');
		
		
		// Append to message_list. This is the history of chat messages.
		message_list.push({"role": "user", "content": my_message});
		
		
		/////////////////////////////
		
		// Weaviate request		
		const requestBody = JSON.stringify({
						    query: `
						    {
						      Get {
						        OHS_ACT_VDB (
						          hybrid: {
						            query: "${my_message}"
						            alpha: 0.5
						          }
						          limit: 10
						        ) {
						          chunk_id
						          chunk_text
						          _additional {
						            distance
						            rerank(
						              property: "chunk_text"
						              query: "${my_message}"
						            ) {
						              score
						            }
						          }
						        }
						      }
						    }
						    `,
						});

		
		
		  const headers = {
			    'Authorization': `Bearer ${weaviateApiKey}`,
			    'Content-Type': 'application/json',
			    'X-OpenAI-Api-Key': openaiApiKey,
				'X-Cohere-Api-Key': cohereApiKey,
			};
			

	  try {
		  
		  
		 const response = await fetch(weaviateEndpoint, {
	      method: 'POST',
	      headers,
	      body: requestBody,
	    });
	
	    const response_json = await response.json();
	
		
		// Print the entire response to the console
		console.log(response_json);
		
		// Print the first result text
	    //console.log(response_json['data']['Get']['MyTable1'][0]['quote_text']);
		console.log(response_json['data']['Get']['OHS_ACT_VDB'][0]['chunk_text']);
		
		
		// Get the response text
		var response_text = response_json['data']['Get']['OHS_ACT_VDB'][0]['chunk_text'];
		
		
		/////////////////////////////
		
		
		// Format the response so it can be displayed on the web page.
		var paragraph_response = formatResponse(response_text);
			
		
		console.log(response_text)
		
		
		// Append to message_list. This is the history of chat messages.
		message_list.push({"role": "assistant", "content": paragraph_response});
			
		
		var input_message = {
		  sender: bot_name,
	  		text: paragraph_response
		};
		
		
		// Add the message from Maiya to the chat
		addMessageToChat(input_message);
		
		
		// Scroll the page up by cicking on a div at the bottom of the page.
		simulateClick('scroll-page-up');
		
		// Put the cursor in the form input field
		const inputField = document.getElementById("user-input");
		inputField.focus();
		
		
	  } catch (error) {
		  
	    console.log(error);
		
	  }
  
  }