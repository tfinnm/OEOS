<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
if (isset($_SESSION["incident"]) && $_SESSION["incident"] != null) {
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
if ($conn->connect_error) {
	// connection failed error goes here
}
$out = "";		

$sql = "SELECT * FROM incidents where ID = ".$_SESSION["incident"];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {

		$out .= "<h4><b>Address:</b></h4>
		".$row["address"]."
		<h4><b>Call Notes:</b></h4>
		".$row["details"];
	}
}

$out = str_replace(array("\n", "\r"), '', $out);

if ($_SESSION["pushHash"]["CallDetails"] == null or $_SESSION["pushHash"]["CallDetails"] != crc32($out)) {

	$_SESSION["pushHash"]["CallDetails"] = crc32($out);
	echo "data: ".$out." \n\n";
	flush();
	
}
$conn->close();
}
?>