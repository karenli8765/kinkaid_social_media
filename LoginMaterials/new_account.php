<!DOCTYPE html>
<html>
<head>
<title>Create New Account</title>
<script type="text/javascript" >

	function check_valid() // this is a bit of javascript that will check we have passwords of at least length 6 and that the passwords match... BEFORE we send this to PHP.
	{
		username = document.getElementById('username').value;		
		pwd1 = document.getElementById('password1').value;
		pwd2 = document.getElementById('password2').value;

		if (username.length < 6)
		{
			alert("Please pick a username with at least 6 characters.");
			return false;		
		}
		if (pwd1 != pwd2)
		{
			alert("Passwords don't match.");
			document.getElementById('password2').value = '';
			return false;	
		}	
		if (pwd1.length < 6)
		{
			alert("Password must be at least 6 characters.");
			document.getElementById('password1').value= '';
			document.getElementById('password2').value= ''; 
			return false;		
		}
		

		// if everything is good, we update the (hidden) command prompt to 'new_user' (instead of 'cancel') and submit the form.
		document.getElementById('command').value = "new_user"; 
		document.getElementById('submitPwdForm').submit();
	}
	
</script>
</head>
<body style="background-color:#ffdd88;">
<?php
	require("../../login_connector_social.php"); // YOU SHOULD DO THIS --> update this to the path for your connector.php file.
	$LOGIN_PAGE_URL = "Login.php";  // YOU SHOULD DO THIS --> update this to be the page where your login screen resides. 
	$MY_DOMAIN_URL = "http://karen.li.kinkaid.org"; // YOU SHOULD DO THIS --> update this to the URL for your overall site (replace my name with yours).
	// --------------------- if the user has filled in the form and the submit button has returned us to this page...	
	if (isset($_REQUEST['command']) and $_REQUEST['command']=="new_user")
	{
		// see what the user typed in.
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password1'];
		$email = $_REQUEST['email'];
		
		// check that the email is in the form of an actual email... something@something.something. This is a built-in function.
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			// find out how many users have this username already....
			$query = "SELECT count(*) AS total FROM users WHERE username = '$username';";
			$response = mysqli_query($db_connection,$query);
			$num = mysqli_fetch_assoc($response)['total'];
			
			if ($num > 0)
			{
				echo("User '$username' already exists.");
				// if we were nice, we'd offer to resend the validation email here.		
			} 
			else 
			{
				$encrypted_password = md5($password);  // encrypt the password that the user typed in. We'll store the encrypted version in the database.
				
				// create a random validation code to send to the user via email to confirm that the email is theirs.
				$validation_seed = rand(); // pick a random number
				$validation_public_code = md5($validation_seed); // encrypt the number to make a random string.
				$validation_stored_code = md5($validation_public_code); // re-encrypt the random string to be stored in the database.
				
				// save a new record for this user ... but it won't be active yet.
				$add_query = "INSERT INTO users (username,password,validation) VALUES ('$username','$encrypted_password','$validation_stored_code');";
				$add_response = mysqli_query($db_connection,$add_query);
				if ($add_response) // if that worked....
				{
					// find the number of the user we just created.
					$query2 = "SELECT user_id FROM users WHERE username = '$username';";
					$response2 = mysqli_query($db_connection,$query2);  
					$id = mysqli_fetch_array($response2)['user_id']; // shortcut, since I only want a single item from a single row....
					
					// send an email to the user with a link back here with the id, validation code and command = validate.
					$myURL = $MY_DOMAIN_URL.strtok($_SERVER['REQUEST_URI'],'?'); // get the URL of the current page, up to (and not including) the '?'
					// feel free to make this look nicer....
					$message = "Welcome to my website! To confirm that you are a user, click on this link to activate your account:<br /><br />
									<a href=\"$myURL?command=validate&user_id=$id&validate=$validation_public_code\">$myURL?command=validate&user_id=$id&validate=$validation_public_code</a>
									<br />";
					$headers = "Content-type: text/html"; // tells the email client that this email is HTML, not just plaintext.
					mail($email,"Confirm your new account.",$message,$headers); // send the email.
					echo("A confirmation email has just been sent to $email. It is probably in your Spam folder from \"daemon\"! Click on the link in that email to continue.<br /><br />");			
				}
			}
		}
		else 
		{
			echo("Not a valid email address.");	
		}
	}
	// -----------------------------------------------------------------  The user has clicked on the link in the email, sending us id and validation code.
	elseif (isset($_REQUEST['command']) and $_REQUEST['command']=="validate")
	{
		// see what was sent to us...	
		$id = $_REQUEST['user_id'];
		$validation_code = $_REQUEST['validate'];
		
		// find the encrypted validation code stored in the database for this (as yet inactive) user.		
		$validation_query = "SELECT validation FROM users WHERE user_id = '$id';";
		$validation_result = mysqli_query($db_connection,$validation_query);
		$encrypted_validation_actual = mysqli_fetch_array($validation_result)['validation'];

		// encrypt the validation code sent to us from the email.
		$encrypted_submitted = md5($validation_code);
		
		// it the two encrypted codes match...
		if ($encrypted_validation_actual == md5($validation_code))
		{
			// tell the database this account is encrypted.
			$set_valid_query = "UPDATE users SET is_validated = 1 WHERE user_id = '$id';";	
			$set_valid_response = mysqli_query($db_connection,$set_valid_query);		
			//... and jump NOW to the login page.
			header("Location:".$LOGIN_PAGE_URL ); 
		}
	}
?>
<form method='post' id='submitPwdForm' action=''>
<table style="margin-left: auto; margin-right: auto; border: 1px solid black; background-color: #ddaa55;">
	<tr><th colspan='2'>Create a new account</th></tr>
	<tr><td>Username</td><td><input type='text' width="32" id='username' name='username' /></td></tr>
	<tr><td>Password</td><td><input type='password' width="32" id='password1' name='password1' /></td></td>
	<tr><td>Verify Password</td><td><input type='password' width='32' id='password2' name='password2' /></td></tr>	
	<tr><td>Email</td><td><input type="text" id="email" name="email" /></td></tr>
	<tr><td>By making this account, I will follow all Kinkaid Core Values</td></td></tr>
	<tr><td><input type='button' value='Submit' onclick='check_valid();return false;' />
			  <input type='reset' value='Reset' /></td></tr>

</table>
<input type='hidden' id = 'command' name='command' value='cancel' />
<form>



</body>
</html>