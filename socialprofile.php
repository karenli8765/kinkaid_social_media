<!DOCTYPE html>
<html>
<head>
<title>Profile Page</title>
<style type="text/css">
	a{color: white;}
	div{color: white;}
	table, td, th {border: 1px solid white;}
</style>
<div style="table-layout: auto;">
</head>
<body style="background-color: black;">
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
		
		if($command == "Add Name") {
			$name_to_change = $_REQUEST['name'];
			$bio_to_change = $_REQUEST['bio'];
			$add_query = "UPDATE users SET nameforuser = '$name_to_change' WHERE user_id = $user_id ;";	
			$add_succeeded = mysqli_query($db_connection,$add_query);
			//echo("<p>Your name has been changed to</p>");
			//echo($name_to_change);
		}
		
		if($command == "Add Bio") {
			$bio_to_change = $_REQUEST['bio'];
			$add_query = "UPDATE users SET biography = '$bio_to_change' WHERE user_id = $user_id ;";	
			$add_succeeded = mysqli_query($db_connection,$add_query);
		}	
		
	if (ISSET($_REQUEST['deletecommand']))
		{
			$dcommand = $_REQUEST['deletecommand'];
		}
	else 
		{
			$dcommand = "None";
		}		
	if($dcommand == "Yes, Delete") {
		$deleteid = $_REQUEST['post_id'];
		$delete_query = "DELETE FROM posts WHERE post_id=$deleteid ;";
		$delete_succeeded = mysqli_query($db_connection,$delete_query);
	}
	
	echo("<div style = 'background-color: gray;'>");
	$query = "SELECT * FROM users WHERE user_id = $user_id";
		$query_result = mysqli_query($db_connection,$query);
		while ($row = mysqli_fetch_array($query_result))
		{
			$name = $row['nameforuser'];
			$username = $row['username'];
			$biography = $row['biography'];
			echo("<h3>Name: ");
			echo($name);
			echo("</h3> \n");
			echo("<h4>Biography: " );
			echo($biography);
			echo("</h4>");
		}
		echo("</div>");
		
	//changing name 
		echo("<div style = 'background-color: gray; table-layout: auto;' ");
		echo('<p>Edit your profile here.</p>'); 
		echo("<form method = 'get' action = 'socialprofile.php'>");
		echo("<table style = 'border: 0px'>");
   		echo("<tr><td>Name</td><td><input type='text' name='name' /></td></tr>");
   		echo("</table>");
   		echo("<input type='submit' name = 'command' value = 'Cancel' />");
   		echo("<input type='submit' name = 'command' value = 'Add Name' />");
   		echo("</form>");
   		
   		//echo('<p>Edit your biography here.</p>'); 
		echo("<form method = 'get' action = 'socialprofile.php'>");
		echo("<table style = 'border: 0px'>");
   		echo("<tr><td>Biography</td><td><input type='text' name='bio' /></td></tr>");
   		echo("</table>");
   		echo("<input type='submit' name = 'command' value = 'Cancel' />");
   		echo("<input type='submit' name = 'command' value = 'Add Bio' />");
   		echo("</form>");
   		echo("</div>");

		
	//displaying own posts
	echo("<div style='background-color: darkslateblue;'>");
	echo("<h3>Your Posts</h3>");
	$postquery = "SELECT * FROM posts INNNER JOIN users USING (user_id) WHERE user_id = $user_id";
	$postquery_result = mysqli_query($db_connection,$postquery);
	echo ("<form method= 'get' action = 'editdeleteposts.php'>\n");
	echo ("<table style = 'width: 100%'>\n");
	echo ("<tr><th>Select</th><th>Post</th></tr>\n");
	while ($row = mysqli_fetch_array($postquery_result))
	{	
			$id = $row['post_id'];
			$postcontent = $row['post_content'];
  			echo("<td><input type='radio' name='selection' value='$id'></td><td>$postcontent</td></tr>\n");
	}
	echo ("</table>");
	echo ("<input type = 'submit' name='deletecommand' value = 'Delete' /> \n");
	echo ("</form>");
	echo("</div>");	
		
	//here's the link back to home page
	echo("<div style = 'background-color: gray;'>");
	echo("<br /><a href='$HOME_PAGE_URL?'>Back to Home</a>");
	echo("</div>");
?>

</body>
</html> 