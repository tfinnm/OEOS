<?php
	include("header.php");
	topbar();
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
						<div class='col-sm-2'>
							<div class=\"panel panel-success\">
								<div class=\"panel-heading\"><center>Safety Officer(s)</center></div>
								<div class=\"panel-body\"><center><h1>".$command."</h1></center></div>
							</div>
						</div>
						<div class='col-sm-3'>
							<form class='form-horizontal' action='/action_page.php'>
								<div class='form-group'>
									<label class='control-label col-sm-2' for='msg'>Message:</label>
									<div class='col-sm-10'>
										<input type='text' class='form-control' id='msg' placeholder='Message' name='msg'>
									</div>
								</div>
								<div class='form-group'>
									<label class='col-sm-2' for='tone'>Select Tone:</label>
									<div class='col-sm-5'>
										<select class='form-control' id='tone'>
											<option value='0'>Emergency</option>
											<option value='2'>Priority 1</option>
											<option value='1'>Priority 2</option>
										</select>
									</div>								
								  <div class='col-sm-2'>
									<div class='checkbox'>
									  <label><input type='checkbox' name='log'> Log</label>
									</div>
								  </div>
								  <div class='col-sm-2'>
									<button type='submit' class='btn btn-default'>Issue</button>
								  </div>
								</div>
							</form>
						</div>
						<div class='col-sm-2'>
							<div class='well'>
								<center>
									<button type='button' class='btn btn-warning'>EVACUATE</button> <br><br>
									<button type='button' class='btn btn-danger'>ABANDON</button>
								</center>
							</div>
						</div>
						<div class='col-sm-1'>
							<div class='well'>
								<center>
									<body onload='startTime(); startElapsed(new Date(),\"elapse\");' />
									<div id='clock'></div>
									<hr>
									<b><div id='elapse'></div></b>
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

		$totalonscene = 0;
		$totalmissed = 0;
		$totallate = 0;
		$totalgood = 0;

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$status = "<h4>Unknown</h4>";
				$par = "<center>Unit Not On Scene</center>";
				switch ($row["status"]) {
					case "dispatched":
						$status = "<h4 style=\"color:blue\">Dispatched</h4>";
						break;
					case "enroute":
						$status = "<h4 style=\"color:blue\">En Route</h4>";
						break;
					case "scene":
						$parcolor = "primary";
						$totalonscene += 1;
						if ($row["PAR"] == 0) {
							$totalmissed += 1;
							$parcolor = "danger";
						} elseif (time()-strtotime($row["lastPAR"]) > 900) {
							$totallate += 1;
							$parcolor = "warning";
						} else {
							$totalgood += 1;
							$parcolor = "success";
						}
						$par = "<div class='row'><div class='col-sm-1'></div><div class='col-sm-5'><h4><b>00:00:00</b></h4></div><div class='col-sm-5'><button type='button' class='btn btn-".$parcolor."' style='float: right;'>Checkin</button></div><div class='col-sm-1'></div></div>";
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
						<div class=\"panel-footer\">".$par."</div>
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
				<div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo (100*($totalgood/$totalonscene));?>%">
					<span class="badge"><?php echo $totalgood;?></span> Checked-in
				</div>
				<div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo (100*($totallate/$totalonscene));?>%">
					<span class="badge"><?php echo $totallate;?></span> >15 Minutes Since Check-in
				</div>
				<div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo (100*($totalmissed/$totalonscene));?>%">
					<span class="badge"><?php echo $totalmissed;?></span> Not Yet Responded
				</div>
			</div>
		</div>
		<script>
			function beginPAR(){
				var ajax = new XMLHttpRequest();
				ajax.open('POST', '/newpar.php', true);
				ajax.send();
			}
			function startTime() {
			  const today = new Date();
			  let h = today.getHours();
			  let m = today.getMinutes();
			  let s = today.getSeconds();
			  m = checkTime(m);
			  s = checkTime(s);
			  h = checkTime(h);
			  document.getElementById('clock').innerHTML =  h + ":" + m + ":" + s;
			  setTimeout(startTime, 1000);
			}
			
			function startElapsed(start, id) {
			  const today = new Date() - new Date(start);
			  let h = today.getHours();
			  let m = today.getMinutes();
			  let s = today.getSeconds();
			  m = checkTime(m);
			  s = checkTime(s);
			  h = checkTime(h);
			  document.getElementById(id).innerHTML =  h + ":" + m + ":" + s;
			  setTimeout(startElapsed, 1000, start, id);
			}

			function checkTime(i) {
			  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
			  return i;
			}
		</script>
		<div class='col-sm-1'><a onclick="beginPAR()" href="javascript:void(0)"><button type="button" class="btn btn-primary">New PAR</button></a></div>
		<div class='col-sm-1'></div>
	</div>
</center>
<hr>
<div class="container">    
  <div class="row">
	<?php echo $boxes; ?>
  </div>
</div><br>