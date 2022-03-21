<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
if (isset ($_GET["unit"])) {
	$incident = -1;
	foreach ($_GET["unit"] as $unit) {
		if (!isset($_SESSION["stationincident"]["".$unit])) {
			$_SESSION["stationincident"]["".$unit] = -1;
		}
		$sql = "SELECT incidentID FROM units WHERE ID = '".$unit."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if ($row["incidentID"] != $_SESSION["stationincident"]["".$unit]) {
					$_SESSION["stationincident"]["".$unit] = $row["incidentID"];
					$incident = $row["incidentID"];
				}
			}
		}
	}
	if ($incident > -1) {
		$incidentType = "";
		$incidentUnits = "";
		$incidentChannel = "";
		$incidentTime = "";
		$sql = "SELECT * FROM incidents where ID = ".$incident;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$incidentType = $row["type"];
				$incidentTime = substr(explode(" ",$row["timeOut"])[1],0,5);
			}
		}
		$sql = "SELECT * FROM units WHERE incidentID = '".$incident."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if (in_array($row["ID"],$_GET["unit"])) {
					$incidentUnits .= $row["pronunciation"].", ";
				}
			}
		}
		$sql = "SELECT * FROM radiocomms where IncidentID = ".$_SESSION["incident"];
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$incidentChannel .= $row3["pronunciation"].". ";
			}
		}
		$text= $incidentUnits.$incidentType.". ".$incidentChannel.$incidentUnits.$incidentType.". ".$incidentChannel." Time out ".$incidentTime;
		if ($_SESSION["pushHash"]["StationBoardLatest"] == null or $_SESSION["pushHash"]["StationBoardLatest"] != crc32($text)) {
			$_SESSION["pushHash"]["StationBoardLatest"] = crc32($text);
			echo "data: ".$text."\n\n";
			flush();		
		}
	}
}
$conn->close();
?>