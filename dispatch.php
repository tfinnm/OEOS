<?php
	include("header.php");
	topbar();
	include("db.php");
	$_SESSION["dispatchrIncident"] = $_SESSION["incident"];
if (isset($_GET["assign"])) {
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: INCI-HT500C]
			</div>";
} 
$sql2 = "UPDATE units SET incidentID = ".$_SESSION["incident"].", status = 'dispatched' WHERE ID = ".$_GET["assign"];
if ($conn->query($sql2) === TRUE) {
	$sql = "SELECT * FROM units WHERE ID = ".$_GET["assign"];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$logsql = "INSERT INTO events (Incident,Event) VALUES ('".$_SESSION["incident"]."','Assigned ".$row["shortName"]." to incident')";
			$conn->query($logsql);
		}
	}
} else {
	echo "
	<div class=\"alert alert-warning alert-dismissible\">
		<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
		<strong>Error:</strong> A Server Error has Occured. - Failed To Update Unit Assignment [ECode: INCI-HT500D]
	</div>";
}
$conn->close();
}
if (isset($_GET["cancel"])) {
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
    echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: INCI-HT500E]
			</div>";
} 
$sql2 = "UPDATE units SET status = 'clear' WHERE ID = ".$_GET["cancel"];
if ($conn->query($sql2) === TRUE) {
} else {
	echo "
	<div class=\"alert alert-warning alert-dismissible\">
		<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
		<strong>Error:</strong> A Server Error has Occured. - Failed To Update Unit Assignment [ECode: INCI-HT500F]
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
				<strong>Error:</strong> A Server Error has Occured. [ECode: INCI-HT500G]
			</div>";
		}
		$depts = "";

		$sql = "SELECT * FROM departments";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$depts .= "<option value=\"".$row["ID"]."\">".$row["Name"]."</option>";
			}
		}
		
		
		$sql = "SELECT * FROM units WHERE assignable = '1'";
		if (isset($_POST["dept"])) {
			$sql = "SELECT * FROM units WHERE assignable = '1' AND deptID = '".$_POST["dept"]."'";
		}
		$result = $conn->query($sql);
		
		$boxes = "";
		$quick = "";

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$status = "<h4>Unknown</h4>";
				switch ($row["status"]) {
					case "AvailableQ":
						$status = "<h4 style=\"color:green\">Available In Quarters</h4>";
						break;
					case "Available":
						$status = "<h4 style=\"color:yellow\">Available Out Of Quarters</h4>";
						break;
					case "StandBy":
						$status = "<h4 style=\"color:yellow\">On Stand-By</h4>";
						break;
					case "Training":
						$status = "<h4 style=\"color:yellow\">Training</h4>";
						break;
					case "Fuel":
						$status = "<h4 style=\"color:yellow\">Refueling</h4>";
						break;
					case "OOS":
						$status = "<h4 style=\"color:red\">Out Of Service</h4>";
						break;
					case "staff":
						$status = "<h4 style=\"color:red\">Not Staffed</h4>";
						break;
					case "dispatched":
						$status = "<h4 style=\"color:blue\">Dispatched</h4>";
						break;
					case "enroute":
						$status = "<h4 style=\"color:blue\">En Route</h4>";
						break;
					case "scene":
						$status = "<h4 style=\"color:blue\">On Scene</h4>";
						break;
					case "clear":
						$status = "<h4 style=\"color:yellow\">Clearing From Scene</h4>";
						break;
				}
				
				$dept = "Unkown Department";
				$deptColor = "panel-default";
				
				$sql2 = "SELECT * FROM departments WHERE ID = '".$row["deptID"]."'";
				$result2 = $conn->query($sql2);

				if ($result2->num_rows > 0) {
					// output data of each row
					while($row2 = $result2->fetch_assoc()) {
						
						$dept = $row2["Name"];
						
						switch ($row2["Color"]) {
							case 0:
								break;
							case 1:
								$deptColor = "panel-danger";
								break;
							case 2:
								$deptColor = "panel-primary";
								break;
							case 3:
								$deptColor = "panel-info";
								break;
							case 4:
								$deptColor = "panel-success";
								break;
							case 5:
								$deptColor = "panel-warning";
								break;
							case 6:
								$deptColor = "panel-basic";
								break;
						}
					}
				}
				
				$assignButton = "";
				if (!$_SESSION["permissions"]["assign"]) {
					$assignButton = "<p>INCI-403 Insufficient Permssions</p>";
				} elseif (!isset($_SESSION["incident"])) {
					$assignButton = "<p>Please Select Incident</p>";
				} elseif ($_SESSION["incident"]  == $row["incidentID"]) {
					$assignButton = "<a href='?cancel=".$row["ID"]."'><button class=\"btn btn-danger\">Cancel from Incident</button></a>";
				} else {
					$assignButton = "<a href='?assign=".$row["ID"]."'><button class=\"btn btn-default\">Assign to Incident</button></a>";
				}
				
				$boxes .= "
				<div class=\"col-sm-4\">
					<div class=\"panel ".$deptColor."\">
						<div class=\"panel-heading\"><center>".$dept."</center></div>
						<div class=\"panel-body\"><center><h1>".$row["longName"]."</h1>".$status."</center></div>
						<div class=\"panel-footer\">".$assignButton."</div>
					</div>
				</div>
	  ";
	  
				$quick .= "
					<option value=\"".$row["ID"]."\">".$row["longName"]." | ".$dept."</option>
				";
			}
		} else {
			$boxes = "<br><center><h1>No Units Assigned!</h1></center>";
		}
		$conn->close();
?>
<center>
<a href="newcall" onclick="window.open('newcall', 
                         'newwindow', 
                         'width=305,height=475'); 
              return false;">New Call</a>
<form class="form-inline" action="#">
  <div class="form-group">
    <label for="quick">Quick Assign:</label>
    <select class="form-control" name="assign" id="quick">
		<?php echo $quick; ?>
	</select>
  </div>
  <button type="submit" class="btn btn-default">Assign</button>
</form>
<form class="form-inline" action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="quick">Filter By Department:</label>
    <select class="form-control" name="dept" id="dept">
		<?php echo $depts; ?>
	</select>
  </div>
  <button type="submit" class="btn btn-default">Filter</button>
  <button type="button" onClick="window.location.href = window.location.href;" class="btn btn-default">Reset</button>
</form>
</center>

<div class="container">    
  <div class="row">
	<?php echo $boxes; ?>
  </div>
</div><br>
<script>
			if(typeof(EventSource) !== 'undefined') {
				var source31 = new EventSource('push/dispatcher.php');
				source31.onmessage = function(event) {
					window.location.href = window.location.href;
					window.location.reload();
				};
			}
</script>