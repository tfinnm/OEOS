<?php
include_once("db.php");
session_start();
	
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
	//Normally, I would show an error here and stop execution, however, we know that if we got to this point, success is a life or death matter, so this should fail safe.
}

$sql = "INSERT INTO maydays (Incident) VALUES ('".$_SESSION["incident"]."')";
 
	if ($conn->query($sql) === TRUE) {
	} else {
		//This is when the query fails.
		//Again, we need to fail safe here.
	}

	$conn->close();
?>