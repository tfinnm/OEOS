<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		if ($conn->connect_error) {
			// connection failed error goes here
		}
		$sql = "SELECT * FROM maydays WHERE Incident = '".$_SESSION["incident"]."' AND Active = '1' ORDER BY 'ID' DESC LIMIT 1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if ($row["ID"] != $_SESSION["maydayID"]) {
					echo "data: ".$row["ID"]." \n\n";
					flush();
					$_SESSION["maydayID"] = $row["ID"];
				}
			}
		} else {
			//There are no mayday situations, this is a very good thing.
		}
$conn->close();
?>