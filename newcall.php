<?php
include("db.php");
if (empty($_GET)) {
	include("header.php");
	bootlibs();
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	$radio = "";
	$sql = "SELECT * FROM radiocomms";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$inusecolor = "";
			if ($row["IncidentID"] != null) {
				$inusecolor = " style='color:red;'";
			}
			$radio .= "<option value='".$row["ID"]."'".$inusecolor.">".$row["Name"]." (".$row["Talkgroup"]." ".$row["Channel"].")</option>";
		}
	}
	$units = "";
	$sql = "SELECT * FROM units WHERE assignable = '1'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$inusecolor = "";
			switch ($row["status"]) {
				case "AvailableQ":
				case "Available":
				case "StandBy":
				case "Training":
				case "Fuel":
					$inusecolor = " style='color:black;'";
					break;
				case "OOS":
				case "staff":
					$inusecolor = " style='color:red;'";
					break;
				case "dispatched":
				case "enroute":
				case "scene":
				case "clear":
					$inusecolor = " style='color:blue;'";
					break;
			}
			$dept = "Unkown Department";
			$sql2 = "SELECT * FROM departments WHERE ID = '".$row["deptID"]."'";
			$result2 = $conn->query($sql2);
			if ($result2->num_rows > 0) {
				while($row2 = $result2->fetch_assoc()) {
					$dept = $row2["Name"];
				}
			}
			$units .= "<option value='".$row["ID"]."'".$inusecolor."><b>".$row["longName"]." | ".$dept."</b></option>";
		}
	}
	$conn->close();
    echo "
	<title>New Incident - EZCall</title>
	<form action=\"\" method=\"get\">
		<select class='form-control' name='type' required>
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
		<label for=\"address\">Address:</label><br>
		<input class='form-control' type=\"text\" name=\"address\" style=\"width:100%\" required></input>
		<label for=\"details\">Details:</label><br>
		<textarea class='form-control' name=\"details\" style=\"width:100%\" required></textarea>
		<label for='radio'>Radio Channels:</label><br>
		<select class='form-control' name='radio[]' id='radio' multiple>
			".$radio."
		</select>
		<label for='units'>Units:</label><br>
		<select class='form-control' name='units[]' id='units' multiple>
			".$units."
		</select>
		<br>
		<input class='form-control' type=\"submit\" value=\"Create Call\"/>
	</form>
	";
} else {
	
	// Create connection
	session_start();
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
 
	$sql = "INSERT INTO incidents (address, type, details) VALUES ('".$_GET["address"]."','".$_GET["type"]."','".$_GET["details"]."')";
 
	if ($conn->query($sql) === TRUE) {
		$last_id = $conn->insert_id;
		foreach ($_GET["radio"] as $r) {
			$sql3 = "UPDATE radiocomms SET IncidentID='".$last_id."' WHERE ID = ".$r;
			$conn->query($sql3);
		}
		foreach ($_GET["units"] as $u) {
			$sql3 = "UPDATE units SET incidentID='".$last_id."', status = 'dispatched' WHERE ID = ".$u;
			$conn->query($sql3);
		}
		$sql2 = "UPDATE units SET incidentID = ".$last_id.", status = 'dispatched' WHERE ID = ".$_SESSION["UnitID"];
		$_SESSION["incident"] = $last_id;
		$conn->query($sql2);
		echo "<script>window.close()</script>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
}

?>