<?php

/*
--------------------
TEST OUTPUT
--------------------
This function cleans up variables
that are output to the screen.
*/
//This function accepts variables as arguments
function test_output(&$data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		$data = htmlentities($data);
		return $data;
		}
		
?>