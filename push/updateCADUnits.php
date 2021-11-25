<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
if ($conn->connect_error) {
	// connection failed error goes here
}
$out = "";

$sql = "SELECT * FROM units WHERE incidentID = '".$_SESSION["incident"]."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$status = $row["status"];
		if ($status == "enroute") {
			$out .= "<b style='color:blue'>".$row["shortName"]."</b>, ";
		} elseif ($status == "scene") {
			$out .= "<b style='color:green'>".$row["shortName"]."</b>, ";
		} else {
			$out .= "<b style='color:grey'>".$row["shortName"]."</b>, ";
		}
	}
}

$out = str_replace(array("\n", "\r"), '', $out);

if ($_SESSION["pushHash"]["CallUnits"] == null or $_SESSION["pushHash"]["CallUnits"] != crc32($out)) {

	$_SESSION["pushHash"]["CallUnits"] = crc32($out);
	echo "data: ".$out." \n\n";
	flush();
	
}
$conn->close();
?>