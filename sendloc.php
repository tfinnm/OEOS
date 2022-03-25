<?php
include_once("db.php");
session_start();
if (isset($_POST["lat"]) && isset($_POST["long"])) {	
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	$sql = "UPDATE units SET lat = '".$_POST["lat"]."', lang = '".$_POST["long"]."' WHERE ID = ".$_SESSION["UnitID"];
	$conn->query($sql);
	$conn->close();
}
?>