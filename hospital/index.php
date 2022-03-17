<?php
include_once("../db.php");
require("header.php");
topbar();

echo "<head>
	<script src='https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js'></script>
<head>
<div class='container-fluid'>";
if (isset($_GET["error"]) && $_GET["error"] == "notadmin") {
	echo "
		<div class=\"alert alert-warning alert-dismissible\">
			<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
			<strong>Error:</strong> Not Authenticated as Admin [ECode: Auth-HT401]
		</div>";
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
		
	echo "
	<div class='row'>
    <div class='col-sm-6'>
	<div class='panel panel-default'>
		<div class='panel-heading'>Diversion Status</div>
		<div class='panel-body'>
			<form action='' method='post'>
				<div class='form-group'>
					<center>
						<select name='statusChange' class='form-control'>
							<option value='' selected disabled hidden>placeholder</option>
							<option value='AvailableQ'>Open</option>
							<option value='Available'>Special Diversion</option>
							<option value='StandBy'>Full Diversion</option>
							<option value='OOS'>Closed</option>
						</select>
					</center>
				</div>
				<div class='form-group'>
					<div class='col-sm-2'>
						<label for=\"details\">Details:</label>
					</div>
					<div class='col-sm-10'>
						<textarea class='form-control' name=\"details\" style=\"width:100%\"></textarea>
						<br>
					</div>
				</div>
				<div class='form-group'>
					<label class='checkbox-inline'><input type='checkbox' value='confirm'>Confirm Changes</label>
					<button style='float:right' type='submit' class='btn btn-default'>Update Status</button>
				</div>
			</form>
			</center>
		</div>
	</div>
	<div class='panel panel-default'>
		<div class='panel-heading'>Recieved Patients</div>
			<div class='panel-body'>
				<table class='table table-striped table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Chief Complaint</th>
					</tr>
				</thead>
				<tbody>";
					$sql = "SELECT * FROM patients WHERE hospital = ".$_SESSION["hospital"];
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						// output data of each row
						while($row = $result->fetch_assoc()) {
							echo "<tr>
									<td>".$row["firstName"]."</td>
									<td>".$row["chiefComplaint"]."</td>
								</tr>";
						}
					} else {
						echo "
								<tr><td>No Patients</td><td></td></tr>";
					}
					echo "
				</tbody>
			</table>
		</div>
	</div>
</div>
    <div class='col-sm-6'>
		<div class='panel panel-default'>
			<div class='panel-heading'>Enroute Patients - Triage</div>
			<div class='panel-body'>";
						$deceased = 0;
						$unknown = 0;
						$minor = 0;
						$delayed = 0;
						$immediate = 0;
						$total = 0;
						$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
						$sql = "SELECT COUNT(ID) as total, SUM(CASE WHEN triage = 4 THEN 1 ELSE 0 END) AS deceased, SUM(CASE WHEN triage = 0 THEN 1 ELSE 0 END) AS unknown, SUM(CASE WHEN triage = 1 THEN 1 ELSE 0 END) AS immediate, SUM(CASE WHEN triage = 2 THEN 1 ELSE 0 END) AS yellow, SUM(CASE WHEN triage = 3 THEN 1 ELSE 0 END) AS minor FROM patients WHERE hospital = ".$_SESSION["hospital"];
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
						<div class='progress-bar' role='progressbar' style='width:".(100*($deceased/$total))."%'>
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
					</ul>
				</div>
			</div>
			<div class='panel panel-default'>
		<div class='panel-heading'>Incoming Patients</div>
			<div class='panel-body'>
				<table class='table table-striped table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Chief Complaint</th>
					</tr>
				</thead>
				<tbody>";
					$sql = "SELECT * FROM patients WHERE hospital = ".$_SESSION["hospital"];
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						// output data of each row
						while($row = $result->fetch_assoc()) {
							echo "<tr onclick=\"BootstrapDialog.show({
										type: BootstrapDialog.TYPE_PRIMARY,
										title: 'Recieve Patient ".$row["lastName"].", ".$row["firstName"]." ".$row["middleInitial"]." (".$row["age"].$row["gender"].")',
										message: '<form> <b>Patient: </b>".$row["lastName"].", ".$row["firstName"]." ".$row["middleInitial"]." (".$row["age"].$row["gender"].")<br><b>Triage Tag: </b>".$row["triageTag"]."<br><b>Chief Complaint: </b>".$row["chiefComplaint"]."'+
								'<br><br><label class=\'checkbox-inline\'><input type=\'checkbox\' value=\'confirm\' required>This is the correct patient.</label><hr>'+ 
								'By signing, I confirm that I have been transferred care of this patient from EMS and that I assume responsibility for this patient and their care.'+			
								'<br>'+
								'<center>'+
									'<input type=\'text\' id=\'signatureinput\' name=\'signatureinput\' hidden required></input>'+
									'<div class=\'wrapper\'>'+
										'<canvas style=\'outline: 1px solid black;\' id=\'signature-pad\' class=\'signature-pad\' width=400 height=200></canvas>'+
										'<script>'+
											'var canvas = document.getElementById(\'signature-pad\');'+
											'var signaturePad = new SignaturePad(canvas, {'+
												'backgroundColor: \'rgb(255, 255, 255)\''+
											'});'+
											'signaturePad.addEventListener(\'endStroke\', () => {'+
												'document.getElementById(\'signatureinput\').setAttribute(\'value\', signaturePad.toDataURL());'+
											'});'+
										'</script>'+
										'<p><b>Sign Here</b> (Recieving Provider Signature)</p>'+
									'</div>'+
								'</center>'+
								'<input type=\'submit\'></input>'+
							'<form>',
										buttons: [{
											label: 'Close',
											action: function(dialogItself){
												dialogItself.close();
											}
										}]
									});;\">
									<td>".$row["firstName"]."</td>
									<td>".$row["chiefComplaint"]."</td>
								</tr>";
						}
					} else {
						echo "
								<tr><td>No Patients</td><td></td></tr>";
					}
					echo "
				</tbody>
			</table>
		</div>
	</div>";
$conn->close();
?>
	</div>
  </div>
</div>