<?php
if (empty($_GET)) {
    echo "
	<title>New Incident - EZCall</title>
	<form action=\"\" method=\"get\">
		<select name='type' required>
			<option value='UNKNOWN' selected>Unknown Incident Type</option>
			<option value='MEDICAL'>Medical - Unknown</option>
			<option value='TRAUMA'>Medical - Trauma</option>
			<option value='ALS'>Medical - ALS (Cardiac/Breathing)</option>
			<option value='BLS'>Medical - BLS/Firstaid</option>
			<option value='ASSIST'>Medical - Assist</option>
			<option value='STANDBY'>Medical - Stand By</option>
			<option value='FIRE'>Fire - Confirmed Fire</option>
			<option value='SMOKE'>Fire - Smoke/Investigation</option>
			<option value='ALARM'>Fire - Automatic Fire Alarm</option>
			<option value='SAR'>Rescue - Missing Person</option>
			<option value='TECH'>Rescue - Tech Rescue</option>
			<option value='WATER'>Rescue - Water Rescue</option>
			<option value='HAZMAT'>HazMat - HazMat</option>
			<option value='PUBLIC'>Service - Public Assist</option>
			<option value='INSPECT'>Service - Inspection</option>
			<option value='PATROL'>Service - Safety Patrol</option>
		</select>
		<br>
		<label for=\"address\">Address:</label><br>
		<input type=\"text\" name=\"address\" style=\"width:100%\" required></input>
		<label for=\"details\">Details:</label><br>
		<textarea name=\"details\" style=\"width:100%\" required></textarea>
		<br><br>
		<input type=\"submit\" value=\"Create Call\"/>
	</form>
	";
} else {
	
	// Create connection
	include("db.php");
	session_start();
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
 
	$sql = "INSERT INTO incidents (address, type, details) VALUES ('".$_GET["address"]."','".$_GET["type"]."','".$_GET["details"]."')";
 
	if ($conn->query($sql) === TRUE) {
		$last_id = $conn->insert_id;
		$sql2 = "UPDATE units SET incidentID = ".$last_id.", status = 'dispatched' WHERE ID = ".$_SESSION["UnitID"];
		$_SESSION["incident"] = $last_id;
		$conn->query($sql2);
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	

	$conn->close();
	
	echo "<script>window.close()</script>";
}

?>