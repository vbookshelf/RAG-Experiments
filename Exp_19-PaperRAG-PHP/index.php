<?php
session_start();

include "name_config.php";

//echo $bot_name;

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>paperRAG-PHP</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="RAG web app to search for research papers in the ArXiv database.">


<!--CSS Stylesheets-->
<link rel="stylesheet" href="css/w3.css">

<link rel="shortcut icon" type="image/png" href="assets/document.png">


<style>
  body {
    background-color: #f9f9f9;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 18px;
    color: #36454F;
    display: flex; /* Use Flexbox for layout */
    margin: 0; /* Reset default margin */
    height: 100vh; /* Full height of the viewport */
    overflow: hidden; /* Prevent scrolling on the body */
  }
  
  a {
        text-decoration: none;
    }
	
  .container {
    display: flex; /* Use Flexbox for inner layout */
    flex: 1; /* Take up the full width */
    max-width: none; /* Override previous max-width */
    height: 100%; /* Full height */
  }
  .left-column, .right-column {
    flex: 1; /* Split space evenly */
    overflow-y: auto; /* Enable independent scrolling */
    height: 100vh; /* Full viewport height */
  }
  .left-column {
    display: flex;
    flex-direction: column; /* Stack elements vertically */
  }
  main {
    flex: 1; /* Allow main content to expand */
    overflow-y: auto; /* Enable scrolling if needed */
  }
  .sticky-bar {
    background-color: #36454F; /* Charcoal */
    color: #fff;
    padding: 10px; /*30px*/
    text-align: center;
  }
  
  /* Existing CSS for input, button, etc. */
  
  .sticky-bar input[type="text"] {
        padding: 10px;
        border-radius: 5px;
        border: none;
        margin-right: 10px;
        width: 60%;
        font-size: 18px;
      }
      .sticky-bar input[type="submit"] {
        background-color: #fff;
        color: #333;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-left: 10px;
      }
	  .message-container {
        margin-bottom: 10px;
        padding: 5px 20px;
        background-color: #f0f0f0;
        border-radius: 5px;
		line-height: 1.8;
		letter-spacing: 0.02em;
	}
	.set-color1 {
		color: red;
	}
	.set-color2 {
		color: purple;
	}
	
	
	#chat-buttons {
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  margin-top: 10px;
	}
	
	#chat-buttons button {
	  margin-right: 20px;
	  padding: 0px 20px;
	  border-radius: 5px;
	  cursor: pointer;
	  font-size: 15px;
	  background-color: #36454F;
	  color: #f9f9f9;
	  border: none;
	}
	
	#chat-buttons input[type="file"] {
	  display: none;
	}
	
	#chat-buttons label {
	  display: inline-block;
	  padding: 0px 20px;
	  border-radius: 5px;
	  cursor: pointer;
	  font-size: 15px;
	  background-color: #36454F;
	  color: #f9f9f9;
	  border: none;
	}
	
	
	
	#chat-buttons input[type="file"] + label {
	  margin-right: 10px;
	}
	
	#chat-buttons input[type="file"] + label:before {
	  content: "Load a saved chat";
  }
  
  /* Style the buttons that are used to open and close the accordion panel */
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  text-align: left;
  border: none;
  outline: none;
  transition: 0.4s;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
.active, .accordion:hover {
  background-color: #ccc;
}

/* Style the accordion panel. Note: hidden by default */
.panel {
  padding: 0 18px;
  background-color: white;
  display: none;
  overflow: hidden;
}

.accordion:after {
  content: '\02795'; /* Unicode character for "plus" sign (+) */
  font-size: 13px;
  color: #777;
  float: right;
  margin-left: 5px;
}

.active:after {
  content: "\2796"; /* Unicode character for "minus" sign (-) */
}
  
</style>
</head>
<body>
<div class="container w3-animate-opacity">
	
	<!-- LEFT COLUMN -->
  <div class="left-column">
    <main id="chat">
      <div class="message-container">
			  <span id="first-chat-block" class="set-color1"><b>&#x2022 ChatGPT</b></span>
	        
			  <p>Search for ArXiv research papers using natural language.<br>
				  This demo searches a database of 256 thousand papers in 10 categories, that include Artificial Intelligence, Machine Learning and Robotics.
		    </p>
			
	        <p>To get started please enter a short description of your research work - or search by entering topics, keywords, phrases, similar paper titles and more...</p>
			
	      </div>
		  
		  
	      <!-- Add more message containers here -->
	  </main>
    <div class="sticky-bar">
		
      <form id="myForm" action="chatgpt-api-code.php" method="post">
          <input id="user-input" type="text" name="my_message" placeholder="Enter your search query..."  autofocus>
		  <input type="hidden" name="robotblock">
		  <input id="submit-btn" type="submit" value="Send">
	  </form>
    </div>
	
	<!--The page gets scrolled up to this id.-->
	<div id="chatgpt">
	</div>
	
	<!--Onload a click is simulated on this to scroll the page to id="bottom-bar"-->
	<a href="#chatgpt" id="scroll-page-up"></a>
	
  </div>
  
  
  <!-- RIGHT COLUMN -->
  <div class="right-column w3-black w3-padding">
    <!-- Insert Lorem Ipsum text here -->
	<p>paperRAG<br>
		<i>Ai assisted ArXiv search</i><br>
		<span class="w3-text-blue">GitHub</span>
	</p>
	
	<div id="paper_info">
		
		<!-- Right hand side black panel -->
		<!-- Search results go here -->
		
	</div>
	
	
    <!-- Add more text as needed -->
  </div>
  
</div>


<!--Onload a click is simulated on this to scroll the page to id="bottom-bar"-->
<a href="#paper_info" id="right-panel"></a>	
</body>
</html>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>




<script>

//Simulates a click.
function simulateClick(tabID) {
	
	// Simulate a click.
	document.getElementById(tabID).click();
	
	console.log('clicked...')
	
}

</script>


<!-- Import the utils.js file -->
<script src="utils.js"></script>




<script>
	
// ##### This needs to be changed later so that the bot_name comes from php
// These names are set in name_config.php
// That file has been included at the top of this page.
const bot_name = "<?php echo $bot_name; ?>";
const user_name = "<?php echo $user_name; ?>";

const vectordb_name = "<?php echo $vdb_name; ?>";




// Remove these suffixes. I think removing them makes the chat sound more natural.
// They will sliced off the bot's responses.
// This is done below in the 'Remove suffixes' part of the code.
var suffixes_list = ['How can I help you?', 'How can I assist you today?', 'How can I help you today?', 'Is there anything else you would like to chat about?', 'Is there anything else I can assist you with today?', 'Is there anything I can help you with today?', 'Is there anything else you would like to chat about today?', 'Is there anything else I can assist you with?', 'Is there anything else I can help you with?'];

</script>


<script>
	// Set the name of the bot in the first chat block
	document.getElementById("first-chat-block").innerHTML = "<b>&#x2022 " + bot_name + "</b>";
</script>

<script>
	
// PHP Ajax Code
/////////////////
	
var form = document.getElementById('myForm');

form.onsubmit = function(event) {
	
	
  // Prevent the default form submission behavior
  event.preventDefault();
  // Get the form data
  var formData = new FormData(form);
  
  // Clear the form input
  form.reset();
  
  // Get the value of my_message
  var $my_message = formData.get("my_message");
  //console.log($my_message);
  
  // Format the input into paragraphs. This
  // adds paragrah html to the students chat.
  // It's main use is in Maiya's chat where the long response needs 
  // to be formatted into separate paragraphs.
  $my_message = formatResponse($my_message);

  
  var input_message = {
  sender: user_name,
  text: $my_message
	};
	
	
	//console.log(input_message.text);
	
	
	// Add a user message to the chat
	addMessageToChat(input_message);
	
	// Show the spinner while waiting for the response from openai
	create_spinner_div();
	
	// Scroll to the last message
	scrollToLastMessage();
	
	
	// Scroll the page up by cicking on a div at the bottom of the page.
	//simulateClick('scroll-page-up');
	
	
	// Delete the id from the message container.
	// It will get added again when the message container is created.
	// ******
	var element = document.getElementById("chatgpt1");
	element.removeAttribute("id");
  
  
  
  //console.log(formdata);
  // Send an AJAX request to the server to process the form data
  var xhr = new XMLHttpRequest();
  xhr.open('POST', form.action, true);
  
  xhr.onload = function() {
	  
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
	  
	  var response_text = response.chat_text;
	  
	  var wcs_response = response.wcs_response;
	  
	  // Add the info into the right hand side panel
	  //update_right_panel(wcs_response);
	  
	  //update_right_panel(wcs_response);
	  
	  
	 const num_results_to_display = response.num_results_to_display;
	  
	  show_paper_info_panel(wcs_response, num_results_to_display);
	  
	  simulateClick('right-panel');
	  
	  //scrollToTop();
	  
	  //var title_text = wcs_response['data']['Get']['ARXIV_100SAMPLE_VDB'][0]['title'];

	  
	  // Write the response on the console
      //console.log(response.chat_text);
	  
	  
	  // Replace the suffixes with "":
		// This removes sentences like: How can I help you today?
		// For each suffix in the list...
		 suffixes_list.forEach(suffix => {
	      
			// Replace the suffix with nothing.
	        response_text = response_text.replace(suffix, "");
			
	  	});
		
	  
	  // *** Remove any html and then speak *** //
		////////////////////////////////////////////
		const cleaned_text = removeHtmlTags(response_text);
		//speak(cleaned_text);
	  
	  
	  // Format the response into separate paragrahs
	  var paragraph_response = formatResponse(response_text);
	 
	  
	  //console.log(paragraph_response);
	  
	  var input_message = {
		  sender: bot_name,
	  	text: paragraph_response
		};
	
	
	//console.log(input_message.text);
	
	
	// Delete the div containing the spinner
	delete_spinner_div();

	// Add the message from Maiya to the chat
	addMessageToChat(input_message);
	
	
	
	
	// Scroll to the last message
	scrollToLastMessage();
	
	
	
	
	// Scroll the page up by cicking on a div at the bottom of the page.
	// ***** Canhge this to click on the bot message div, then delete the div id ****
	//simulateClick('scroll-to-bot-message');
	
	
	// Delete the id from the message container.
	// It will get added again when the message container is created.
	// ******
	var element = document.getElementById("chatgpt1");
	element.removeAttribute("id");
	
	
	
	// Only put the cursor into the input field
	// if the user is not using a cellphone.
	// If the cursor is in the input field on a phone then the keyboard
	// gets displayed. This affects the page scrolling to the bot message.
	var screenWidth = window.screen.width;
	var screenHeight = window.screen.height;
	
	// Assuming a threshold of 768 pixels as a cutoff for mobile devices
	var isMobile = screenWidth <= 768;
	
	if (isMobile) {
	  	console.log("User is using a cellphone");
	} else {
	  	console.log("User is not using a cellphone");
	  	// Put the cursor in the form input field
		const inputField = document.getElementById("user-input");
		inputField.focus();
	}
	
	  
    }
  };
  
  xhr.send(formData);
};

</script>

<?php
// This is important.
// If this is not done then the session variables will still
// be available even after the tab is closed. By doing this the
// session variables get deleted when the tab is closed.
// You can print out the message history to confirm that the
// session variable has been deleted: print_r($_SESSION['message_history']);

// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>
