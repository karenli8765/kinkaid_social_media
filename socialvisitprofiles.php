<!DOCTYPE html>
<html>
<head>
<title>Content Page Example</title>
<style type="text/css">
	h3{color: white;}
	body{background-color: black;}
	a{color: white;}
	p{color: white;}
</style>
</head>
<body>
<?php
	require("../login_connector_social.php");
   // ----------------------------------------------------------------------------------------------------------------------
	// >>>>>>>> START code to check that user is logged in   <<<<<<<<<<
	$LOGIN_PAGE_URL = "LoginMaterials/Login.php";  // YOU SHOULD DO THIS --- update this with the url of your login page.	
	session_start();  // turn on the "session" - the server is storing information about this browser session for this user.
	$HOME_PAGE_URL = "socialhome.php";
	
	if (isset($_SESSION['user_id'])) // 'user_id' is the piece of information we saved on the login page...
	{
		$user_id = $_SESSION['user_id']; // ... and we can use it in PHP queries for this user!
	}
	else
	{
		header("Location:".$LOGIN_PAGE_URL ); // jump NOW to the login page.
	}
	// >>>>>>>> END code to check that user is logged in   <<<<<<<<<<	
   // ----------------------------------------------------------------------------------------------------------------------
	// if the user isn't logged in, then they won't get here....	
	
	if (ISSET($_REQUEST['command']))
	{
		$command = $_REQUEST['command'];
	}
	else 
	{
		$command = "None";
	}
	
	echo("<div style = 'background-color: gray;'>");
	if($command == "Visit") {
		$person_id = $_REQUEST['selection'];
		$query = "SELECT * FROM users WHERE user_id = $person_id";
		//$postquery = "SELECT * FROM posts WHERE user_id = $person_id";
		$query_result = mysqli_query($db_connection,$query);
		while ($row = mysqli_fetch_array($query_result))
		{
			$name = $row['nameforuser'];
			$username = $row['username'];
			$biography = $row['biography'];
			$postcontent = $row['post_content'];
			echo("<h3>Name: ");
			echo($name);
			echo("</h3> \n");
			echo("<h3>Username: ");
			echo($username);
			echo("</h3> \n");
			echo("<h3>Biography: " );
			echo($biography);
			echo("</h3>");
		}
	}
	else {
		echo("<p>Select a profile</p>");
	}
	echo("</div>");
		
	//here's the link back to home page
	echo("<br /><a href='$HOME_PAGE_URL?'>Back to Home</a>");
?>

</body>
</html> 