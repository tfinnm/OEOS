<?php
include_once("db.php");
require("header.php");
topbar();
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
if (isset($_GET["command"])) {
	if ($_SESSION["permissions"]["command"]) {
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	$sql2 = "UPDATE incidents SET commandUnitID = ".$_SESSION["UnitID"]." WHERE ID = ".$_SESSION["incident"];
	if ($conn->query($sql2) === TRUE) {
	$sql3 = "SELECT * FROM units WHERE ID = ".$_SESSION["UnitID"];
	$result3 = $conn->query($sql3);
	if ($result3->num_rows > 0) {
		while($row3 = $result3->fetch_assoc()) {
			$logsql = "INSERT INTO events (Incident,Event) VALUES ('".$_SESSION["incident"]."','".$row3["shortName"]." assumed command')";
			$conn->query($logsql);
		}
	}
}
	$conn->close();
}
	echo "<script>window.location.replace('.');</script>";
	die();
}
if (isset($_GET["termcommand"])) {
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	$sql2 = "UPDATE incidents SET commandUnitID = null WHERE ID = ".$_SESSION["incident"];
	if ($conn->query($sql2) === TRUE) {
	$sql3 = "SELECT * FROM units WHERE ID = ".$_SESSION["UnitID"];
	$result3 = $conn->query($sql3);
	if ($result3->num_rows > 0) {
		while($row3 = $result3->fetch_assoc()) {
			$logsql = "INSERT INTO events (Incident,Event) VALUES ('".$_SESSION["incident"]."','".$row3["shortName"]." terminated/released command')";
			$conn->query($logsql);
		}
	}
	$conn->close();
}
	echo "<script>window.location.replace('.');</script>";
	die();
}
if (isset($_GET["terminci"])) {
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	$sql2 = "UPDATE incidents SET active = 0 WHERE ID = ".$_SESSION["incident"];
	if ($conn->query($sql2) === TRUE) {
	$sql3 = "SELECT * FROM units WHERE ID = ".$_SESSION["UnitID"];
	$result3 = $conn->query($sql3);
	if ($result3->num_rows > 0) {
		while($row3 = $result3->fetch_assoc()) {
			$logsql = "INSERT INTO events (Incident,Event) VALUES ('".$_SESSION["incident"]."','Incident Ended')";
			$conn->query($logsql);
		}
	}
	$notifsql = "INSERT INTO notification (Incident,Content,Tone) VALUES ('".$_SESSION["incident"]."','Incident Ended','-1')";
	$conn->query($notifsql);
	$conn->close();
}
	echo "<script>window.location.replace('.');</script>";
	die();
}
if (isset($_GET["assign"])) {
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
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
	$sql3 = "SELECT * FROM units WHERE ID = ".$_SESSION["UnitID"];
	$result3 = $conn->query($sql3);
	if ($result3->num_rows > 0) {
		while($row3 = $result3->fetch_assoc()) {
			$logsql = "INSERT INTO events (Incident,Event) VALUES ('".$_GET["assign"]."','Assigned ".$row3["shortName"]." to incident')";
			$conn->query($logsql);
		}
	}
	echo "<script>window.location.replace('.');</script>";
	die();
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
if (isset($_GET["error"]) && $_GET["error"] == "notadmin") {
	echo "
		<div class=\"alert alert-warning alert-dismissible\">
			<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
			<strong>Error:</strong> Not Authenticated as Admin [ECode: Auth-HT401]
		</div>";
}
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
 
 if ($_POST["statusChange"] == "scene") {
	 $sql = "UPDATE units SET PAR = 1, lastPAR = CURRENT_TIMESTAMP, lastRehab = CURRENT_TIMESTAMP WHERE ID = ".$_SESSION["UnitID"];
	 $conn->query($sql);
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
		$sql3 = "SELECT * FROM units WHERE ID = ".$_SESSION["UnitID"];
		$result3 = $conn->query($sql3);
		if ($result3->num_rows > 0) {
			while($row3 = $result3->fetch_assoc()) {
				$logsql = "INSERT INTO events (Incident,Event) VALUES ('".$_SESSION["incident"]."','".$row3["shortName"]." cleared from incident')";
				$conn->query($logsql);
			}
		}
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
	<button class='btn btn-primary dropdown-toggle' type='button' data-toggle='dropdown'>Menu<span class='caret'></span></button>
	<ul class='dropdown-menu'>
		<li class='dropdown-header'>Unit</li>
		<li><a href='logout.php'>Logout</a></li>
		<li class='divider'></li>
		<li class='dropdown-header'>Tools</li>
		<li><a href='hospital'>Hospital Portal</a></li>
		<li><a href='selfserve'>Self-Serivce</a></li>
	</ul>
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
							$mapZoom = 12;
							$sql2 = "SELECT * FROM units WHERE ID = '".$_SESSION["UnitID"]."'";
							$result2 = $conn->query($sql2);
							if ($result2->num_rows > 0) {
								while($row2 = $result2->fetch_assoc()) {
									$status = $row2["status"];
									if ($status == "dispatched") {
										$ackbutton = "<button type='submit'>En Route</button>";
										$ackval = "enroute";
										$mapZoom = 10;
									} elseif ($status == "enroute") {
										$ackbutton = "<button type='submit'>On Scene</button>";
										$ackval = "scene";
										$mapZoom = 12;
									} elseif ($status == "clear") {
										$ackbutton = "<button type='submit'>Return to Incident</button>";
										$ackval = "enroute";
									} else {
										$ackbutton = "<button type='submit'>Return to Service</button>";
										$ackval = "clear";
										$mapZoom = 14;
									}
								}
							}
							$command = "";
							$iscommand = false;
							if ($row["commandUnitID"] == $_SESSION["UnitID"]) {
								$iscommand = true;
							}
							$sql2 = "SELECT * FROM units WHERE ID = '".$row["commandUnitID"]."'";
							$result2 = $conn->query($sql2);
							if ($result2->num_rows > 0) {
								while($row2 = $result2->fetch_assoc()) {
									$command = $row2["longName"];
								}
							} else {
								$command = "N/A";
							}
							include("resources/IncidentSymbology/incidentSymbology.php");
							$locSym = "";
							foreach ($incidentSymbology["loc"] as $loc) {
								$locSym .= "'<label><input type=\"radio\" name=\"icon\" value=\"".$loc."\"><img style=\"width:75\" src=\"resources/IncidentSymbology/".$loc."\" /></label>'+";
							}
							$assignSym = "";
							foreach ($incidentSymbology["assign"] as $loc) {
								$assignSym .= "'<label><input type=\"radio\" name=\"icon\" value=\"".$loc."\"><img style=\"width:75\" src=\"resources/IncidentSymbology/".$loc."\" /></label>'+";
							}
							$usarSym = "";
							foreach ($incidentSymbology["usar"] as $loc) {
								$usarSym .= "'<label><input type=\"radio\" name=\"icon\" value=\"".$loc."\"><img style=\"width:75\" src=\"resources/IncidentSymbology/".$loc."\" /></label>'+";
							}
							$waterSym = "";
							foreach ($incidentSymbology["water"] as $loc) {
								$waterSym .= "'<label><input type=\"radio\" name=\"icon\" value=\"".$loc."\"><img style=\"width:75\" src=\"resources/IncidentSymbology/".$loc."\" /></label>'+";
							}
							$hazardIcons = "";
							foreach ($incidentSymbology["hazards"] as $loc) {
								$hazardIcons .= "'<option value=\"".$loc[1]."\">".$loc[0]."</option>'+";
							}
							$iedrows = "";
							foreach ($incidentSymbology["ied"] as $loc) {
								$iedrows .= "'<tr><td>".$loc[0]."</td><td>".$loc[1]."</td><td><button type=\"button\" class=\"btn btn-default\" onclick=\"setHazardMarkerOptions(".$loc[3].",".$loc[4].",0,0,\'IED - ".$loc[0]."\',\'".$loc[2]."\')\">Select</button></td></tr>'+";
							}
							$bleverows = "";
							foreach ($incidentSymbology["bleve"] as $loc) {
								$bleverows .= "'<tr><td>".$loc[0]."</td><td>".$loc[1]."</td><td>".$loc[2]."</td><td><button type=\"button\" class=\"btn btn-default\" onclick=\"setHazardMarkerOptions(".$loc[4].",".$loc[5].",0,".$loc[6].",\'BLEVE - ".$loc[0]."\',\'".$loc[3]."\')\">Select</button></td></tr>'+";
							}
							$wmdrows = "";
							foreach ($incidentSymbology["wmd"] as $loc) {
								$wmdrows .= "'<tr><td>".$loc[0]."</td><td><button type=\"button\" class=\"btn btn-default\" onclick=\"setHazardMarkerOptions(".$loc[2].",".$loc[3].",".$loc[4].",".$loc[5].",\'WMD - ".$loc[0]."\',\'".$loc[1]."\')\">Select</button></td></tr>'+";
							}
							echo "
							<form action='' method='post'><input type='text' name='statusChange' value='".$ackval."' hidden></input>
							<table class='table table-compact'>
							<thead>
							<tr>
								<th>Incident ID</th>
								<th>Time Out</th>
								<th>Type</th>
								<th>Command</th>
								<th>ACK</th>
							<tr>
							</thead>
							<tbody>
							<tr>
								<td>".$row["ID"]."</td>
								<td>".$row["timeOut"]."</td>
								<td>".$row["type"]."</td>
								<td>".$command."</td>
								<td>".$ackbutton."</td>
							</tr>
							</tbody>
							</table>
							</form>
							<div class='row'>
							<link rel='stylesheet' href='libraries/leaflet/leaflet.css'/>
							<script src='libraries/leaflet/leaflet.js'></script>
							<link rel='stylesheet' href='libraries\leaflet-pulsingicon/L.Icon.Pulse.min.css'/>
							<script src='libraries\leaflet-pulsingicon/L.Icon.Pulse.min.js'></script>
							<script src='libraries\leaflet-fullscreen\Leaflet.fullscreen.min.js'></script>
							<link href='libraries\leaflet-fullscreen\leaflet.fullscreen.css' rel='stylesheet' />
							<script src='libraries\leaflet-contextmenu\leaflet.contextmenu.min.js'></script>
							<link href='libraries\leaflet-contextmenu\leaflet.contextmenu.min.css' rel='stylesheet' />
							<script src='libraries\leaflet-semicircle\Semicircle.js'></script>
							<div style='height: 30%' class='col-sm-6' id='incidentmap'></div>
							<div id='incidentmap2'></div>
							<script>
								var mymap = L.map('incidentmap', {
									fullscreenControl: {
										pseudoFullscreen: true
									},
									contextmenu: true,
									contextmenuItems: [
									{
										text: 'New Point',
										callback: placePoint
									}, {
										text: 'Mark Hazard',
										callback: placeHazMat
									}, 
									'-',
									{
										text: 'Center map here',
										callback: centerMap
									}, 
									'-',
									{
										text: 'Zoom in',
										icon: 'libraries/leaflet-contextmenu/images/zoom-in.png',
										callback: zoomIn
									}, {
										text: 'Zoom out',
										icon: 'libraries/leaflet-contextmenu/images/zoom-out.png',
										callback: zoomOut
									}],
								}).setView(new L.LatLng(".$row["lat"].", ".$row["lang"]."), ".$mapZoom.");
								L.control.scale().addTo(mymap);
								L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'Tiles &copy; Open Street Map'}).addTo(mymap);
								var markerGroup = L.layerGroup().addTo(mymap);
								L.marker([".$row["lat"].", ".$row["lang"]."]).addTo(mymap);

								function centerMap (e) {
									mymap.panTo(e.latlng);
								}

								function zoomIn (e) {
									mymap.zoomIn();
								}

								function zoomOut (e) {
									mymap.zoomOut();
								}
								
								function placePoint(e) {
									if (!mymap.isFullscreen()) {
										BootstrapDialog.show({
								 			type: BootstrapDialog.TYPE_PRIMARY,
								 			title: 'Place Marker',
								 			message: '<form id=\"iconSelect\"><ul class=\"nav nav-tabs\"><li class=\"active\"><a data-toggle=\"tab\" href=\"#ics1\">ICS Locations</a></li><li><a data-toggle=\"tab\" href=\"#ics2\">ICS Assignments</a></li><li><a data-toggle=\"tab\" href=\"#usar\">USAR</a></li><li><a data-toggle=\"tab\" href=\"#water\">Water Supply</a></li></ul>'+
								 					'<div class=\"tab-content\"><div id=\"ics1\" class=\"tab-pane fade in active\">'+
								 					".$locSym."
								 					'</div><div id=\"ics2\" class=\"tab-pane fade\">'+
								 					".$assignSym."
								 					'</div><div id=\"usar\" class=\"tab-pane fade\">'+
								 					".$usarSym."
								 					'</div><div id=\"water\" class=\"tab-pane fade\">'+
								 					".$waterSym."
								 					'</div></div><hr><center>'+
													'<label class=\'radio-inline\'><input type=\'radio\' name=\'iconSize\' value=\'25\' required>Small</label>'+
													'<label class=\'radio-inline\'><input type=\'radio\' name=\'iconSize\' value=\'37.5\' checked required>Medium</label>'+
													'<label class=\'radio-inline\'><input type=\'radio\' name=\'iconSize\' value=\'50\' required>Large Icon</label>'+
													'</center></form>',
								 			buttons: [{
								 				label: 'Place',
								 				action: function(dialogItself){
								 					if (document.forms.iconSelect.icon.value != \"\") {
								 						L.marker(e.latlng, {
															contextmenu: true,
																contextmenuItems: [{
																	text: 'Remove Point',
																	index: 0
																}, {
																	separator: true,
																	index: 1
																}],
								 							icon: L.icon({
								 								iconUrl: 'resources/IncidentSymbology/'+document.forms.iconSelect.icon.value,
								 								iconSize: [document.forms.iconSelect.iconSize.value, document.forms.iconSelect.iconSize.value],
								 								iconAnchor: [document.forms.iconSelect.iconSize.value/2, document.forms.iconSelect.iconSize.value/2],
								 								popupAnchor: [document.forms.iconSelect.iconSize.value/2, document.forms.iconSelect.iconSize.value/2],
								 							})
								 						}).addTo(mymap);
								 					}
								 					dialogItself.close();
								 				}
								 			}]
								 		});
								 	}
								}
								function setHazardMarkerOptions(distance1,distance2,distance3,distance4,name,icon) {
									document.getElementById('multi1').innerHTML = 'ft';
									document.getElementById('multiplier1').setAttribute('value', '0.305');
									document.getElementById('multi2').innerHTML = 'ft';
									document.getElementById('multiplier2').setAttribute('value', '0.305');
									document.getElementById('multi3').innerHTML = 'ft';
									document.getElementById('multiplier3').setAttribute('value', '0.305');
									document.getElementById('multi4').innerHTML = 'ft';
									document.getElementById('multiplier4').setAttribute('value', '0.305');
									document.getElementById('dist1').setAttribute('value', distance1);
									document.getElementById('dist2').setAttribute('value', distance2);
									document.getElementById('dist3').setAttribute('value', distance3);
									document.getElementById('dist4').setAttribute('value', distance4);
									document.getElementById('hazardIcon').add(new Option(name,icon));
									document.getElementById('hazardIcon').value = icon;
								}
								function placeHazMat(e) {
									if (!mymap.isFullscreen()) {
										BootstrapDialog.show({
								 			type: BootstrapDialog.TYPE_PRIMARY,
								 			title: 'Place Hazard Marker',
								 			message: '<form id=\"hazardSelect\"><ul class=\"nav nav-tabs\"><li class=\"active\"><a data-toggle=\"tab\" href=\"#erg\">HazMat</a></li><li><a data-toggle=\"tab\" href=\"#bleve\">BLEVE</a></li><li><a data-toggle=\"tab\" href=\"#ied\">IED</a></li><li><a data-toggle=\"tab\" href=\"#wmd\">WMD</a></li><li><a data-toggle=\"tab\" href=\"#man\">Manual Entry</a></li><li><a data-toggle=\"tab\" href=\"#other\">Other</a></li></ul>'+
								 					'<div class=\"tab-content\"><div id=\"erg\" class=\"tab-pane fade in active\">'+
								 					'</div><div id=\"bleve\" class=\"tab-pane fade\">'+
														'<table class=\'table table-striped table-hover table-condensed\'><thead><tr><th>Capacity</th><th>Diameter</th><th>Length</th><th>Select</th></tr></thead><tbody>'+
														".$bleverows."
														'</tbody></table>'+
								 					'</div><div id=\"ied\" class=\"tab-pane fade\">'+
														'<table class=\'table table-striped table-hover table-condensed\'><thead><tr><th>Name</th><th>TNT Equiv./LPG Volume</th><th>Select</th></tr></thead><tbody>'+
														".$iedrows."
														'</tbody></table>'+
								 					'</div><div id=\"wmd\" class=\"tab-pane fade\">'+
														'<table class=\'table table-striped table-hover table-condensed\'><thead><tr><th>Name</th><th>Select</th></tr></thead><tbody>'+
														".$wmdrows."
														'</tbody></table>'+
								 					'</div><div id=\"man\" class=\"tab-pane fade\"><br>'+
														'<div class=\"row\"><div class=\"col-sm-4\"><label for=\"icon\">Hazard: </label></div><div class=\"col-sm-8\"><select class=\"form-control\" id=\"hazardIcon\" name=\"icon\" required>'+
															'<option value=\"incidentType/other-hazard.png\" selected>Unknown/Other Hazard</option>'+
															".$hazardIcons."
														'</select></div></div><br>'+
														'<div class=\"row\"><div class=\"col-sm-6\"><label for=\"dist1\">Primary Distance:</label></div><div class=\"col-sm-6\"><div style=\"width:100%;\" class=\"input-group\"><input class=\"form-control\" type=\"text\" name=\"dist1\" id=\"dist1\" required></input><div class=\"input-group-btn\"><button type=\"button\" data-toggle=\"dropdown\" id=\"multi1\" class=\"btn btn-default dropdown-toggle\">ft</button>'+
														'<ul class=\"dropdown-menu\"><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi1\').innerHTML = \'ft\';document.getElementById(\'multiplier1\').setAttribute(\'value\', \'0.305\');\">Feet</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi1\').innerHTML = \'mi\';document.getElementById(\'multiplier1\').setAttribute(\'value\', \'1609.34\');\">Miles</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi1\').innerHTML = \'m\';document.getElementById(\'multiplier1\').setAttribute(\'value\', \'1\');\">Meters</a></li></ul></div></div></div></div><br>'+
														'<div class=\"row\"><div class=\"col-sm-6\"><label for=\"dist2\">Secondary Distance:</label></div><div class=\"col-sm-6\"><div style=\"width:100%;\" class=\"input-group\"><input class=\"form-control\" type=\"text\" name=\"dist2\" id=\"dist2\" required></input><div class=\"input-group-btn\"><button type=\"button\" data-toggle=\"dropdown\" id=\"multi2\" class=\"btn btn-default dropdown-toggle\">ft</button>'+
														'<ul class=\"dropdown-menu\"><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi2\').innerHTML = \'ft\';document.getElementById(\'multiplier2\').setAttribute(\'value\', \'0.305\');\">Feet</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi2\').innerHTML = \'mi\';document.getElementById(\'multiplier2\').setAttribute(\'value\', \'1609.34\');\">Miles</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi2\').innerHTML = \'m\';document.getElementById(\'multiplier2\').setAttribute(\'value\', \'1\');\">Meters</a></li></ul></div></div></div></div><br>'+
														'<div class=\"row\"><div class=\"col-sm-6\"><label for=\"dist4\">Response Distance:</label></div><div class=\"col-sm-6\"><div style=\"width:100%;\" class=\"input-group\"><input class=\"form-control\" type=\"text\" name=\"dist4\" id=\"dist4\" required></input><div class=\"input-group-btn\"><button type=\"button\" data-toggle=\"dropdown\" id=\"multi4\" class=\"btn btn-default dropdown-toggle\">ft</button>'+
														'<ul class=\"dropdown-menu\"><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi4\').innerHTML = \'ft\';document.getElementById(\'multiplier4\').setAttribute(\'value\', \'0.305\');\">Feet</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi4\').innerHTML = \'mi\';document.getElementById(\'multiplier4\').setAttribute(\'value\', \'1609.34\');\">Miles</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi4\').innerHTML = \'m\';document.getElementById(\'multiplier4\').setAttribute(\'value\', \'1\');\">Meters</a></li></ul></div></div></div></div><br>'+
														'<div class=\"row\"><div class=\"col-sm-6\"><label for=\"dist3\">Downwind Distance:</label></div><div class=\"col-sm-6\"><div style=\"width:100%;\" class=\"input-group\"><input class=\"form-control\" type=\"text\" name=\"dist3\" id=\"dist3\" required></input><div class=\"input-group-btn\"><button type=\"button\" data-toggle=\"dropdown\" id=\"multi3\" class=\"btn btn-default dropdown-toggle\">ft</button>'+
														'<ul class=\"dropdown-menu\"><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi3\').innerHTML = \'ft\';document.getElementById(\'multiplier3\').setAttribute(\'value\', \'0.305\');\">Feet</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi3\').innerHTML = \'mi\';document.getElementById(\'multiplier3\').setAttribute(\'value\', \'1609.34\');\">Miles</a></li><li><a href=\"javascript:void(\'0\');\" onclick=\"document.getElementById(\'multi3\').innerHTML = \'m\';document.getElementById(\'multiplier3\').setAttribute(\'value\', \'1\');\">Meters</a></li></ul></div></div></div></div><br>'+
													'</div><div id=\"other\" class=\"tab-pane fade\">'+
								 					'</div></div><input hidden value=\'0.305\' id=\'multiplier1\' name=\'multiplier1\'></input><input hidden value=\'0.305\' id=\'multiplier2\' name=\'multiplier2\'></input><input hidden value=\'0.305\' id=\'multiplier3\' name=\'multiplier3\'></input><input hidden value=\'0.305\' id=\'multiplier4\' name=\'multiplier4\'></input></form>',
								 			buttons: [{
								 				label: 'Place',
								 				action: function(dialogItself){
													if (document.forms.hazardSelect.icon.value != \"\") {
								 						L.marker(e.latlng, {
															contextmenu: true,
																contextmenuItems: [{
																	text: 'Remove Point',
																	index: 0
																}, {
																	separator: true,
																	index: 1
																}],
								 							icon: L.icon({
								 								iconUrl: 'resources/IncidentSymbology/hazard/'+document.forms.hazardSelect.icon.value,
								 								iconSize: [50, 50],
								 								iconAnchor: [25, 25],
								 								popupAnchor: [25, 25],
								 							})
								 						}).addTo(mymap);
														L.circle(e.latlng, {
															radius: (document.forms.hazardSelect.dist1.value*document.forms.hazardSelect.multiplier1.value),
															color: 'red',
														}).addTo(mymap);
														L.circle(e.latlng, {
															radius: (document.forms.hazardSelect.dist2.value*document.forms.hazardSelect.multiplier2.value),
															color: 'yellow',
														}).addTo(mymap);
														L.circle(e.latlng, {
															radius: (document.forms.hazardSelect.dist4.value*document.forms.hazardSelect.multiplier4.value),
															color: 'green',
														}).addTo(mymap);
														L.semiCircle(e.latlng, {
															radius: (document.forms.hazardSelect.dist3.value*document.forms.hazardSelect.multiplier3.value),
															color: 'orange',
														}).setDirection(90, 45)
														.addTo(mymap);
								 					}
								 					dialogItself.close();
								 				}
								 			}]
								 		});
								 	}
								}
							</script>
							<div id='callDetails' class='col-sm-6'>
								<h4><b>Address:</b></h4>
								".$row["address"]."
								<h4><b>Call Notes:</b></h4>
								".$row["details"]."
							</div>
							</div>
							<h4><b>Assigned Units:</b></h4>	<span id='callUnits'>
							";
							$sql2 = "SELECT * FROM units WHERE display = '1' AND incidentID = '".$_SESSION["incident"]."'";
							$result2 = $conn->query($sql2);
							if ($result2->num_rows > 0) {
								while($row2 = $result2->fetch_assoc()) {
									$status = $row2["status"];
									$mapColor = "";
									if ($status == "enroute") {
										echo "<b style='color:blue'>".$row2["shortName"]."</b>, ";
										$mapColor = "blue";
									} elseif ($status == "scene") {
										echo "<b style='color:green'>".$row2["shortName"]."</b>, ";
										$mapColor = "green";
									} else {
										echo "<b style='color:grey'>".$row2["shortName"]."</b>, ";
										$mapColor = "grey";
									}
									echo "<script>L.marker([".$row2["lat"].", ".$row2["lang"]."], {icon: L.icon.pulse({iconSize:[10,10],fillColor:'".$mapColor."',animate:false,color:'".$mapColor."'})}).bindTooltip('".$row2["shortName"]."',{permanent: true}).addTo(markerGroup);</script>";
								}
							}
							echo "<script>";
							$sql = "SELECT * FROM incidentpoints WHERE Incident = '".$_SESSION["incident"]."'";
							$result = $conn->query($sql);
							if ($result->num_rows > 0) {
								while($row = $result->fetch_assoc()) {
									echo "L.marker([".$row["lat"].", ".$row["lang"]."], {
												contextmenu: true,
												contextmenuItems: [{
													text: 'Remove Point',
													callback: remove,
													index: 0
												}, {
													separator: true,
													index: 1
												}],
												icon: L.icon({
													iconUrl: 'resources/IncidentSymbology/".$row["file"].".png',iconSize: [25, 25],iconAnchor: [12.5, 12.5],popupAnchor: [12.5, 12.5],})}).addTo(markerGroup);";
								}
							}
							$sql = "SELECT * FROM universalpoints";
							$result = $conn->query($sql);
							if ($result->num_rows > 0) {
								while($row = $result->fetch_assoc()) {
									echo "L.marker([".$row["lat"].", ".$row["lang"]."], {icon: L.icon({iconUrl: 'resources/IncidentSymbology/".$row["file"].".png',iconSize: [25, 25],iconAnchor: [12.5, 12.5],popupAnchor: [12.5, 12.5],})}).addTo(mymap);";
								}
							}
							echo "</script>";
							echo "</span><br><br><a href='#incilog' class='btn btn-info' data-toggle='collapse'>Show Incident Log</a><a href='#comms' class='btn btn-info' data-toggle='collapse'>Show Radio Channels</a><a href='#command' class='btn btn-info' data-toggle='collapse'>Show Command Options</a>
							<div id='incilog' class='collapse'>
								<table class='table'>
									<thead>
										<tr>
											<th>Incident Log</th>
										</tr>
									</thead>
									<tbody>";
										$sql = "SELECT * FROM Events where Incident = ".$_SESSION["incident"]." ORDER BY ID  DESC";
										$result3 = $conn->query($sql);
										if ($result3->num_rows > 0) {
											// output data of each row
											while($row3 = $result3->fetch_assoc()) {
												echo "
													<tr>
														<td>".explode(" ",$row3["time"])[1]."  |  ".$row3["Event"]."</td>
													</tr>
												";
											}
										}
									echo"
									</tbody>
								</table>
							</div>
							<div id='comms' class='collapse'>
								<table class='table'>
									<thead>
										<tr>
											<th>Name</th>
											<th>Talkgroup</th>
											<th>Channel</th>
										</tr>
									</thead>
									<tbody>";
										$sql = "SELECT * FROM radiocomms where IncidentID = ".$_SESSION["incident"];
										$result3 = $conn->query($sql);
										if ($result3->num_rows > 0) {
											// output data of each row
											while($row3 = $result3->fetch_assoc()) {
												echo "
													<tr>
														<td>".$row3["Name"]."</td>
														<td>".$row3["Talkgroup"]."</td>
														<td>".$row3["Channel"]."</td>
													</tr>
												";
											}
										}
									echo"
									</tbody>
								</table>
							</div><div id='command' class='collapse'><br>";
							if ($iscommand) {
								echo "<a href='?terminci=true' class='btn btn-danger'>Close Incident</a> ";
								echo " <a href='?termcommand=true' class='btn btn-danger'>Terminate Command</a>";
							} elseif ($_SESSION["permissions"]["command"]) {
								echo "<a href='?command=true' class='btn btn-danger'>Assume Command</a>";
							} else {
								echo "No options available.";
							}
							echo "</div>";
						}
					} else {
						echo "<h4 style='color:red;'><b>Error:</b> Failed to find incident. [ECode: INCI-HT404]</h4>";
					}
				} else {
					echo "
					<center>
						<h4>No Assigned Incident</h4>
					</center>
					<hr>
					<table class='table'>
						<thead>
							<tr>
								<th>Name</th>
								<th>Talkgroup</th>
								<th>Channel</th>
							</tr>
						</thead>
						<tbody>";
							$sql = "SELECT * FROM radiocomms where IncidentID = -1";
							$result3 = $conn->query($sql);
							if ($result3->num_rows > 0) {
								// output data of each row
								while($row3 = $result3->fetch_assoc()) {
									echo "
										<tr>
											<td>".$row3["Name"]."</td>
											<td>".$row3["Talkgroup"]."</td>
											<td>".$row3["Channel"]."</td>
										</tr>
									";
								}
							}
						echo"
						</tbody>
					</table>
					";
				}
			
echo"		</div>
		</div>
<div class='panel panel-default'>
  <div class='panel-heading'>Active Incidents</div>
  <div id='callList' class='panel-body'>
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
		$sql2 = "SELECT * FROM maydays WHERE Incident = '".$row["ID"]."' AND Active = '1'";
		$result2 = $conn->query($sql2);
		$maydaytableindicator = "";
		if ($result2->num_rows > 0) {
			while($row2 = $result2->fetch_assoc()) {
				$maydaytableindicator = "class='danger'";
			}
		}
		echo "
		<tr ".$maydaytableindicator.">
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
	var source2 = new EventSource("push/updateCADCallList.php");
	source2.onmessage = function(event) {
		if (document.getElementById('callList').innerHTML == event.data) {
			
		} else {
			document.getElementById('callList').innerHTML = event.data;
		}
	};
}
if(typeof(EventSource) !== "undefined") {
	var source3 = new EventSource("push/updateCADUnits.php");
	source3.onmessage = function(event) {
		if (document.getElementById('callUnits').innerHTML == event.data) {
			
		} else {
			document.getElementById('callUnits').innerHTML = event.data;
		}
	};
}
if(typeof(EventSource) !== "undefined") {
	var source4 = new EventSource("push/updateCADLog.php");
	source4.onmessage = function(event) {
		if (document.getElementById('incilog').innerHTML == event.data) {
			
		} else {
			document.getElementById('incilog').innerHTML = event.data;
		}
	};
}
if(typeof(EventSource) !== "undefined") {
	var source5 = new EventSource("push/updateCADRadio.php");
	source5.onmessage = function(event) {
		if (document.getElementById('comms').innerHTML == event.data) {
			
		} else {
			document.getElementById('comms').innerHTML = event.data;
		}
	};
}
if(typeof(EventSource) !== "undefined") {
	var source6 = new EventSource("push/updateCADDetails.php");
	source6.onmessage = function(event) {
		const audio = new Audio("resources/cadUpdate.mp3");
		audio.play();
		if (document.getElementById('callDetails').innerHTML == event.data) {
			
		} else {
			document.getElementById('callDetails').innerHTML = event.data;
		}
	};
}
if(typeof(EventSource) !== "undefined") {
	var sourcemap = new EventSource("push/updateCADMap.php");
	sourcemap.onmessage = function(event) {
		markerGroup.clearLayers();
		eval(event.data);
	};
}
</script>