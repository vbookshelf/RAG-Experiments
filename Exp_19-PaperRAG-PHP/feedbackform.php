<?php

require '/home/soywoza/Run2Give_createconnection/myee_createconnection.php';
require '/home/soywoza/Run2Give_createconnection/myee_createconnectionbackup.php';

require 'brain/testinput.php';
require 'brain/testoutput.php';

require 'brain/functions.php';
require 'brain/npsmachinesurvey.php';

?>



<!DOCTYPE html>
<html lang="en">



<head>
<meta charset="utf-8">
<title>Feedback Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">


<!--CSS Stylesheets-->
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/feedback.css">


<link rel="shortcut icon" type="image/png" href="assets/cross.png">

<!--Link to Font Awesome icons-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">


</head>






<body class="bground-color">
<!-- w3-content defines a container for fixed size centered content,
and is wrapped around the whole page content. -->
<div class="bground-color" style="max-width:1500px">
	
<!-- 1. HOME PAGE TAB -->
<div class="w3-animate-opacity w3-margin-bottom">
	



<!-- Top Bar -->
<div class='normal-bar w3-padding'>
	
	<p class="w3-padding-left no-margin space-letters w3-left-align unblock">
	<a class="change-size w3-text-white" href="feedbackform.php"></a>
	</p>
	
</div>



<!-- Top Bar - Mobile -->
<div class='mobile-bar w3-center w3-margin-left w3-margin-right'>
	
	<p class="w3-purple no-margin unblock space-letters w3-padding w3-center">
	<a class="change-size w3-text-white" href="feedbackform.php">Barbarabot Feedback Form</a>
	</p>
</div>



<!-- 960 width region -->
<div class='w3-content w3-padding' style="max-width:960px">


	<!--3. CONTACT US TAB-->
	<div class="tabbed w3-padding-top w3-margin-bottom pad-bottom1" id="contactus">
	
		<div class="w3-center w3-round form-width8 w3-white" style="margin: 0 auto">
		<?php
		
			contact_success($isSent, $contacttick, $contactsuccess);
			
			contact_failure($hasFailedContact, $contactfailure);
			
		?>
		</div>
		
		
		<div class="w3-center w3-white w3-round w3-border form-width8" style="margin: 0 auto">
		<!--Form-->
		<div class="" style="max-width:960px;position:relative">
			<div class="w3-display-container w3-padding-large">
		  	<form method="post" action="">
					<div class="w3-padding-bottom">
						
					
					<p class="text-color space-letters">How likely are you to recommend<br>
					Barbarabot to a friend?</p>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==0) echo "checked";?> value="0"><br>
						<label  class="w3-label" for="0">0</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==1) echo "checked";?> value="1"><br>
						<label  class="w3-label" for="1">1</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==2) echo "checked";?> value="2"><br>
						<label  class="w3-label" for="2">2</label>
					</div>
					
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==3) echo "checked";?> value="3"><br>
						<label  class="w3-label" for="3">3</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==4) echo "checked";?> value="4"><br>
						<label  class="w3-label" for="4">4</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==5) echo "checked";?> value="5"><br>
						<label  class="w3-label" for="5">5</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==6) echo "checked";?> value="6"><br>
						<label  class="w3-label" for="6">6</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==7) echo "checked";?> value="7"><br>
						<label  class="w3-label" for="7">7</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==8) echo "checked";?> value="8"><br>
						<label  class="w3-label" for="8">8</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==9) echo "checked";?> value="9"><br>
						<label  class="w3-label" for="9">9</label>
					</div>
					
					<div class="w3-padding unblock">
						<input type="radio" name="score" <?php if (isset($score) && $score==10) echo "checked";?> value="10"><br>
						<label  class="w3-label" for="10">10</label>
					</div>
						
						
						
						<div class="w3-left-align">
						
						<p class="space-letters quest-text">
							Please share your experience, for example:<br>
							
							- Did you receive the gift of eternal life?<br>
							- What did you like?<br>
							- What didn't you like?<br>
							- Did you find any bugs?
							
						</p>
							<textarea maxlength="2500" class="w3-input w3-border faq-text" name="message3" placeholder="..."></textarea>
						</div>
						
						<label class="w3-label w3-large w3-left"></label>
						<div class="w3-padding-8 email-field">
							<input class="w3-input w3-border" type="text" placeholder="Your Email" name="email">
						</div>
						
						
						
						<div class="w3-padding-8 w3-margin faq-text">
							<input type="submit" name="contactbtn" value="Submit"  class="w3-btn-block w3-round w3-purple w3-hover-blue">
						</div>
					
			</form>
			</div>
		</div>
		</div>
	
	</div><!--END OF MOB APPOINTMENT FORM TAB-->


</div><!-- End of 960 width region -->


</div><!--END OF HOME PAGE TAB-->
</div> <!-- w3-content -->
</body>
</html>




<!--PHP CODE-->
<?php

	ob_flush();
?>