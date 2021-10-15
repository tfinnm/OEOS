<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//$myfile = fopen("reload.txt", "r") or die("");
//fclose($myfile);
//unlink("reload.txt");
//echo "data: " . uniqid() . " \n\n";
session_start();
include("db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		if ($conn->connect_error) {
			// connection failed error goes here
		}
		
		$sql = "SELECT incidentID FROM units WHERE ID = '".$_SESSION["UnitID"]."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if ($row["incidentID"] != $_SESSION["incident"]) {
					$_SESSION["incident"] = $row["incidentID"];
					echo "data: ".$row["incidentID"]." \n\n";
				}
			}
		} else {
			//unit not found error goes here.
		}
flush();
$conn->close();
?>