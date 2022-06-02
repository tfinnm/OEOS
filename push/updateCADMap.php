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

$sql = "SELECT * FROM units WHERE display = '1' AND incidentID = '".$_SESSION["incident"]."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$status = $row["status"];
		$mapColor = "";
		if ($status == "enroute") {
			$mapColor = "blue";
		} elseif ($status == "scene") {
			$mapColor = "green";
		} else {
			$mapColor = "grey";
		}
		$out .= "L.marker([".$row["lat"].", ".$row["lang"]."], {icon: L.icon.pulse({iconSize:[10,10],fillColor:'".$mapColor."',animate:false,color:'".$mapColor."'})}).bindTooltip('".$row["shortName"]."',{permanent: true}).addTo(markerGroup);";
	}
}

$sql = "SELECT * FROM incidentpoints WHERE Incident = '".$_SESSION["incident"]."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$out .= "L.marker([".$row["lat"].", ".$row["lang"]."], {icon: L.icon({iconUrl: 'resources/IncidentSymbology/".$row["file"].".png',iconSize: [25, 25],iconAnchor: [12.5, 12.5],popupAnchor: [12.5, 12.5],})}).addTo(markerGroup);";
	}
}

if ($_SESSION["pushHash"]["CallMap"] == null or $_SESSION["pushHash"]["CallMap"] != crc32($out)) {

	$_SESSION["pushHash"]["CallMap"] = crc32($out);
	echo "data: ".$out." \n\n";
	flush();
	
}
$conn->close();
?>