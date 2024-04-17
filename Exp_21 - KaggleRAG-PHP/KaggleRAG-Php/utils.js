
function show_paper_info_panel(response_json, num_results_to_display) {
	
	
	// Add the references to the right hand side panel
	var context_list = [];
	
	var paper_info = document.getElementById("paper_info");
	
	// Remove the current text inside the div element
	document.getElementById('paper_info').innerHTML = '';

	// Get the first few reranked items
	for (var i = 0; i < num_results_to_display; i++) {
		
		//var abstract = response_json['data']['Get'][vectordb_name][i]['abstract'];
		// Remove the title portion from the string
		// Split on '{title}' and choose the second list item
		//abstract = abstract.split('{title}')[1];
		
		//var title_text = response_json['data']['Get'][vectordb_name][i]['title'];
		//var arxiv_id = response_json['data']['Get'][vectordb_name][i]['arxiv_id'];
		var prepared_text = response_json['data']['Get'][vectordb_name][i]['prepared_text'];
		
		//var pdf_link = 'https://arxiv.org/pdf/' + arxiv_id;
		
		
		
		// Create a new div element to hold the content
        var div = document.createElement("div");
        // Set the HTML content of the div
        div.innerHTML = `
            <div>
                <p>Excerpt: ${prepared_text}<br>
            </div>
			`;
			
			
        // Append the div to the paper_info element
		paper_info.appendChild(div);
		
		
		//var paper_info = document.getElementById("paper_info");
  
  		//paper_info.appendChild(text);
		
		// Add the text to the context list
	    context_list.push(prepared_text); // Append the value of i to the array
		
	}
	
}






function getFirstFiveWords(str) {
  // Split the string into an array of words
  var words = str.split(' ');
  
  // Select the first five words
  var firstFiveWords = words.slice(0, 5);
  
  // Join the first five words back into a string
  var result = firstFiveWords.join(' ');
  
  return result;
}


function replaceTitle(title_text, buttonId) {
  // Find the div by its ID
  var targetButton = document.getElementById(buttonId);

  // Check if the target button exists
  if (targetButton) {
    // Replace the inner HTML of the button with the new paragraph
    targetButton.innerHTML = title_text;
  } else {
    console.error('No div found with the ID:', buttonId);
  }
}


function replaceTextInDiv(text, divId) {
  // Find the div by its ID
  var targetDiv = document.getElementById(divId);

  // Check if the target div exists
  if (targetDiv) {
    // Replace the inner HTML of the div with the new paragraph
    targetDiv.innerHTML = '<p>' + text + '</p>';
  } else {
    console.error('No div found with the ID:', divId);
  }
}




function scrollToBottom() {
  var chat = document.getElementById("chat");
  chat.scrollTop = chat.scrollHeight;
}


// Call this function right after adding a new message to the chat
// For demonstration purposes, you might call scrollToBottom() manually or within another function that appends messages

function scrollToLastMessage() {
  var chat = document.getElementById("chat");
  var messages = chat.children; // Assuming all children are message elements
  if (messages.length > 0) {
    var lastMessage = messages[messages.length - 1];
    // Calculate the position of the last message
    var lastMessageTop = lastMessage.offsetTop;
    chat.scrollTop = lastMessageTop - chat.offsetTop;
  }
}


function scrollToTop() {
  var chat = document.getElementById("paper_info");
  var messages = chat.children; // Assuming all children are message elements
  if (messages.length > 0) {
    var lastMessage = messages[messages.length - 1];
    // Calculate the position of the last message
    var lastMessageTop = lastMessage.offsetTop;
    chat.scrollTop = lastMessageTop - chat.offsetTop;
  }
}



function scrollToDiv(divId) {
  // Prevent the default anchor link behavior
  event.preventDefault();

  // Find the div by its ID
  var targetDiv = document.getElementById(divId);
  
  // Check if the target div exists
  if (targetDiv) {
    // Scroll the target div into view
    targetDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}








// This function creates the three dot spinner.
// Calling this function starts the spinner.
function spinner() {
	
	// Select the element where the spinner will be displayed
	const spinnerElement = document.getElementById("spinner");
	
	// Define an array of dots
	const dots = ["", ".", "..", "..."];
	
	// Initialize the dot counter
	let dotIndex = 0;
	
	// Start the spinner animation
	setInterval(() => {
	  // Update the text content of the spinner element with the current dot
	  spinnerElement.textContent = `>${dots[dotIndex]}`;
	
	  // Increment the dot counter
	  dotIndex = (dotIndex + 1) % dots.length;
	}, 500);

}



// We create the div containing the spinner.
// We append the div to the chat.
// This displays the spinner.
function create_spinner_div() {
	
	// Create a new div element
	const spinnerElement = document.createElement("div");
	
	// Set the id attribute of the div element to "spinner"
	spinnerElement.setAttribute("id", "spinner");
	
	var chat = document.getElementById("chat");
  
	// Append the div to the chat
  	chat.appendChild(spinnerElement);
	
	// Start the spinner
	spinner();
}



// This function deletes the div containing the spinner.
// This causes the spinner to disappear.
function delete_spinner_div() {
	
	// Get the div element you want to delete
	const elementToDelete = document.getElementById("spinner");
	
	// Get the parent node of the div element
	const parentElement = elementToDelete.parentNode;
	
	// Remove the div element from its parent node
	parentElement.removeChild(elementToDelete);

}



// This function deletes the div containing the spinner.
// This causes the spinner to disappear.
function delete_temp_p() {
	
	// Get the div element you want to delete
	const elementToDelete = document.getElementById("temp_p");
	
	// Get the parent node of the div element
	const parentElement = elementToDelete.parentNode;
	
	// Remove the div element from its parent node
	parentElement.removeChild(elementToDelete);

}



// This functions takes a list of text (paragraphs).
// If the paragraph does not have p tags then it adds them.
function wrapInPTags(paragraphs) {
	
  let result = '';

  for (let i = 0; i < paragraphs.length; i++) {
    const paragraph = paragraphs[i];

    if (paragraph.includes('<p>')) {
      result += paragraph;
    } else {
      result += '<p>' + paragraph + '</p>';
    }
  }

  return result;
}



// This function formats the text into paragraphs.
function formatResponse(response) {
	
    // Split the response into lines
    const lines = response.split("\n");

    // Combine the lines into paragraphs
    const paragraphs = [];
    let currentParagraph = "";

    for (const line of lines) {
        if (line.trim()) {  // Check if the line is non-empty
            currentParagraph += line.trim() + " ";
        } else if (currentParagraph) {  // Check if the current paragraph is non-empty
            paragraphs.push(currentParagraph.trim());
            currentParagraph = "";
        }
    }

    // Append the last paragraph
    if (currentParagraph) {
        paragraphs.push(currentParagraph.trim());
    }

	// Some text thats returned has \n character but no <p> tags.
	// Other text has <p> tags that we can use when displaying the text on the page.
	// Here we check each list item (paragraph). If it doesn't have <p> tags then add them.
	// This is also important when we save and then reload the chat history.
	//	If you change this make sure that the saving and reloading also works well.
	formattedResponse = wrapInPTags(paragraphs);
	
	
    // Add HTML tags to separate paragraphs
    //const formattedResponse = paragraphs.map(p => `<p>${p}</p>`).join("");
	
	return formattedResponse;
	
	
}



// Function to create a new message container
function createMessageContainer(message) {
	
  var messageContainer = document.createElement("div");
  messageContainer.classList.add("message-container");
  
  messageContainer.classList.add("w3-animate-opacity");
  
  // Add an id attribute. This will help to scroll to
  // the bot message. This gets detelted after the page
  // is scrolled to the bot message.
  messageContainer.setAttribute("id", "chatgpt1");
  

  var messageText = document.createElement("span"); //p
  
  
  // This if statement sets the coour of the name that gets displayed
  if (message.sender == bot_name) {
  
	  messageText.innerHTML = "<span class='set-color1'><b>&#x2022 " + message.sender + "</b></span>" + message.text;
  } else {
  	messageText.innerHTML = "<span class='set-color2'><b>&#x2022 " + message.sender + "</b></span>" + message.text;
	}

 
  messageContainer.appendChild(messageText);
  

  return messageContainer;
}


// Function to add a new message to the chat
function addMessageToChat(message) {
	
  var chat = document.getElementById("chat");
  var messageContainer = createMessageContainer(message);
  
  chat.appendChild(messageContainer);
  
}




// This functions saves the chat to a csv file.
// The system setup message, that defines the bot's behaviour, is part of the chat.
function saveChatHistoryToCsv() {
	
  const rows = [
    ['Role', 'Message']
  ];

  message_list.forEach((message) => {
    rows.push([message.role, message.content]);
  });

  const csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");

  const encodedUri = encodeURI(csvContent);
  const timestamp = new Date().toISOString().replace(/[:.]/g, "-");
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  
  // Save the config in the file name
  link.setAttribute("download", `temp${temperature}_prespen${presence_penalty}_freqpen${frequency_penalty}_${timestamp}.csv`);
  
  link.style.display = "none";

  // Attach the link to the DOM
  document.body.appendChild(link);

  // Trigger the download in the background
  link.click();

  // Remove the link from the DOM
  document.body.removeChild(link);
}


// This function reads the chat history from a csv file and
// displays the chat content on the page.
function writeCsvFileContentToPage(input_list) {
		
		let my_list = input_list;
		
	    for (let i = 0; i < my_list.length; i++) {
			
			
			// row 0 in the csv file is system message.
			// We don't want to display the system message on the page.
			if (i >= 1) {
				
				let chat_role;
				
				if (my_list[i].role === "assistant") {
					chat_role = bot_name;
				} else {
					chat_role = user_name;
					
				}
		  		
				let input_message = {
				  sender: chat_role,
			  		text: my_list[i].content
				}
				
				
				// Add the message from Maiya to the chat
				addMessageToChat(input_message);
				
				
				// Scroll the page up by cicking on a div at the bottom of the page.
				simulateClick('scroll-page-up');
				
				// Put the cursor in the form input field
				const inputField = document.getElementById("user-input");
				inputField.focus();
			
			}	
		}
	}



  
  
  // This function reads the chat history from the csv file 
  // where it has been saved.
  function loadChatHistoryFromCsv(file) {
	  
	  const reader = new FileReader();
	
	  reader.readAsText(file);
	
	  reader.onload = function(event) {
	    const csv = event.target.result;
		
	    const rows = csv.split("\n");
		
	    const messages = [];
	
	    rows.forEach(row => {
	      const cols = row.split(',');
	      const role = cols[0];
	      const content = cols.slice(1).join(',').replace(/^"(.*)"$/, '$1');
	      messages.push({ role, content });
	    });
	
	    // Set the message_list to the loaded messages
	    // The first item is the header row. Here we are slicing it out.
	    let chat_messages = messages.slice(1);
	
	    // Display the csv file chat history on the page
	    writeCsvFileContentToPage(chat_messages);
	
	    // Note: message_list is a global variable
	    // This line will change the value of the global variable.
	    message_list = chat_messages;
	  }
}

  

// Function to remove html tags from a string
function removeHtmlTags(str) {
	
  return str.replace(/(<([^>]+)>)/gi, "");
  
}
  
  