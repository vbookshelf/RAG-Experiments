<?php
/*
----------------------------------------
Capture the input from campaignpage.php
----------------------------------------
*/

//require '/home/soywoza/Run2Give_createconnection/createconnection.php';
//require '/home/soywoza/Run2Give_createconnection/createconnectionbackup.php';


function contact_success(&$isSent, &$contacttick, &$contactsuccess) {

	if ($isSent == true) {
		
		echo '<div class="w3-margin w3-border-color-indigo w3-padding w3-animate-top">
<h5 class="w3-text-indigo">';

		//echo '<span class="w3-badge w3-text-pink w3-border w3-border-color-pink w3-white"><b>' .$contacttick . '</b></span> ';
		echo '<i class="fa fa-check" style="font-size:36px;color:orange"></i><br>';
		echo $contactsuccess;

		echo '</h5></div>';
	}

}


function contact_failure(&$hasFailedContact, &$contactfailure) {

	if ($hasFailedContact == true) {
		
		echo '<div class="w3-margin w3-border-color-indigo w3-padding w3-animate-top">
<h5 class="w3-text-red">';

		echo '<i class="fa fa-umbrella" style="font-size:36px;color:red"></i><br>';
		echo $contactfailure;

		echo '</h5></div>';
	}

}


// Note: I'm changing the name contactrobotblock to email.
// If a robot fills in the email then the form will not submit.
if (isset($_POST["contactbtn"]) && empty($_POST["email"])) {	
	
	$isSent = false;
	$hasFailedContact= false;
	$tabName = "contactus";
	
	
	
//Validate the token - Only applies when using sessions with a login system.
//if(validate_contactus_token($_POST["contactustoken"])) { //if this condition returns true
	
	//If all the fields are empty don't do anything 
	//or the user has only entered a single character
if (empty(test_input($_POST["message3"])) && empty(test_input($_POST["score"]))) {

//do nothing
return;
} else {
	
	
	
	//Assign the array elements to variables. Queries don't work
	//if array elements are used.
	
	$contactmessage = test_input($_POST["message3"]);
	//$id = $_SESSION["id"];
	
	$score = test_input($_POST["score"]);
	
	// These variables are defined on the top of the page for each app.
	//$app_id = $_SESSION["app_id"];
	//$app_title = $_SESSION["app_title"];
	//$app_status = $_SESSION["app_status"];
	
	//$isActive = true;
	
	
	
	$contacttick = "";
	$contactsuccess = "";
	
	//Get the users email address
	//$query = "SELECT * FROM MagicTableCourse WHERE id = '$id' ";
	
	//run the query
	//$result = mysqli_query($conn, $query);
	
	
	//USE A PREPARED STATEMENT HERE
	//$stmt6 = $conn->prepare("SELECT * FROM MagicTableCourse WHERE id = ? "); 
	
	//Bind paramter to the marker
	//$stmt6->bind_param("i", $id);
	
	//Execute the query
	//$stmt6->execute();
	
	//Get the result set
	//$result = $stmt6->get_result();
	
	//convert the result set into an associative array
	//$row = mysqli_fetch_assoc($result);
	
	//get the email address
	//$contactemail = $row["email"];
	//$contactname = $row["username"];
	
	//echo $contactname;
	//echo $contactemail;
	//echo $emotion;
	
	
	//UPDATE THE BACKUP DATABASE
		$stmt8 = $connbackup->prepare("INSERT INTO myeefeedback (feedback_rating, feedback_message) VALUES (?, ?)");
		
		$stmt8->bind_param("is", $score, $contactmessage);
		$stmt8->execute();
	

	
	//UPDATE THE PRIMARY DATABASE
		$stmt8 = $conn->prepare("INSERT INTO myeefeedback (feedback_rating, feedback_message) VALUES (?, ?)");
		
		$stmt8->bind_param("is", $score, $contactmessage);
		$stmt8->execute();

		
		
		if (mysqli_affected_rows($conn) == 1) { //Returns true if the statement was executed
				
				$isSent = true;
				$contacttick = "&#10003";
				$contactsuccess = "Thank you. Your feedback has been received.";
				
				//Clear the form fields
				$_POST["name3"] = "";
				$_POST["message3"] = "";
				
				
				
		} else {
		
			
			$hasFailedContact= true;
			$contactfailure = "There seems to be a problem with the connection. Your message was not sent. Please try again.";
			//echo "Error updating record: " . mysqli_error($conn);
		}
		



	$stmt8->close();
	
	}
	
} else {
//if the token is not valid do nothing
return;
//header("Location: dashboardpage.php");

}

//}
	
?>