<?php
	include("header.php");
?>

<?php
		include("db.php");
		$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		// Check connection
		if ($conn->connect_error) {
			die("<script>location.href = 'index.php?error=server'</script>");
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
		
		
		$sql = "SELECT * FROM units";
		if (isset($_POST["dept"])) {
			$sql = "SELECT * FROM units WHERE deptID = '".$_POST["dept"]."'";
		}
		$result = $conn->query($sql);
		
		$boxes = "";
		$quick = "";

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$status = "<h4 style=\"color:blue\">Unknown</h4>";
				switch ($row["status"]) {
					case "available":
						$status = "<h4 style=\"color:green\">Available</h4>";
						break;
					case "dispatched":
						$status = "<h4 style=\"color:yellow\">Dispatched</h4>";
						break;
					case "returning":
						$status = "<h4 style=\"color:yellow\">Returning to Service</h4>";
						break;
					case "oos":
						$status = "<h4 style=\"color:red\">Out of Service</h4>";
						break;
					case "staff":
						$status = "<h4 style=\"color:red\">Missing Staffing</h4>";
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
				if (!isset($_SESSION["incident"])) {
					$assignButton = "<p>Please Select Incident</p>";
				} elseif ($_SESSION["incident"]  == $row["incidentID"]) {
					$assignButton = "<button class=\"btn btn-danger\">Cancel from Incident</button>";
				} else {
					$assignButton = "<button class=\"btn btn-default\">Assign to Incident</button>";
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
<form class="form-inline" action="#">
  <div class="form-group">
    <label for="quick">Quick Assign:</label>
    <select class="form-control" id="quick">
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
  <button href="" class="btn btn-default">Reset</button>
</form>
</center>

<div class="container">    
  <div class="row">
	<?php echo $boxes; ?>
  </div>
</div><br>