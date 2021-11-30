<?php
	include("header.php");

	include("db.php");
		
		$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		// Check connection
		if ($conn->connect_error) {
			echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: INCI-HT500G]
			</div>";
		}
		$sql = "SELECT * FROM incidents where ID = ".$_SESSION["incident"];
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$command = "";
				$sql2 = "SELECT * FROM units WHERE ID = '".$row["commandUnitID"]."'";
				$result2 = $conn->query($sql2);
				if ($result2->num_rows > 0) {
					while($row2 = $result2->fetch_assoc()) {
						$command = $row2["longName"];
					}
				} else {
				$command = "N/A";
				}
				echo "<div class='row'>
						<div class='col-sm-1'></div>
						<div class='col-sm-2'>
							<div class=\"panel panel-primary\">
								<div class=\"panel-heading\"><center>Incident Command</center></div>
								<div class=\"panel-body\"><center><h1>".$command."</h1></center></div>
							</div>
						</div>
						<div class='col-sm-6'>Notification Sender Goes Here</div>
						<div class='col-sm-2'>
							<div class='well'>
								<center>
									<button type='button' class='btn btn-warning'>EVACUATE</button> <br><br>
									<button type='button' class='btn btn-danger'>ABANDON</button>
								</center>
							</div>
						</div>
						<div class='col-sm-1'></div>
					</div>";
			}
		}
		
		$sql = "SELECT * FROM units WHERE incidentID = ".$_SESSION["incident"];
		$result = $conn->query($sql);
		
		$boxes = "";

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$status = "<h4>Unknown</h4>";
				switch ($row["status"]) {
					case "dispatched":
						$status = "<h4 style=\"color:blue\">Dispatched</h4>";
						break;
					case "enroute":
						$status = "<h4 style=\"color:blue\">En Route</h4>";
						break;
					case "scene":
						$status = "<b>Assignment: </b><select><option>Medical Group</option><option>Suppression: Division 1</option><option>Vent Group</option></select>";
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
				
				$boxes .= "
				<div class=\"col-sm-4\">
					<div class=\"panel ".$deptColor."\">
						<div class=\"panel-heading\"><center>".$dept."</center></div>
						<div class=\"panel-body\"><center><h1>".$row["longName"]."</h1>".$status."</center></div>
						<div class=\"panel-footer\">PAR Goes Here</div>
					</div>
				</div>
	  ";
			}
		} else {
			$boxes = "<br><center><h1>No Units Assigned!</h1></center>";
		}
		$conn->close();
?>
<center>
	<div class="row">
		<div class='col-sm-1'></div>
		<div class='col-sm-9'>
			<div class="progress">
				<div class="progress-bar progress-bar-success" role="progressbar" style="width:70%">
					<span class="badge">5</span> Checked-in
				</div>
				<div class="progress-bar progress-bar-warning" role="progressbar" style="width:20%">
					<span class="badge">5</span> >15 Minutes Since Check-in
				</div>
				<div class="progress-bar progress-bar-danger" role="progressbar" style="width:10%">
					<span class="badge">5</span> Not Yet Responded
				</div>
			</div>
		</div>
		<div class='col-sm-1'><button type="button" class="btn btn-primary">New PAR</button></div>
		<div class='col-sm-1'></div>
	</div>
</center>
<hr>
<div class="container">    
  <div class="row">
	<?php echo $boxes; ?>
  </div>
</div><br>