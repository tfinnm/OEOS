<?php
include_once("db.php");
include("header.php");
if (isset($_GET["logout"])) {
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: UAuth-HT500A]
			</div>";
} 
$sql2 = "UPDATE personel SET Unit = null WHERE ID = ".$_GET["logout"]." AND Unit = ".$_SESSION["UnitID"];
if ($conn->query($sql2) === TRUE) {
	getPermissions();
	echo "<script>window.location.replace('.');</script>";
} else {
	echo "
	<div class=\"alert alert-warning alert-dismissible\">
		<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
		<strong>Error:</strong> A Server Error has Occured. - Failed To Update Unit Assignment [ECode: UAuth-HT500B]
	</div>";
}
$conn->close();
}
if (isset($_GET["assign"])) {
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: INCI-HT500A]
			</div>";
} 
$sql2 = "UPDATE units SET incidentID = ".$_GET["assign"].", status = 'dispatched' WHERE ID = ".$_SESSION["UnitID"];
if ($_GET["assign"] == "null") {
	$sql2 = "UPDATE units SET incidentID = ".$_GET["assign"]." WHERE ID = ".$_SESSION["UnitID"];
	$_SESSION["incident"] = null;
} else {
	$_SESSION["incident"] = $_GET["assign"];
}
if ($conn->query($sql2) === TRUE) {
	echo "<script>window.location.replace('.');</script>";
} else {
	echo "
	<div class=\"alert alert-warning alert-dismissible\">
		<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
		<strong>Error:</strong> A Server Error has Occured. - Failed To Update Unit Assignment [ECode: INCI-HT500B]
	</div>";
}
$conn->close();
}
if (isset($_POST["usrnm"]) && isset($_POST["pswrd"])) {
$user = $_POST["usrnm"];
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: UAuth-HT500A]
			</div>";
} 
$sql = "SELECT * FROM personel where uname = '$user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$pswrd = $row["upass"];
		$id = $row["ID"];
		$uid = $row["Unit"];
    }
	if (($uid == null) or ($uid == "null") or ($uid == "")) {
	if (password_verify( $_POST["pswrd"] , $pswrd )) {
		$sql2 = "UPDATE personel SET Unit = '".$_SESSION["UnitID"]."' WHERE ID = ".$id;
		if ($conn->query($sql2) === TRUE) {
			getPermissions();
			echo "<script>window.location.replace('.');</script>";
		} else {
			echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. - Failed To Update Unit Assignment [ECode: UAuth-HT500B]
			</div>";
		}
	}else {
		echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> Invalid Username/Password Combo. [ECode: Auth-HT401]
			</div>";
	}
	}else{
		echo "<div class=\"alert alert-info alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> Already Logged In. Please log out before logging in.
			</div>";
	}
} else {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> Invalid Username/Password Combo. [ECode: Auth-HT401]
			</div>";
}
$conn->close();
}
echo "<div class='container-fluid'>";
if (isset($_POST["statusChange"])) {
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
 // Check connection
  if ($conn->connect_error) {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: UNIT-HT500A]
			</div>";
} 
 
$sql = "UPDATE units SET status='".$_POST["statusChange"]."' WHERE ID = ".$_SESSION["UnitID"];
 
if ($conn->query($sql) === TRUE) {
} else {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. - Failed To Update Status [ECode: UNIT-HT500C]
			</div>";
}

$conn->close();
}
 		$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		// Check connection
		if ($conn->connect_error) {
			echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: UNIT-HT500A]
			</div>";
		}
		
		$sql = "SELECT * FROM units WHERE ID = '".$_SESSION["UnitID"]."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$status = $row["status"];
				$unit = $row["longName"];
			}
		} else {
			echo "
			<div class='alert alert-warning alert-dismissible'>
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. - Unable to Find Unit [ECode: UNIT-HT500B]
			</div>";
		}
	$disableChange = "";
	$statusColor = "panel-default";
	$statusText = $status;
	switch ($status) {
		case "AvailableQ":
			$statusText = "Available In Quarters";
			$statusColor = "panel-success";
			break;
		case "Available":
			$statusText = "Available Out of Quarters";
			$statusColor = "panel-warning";
			break;
		case "StandBy":
			$statusText = "On-Stand By";
			$statusColor = "panel-warning";
			break;
		case "Training":
			$statusText = "Training";
			$statusColor = "panel-warning";
			break;
		case "Fuel":
			$statusText = "Refueling";
			$statusColor = "panel-warning";
			break;
		case "OOS":
			$statusText = "Out of Service";
			$statusColor = "panel-danger";
			break;
		case "staff":
			$statusText = "No Staffing";
			$statusColor = "panel-danger";
			$disableChange = "disabled";
			break;
		case "dispatched":
			$statusText = "Dispatched";
			$statusColor = "panel-primary";
			$disableChange = "disabled";
			break;
		case "enroute":
			$statusText = "Enroute";
			$statusColor = "panel-primary";
			$disableChange = "disabled";
			break;
		case "scene":
			$statusText = "On Scene";
			$statusColor = "panel-primary";
			$disableChange = "disabled";
			break;
		case "clear":
			$statusText = "Clearing from Scene";
			$statusColor = "panel-warning";
			break;
	}
	if (($_SESSION["incident"] != null) and !(($status == "clear") or ($status == "scene") or ($status == "enroute") or ($status == "dispatched"))) {
		echo "<script>window.location.replace('?assign=null');</script>";
	}
	echo "
	<div class='row'>
    <div class='col-sm-5'>
	<div class='panel ".$statusColor."'>
		<div class='panel-heading'>".$unit." Status</div>
		<div class='panel-body'>
			<center>
			<form action='' method='post'><select name='statusChange' class='form-control' onchange='if(this.value != 0) { this.form.submit(); }' ".$disableChange.">
						<option value='' selected disabled hidden>".$statusText."</option>
						<option value='AvailableQ'>Available In Quarters</option>
						<option value='Available'>Available Out of Quarters</option>
						<option value='StandBy'>On Stand-By</option>
						<option value='Training'>Training</option>
						<option value='Fuel'>Refueling</option>
						<option value='OOS'>Out of Service</option>
						</select></form>
			</center>
		</div>
	</div>
	<div class='panel panel-default'>
  <div class='panel-heading'>".$unit." Crew</div>
  ";
  echo "
  <div class='panel-body'>
	<table class='table table-striped table-hover'>
    <thead>
      <tr>
        <th>Name</th>
        <th>Rank</th>
        <th>Options</th>
      </tr>
    </thead>
    <tbody>";
$sql = "SELECT * FROM personel where Unit = ".$_SESSION["UnitID"];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		echo "
		<tr>
			<td>".$row["name"]."</td>
			<td>".$row["rankname"]."</td>
			<td><a href='?logout=".$row["ID"]."'><button class='btn btn-danger'>Log Out</button></a></td>
		</tr>
		";
    }
} else {
    echo "
			<tr><td>No Staffing</td><td></td><td></td></tr>";
}
echo "
    </tbody>
  </table>
  <form class='form-inline' action='' method='post'>
  <div class='form-group'>
    <input required type='text' class='form-control' id='usrnm' name='usrnm' placeholder='Enter username'>
  </div>
  <div class='form-group'>
    <input required type='password' class='form-control' id='pwd' name='pswrd' placeholder='Enter password'>
  </div>
  <button type='submit' class='btn btn-default'>Log In</button>
</form>
</div>
</div>
</div>
    <div class='col-sm-7'>
		<div class='panel panel-default'>
			<div class='panel-heading'>Incident Information</div>
			<div class='panel-body'>";
			
				if (isset($_SESSION["incident"]) && $_SESSION["incident"] != null) {
					$sql = "SELECT * FROM incidents where ID = ".$_SESSION["incident"];
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$ackbutton = "";
							$ackval = "";
							$sql2 = "SELECT * FROM units WHERE ID = '".$_SESSION["UnitID"]."'";
							$result2 = $conn->query($sql2);
							if ($result2->num_rows > 0) {
								while($row2 = $result2->fetch_assoc()) {
									$status = $row2["status"];
									if ($status == "dispatched") {
										$ackbutton = "<button type='submit'>En Route</button>";
										$ackval = "enroute";
									} elseif ($status == "enroute") {
										$ackbutton = "<button type='submit'>On Scene</button>";
										$ackval = "scene";
									} else {
										$ackbutton = "<button type='submit'>Return to Service</button>";
										$ackval = "clear";
									}
								}
							}
							echo "
							<form action='' method='post'><input type='text' name='statusChange' value='".$ackval."' hidden></input>
							<table class='table table-compact'>
							<thead>
							<tr>
								<th>Incident ID</th>
								<th>Time Out</th>
								<th>Type</th>
								<th>ACK</th>
							<tr>
							</thead>
							<tbody>
							<tr>
								<td>".$row["ID"]."</td>
								<td>".$row["timeOut"]."</td>
								<td>".$row["type"]."</td>
								<td>".$ackbutton."</td>
							</tr>
							</tbody>
							</table>
							</form>
							<div class='row'>
							<link rel='stylesheet' href='libraries/leaflet/leaflet.css'/>
							<script src='libraries/leaflet/leaflet.js'></script>
							<link rel='stylesheet' href='https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css'/>
							<script src='https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.umd.js'></script>
							<div style='height: 30%' class='col-sm-6' id='incidentmap'></div>
							<script>
								var mymap = L.map('incidentmap').setView([0, 0], 2);
								L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {attribution: 'Tiles &copy; Esri'}).addTo(mymap);
								new GeoSearchControl({
									provider: new OpenStreetMapProvider(),
								}).geosearch('New York, NY');
							</script>
							<div class='col-sm-6'>
								<h4><b>Address:</b></h4>
								".$row["address"]."
								<h4><b>Call Notes:</b></h4>
								".$row["details"]."
							</div>
							</div>
							<h4><b>Assigned Units:</b></h4>
							";
							$sql2 = "SELECT * FROM units WHERE incidentID = '".$_SESSION["incident"]."'";
							$result2 = $conn->query($sql2);
							if ($result2->num_rows > 0) {
								while($row2 = $result2->fetch_assoc()) {
									$status = $row2["status"];
									if ($status == "enroute") {
										echo "<b style='color:blue'>".$row2["shortName"]."</b>, ";
									} elseif ($status == "scene") {
										echo "<b style='color:green'>".$row2["shortName"]."</b>, ";
									} else {
										echo "<b>".$row2["shortName"]."</b>, ";
									}
								}
							}
						}
					} else {
						echo "<h4 style='color:red;'><b>Error:</b> Failed to find incident. [ECode: INCI-HT404]</h4>";
					}
				} else {
					echo "
					<center>
						<h4>No Assigned Incident</h4>
					</center>
					";
				}
			
echo"		</div>
		</div>
<div class='panel panel-default'>
  <div class='panel-heading'>Active Incidents</div>
  <div class='panel-body'>
	<table class='table table-striped table-hover table-condensed'>
    <thead>
      <tr>
        <th>Time Out</th>
        <th>Type</th>
        <th>Address</th>
		<th>Details</th>
		<th>Options</th>
      </tr>
    </thead>
    <tbody>";
$sql = "SELECT * FROM incidents where active = 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$permdis = "";
		$currdis = ">Assign";
		if ($row["ID"] == $_SESSION["incident"]) {
			$currdis = "disabled>Assigned";
		}
		if (!($_SESSION["permissions"]["selfassign"])) {
			$permdis = "disabled ";
		}
		$sql2 = "SELECT * FROM maydays WHERE Incident = '".$row["ID"]."' AND Active = '1' ORDER BY 'ID' DESC LIMIT 1";
		$result2 = $conn->query($sql);
		if ($result2->num_rows > 0) {
			
		}
		echo "
		<tr>
			<td>".explode(" ",$row["timeOut"])[1]."</td>
			<td>".$row["type"]."</td>
			<td>".$row["address"]."</td>
			<td>".substr($row["details"],0,40)."</td>
			<td><a href='?assign=".$row["ID"]."'><button class='btn btn-success' ".$permdis.$currdis."</button></a></td>
		</tr>
		";
    }
} else {
    echo "<tr><td>No Active Incidents</td><td></td><td></td><td></td><td></td></tr>";
}
$conn->close();
?>
    </tbody>
  </table>
</div>
</div>
	</div>
  </div>
</div>
<script>
if(typeof(EventSource) !== "undefined") {
  var source = new EventSource("updateCAD.php");
  source.onmessage = function(event) {
	const audio = new Audio("resources/cadUpdate.mp3");
	audio.play();
	setTimeout(function(){
		window.location.href = window.location.href;
		window.location.reload();
	}, 2000);
  };
} else {
  document.write("<meta http-equiv='refresh' content='5'>");
}
</script>