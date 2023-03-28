<!DOCTYPE html>
<html>
<head>
<title>Edit/Delete Songs</title>
<style type="text/css">

</style>
</head>
<body>
<?php
require("../login_connector_social.php");
$LOGIN_PAGE_URL = "LoginMaterials/Login.php";  // YOU SHOULD DO THIS --- update this with the url of your login page.	
	session_start();  
	$HOME_PAGE_URL = "socialhome.php";
	$PROFILE_PAGE_URL = "socialprofile.php";
	
	if (ISSET($_REQUEST['deletecommand']))
	{
		$command = $_REQUEST['deletecommand'];
	}
	else 
	{
		$command = "None";
	}
	if ($command == "Delete")
	 {
		echo("<form method = 'get'	action = 'socialprofile.php'>\n"); 
		$id = $_REQUEST['selection'];	
	 	echo("<input type = 'hidden' name='post_id' value='$id' />\n");
		echo("Are you sure you want to delete this? \n");
		echo("<p><input type='submit' name='deletecommand' value='Cancel' />
		         <input type='submit' name='deletecommand' value='Yes, Delete' /></p>\n");
	 	echo("</form>");
	 }
	 else 
	{
 		 echo ("You need to select a post to delete.<br /> <a href='socialprofile.php'>Return</a>");
	}

?>
</body>
</html>