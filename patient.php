<?php
include_once("db.php");
require("header.php");
topbar();
echo"
	<head>
		<script src='https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js'></script>
	<head>
	<div class='col-sm-8'>
		<div class='panel panel-info'>
			<div class='panel-heading'>Patient Details</div>
			<div class='panel-body'>";
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
									<tr>
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
    <div class='col-sm-4'>
		<div class='panel panel-default'>
			<div class='panel-heading'>Signatures & Approvals</div>
				<div class='panel-body'>
				<button onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_PRIMARY,
						title: 'Refusal of Care Form',
						message: 'Because it is sometimes impossible to recognize actual or potential medical problems outside the hospital, we strongly encourage you to be evaluated, treated if necessary, and transported to a hospital by EMS personnel for more complete examination by a physician.'+
								'<br><br>You have the right to choose to not be evaluated, treated or transported if you wish; however, there is the possibility that you could suffer serious complications or even death from conditions that are not apparent at this time.'+ 
								'<br><br>By signing below, you are acknowledging that EMS personnel have advised you, and that you understand, the potential harm to your health that may result from your refusal of the recommended care; and, you release EMS and supporting personnel from liability resulting from refusal.'+			
							'<hr><form>'+
								'I refuse:'+
								'<center>'+
									'<label class=\'radio-inline\'>'+
										'<input type=\'radio\' name=\'refuseType\' value=\'eval\' required>Evaluation'+
									'</label>'+
									'<label class=\'radio-inline\'>'+
										'<input type=\'radio\' name=\'refuseType\' value=\'treat\' required>Treatment'+
									'</label>'+
									'<label class=\'radio-inline\'>'+
										'<input type=\'radio\' name=\'refuseType\' value=\'trans\' required>Transport'+
									'</label>'+
								'</center>'+
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
										'<p><b>Sign Here</b> (Patient Signature)</p>'+
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
					});;\">Refusal</button>
				</div>
			</div>
		<div class='panel panel-default'>
			<div class='panel-heading'>Transport</div>
				<div class='panel-body'>
				//stuff goes here
				</div>
			</div>
		<div class='panel panel-default'>
			<div class='panel-heading'>Providers</div>
			<div class='panel-body'>
				<table class='table table-striped table-hover'>
					<thead>
						<tr>
							<th>Role</th>
							<th>Name</th>
							<th>Provider Level</th>
						</tr>
					</thead>
					<tbody>";
						$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
						$sql = "SELECT providers.role, personel.name, personel.providerLevel FROM personel INNER JOIN providers ON providers.provider=personel.ID WHERE providers.patient = ".$_GET["id"];
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								echo "<tr>
							<td>".$row["role"]."</td>
							<td>".$row["name"]."</td>
							<td>".$row["providerLevel"]."</td>
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