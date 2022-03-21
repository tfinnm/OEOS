<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
if (isset ($_GET["unit"])) {
	$incidents = false;
	$incidentIDs = array();
	foreach ($_GET["unit"] as $unit) {
		$sql = "SELECT incidentID FROM units WHERE ID = '".$unit."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$incidents = true;
				array_push($incidentIDs, $row["incidentID"]);
			}
		}
	}
	$incidentIDs = array_unique($incidentIDs);
	$text = "";
	if ($incidents) {
		foreach ($incidentIDs as $id) {
			$incidentType = "";
			$incidentAddr = "";
			$incidentUnits = "";
			$incidentTime = "";
			$sql = "SELECT * FROM incidents where ID = ".$id;
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$incidentType = $row["type"];
					$incidentAddr = $row["address"];
					$incidentTime = substr(explode(" ",$row["timeOut"])[1],0,5);
				}
			}
			$sql = "SELECT * FROM units WHERE incidentID = '".$id."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					if (in_array($row["ID"],$_GET["unit"])) {
						$incidentUnits .= $row["longName"].", ";
					}
				}
			}
			$text .= "<span style='float: left;'>".$id."</span><span style='float: right;'>".$incidentTime."</span><h3>".$incidentType."</h3><h1>".$incidentUnits."</h1>"."<h4>".$incidentAddr."</h4>"."<hr>";
		}
	} else {
		$text = "No Active Incidients";
	}
	//	if (($_SESSION["pushHash"]["StationBoard"] == null or $_SESSION["pushHash"]["StationBoard"] != crc32($text)) or (isset($_GET["force"]) && $_GET["force"]=="true")) {
	//		$_SESSION["pushHash"]["StationBoard"] = crc32($text);
			echo "data: ".$text."\n\n";
			flush();		
	//	}
}
$conn->close();
?>