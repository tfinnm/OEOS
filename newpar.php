<?php
include_once("db.php");
session_start();
	
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);

$sql = "UPDATE units SET PAR = 0 WHERE incidentID = ".$_SESSION["incident"];
$conn->query($sql);

$logsql = "INSERT INTO events (Incident,Event) VALUES ('".$_SESSION["incident"]."','PAR Initiated')";
$conn->query($logsql);

$conn->close();
?>