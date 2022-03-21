<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		if ($conn->connect_error) {
			// connection failed error goes here
		}
		
		$sql = "SELECT * FROM notification WHERE Incident = '".$_SESSION["incident"]."' ORDER BY ID DESC LIMIT 1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if ($row["ID"] != $_SESSION["NotifID"]) {
					$_SESSION["NotifID"] = $row["ID"];
					echo "data: ".$row["Tone"]."\n";
					echo "data: ".$row["Content"]." \n\n";
					flush();
				}
			}
		}
$conn->close();
?>