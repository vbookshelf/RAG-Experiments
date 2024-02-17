
// basicRAG
// basicRAG does not have memory.


// Config
//-------
// Chat parameters are explained here: https://platform.openai.com/docs/api-reference/chat
// GPT-3-5 specs: https://platform.openai.com/docs/models/gpt-3-5


const bot_name = 'Assistant';  // Give the bot a name
const user_name = 'User';	// Set your chat name 

// *** This url constantly changes depending on the curl request that needs to be sent
const weaviateEndpoint = 'https://my-sandbox1-1486fdzz.weaviate.network/v1/graphql';

const weaviateApiKey = 'YOUR-API-KEY';
const openaiApiKey = 'YOUR-API-KEY';
const cohereApiKey = 'YOUR-API-KEY';

// The number of results after reranking
const top_k = 10;

// The number of results that are passed to OpenAi
// to generate the natural langiage output.
const num_in_context = 3;



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






// Create a list with the first item being a dict
//message_list = [{"role": "system", "content": system_setup_message}];




async function makeApiRequest(my_message) {
	
		// This scrolls the page up by cicking on a div at the bottom of the page.
		// This shows the user's message.
		// Note that if the click is simlated "on page load" then the cursor 
		// will not autofocus in the form input.
		simulateClick('scroll-page-up');
		
		
		// Append to message_list. This is the history of chat messages.
		//message_list.push({"role": "user", "content": my_message});
		
		
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
						          limit: ${top_k}
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
		  
		  
		 const weviate_response = await fetch(weaviateEndpoint, {
	      method: 'POST',
	      headers,
	      body: requestBody,
	    });
	
	    const response_json = await weviate_response.json();
	
		
		// Print the entire response to the console
		//console.log(response_json);
		
		// Print the first result text
	    //console.log(response_json['data']['Get']['MyTable1'][0]['quote_text']);
		//console.log(response_json['data']['Get']['OHS_ACT_VDB'][0]['chunk_text']);
		
		
		// Get the response text
		//var weviate_response_text = response_json['data']['Get']['OHS_ACT_VDB'][0]['chunk_text'];
		
		
		//---
		
		// Add the user query to the rught hand side panel
		replaceTextInDiv(my_message, 'user_question');
		
		
		// Add the references to the right hand side panel
		var context_list = [];

		// Get the first few reranked items
		for (var i = 0; i < num_in_context; i++) {
			
			var text = response_json['data']['Get']['OHS_ACT_VDB'][i]['chunk_text'];
			
			// Insert the text into the right panel on the page
			var div_list = ["section1", "section2", "section3"];
			var divId = div_list[i];
			replaceTextInDiv(text, divId);
			
			// Use the first five words as a button title
			var title_text = getFirstFiveWords(text);
			title_text = title_text + '...';
			var title_list = ["title1", "title2", "title3"];
			var buttonId = title_list[i];
			replaceTitle(title_text, buttonId);
			
			
			// Add the text to the context list
		    context_list.push(text); // Append the value of i to the array
			
		}
		
		//---
		
		
		
		
		/////////////////////////////
		// USE OPENAI TO GET A NATURAL LANGUAGE RESPONSE
		/////////////////////////////
		
		//var context = weviate_response_text;
		var context = context_list;
		
		console.log(context)
		
		var promp_with_context =  `
		Excerpts from the South African Occupational Health and Safety Act (OHS Act): 
		${context}
		Question: ${my_message}
		
		Extract the answer to the question from the text provided. 
		If the text doesn't contain the answer, 
		reply that the answer is not available.
		Always respond in professional tone.
		Always use bullet points when possible.
		`;
		
		
		
		// Note: We are not saving the chat history.
		// Each time we create a NEW message list with the first item being the system message.
		var message_list;
		system_setup_message = "You are a helpful legal assistant.";
		message_list = [{"role": "system", "content": system_setup_message}];
		
		
		// Append to message_list. This is the history of chat messages.
		message_list.push({"role": "user", "content": promp_with_context});
		
	    const response = await fetch(openai_url, {
			
	      method: 'POST',
	      headers: {
			Authorization: `Bearer ${openaiApiKey}`,
	        'Content-Type': 'application/json'
	      },
	      body: JSON.stringify({
			 model: model_type,
	        messages: message_list,
	        max_tokens: max_tokens,
			temperature: temperature,
			presence_penalty: presence_penalty,
			frequency_penalty: frequency_penalty
	      })
	    })
		
		
	    const data = await response.json();
		
		
		// Get the response text
		var response_text = data['choices'][0]['message']['content'];
		
	
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
		
		// Scroll the chat messages up
		//scrollToBottom();
		
		// We want to scroll to the top of the last message that has id 'test100'
		scrollToLastMessage();
		
		
		
		// Put the cursor in the form input field
		const inputField = document.getElementById("user-input");
		inputField.focus();
		
		
	  } catch (error) {
		  
	    console.log(error);
		
	  }
  
  }