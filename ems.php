<?php
include_once("db.php");
require("header.php");
topbar();
echo"
	<div class='col-sm-5'>
		<div class='panel panel-info'>
			<div class='panel-heading'>Patients</div>
			<div class='panel-body'>  <button onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_PRIMARY,
						title: 'New Patient',
						message: '<form><input></input></form>',
						buttons: [{
							label: 'Close',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});;\">New Patient</button>";
			if ($_SESSION["incident"] != null) {
				echo"<table class='table table-striped table-hover'>
					<thead>
						<tr>
							<th>Name</th>
							<th>Chief Complaint</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>";
						$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
						$sql = "SELECT * FROM patients WHERE Incident = ".$_SESSION["incident"];
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								$triagecolor = "label-primary";
								$triagename = "Unknown";
								switch ($row["triage"]) {
									case 1:
										$triagecolor = "label-danger";
										$triagename = "Immediate";
										break;
									case 2:
										$triagecolor = "label-warning";
										$triagename = "Delayed";
										break;
									case 3:
										$triagecolor = "label-success";
										$triagename = "Minor";
										break;
									case 4:
										$triagecolor = "label-default";
										$triagename = "Deceased";
										break;
								}
								echo "
									<tr onclick=\"location.href = 'patient?id=".$row["ID"]."'\">
										<td><span class='label ".$triagecolor."'>".$triagename."</span> <abbr title='".$row["triageTag"]."'>".$row["firstName"]." ".$row["lastName"]."</abbr> (".$row["age"]."M)</td>
										<td>".$row["chiefComplaint"]."</td>
										<td>".$row["status"]."</td>
									</tr>
								";
							}
						}
						$conn->close();
echo "				</tbody>
				</table>";
			} else {
				echo "<center>
						<h4>No Assigned Incident</h4>
					</center>";
			}
echo"		</div>
		</div>
	</div>

    <div class='col-sm-7'>
		<div class='panel panel-default'>
			<div class='panel-heading'>Triage</div>
				<div class='panel-body'>";
					if ($_SESSION["incident"] != null) {
						$deceased = 0;
						$unknown = 0;
						$minor = 0;
						$delayed = 0;
						$immediate = 0;
						$total = 0;
						$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
						$sql = "SELECT COUNT(ID) as total, SUM(CASE WHEN triage = 4 THEN 1 ELSE 0 END) AS deceased, SUM(CASE WHEN triage = 0 THEN 1 ELSE 0 END) AS unknown, SUM(CASE WHEN triage = 1 THEN 1 ELSE 0 END) AS immediate, SUM(CASE WHEN triage = 2 THEN 1 ELSE 0 END) AS yellow, SUM(CASE WHEN triage = 3 THEN 1 ELSE 0 END) AS minor FROM patients WHERE Incident = ".$_SESSION["incident"];
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								$deceased = $row["deceased"];
								$unknown = $row["unknown"];
								$minor = $row["minor"];
								$delayed = $row["yellow"];
								$immediate = $row["immediate"];
								$total = $row["total"];
							}
						}
						$conn->close();
						if ($total < 1) {
							$total = 1;
						}
						echo "
					<div class='progress'>
						<div class='progress-bar progress-bar-success' role='progressbar' style='width:".(100*($minor/$total))."%'>
							Minor
						</div>
						<div class='progress-bar progress-bar-warning' role='progressbar' style='width:".(100*($delayed/$total))."%'>
							Delayed
						</div>
						<div class='progress-bar progress-bar-danger' role='progressbar' style='width:".(100*($immediate/$total))."%'>
							Immediate
						</div>
						<div class='progress-bar progress-bar-default' role='progressbar' style='width:".(100*($deceased/$total))."%'>
							Deceased
						</div>
						<div class='progress-bar progress-bar-primary' role='progressbar' style='width:".(100*($unknown/$total))."%'>
							Unknown
						</div>
					</div>
					<ul class='list-group'>
						<div class='col-sm-6'>
							<li class='list-group-item list-group-item-danger'>Immediate <span class='badge'>".$immediate."</span></li>
							<li class='list-group-item list-group-item-warning'>Delayed <span class='badge'>".$delayed."</span></li>
							<li class='list-group-item list-group-item-success'>Minor <span class='badge'>".$minor."</span></li>
						</div>
						<div class='col-sm-6'>				
							<li class='list-group-item'>Deceased/Expectant <span class='badge'>".$deceased."</span></li>
							<li class='list-group-item active'>Unknown <span class='badge'>".$unknown."</span></li>
						</div>
					</ul>";
					} else {
						echo "<center>
							<h4>No Assigned Incident</h4>
						</center>";
					}
echo "			</div>
			</div>
		<div class='panel panel-default'>
			<div class='panel-heading'>Hospitals</div>
			<div class='panel-body'>
				<table class='table table-striped table-hover'>
					<thead>
						<tr>
							<th>Name</th>
							<th>Distance</th>
							<th>Diversion</th>
							<th>Capabilities</th>
						</tr>
					</thead>
					<tbody>";
						$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
						$sql = "SELECT * FROM hospitals";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								$divertRed = " class='warning'";
								$divertColor = "label-warning";
								$strike = "";
								switch ($row["diversion"]) {
									case "Closed":
										$strike = "<s>";
									case "Full Diversion":
										$divertRed = " class='danger'";
										$divertColor = "label-danger";
										break;
									case "Open":
										$divertColor = "label-success";
										$divertRed = "";
										break;
								}
								$capt = "";
								$capm = "";
								if ($row["Helipad"] > 0) {
									$capt .= "<span class='label label-primary'>Helipad</span> ";
									$capm .= "<span class=\'label label-primary\'>Helipad</span> ";
								}
								if ($row["Trauma"] > 0) {
									$capt .= "<span class='label label-danger'>Trauma | ".$row["Trauma"]."</span> ";
									$capm .= "<span class=\'label label-danger\'>Trauma | ".$row["Trauma"]."</span> ";
								}
								if ($row["Burn"] > 0) {
									$capt .= "<span class='label label-warning'>Burn</span> ";
									$capm .= "<span class=\'label label-warning\'>Burn</span> ";
								}
								if ($row["STEMI"] > 0) {
									$capt .= "<span class='label label-warning'>STEMI</span> ";
									$capm .= "<span class=\'label label-warning\'>STEMI</span> ";
								}
								if ($row["Stroke"] > 0) {
									$capt .= "<span class='label label-warning'>Stroke</span> ";
									$capm .= "<span class=\'label label-warning\'>Stroke</span> ";
								}

								echo "<tr".$divertRed." onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_PRIMARY,
						title: 'Details for ".$row["Name"]."',
						message: '<b>Address:</b> ".$row["Address"]."<br><b>Capabilities:</b> ".$capm."<hr><b>Diversion Status: </b>".$row["diversion"]."<br><b>Diversion Note:</b> ".$row["diversionNote"]."<br><b>Last Updated:</b> ".$row["diversionUpdate"]."<hr><b>Contact Details:</b><br>".$row["Contact"]."',
						buttons: [{
							label: 'Close',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});;\" style='cursor: pointer;'>
							<td>".$strike.$row["Name"]."</s></td>
							<td>".$strike."00 Mi (00 min)</s></td>
							<td>".$strike."<span class='label ".$divertColor."'>".$row["diversion"]."</span></s></td>
							<td>".$strike.$capt."</s></td>
						</tr>";
							}
						}
						$conn->close();
echo"					</tbody>
				</table>
			</div>
		</div>
	</div>";

?>