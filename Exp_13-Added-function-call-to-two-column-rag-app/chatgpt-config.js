
// Config
//-------
// This code uses ChatGPT function calling.

const bot_name = 'Assistant';  	// Give the bot a name
const user_name = 'User';	// Set your chat name 

// *** This url constantly changes depending on the curl request that needs to be sent
const weaviateEndpoint = 'https://my-sandbox1-1486fdzz.weaviate.network/v1/graphql';

const weaviateApiKey = 'YOUR-API-KEY';
const openaiApiKey = 'YOUR-API-KEY';
const cohereApiKey = 'YOUR-API-KEY';


const model_type = "gpt-3.5-turbo-0125"; // gpt-4, gpt-3.5-turbo-0125
const openai_url = 'https://api.openai.com/v1/chat/completions';

// The max number of tokens to generate in the chat completion.
// I found that if this number is set too high then there will be 
// an undefined response, even if the number is within the model's token limit. 
const max_tokens = 500; 

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




// Remove these suffixes. I think removing them makes the chat sound more natural.
// They will sliced off the bot's responses.
// This is done below in the 'Remove suffixes' part of the code.
var suffixes_list = ['How can I help you?', 'How can I assist you today?', 'How can I help you today?', 'Is there anything else you would like to chat about?', 'Is there anything else I can assist you with today?', 'Is there anything I can help you with today?', 'Is there anything else you would like to chat about today?', 'Is there anything else I can assist you with?'];


// The message history is stored in this variable.
// Storing the message history allows the bot to have context memory.

var message_list;





// Option 1: The user does not load a saved chat
//-----------------------------------------------

		
var system_setup_message = `
		You are a helpful legal assistant.
		You answer questions about the South African Occupational Health and Safety Act (OHS Act).
		The information that you need to answer the questions is stored in a vector database.
		From the database you retrieve exerpts from South African Occupational Health and Safety Act (OHS Act) that have been matched to the user's question.
		If the text from the database doesn't contain the answer, reply that the answer is not available.	
		You can read from a database but you cannot change the data in the database.
		Explain at a level that a highschooler would understand.
		`;
		
		
		

// Create a list with the first item being a dict
message_list = [{"role": "system", "content": system_setup_message}];






// OpenAI API - Javascript
//-------------------------

async function makeApiRequest(my_message) {
	
		// Show the spinner while waiting for the response from openai
		create_spinner_div();
	
		//-----------------------------------
		// Define the tools and the function
		//-----------------------------------
	
		const tools = [
		  {
		    type: "function", // What kind of tool is it
		    function: {
		      name: "getOhsActInfo",
		      description: "Given a question from a user as input, this function returns a list of text excerpts from the OHS Act that have been matched to the question.",
		      // What do we want chatgpt to return
		      parameters: {
		        type: "object", // We want an object
		        properties: {
		          user_question: {
		            type: "string",
		            description: "The question from the user.",
		          },
		        }, // close properties
		
		        // This input is required
		        required: ["user_question"],
		      }, // close parameters
		    }, // close function
		  }, // close first curly brace
		  ];
	
		  
		  
		  
			
			
			
			/* THIS IS THE FUNCTION
			//----------------------
			
			// This function provides info for just one student
			function getStudentInfo(password) {
				return data;
			}
			*/
			
			
			
			
		//----------------
		// Run the code
		//----------------
	
		// This scrolls the page up by cicking on a div at the bottom of the page.
		// This shows the user's message.
		// Note that if the click is simlated "on page load" then the cursor 
		// will not autofocus in the form input.
		simulateClick('scroll-page-up');

	  try {
		  
		// Append to message_list. This is the history of chat messages.
		message_list.push({"role": "user", "content": my_message});
		
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
			frequency_penalty: frequency_penalty,
			tools:tools,
			tool_choice:"auto"
	      })
	    })
		
		
	    const data = await response.json();
		
		
		// Get the response text
		var response_text = data['choices'][0]['message']['content'];
		
		// Get the finish_reason
		// "tool_calls"
		var finish_reason = data['choices'][0]['finish_reason'];
		
		console.log(finish_reason)
		
		
		// If the model did NOT return function arguments
		if (finish_reason != "tool_calls") {
		
		
			// Replace the suffixes with "":
			// This removes sentences like: How can I help you today?
			// For each suffix in the list...
			 suffixes_list.forEach(suffix => {
		      
				// Replace the suffix with nothing.
		        response_text = response_text.replace(suffix, "");
				
		  	});
			
			
			
			// Format the response so it can be displayed on the web page.
			var paragraph_response = formatResponse(response_text);
				
			
			//console.log(response_text)
			
			
			// Append to message_list. This is the history of chat messages.
			message_list.push({"role": "assistant", "content": paragraph_response});
				
			
			var input_message = {
			  sender: bot_name,
		  		text: paragraph_response
			};
			
			
			// Delete the div containing the spinner
			delete_spinner_div();
			
			// Add the message from Maiya to the chat
			addMessageToChat(input_message);
			
			
			// Scroll the page up by cicking on a div at the bottom of the page.
			//simulateClick('scroll-page-up');
			
			// We want to scroll to the top of the last message that has id 'test100'
			scrollToLastMessage();
			
			// Put the cursor in the form input field
			const inputField = document.getElementById("user-input");
			inputField.focus();
			
		
		} else {
		// The model has output function arguments
		
			// Get the function name
			const function_name = data['choices'][0]['message']['tool_calls'][0]['function']['name'];
		
			// Get the function arguments
			var arguments = data['choices'][0]['message']['tool_calls'][0]['function']['arguments'];
			
			console.log(arguments)
			
			//const studentId = arguments['student_id'];
			
			const argObject = JSON.parse(arguments);
			const user_question = argObject['user_question'];
			
			
			///////////////
			
			// Call the function
			// This queries the Weaviate database
			//--------------------
			
			const ohs_act_info = await getOhsActInfo(user_question);
			
			/////////////////
			
			
			
			
			//---
			// Add the text to the right hand side panel
			// Get the first few reranked items
			
			const lengthOfList = ohs_act_info.length;
			
		    for (var i = 0; i < lengthOfList; i++) {
		
		        var text = ohs_act_info[i];
		
				
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
				
		    }
			
			//----
			
			
			
			
			// Convert the object to a JSON-formatted string
			const jsonString = JSON.stringify(ohs_act_info);
			
			
			// Append to message_list. This is the history of chat messages.
			message_list.push({"role": "function", "name": function_name, "content": jsonString});
			
			//console.log(message_list)
			
			
		// Make an API request to send the output of
		// the function to the model as a message.
		try {
		  
			
			
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
				frequency_penalty: frequency_penalty,
				tools:tools,
				tool_choice:"auto"
		      })
		    })
			
			
		    const data = await response.json();
			
			
			// Get the response text
			var response_text = data['choices'][0]['message']['content'];
			
			// Get the finish_reason
			// "tool_calls"
			var finish_reason = data['choices'][0]['finish_reason'];
			
			//console.log(response_text)
			
			
			// Replace the suffixes with "":
			// This removes sentences like: How can I help you today?
			// For each suffix in the list...
			 suffixes_list.forEach(suffix => {
		      
				// Replace the suffix with nothing.
		        response_text = response_text.replace(suffix, "");
				
		  	});
			
			
			
			// Format the response so it can be displayed on the web page.
			var paragraph_response = formatResponse(response_text);
				
			
			//console.log(response_text)
			
			
			// Append to message_list. This is the history of chat messages.
			message_list.push({"role": "assistant", "content": paragraph_response});
				
			
			var input_message = {
			  sender: bot_name,
		  		text: paragraph_response
			};
			
			
			// Delete the div containing the spinner
			delete_spinner_div();
			
			
			// Add the message from Maiya to the chat
			addMessageToChat(input_message);
			
			
			// Scroll the page up by cicking on a div at the bottom of the page.
			//simulateClick('scroll-page-up');
			
			// We want to scroll to the top of the last message that has id 'test100'
			scrollToLastMessage();
			
			// Put the cursor in the form input field
			const inputField = document.getElementById("user-input");
			inputField.focus();
			
			
		} catch (error) {
			
		// Delete the div containing the spinner
		delete_spinner_div();
		  
	    console.log(error);
		
		}
			
		
				
			
			
		}
		
		
	  } catch (error) {
		  
		 // Delete the div containing the spinner
		 delete_spinner_div();
		  
	    console.log(error);
		
	  }
  
  }