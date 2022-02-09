<?php
include_once("db.php");
require("header.php");
topbar();
echo"
	<div class='col-sm-5'>
		<div class='panel panel-info'>
			<div class='panel-heading'>Patients</div>
			<div class='panel-body'>
				<table class='table table-striped table-hover'>
					<thead>
						<tr>
							<th>Name</th>
							<th>Chief Complaint</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><span class='label label-danger'>Immediate</span> John Doe (35M)</td>
							<td>Major Hemorrhage</td>
							<td>Transported</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

    <div class='col-sm-7'>
		<div class='panel panel-default'>
			<div class='panel-heading'>Triage</div>
				<div class='panel-body'>
					<ul class='list-group'>
						<div class='col-sm-6'>
							<li class='list-group-item list-group-item-danger'>Immediate <span class='badge'>12</span></li>
							<li class='list-group-item list-group-item-warning'>Urgent <span class='badge'>12</span></li>
							<li class='list-group-item list-group-item-success'>Delayed <span class='badge'>12</span></li>
						</div>
						<div class='col-sm-6'>				
							<li class='list-group-item'>Deceased/Expectant <span class='badge'>12</span></li>
							<li class='list-group-item active'>Unknown <span class='badge'>12</span></li>
						</div>
					</ul>
				</div>
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
							<th>Type</th>
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
									$capt .= "<span class='label label-warning'>Stoke</span> ";
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
echo"					</tbody>
				</table>
			</div>
		</div>
	</div>";

?>