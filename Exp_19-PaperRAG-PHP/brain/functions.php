<?php

/*
------------------------------
CHECK IF USER HAS ACTIVE HIRE
------------------------------
*/

function check_if_user_has_active_hire(&$conn, &$app_id) {
	
	// Take note that the app details are hard coded on the same page 
	// where this function is called - above the function call. This is important 
	// to remember. This hard coded data is easy to forget.
	// This function checks if this user has hired this app. 
	
	
	global $hide_button;
	
	$id = $_SESSION["id"];
	
	
	$hide_button = "";
	$unix_time_now = time();

	$stmt7 = $conn->prepare("SELECT * FROM PaypalRenewalTable1 WHERE app_id = ? AND id = ?
		ORDER BY unix_expiry_date DESC LIMIT 1"); 
	
	//Bind paramter to the marker
	$stmt7->bind_param("ss", $app_id, $id);
	
	//Execute the query
	$stmt7->execute();
	
	//Get the result set
	$result7 = $stmt7->get_result();
	
	//only one row in the result set. convert the result set into an associative array
	$row7 = mysqli_fetch_assoc($result7);

	
	//Determine if the app was purchased previously.
	$numrows = mysqli_num_rows($result7);
	
	if ($numrows == 0) {
		//echo "one";
		header("Location: hire_expired.html");
	}
	
	if ($row7['unix_expiry_date'] < $unix_time_now) {
		
		//echo "two";
		
		header("Location: hire_expired.html");
		
	}


}



/*
------------------------------------------
CHECK IF USER HAS EXCEEDED THE DEMO LIMIT
-------------------------------------------
*/

function check_if_user_has_exceeded_demo_limit(&$conn, &$app_id, &$demo_limit) {
	
	global $num_preds_made;
	
	// Take note that the app details are hard coded on the same page 
	// where this function is called - above the function call. This is important 
	// to remember. This hard coded data is easy to forget.
	// This function monitors how many demo predictions the user has made.
	
	
	$id = $_SESSION["id"];
	$app_status = 'demo';
	
	
	$hide_button = "";
	$unix_time_now = time();

	$stmt7 = $conn->prepare("SELECT * FROM MachineApiCalls1 WHERE app_id = ? AND id = ? AND app_status = ?"); 
	
	//Bind paramter to the marker
	$stmt7->bind_param("sss", $app_id, $id, $app_status);
	
	//Execute the query
	$stmt7->execute();
	
	//Get the result set
	$result7 = $stmt7->get_result();
	
	//only one row in the result set. convert the result set into an associative array
	$row7 = mysqli_fetch_assoc($result7);

	
	//Determine how many predictions (API calls) the user has made.
	$numrows = mysqli_num_rows($result7);
	

	if ($numrows > $demo_limit) {
		
		// Redirect to the demo_expired page.
		header("Location: demo_expired.html");
		
	}
	
	// Make the $num_preds_made variable available outside this function. 
	$num_preds_made = $numrows;


}









/*
-----------------------
LOAD APP INFO
-----------------------
Loads info from the DraftCampaigns table.
*/


function load_app_info(&$conn) {

	global $app_id, $app_title, $app_price, $path_to_image, $path_to_app, $path_to_demo_app, $paypal_button_id;
	
	$app_id = $_SESSION["app_id"]; //putting a $_SESSION["id"] below creates an error
	$isActive = true;
	
	//reverse the table. last entry now appears first. list is limited to only one row
		//$sql1 = "SELECT * FROM DraftCampaigns WHERE id = '$id' AND isObselete = '$isObselete' ORDER BY draft_id DESC LIMIT 1";
		//$result1 = mysqli_query($conn, $sql1);
		
		
		//USE A PREPARED STATEMENT HERE
	$stmt6 = $conn->prepare("SELECT * FROM AppStoreList4 WHERE app_id = ? AND isActive = ? ORDER BY app_id DESC LIMIT 1"); 
	
	//Bind paramter to the marker
	$stmt6->bind_param("ss", $app_id, $isActive);
	
	//Execute the query
	$stmt6->execute();
	
	//Get the result set
	$result1 = $stmt6->get_result();

	//only one row in the result set. convert the result set into an associative array
	$row = mysqli_fetch_assoc($result1);
	
	//determine the number of rows in the result set
	//$numrows = mysqli_num_rows($result1);
	
	$app_id = $row["app_id"];
	$app_price = $row["app_price"];
	$app_title = $row["app_title"];
	$path_to_image = $row["path_to_image"];
	$path_to_app = $row["path_to_app"];
	$paypal_button_id = $row["paypal_button_id"];
	
	$path_to_demo_app = $row["path_to_demo_app"];
	
		
	}
	
	
	
	

	
	
/*
---------------------------
LOAD PAYPAL PURCHASED APPS
---------------------------
*/


function load_paypal_purchased_apps(&$conn) {
	
	
	$id = $_SESSION["id"]; //putting a $_SESSION["id"] below creates an error

		
		
		//USE A PREPARED STATEMENT HERE
	$stmt6 = $conn->prepare("SELECT * FROM PaypalRenewalTable1 WHERE id = ? ORDER BY renewal_id DESC"); 
	
	//Bind paramter to the marker
	$stmt6->bind_param("s", $id);
	
	//Execute the query
	$stmt6->execute();
	
	//Get the result set
	$result1 = $stmt6->get_result();

		//only one row in the result set. convert the result set into an associative array
		$row = mysqli_fetch_assoc($result1);
		
		//determine the number of rows in the result set
		$numrows1 = mysqli_num_rows($result1);
		

		//If the user has no records, print an example row
	if ($numrows1 == 0) {
		//print an example entry
		echo '<h5 class="w3-center w3-text-blue space-letters"><i><b>Hello! Welcome to Woza Ai Tools.</b></i></h5>';
		
			echo '<p class="w3-center w3-text-grey space-letters">The machines you hire will appear here.<br>
			Get started by heading over to the store and<br>
			trying a demo.</p>';
		
		
		
			// Print the example machine
		// Print each array element
			echo '<div class="w3-white w3-margin unblock" style="width:250px">';
			echo '<div class="text-color w3-border w3-border-blue w3-round w3-padding">';
				
				echo '<div class="w3-text-blue">';
					   echo '<h5 class="space-letters"><b>Mach</b></h5> ' ;
				 echo '</div>';
				
			echo '<img src="assets/robot12.png" alt="machine image" style="width:150px">';
				
			echo '<h5><b>-----</b></h5>';
			
			
			echo '<div class="price-text-size" style="margin-bottom:-15px">';
			echo '<p class="price-text-size1 text-color space-letters">0<br><span class="w3-text-teal w3-small w3-round space-letters"><b>days remaining</b></p>';
			echo '</div>';
			
			echo '<form method="post">';
				echo '<input class="w3-input" type="hidden" placeholder="Password" name="launchrobotblock">';
					
				echo '<input type="hidden" name="app_id" value="0">';
				
				
				echo '<input type="submit" id="paypal_buy_button" value="---" name="" class="w3-btn-block w3-text-blue w3-white w3-small space-letters">';
				
				echo '</form>';
			
			
				
				echo '<div class="w3-center topp-margin buy">';
				echo '<p><a class="w3-round w3-margin-bottom adjust-spacing btn-font w3-padding space-letters w3-text-blue w3-border w3-border-blue w3-hover-blue" href="dashboardpage.php">';
					echo '<b>-----</b></a></p>';
				echo '</div>';
				

				
				
				
			echo '</div>';
			echo '</div>';
			

			
		
		
		
		
		
	} else {
	
		//loop through each row
		for ($x = 0; $x < $numrows1; $x++) {
		
			//identify the row number (row 1, row 2, etc)
			mysqli_data_seek($result1, $x);
		
			//convert that row into an associative array
			$row = mysqli_fetch_assoc($result1);

			
			$app_id = test_output($row["app_id"]);
			
			
			// $unix_expiry_date was calculated in listener.php.
			// It checks whether the user has previously bought this app
			// and it has not expired yet. If it has not expired then the new
			// purchase expiry dated was calculated from the future day when the
			// previous purchase will expire.
			$unix_expiry_date = $row["unix_expiry_date"];
			
			
			// Get the current time.
			$unix_todays_date = time();
			
			
			// The default number of decimal places is 0.
			$num_days_remaining = round(($unix_expiry_date - $unix_todays_date) / 86400);
			
			
			// Set the value to zero if number of days is less than 0.
			if ($num_days_remaining < 0) {
				
				$num_days_remaining = 0;
			}
			

			
			//Here we are loading the app title, the path to the image and
			// the path to the app from the main AppStoreList.
			$stmt7 = $conn->prepare("SELECT * FROM AppStoreList4 WHERE app_id = ? ORDER BY app_id DESC LIMIT 1"); 
			
			//Bind paramter to the marker
			$stmt7->bind_param("s", $app_id);
			
			//Execute the query
			$stmt7->execute();
			
			//Get the result set
			$result2 = $stmt7->get_result();
		
			//only one row in the result set. convert the result set into an associative array
			$row2 = mysqli_fetch_assoc($result2);
			
			//determine the number of rows in the result set
			//$numrows = mysqli_num_rows($result1);
			
			//$app_id = $row["app_id"];
			//$app_price = $row["app_price"];
			$app_title = $row2["app_title"];
			$path_to_image = $row2["path_to_image"];
			$path_to_app = $row2["path_to_app"];
			
			

			
			
			
			// Print each array element
			echo '<div class="w3-white w3-margin unblock" style="width:250px">';
			echo '<div class="text-color w3-border w3-border-blue w3-round w3-padding">';
				
				echo '<div class="w3-text-blue">';
					   echo '<h5 class="space-letters"><b>Mach-0'. $app_id .'</b></h5> ' ;
				 echo '</div>';
				
			echo '<img src="'. $path_to_image .'" alt="machine image" style="width:150px">';
				
			echo '<h5><b>'. $app_title . '</b></h5>';
			
			
			echo '<div class="price-text-size" style="margin-bottom:-15px">';
			echo '<p class="price-text-size1 text-color space-letters">'. $num_days_remaining .'<br><span class="w3-text-teal w3-small w3-round space-letters"><b>days remaining</b></p>';
			echo '</div>';
			
			echo '<form method="post">';
				echo '<input class="w3-input" type="hidden" placeholder="Password" name="launchrobotblock">';
					
				echo '<input type="hidden" name="app_id" value="'. $app_id .'">';
				
				
				echo '<input type="submit" id="paypal_buy_button" value="RENEW" name="launchcampaign" class="w3-btn-block w3-text-blue w3-white w3-small space-letters">';
				
				echo '</form>';
			
			
				
				echo '<div class="w3-center topp-margin buy">';
				echo '<p><a class="w3-round w3-margin-bottom adjust-spacing btn-font w3-padding space-letters w3-text-blue w3-border w3-border-blue w3-hover-blue" href=" '.$path_to_app .' ">';
					echo '<b>Launch</b></a></p>';
				echo '</div>';
				

				
				
				
			echo '</div>';
			echo '</div>';
			
			
			
			
			
			
					
		}
	}
					
					
}
		







/*
---------------------------
LOAD PAYPAL PURCHASE HISTORY
---------------------------
*/


function load_paypal_purchase_history(&$conn) {
	
	
	$id = $_SESSION["id"]; //putting a $_SESSION["id"] below creates an error

	$custom = $id;
		
		//USE A PREPARED STATEMENT HERE
	$stmt6 = $conn->prepare("SELECT * FROM PaypalPaymentHistory2 WHERE custom = ? ORDER BY payment_id DESC"); 
	
	//Bind paramter to the marker
	$stmt6->bind_param("s", $custom);
	
	//Execute the query
	$stmt6->execute();
	
	//Get the result set
	$result1 = $stmt6->get_result();

		//only one row in the result set. convert the result set into an associative array
		$row = mysqli_fetch_assoc($result1);
		
		//determine the number of rows in the result set
		$numrows1 = mysqli_num_rows($result1);
		

		//If the user has no records, print an example row
	if ($numrows1 == 0) {
		//print an example entry
		echo '<p class="w3-center w3-text-grey space-letters">You have not hired any machines yet.</p>';
		
	} else {
	
		//loop through each row
		for ($x = 0; $x < $numrows1; $x++) {
		
			//identify the row number (row 1, row 2, etc)
			mysqli_data_seek($result1, $x);
		
			//convert that row into an associative array
			$row = mysqli_fetch_assoc($result1);
			
			$app_id = test_output($row["item_number"]);
			$app_title = test_output($row["item_name"]);
			$payment_date = test_output($row["payment_date"]);
			$payment_gross = test_output($row["payment_gross"]);
			$txn_id = test_output($row["txn_id"]);
			$payment_id = test_output($row["payment_id"]);
			
			
			

			
			//Here we are loading the app title, the path to the image and
			// the path to the app from the main AppStoreList.
			$stmt7 = $conn->prepare("SELECT * FROM AppStoreList4 WHERE app_id = ? ORDER BY app_id DESC LIMIT 1"); 
			
			//Bind paramter to the marker
			$stmt7->bind_param("s", $app_id);
			
			//Execute the query
			$stmt7->execute();
			
			//Get the result set
			$result2 = $stmt7->get_result();
		
			//only one row in the result set. convert the result set into an associative array
			$row2 = mysqli_fetch_assoc($result2);
			
			//determine the number of rows in the result set
			//$numrows = mysqli_num_rows($result1);
			
			//$app_id = $row["app_id"];
			//$app_price = $row["app_price"];
			$app_title = $row2["app_title"];
			$path_to_image = $row2["path_to_image"];
			$path_to_app = $row2["path_to_app"];
			
			

			
			
			
			// Print each array element
			echo '<div class="w3-white w3-margin unblock" style="width:250px">';
			echo '<div class="text-color w3-border w3-border-blue w3-round w3-padding">';
			
			echo '<div class="text-color">';
					   echo '<p class="space-letters w3-text-grey"><b>'. $payment_date .'</b></p> ' ;
					   echo '<p class="space-letters"><b>'. $payment_gross .' USD</b></p> ' ;
					   
					   echo '</div>';
				
				echo '<div class="w3-text-blue">';
					   echo '<p class="space-letters"><b>Mach-0'. $app_id .'</b></p> ' ;
				 echo '</div>';
				
				
			echo '<p><b>'. $app_title . '</b></p>';
			
			echo '<p class="w3-text-grey">Paypal txn_id :<br><b>'. $txn_id . '</b></p>';
			
			
				
			echo '</div>';
			echo '</div>';
			
			
			
			
			
			
					
		}
	}
					
					
}
		











/*
-----------------------
LOAD APPS FOR SALE
-----------------------
*/


function load_apps_for_sale(&$conn) {

global $app_id, $app_title, $app_description, $app_price, $path_to_image, $path_to_app, $path_to_demo_app, $paypal_button_id, $numrows1;
	
	$id = $_SESSION["id"]; //putting a $_SESSION["id"] below creates an error

	$isActive = True;
	
	//reverse the table. last entry now appears first. list is limited to only 7 rows
		//$sql1 = "SELECT * FROM CampaignHistory1 WHERE id = '$id' ORDER BY campaign_id DESC";
		//$result1 = mysqli_query($conn, $sql1);
		
		
		//USE A PREPARED STATEMENT HERE
	$stmt6 = $conn->prepare("SELECT * FROM AppStoreList4 WHERE isActive = ? ORDER BY app_id"); 
	
	//Bind paramter to the marker
	$stmt6->bind_param("s", $isActive);
	
	//Execute the query
	$stmt6->execute();
	
	//Get the result set
	$result1 = $stmt6->get_result();

		//only one row in the result set. convert the result set into an associative array
		$row = mysqli_fetch_assoc($result1);
		
		//determine the number of rows in the result set
		$numrows1 = mysqli_num_rows($result1);
		
	
		//loop through each row
		for ($x = 0; $x < $numrows1; $x++) {
		
			//identify the row number (row 1, row 2, etc)
			mysqli_data_seek($result1, $x);
		
			//convert that row into an associative array
			$row = mysqli_fetch_assoc($result1);
			
		
		
		
		$app_id1 = test_output($row["app_id"]);
		$app_title1 = test_output($row["app_title"]);
		$app_description1 = test_output($row["app_description"]);
		$app_price1 = test_output($row["app_price"]);
		$path_to_image1 = test_output($row["path_to_image"]);
		$path_to_app1 = test_output($row["path_to_app"]);
		$paypal_button_id1 = test_output($row["paypal_button_id"]);
		
		$path_to_demo_app1 = test_output($row["path_to_demo_app"]);
			
		//$path_to_demo = "APPS/app-wheatcounter/demo_wheatcounter.php";
		
		
	
		//$url1 = trim($url1);
		
// The app_id is part of the form.
// When the form is submitted we save the app_id as a session 
// variable so that it can be referenced on the payment confirmation page.

echo '<div class="w3-white w3-margin unblock" style="width:300px">';
echo '<div class="text-color w3-border w3-border-blue w3-round w3-padding">';
	
	echo '<div class="w3-text-blue">';
		   echo '<h5 class="space-letters"><b>Mach-0'. $app_id1 .'</b></h5>'; 
	 echo '</div>';
	
echo '<img src="'. $path_to_image1 .'" style="width:80%">';
	
echo '<h5><b>'. $app_title1 .'</b></h5>';
	
	echo '<div>';
		echo '<p class="w3-text-grey">'. $app_description1 .'</p>';
	echo '</div>';
	
	echo '<div class="topp-margin price-text-size">';
	  echo '<p class="text-color"><b>$ '. $app_price1 .'</b><span class="price-text-size1 w3-text-teal w3-opacity">/month</span></p>';
	echo '</div>';
	


	echo '<form method="post">';
			echo '<input class="w3-input w3-border" type="hidden" placeholder="Password" name="launchrobotblock">';
				
			echo '<input type="hidden" name="app_id" value="'. $app_id1 .'">';
			
			echo '<div class="w3-padding-8 unblock">';
					echo '<input type="submit" value="Hire Machine" name="launchcampaign" class="w3-padding w3-round w3-white w3-border w3-text-blue space-letters w3-border-blue make-bold">';
			echo '</div>';
			

			
			echo '<div class="w3-center w3-padding unblock">';
	echo '<p><a class="w3-round w3-margin-bottom demo-padding space-letters w3-text-grey w3-border w3-border-grey" href=" '. $path_to_demo_app1 .' ">';
		echo '<b>Demo</b></a></p>';
		echo '</div>';
			
	echo '</form>';
	
	
	
echo '</div>';
echo '</div>';

		
		}
	}
	


	
/*
------------------------------------------
LOAD APPS FOR SALE - DONT DISPLAY BUTTONS
------------------------------------------
*/


function load_apps_for_sale_nobuybutton(&$conn) {

global $app_id, $app_title, $app_description, $app_price, $path_to_image, $path_to_app, $path_to_demo_app, $paypal_button_id, $numrows1;
	
	$id = $_SESSION["id"]; //putting a $_SESSION["id"] below creates an error

	$isActive = True;
	
	//reverse the table. last entry now appears first. list is limited to only 7 rows
		//$sql1 = "SELECT * FROM CampaignHistory1 WHERE id = '$id' ORDER BY campaign_id DESC";
		//$result1 = mysqli_query($conn, $sql1);
		
		
		//USE A PREPARED STATEMENT HERE
	$stmt6 = $conn->prepare("SELECT * FROM AppStoreList4 WHERE isActive = ? ORDER BY app_id"); 
	
	//Bind paramter to the marker
	$stmt6->bind_param("s", $isActive);
	
	//Execute the query
	$stmt6->execute();
	
	//Get the result set
	$result1 = $stmt6->get_result();

		//only one row in the result set. convert the result set into an associative array
		$row = mysqli_fetch_assoc($result1);
		
		//determine the number of rows in the result set
		$numrows1 = mysqli_num_rows($result1);
		
	
		//loop through each row
		for ($x = 0; $x < $numrows1; $x++) {
		
			//identify the row number (row 1, row 2, etc)
			mysqli_data_seek($result1, $x);
		
			//convert that row into an associative array
			$row = mysqli_fetch_assoc($result1);
			
		
		
		
		$app_id1 = test_output($row["app_id"]);
		$app_title1 = test_output($row["app_title"]);
		$app_description1 = test_output($row["app_description"]);
		$app_price1 = test_output($row["app_price"]);
		$path_to_image1 = test_output($row["path_to_image"]);
		$path_to_app1 = test_output($row["path_to_app"]);
		$paypal_button_id1 = test_output($row["paypal_button_id"]);
		
		$path_to_demo_app1 = test_output($row["path_to_demo_app"]);
		
		//$path_to_demo = "APPS/app-wheatcounter/demo_wheatcounter.php";
		
		
	
		//$url1 = trim($url1);
		
// The app_id is part of the form.
// When the form is submitted we save the app_id as a session 
// variable so that it can be referenced on the payment confirmation page.

echo '<div class="w3-white w3-margin unblock" style="width:300px">';
echo '<div class="text-color w3-border w3-border-blue w3-round w3-padding w3-hover-shadow">';
	
	echo '<div class="w3-text-blue">';
		   echo '<h5 class="space-letters"><b>Mach-0'. $app_id1 .'</b></h5>'; 
	 echo '</div>';
	
echo '<img src="'. $path_to_image1 .'" style="width:80%">';
	
echo '<h5><b>'. $app_title1 .'</b></h5>';
	
	echo '<div>';
		echo '<p class="w3-text-grey">'. $app_description1 .'</p>';
	echo '</div>';
	
	echo '<div class="topp-margin price-text-size">';
	  echo '<p class="text-color"><b>$ '. $app_price1 .'</b><span class="price-text-size1 w3-text-teal w3-opacity">/month</span></p>';
	echo '</div>';
	
	
	
echo '</div>';
echo '</div>';

		
		}
	}
	



/*
-----------------------------
LOAD APPS FOR SALE BY FILTER
-----------------------------
*/


function load_apps_for_sale_by_filter(&$conn, &$app_type) {

global $app_id, $app_title, $app_description, $app_price, $path_to_image, $path_to_app, $paypal_button_id, $app_type_chosen, $numrows1;
	
	$id = $_SESSION["id"]; //putting a $_SESSION["id"] below creates an error

	$isActive = True;
	
	$app_type_chosen = $app_type;
	
	//reverse the table. last entry now appears first. list is limited to only 7 rows
		//$sql1 = "SELECT * FROM CampaignHistory1 WHERE id = '$id' ORDER BY campaign_id DESC";
		//$result1 = mysqli_query($conn, $sql1);
		
		
		//USE A PREPARED STATEMENT HERE
	$stmt6 = $conn->prepare("SELECT * FROM AppStoreList4 WHERE app_type = ? AND isActive = ? ORDER BY app_id"); 
	
	//Bind paramter to the marker
	$stmt6->bind_param("ss", $app_type, $isActive);
	
	//Execute the query
	$stmt6->execute();
	
	//Get the result set
	$result1 = $stmt6->get_result();

		//only one row in the result set. convert the result set into an associative array
		$row = mysqli_fetch_assoc($result1);
		
		//determine the number of rows in the result set
		$numrows1 = mysqli_num_rows($result1);
		
	
		//loop through each row
		for ($x = 0; $x < $numrows1; $x++) {
		
			//identify the row number (row 1, row 2, etc)
			mysqli_data_seek($result1, $x);
		
			//convert that row into an associative array
			$row = mysqli_fetch_assoc($result1);
			
		
		
		
		$app_id1 = test_output($row["app_id"]);
		$app_title1 = test_output($row["app_title"]);
		$app_description1 = test_output($row["app_description"]);
		$app_price1 = test_output($row["app_price"]);
		$path_to_image1 = test_output($row["path_to_image"]);
		$path_to_app1 = test_output($row["path_to_app"]);
		$paypal_button_id1 = test_output($row["paypal_button_id"]);
		
		$path_to_demo_app1 = test_output($row["path_to_demo_app"]);
		
		
		//$path_to_demo = "APPS/app-wheatcounter/demo_wheatcounter.php";
		
		
	
		//$url1 = trim($url1);
		
// The app_id is part of the form.
// When the form is submitted we save the app_id as a session 
// variable so that it can be referenced on the payment confirmation page.

echo '<div class="w3-white w3-margin unblock" style="width:300px">';
echo '<div class="text-color w3-border w3-border-blue w3-round w3-padding">';
	
	echo '<div class="w3-text-blue">';
		   echo '<h5 class="space-letters"><b>Mach-0'. $app_id1 .'</b></h5>'; 
	 echo '</div>';
	
echo '<img src="'. $path_to_image1 .'" style="width:80%">';
	
echo '<h5><b>'. $app_title1 .'</b></h5>';
	
	echo '<div>';
		echo '<p class="w3-text-grey">'. $app_description1 .'</p>';
	echo '</div>';
	
	echo '<div class="topp-margin price-text-size">';
	  echo '<p class="text-color"><b>'. $app_price1 .'</b><span class="price-text-size1 w3-text-teal w3-opacity">/month</span></p>';
	echo '</div>';
	


	echo '<form method="post">';
			echo '<input class="w3-input w3-border" type="hidden" placeholder="Password" name="launchrobotblock">';
				
			echo '<input type="hidden" name="app_id" value="'. $app_id1 .'">';
			
			echo '<div class="w3-padding-8 unblock">';
					echo '<input type="submit" value="Hire Machine" name="launchcampaign" class="w3-padding w3-round w3-white w3-border w3-text-blue space-letters w3-border-blue make-bold">';
			echo '</div>';
			

			
			echo '<div class="w3-center w3-padding unblock">';
	echo '<p><a class="w3-round w3-margin-bottom demo-padding space-letters w3-text-grey w3-border w3-border-grey" href=" '. $path_to_demo_app1 .' ">';
		echo '<b>Demo</b></a></p>';
		echo '</div>';
			
	echo '</form>';
	
	
	
echo '</div>';
echo '</div>';

		
		}
	}
	


	
/*
-------------------------------------------------
LOAD APPS FOR SALE BY FILTER - DONT SHOW BUTTONS
-------------------------------------------------
*/


function load_apps_for_sale_by_filter_nobuybutton(&$conn, &$app_type) {

global $app_id, $app_title, $app_description, $app_price, $path_to_image, $path_to_app, $path_to_demo_app, $paypal_button_id, $app_type_chosen, $numrows1;
	
	$id = $_SESSION["id"]; //putting a $_SESSION["id"] below creates an error

	$isActive = True;
	
	$app_type_chosen = $app_type;
	
	//reverse the table. last entry now appears first. list is limited to only 7 rows
		//$sql1 = "SELECT * FROM CampaignHistory1 WHERE id = '$id' ORDER BY campaign_id DESC";
		//$result1 = mysqli_query($conn, $sql1);
		
		
		//USE A PREPARED STATEMENT HERE
	$stmt6 = $conn->prepare("SELECT * FROM AppStoreList4 WHERE app_type = ? AND isActive = ? ORDER BY app_id"); 
	
	//Bind paramter to the marker
	$stmt6->bind_param("ss", $app_type, $isActive);
	
	//Execute the query
	$stmt6->execute();
	
	//Get the result set
	$result1 = $stmt6->get_result();

		//only one row in the result set. convert the result set into an associative array
		$row = mysqli_fetch_assoc($result1);
		
		//determine the number of rows in the result set
		$numrows1 = mysqli_num_rows($result1);
		
	
		//loop through each row
		for ($x = 0; $x < $numrows1; $x++) {
		
			//identify the row number (row 1, row 2, etc)
			mysqli_data_seek($result1, $x);
		
			//convert that row into an associative array
			$row = mysqli_fetch_assoc($result1);
			
		
		
		
		$app_id1 = test_output($row["app_id"]);
		$app_title1 = test_output($row["app_title"]);
		$app_description1 = test_output($row["app_description"]);
		$app_price1 = test_output($row["app_price"]);
		$path_to_image1 = test_output($row["path_to_image"]);
		$path_to_app1 = test_output($row["path_to_app"]);
		$paypal_button_id1 = test_output($row["paypal_button_id"]);
		
		$path_to_demo_app1 = test_output($row["path_to_demo_app"]);
		//$path_to_demo = "APPS/app-wheatcounter/demo_wheatcounter.php";
		
		
	
		//$url1 = trim($url1);
		
// The app_id is part of the form.
// When the form is submitted we save the app_id as a session 
// variable so that it can be referenced on the payment confirmation page.

echo '<div class="w3-white w3-margin unblock" style="width:300px">';
echo '<div class="text-color w3-border w3-border-blue w3-round w3-padding w3-hover-shadow">';
	
	echo '<div class="w3-text-blue">';
		   echo '<h5 class="space-letters"><b>Mach-0'. $app_id1 .'</b></h5>'; 
	 echo '</div>';
	
echo '<img src="'. $path_to_image1 .'" style="width:80%">';
	
echo '<h5><b>'. $app_title1 .'</b></h5>';
	
	echo '<div>';
		echo '<p class="w3-text-grey">'. $app_description1 .'</p>';
	echo '</div>';
	
	echo '<div class="topp-margin price-text-size">';
	  echo '<p class="text-color"><b>'. $app_price1 .'</b><span class="price-text-size1 w3-text-teal w3-opacity">/month</span></p>';
	echo '</div>';
	
	
	
echo '</div>';
echo '</div>';

		
		}
	}
	
	
	
/*
--------------------------
RECORD USER LOGIN HISTORY
--------------------------
*/
	
function record_user_login_history(&$conn, &$connbackup, &$id, &$username, &$email) {
	
	
	
	date_default_timezone_set("Africa/Johannesburg");
	$za_added_date = date("d-m-Y");
	$za_added_time = date("h:i:sa");
	
	$unix_login_date = time(); // unix time now.
	$isActive = true;
	
	
	
	//UPDATE THE PRIMARY DATABASE
		$stmt10 = $conn->prepare("INSERT INTO UserLoginHistory1 (id, username, email, za_added_date, za_added_time, unix_login_date, isActive) VALUES (?, ?, ?, ?, ?, ?, ?)");
		
		$stmt10->bind_param("sssssis", $id, $username, $email, $za_added_date, $za_added_time, $unix_login_date, $isActive);
		
		$stmt10->execute();

		
		$stmt10->close();
		
		
	//UPDATE THE BACKUP DATABASE
		$stmt10 = $connbackup->prepare("INSERT INTO UserLoginHistory1 (id, username, email, za_added_date, za_added_time, unix_login_date, isActive) VALUES (?, ?, ?, ?, ?, ?, ?)");
		
		$stmt10->bind_param("sssssis", $id, $username, $email, $za_added_date, $za_added_time, $unix_login_date, $isActive);
		
		$stmt10->execute();

		
		$stmt10->close();
		
	
	
}
	








/*
-----------------------
LOGOUT IF INACTIVE
-----------------------
Logs the user out. Used in the guard function.
*/
function logout() {

//Delete the cookies from the users computer
//If this is not done, the user will be automtically logged in when he visits the home page later.
if (isset($_COOKIE["rememberUserCookie"])) {

		unset($_COOKIE["rememberUserCookie"]);
		setcookie("rememberUserCookie", null, -1, "/");
}

//unset the session variables
session_unset();

//destroy the session
session_destroy();

session_regenerate_id(true);

//redirect to the login page
header('location: logout.php');

}

function logout1() {

//Delete the cookies from the users computer
//If this is not done, the user will be automtically logged in when he visits the home page later.
if (isset($_COOKIE["rememberUserCookie"])) {

		unset($_COOKIE["rememberUserCookie"]);
		setcookie("rememberUserCookie", null, -1, "/");
}

//unset the session variables
session_unset();

//destroy the session
session_destroy();

session_regenerate_id(true);

}


/*
----------------------------------------
LOGOUT IF INACTIVE FOR A SPECIFIED TIME
----------------------------------------
Logs the user out if he is inactive for a specified time.
*/


function guard() {

//echo "Guard is active";



	$isValid = true;
	
	//set the time limit
	$inactivetime = 60 * 120; //120 minutes
	
	//get the user's ip address and user agent info
	//(The user agent is the info the browser sends to the 
	//server to identify itself.)
	//This info is then hashed using md5 (now sha256).
	
	
	$thisfingerprint = crypt($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'], CRYPT_SHA_256);
	//$thisfingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);


	//check if the time set session variable set at login minus current time is greater than
	//the allowed inactive time.
	if (isset($_SESSION['login_fingerprint']) && $_SESSION['login_fingerprint'] != $thisfingerprint) {
		
		$isValid = false;
		logout();
		
		//} else if (isset($_SESSION['last_active']) && (time() - $_SESSION['last_active'] > $inactivetime) && isset($_SESSION['id'])) { 
		
		
		//$isValid = false;
		//logout();
		
	//} else {
	
	//Note: This only happens when the page is reloaded not when the user opens
	//a new tab.
		
		//$_SESSION['last_active'] = time();
			
	}

return $isValid; //returns true or false when this function is echoed.

}

/*
----------------------------------------
CREATES A RANDOM STRING (TOKEN) AND ENCODES IT
----------------------------------------
Used to prevent CSRF attacks.
*/

function _logintoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['logintoken'] = $randomToken;

return $_SESSION['logintoken'];
}


function _registertoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['registertoken'] = $randomToken;

return $_SESSION['registertoken'];
}



function _createcampaigntoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['createcampaigntoken'] = $randomToken;

return $_SESSION['createcampaigntoken'];
}


function _updatecampaigntoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['updatecampaigntoken'] = $randomToken;

return $_SESSION['updatecampaigntoken'];
}

function _contactustoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['contactustoken'] = $randomToken;

return $_SESSION['contactustoken'];
}


function _endcampaigntoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['endcampaigntoken'] = $randomToken;

return $_SESSION['endcampaigntoken'];
}


function _sponsorcommentstoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['sponsorcommentstoken'] = $randomToken;

return $_SESSION['sponsorcommentstoken'];
}


function _changeusernametoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['changeusernametoken'] = $randomToken;

return $_SESSION['changeusernametoken'];
}


function _changeemailtoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['changeemailtoken'] = $randomToken;

return $_SESSION['changeemailtoken'];
}



function _changepasswordtoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['changepasswordtoken'] = $randomToken;

return $_SESSION['changepasswordtoken'];
}



function _passwordrecoveryemailtoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['passwordrecoveryemailtoken'] = $randomToken;

return $_SESSION['passwordrecoveryemailtoken'];
}



function _passwordresettoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['passwordresettoken'] = $randomToken;

return $_SESSION['passwordresettoken'];
}


function _confirmpaymenttoken() {

$randomToken = base64_encode(openssl_random_pseudo_bytes(32));

$_SESSION['confirmpaymenttoken'] = $randomToken;

return $_SESSION['confirmpaymenttoken'];
}


/*
--------------------
VALIDATES THE TOKEN
--------------------
Used to prevent CSRF attacks.
*/

function validate_login_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['logintoken']) && trim($formToken) === trim($_SESSION['logintoken'])) {
	
		unset($_SESSION['logintoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_register_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['registertoken']) && trim($formToken) === trim($_SESSION['registertoken'])) {
	
		unset($_SESSION['registertoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_createcampaign_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['createcampaigntoken']) && trim($formToken) === trim($_SESSION['createcampaigntoken'])) {
	
		unset($_SESSION['createcampaigntoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_updatecampaign_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['updatecampaigntoken']) && trim($formToken) === trim($_SESSION['updatecampaigntoken'])) {
	
		unset($_SESSION['updatecampaigntoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_contactus_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['contactustoken']) && trim($formToken) === trim($_SESSION['contactustoken'])) {
	
		unset($_SESSION['contactustoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_endcampaign_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['endcampaigntoken']) && trim($formToken) === trim($_SESSION['endcampaigntoken'])) {
	
		unset($_SESSION['endcampaigntoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_sponsorcomments_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['sponsorcommentstoken']) && trim($formToken) === trim($_SESSION['sponsorcommentstoken'])) {
	
		unset($_SESSION['sponsorcommentstoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_changeusername_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['changeusernametoken']) && trim($formToken) === trim($_SESSION['changeusernametoken'])) {
	
		unset($_SESSION['changeusernametoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_changeemail_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['changeemailtoken']) && trim($formToken) === trim($_SESSION['changeemailtoken'])) {
	
		unset($_SESSION['changeemailtoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_changepassword_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['changepasswordtoken']) && trim($formToken) === trim($_SESSION['changepasswordtoken'])) {
	
		unset($_SESSION['changepasswordtoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_passwordrecoveryemail_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['passwordrecoveryemailtoken']) && trim($formToken) === trim($_SESSION['passwordrecoveryemailtoken'])) {
	
		unset($_SESSION['passwordrecoveryemailtoken']);
		
		return true;
	}

	return false; //function returns false by default.
}



function validate_passwordreset_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['passwordresettoken']) && trim($formToken) === trim($_SESSION['passwordresettoken'])) {
	
		unset($_SESSION['passwordresettoken']);
		
		return true;
	}

	return false; //function returns false by default.
}


function validate_confirmpayment_token($formToken) {

	/*
	If the session token is not set it means that
	the request does not originate from the browser
	of a logged in user.
	$formToken is sent along with the form inputs.
	The trim() function must be used or the two values will
	be assessed as unequal even though they look equal when echoed.
	*/
	if (isset($_SESSION['confirmpaymenttoken']) && trim($formToken) === trim($_SESSION['confirmpaymenttoken'])) {
	
		unset($_SESSION['confirmpaymenttoken']);
		
		return true;
	}

	return false; //function returns false by default.
}



/*
--------------------------------
5. REMEMBER ME
--------------------------------
This function creates a cookie that logs a user in automatically
if he hasn't closed the browser after initially logging in.
*/

function remember_me(&$id) {

	$encryptCookieData = base64_encode("hemsowl#skejskd!kdkjc{$id}");
	
	//Set the cookie to expire when the user closes the browser.
	//This is done by seeting the duration to 0.
	//Cookie is set to expire after 30 days
	setcookie("rememberUserCookie", $encryptCookieData, time()+60*60*24*100, "/");

}


/*
--------------------------------
5. IS COOKIE VALID
--------------------------------
*/

function is_cookie_valid(&$conn) {

	$isValid = false;
	
	if (isset($_COOKIE["rememberUserCookie"])) {
	
		//Decode cookie and extract the user id
		$decryptCookieData = base64_decode($_COOKIE["rememberUserCookie"]);
		$user_id = explode("hemsowl#skejskd!kdkjc", $decryptCookieData);
		$userID = $user_id[1];
	
		
		
		//Check if the id retrieved from the cookie exists in the database
		//USE A PREPARED STATEMENT HERE
		$stmt6 = $conn->prepare("SELECT * FROM MagicTableCourse WHERE id = ? "); 
	
		//Bind paramter to the marker
		$stmt6->bind_param("i", $userID);
	
		//Execute the query
		$stmt6->execute();
	
		//Get the result set
		$result = $stmt6->get_result();
		
		//convert the result set into an associative array
		$row = mysqli_fetch_assoc($result);
	
		//Get the number of rows
		$numrows = mysqli_num_rows($result);
		
		//If the record exists get the id from the database
		if ($numrows == 1) {
		
			
			
			$id = $row["id"];
		
			//Create the session variable
			$_SESSION["id"] = $id;
			
			$isValid = true;
			
			//this will be referenced on future pages to check if a user is logged in
			$_SESSION["userIsLoggedIn"] = true;
			
			//Redirect to the dashboard page.
			header("Location: dashboardpage.php");
			
			
		
		} else {
			//There is no matching record in the database. The id has been tampered with.
			//Destroy the session and logout the user.
			$isValid = false;
			sign_out();
		
		}
	
	}
	
	return $isValid;
}


/*
--------------------------------
5. SIGNOUT
--------------------------------
*/

function sign_out() {
	
	unset($_SESSION["id"]);
	
	if (isset($_COOKIE["rememberUserCookie"])) {
	
		unset($_COOKIE["rememberUserCookie"]);
		setcookie("rememberUserCookie", null, -1, "/");
		
	}
	
	session_destroy();
	session_regenerate_id(true);
	
	//Redirect to the home page.
	header("Location: index.html");

}

?>