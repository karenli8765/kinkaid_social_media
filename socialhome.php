<!DOCTYPE html>
<html>
<head>
<title>Social Home Page</title>
<style type="text/css">
 	p {color: white;}
	table, td, th {border: 1px solid white; height: 25px}
	body{background-color: black;}
	a{color: white; text-align: center;}
	td{color: white; text-align: center;}
	th{color: white;}
	div{background-color: gray;};
	h1{color: white; text-align: center;}
	h3{color: white; text-align: center;}
	h2{color: white;}
 table
</style>
</head>
<body>
<div> </div>
<?php
	require("../login_connector_social.php");
   // ----------------------------------------------------------------------------------------------------------------------
	// >>>>>>>> START code to check that user is logged in   <<<<<<<<<<
	$LOGIN_PAGE_URL = "LoginMaterials/Login.php";  // YOU SHOULD DO THIS --- update this with the url of your login page.	
	$PROFILE_PAGE_URL = "socialprofile.php";
	session_start();  // turn on the "session" - the server is storing information about this browser session for this user.
	
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
	//echo("Congrats! You have logged in.");
	// here's a link to profile page
	echo("<div style = 'background-color: gray;'>");
	echo("<h1 style = 'color: white; text-align: center;'><b>FALCON HUB</b></h1>");
	echo("<h2 style = 'color: white; text-align: center;'>Home</h2>");
	echo("</div>");
	
	echo("<div style = 'background-color: gray; text-align: center;'>");
	echo("<br /><a href='$PROFILE_PAGE_URL?'>Profile Page</a>");
	
	//link to logout
	echo("<br /><a href='$LOGIN_PAGE_URL?command=logout'>Log out</a>");
	echo("</div>");
	
	//adding your own post
	echo("<div style = 'background-color: gray; text-align: center;'>");
	$query = "SELECT * FROM users WHERE user_id = $user_id";
	$query_result = mysqli_query($db_connection,$query);
	while ($row = mysqli_fetch_array($query_result))
		{
			echo("<p>Add new post</p>");
			echo("<form method = 'get' action = 'socialhome.php'>");
			echo("<table align='center'>");
   			echo("<tr><td>Post</td><td><input type='text' name='post' /></td></tr>");
  		 	echo("</table>");
 	 	 	echo("<input type='submit' name = 'command' value = 'Cancel' />");
 	 		echo("<input type='submit' name = 'command' value = 'Add' />");
  	 		echo("</form>");
 	  	}
   	   
   	if (ISSET($_REQUEST['command']))
	{
		$command = $_REQUEST['command'];
	}
	else 
	{
		$command = "None";
	}
	if($command == "Add") {
			$addpost = $_REQUEST['post'];
			//echo($addpost);
			$add_query = "INSERT INTO posts (user_id, post_content) VALUES ('$user_id', '$addpost');";	
			$add_succeeded = mysqli_query($db_connection,$add_query);
		}		
		echo('</div>');
	
	//shows other peoples posts
	echo("<div style='background-color: darkslateblue;'>");
	echo("<h3>Posts</h3>");
	$postquery = "SELECT * FROM posts INNNER JOIN users USING (user_id)";
	$postquery_result = mysqli_query($db_connection,$postquery);
	echo ("<form method= 'get' action = 'socialvisitprofiles.php'>\n");
	echo ("<table style = 'width: 100%'>\n");
	echo ("<tr><th>Select</th><th>Username</th><th>Post</th></tr>\n");
	while ($row = mysqli_fetch_array($postquery_result))
	{	
		$id = $row['post_id'];
		$person_id = $row['user_id'];
		$postusername = $row['username'];
		$postcontent = $row['post_content'];
  		echo("<td><input type='radio' name='selection' value='$person_id'></td><td>$postusername</td><td>$postcontent</td></tr>\n");
	}
	echo ("</table>");
	echo ("<input type = 'submit' name='command' value = 'Visit' /> \n");
	echo ("</form>\n");
	echo("</div>");
	
	
?>

</body>
</html> 