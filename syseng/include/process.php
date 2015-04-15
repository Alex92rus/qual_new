<?php
if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['action']))):

if (isset($_POST['startdate'])) { $startdate = $_POST['startdate']; } else { $startdate = ''; }
if (isset($_POST['enddate'])) { $enddate = $_POST['enddate']; } else { $enddate = ''; }

	$formerrors = false;

	if(isset($_POST['enddate'])){
		if ($enddate === '') :
			$err_enddate = '<div class="error">Sorry, enddate is a required field</div>';
			$formerrors = true;
		endif; // input field empty


	}




	if (!($formerrors)) :


	endif; // check for form errors

endif; //form submitted
?>
