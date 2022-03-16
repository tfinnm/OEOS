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
			<div class='panel-body'>
				<form class='form-inline' action='/action_page.php'>
					<div class='form-group'>
						<div class='dropdown'>
							<input type='text' id='triagelevelinput' name='triagelevelinput' hidden required></input>
							<button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'><span class='label label-primary'>Unknown</span><span class='caret'></span></button>
							<ul class='dropdown-menu'>
							  <li><a href=\"javascript:void('0');\" onclick=\"document.getElementById('triagelevelinput').setAttribute('value', '1');\"><span class='label label-danger'>Immediate</span></a></li>
							  <br>
							  <li><a href=\"javascript:void('0');\" onclick=\"document.getElementById('triagelevelinput').setAttribute('value', '2');\"><span class='label label-warning'>Delayed</span></a></li>
							  <br>
							  <li><a href=\"javascript:void('0');\" onclick=\"document.getElementById('triagelevelinput').setAttribute('value', '3');\"><span class='label label-success'>Minor</span></a></li>
							  <br>
							  <li><a href=\"javascript:void('0');\" onclick=\"document.getElementById('triagelevelinput').setAttribute('value', '4');\"><span class='label label-default'>Deceased</span></a></li>
							</ul>
						</div>
					</div>
					<div class='form-group'>
						<input type='text' class='form-control' size= '10' id='triagetag' placeholder='Triage Tag #' name='triagetag'>
					</div>
					<div class='form-group'>
						<input type='text' class='form-control' size='20' id='fname' placeholder='First Name' name='fname'>
						<input type='text' class='form-control' size='02' id='mname' placeholder='MI' name='mname'>
						<input type='text' class='form-control' size='20' id='lname' placeholder='Last Name' name='lname'>
					</div>
					<div class='form-group'>
						<input type='text' class='form-control' size='3' id='age' placeholder='Age' name='age'>
						<select name='gender' class='form-control' id='gender'>
							<option value='' selected disabled hidden>Gender</option>
							<option value='M'>Male</option>
							<option value='F'>Female</option>
							<option value='O'>Other</option>
						</select>
					</div>
					<hr>
					<div class='form-group'>
						<label class='control-label' for='cc'>Chief Complaint: </label>
						<input type='text' class='form-control' id='cc' placeholder='Chief Complaint' name='cc'>
					</div>
					<h4 style='float:right'><b>Refused Care</b></h4>
				</form>
				<ul class='nav nav-tabs'>
					<li class='active'><a data-toggle='tab' href='#menu1'>Main</a></li>
					<li><a data-toggle='tab' href='#sympt'>Symptoms</a></li>
					<li><a data-toggle='tab' href='#vitals'>Vitals</a></li>
					<li><a data-toggle='tab' href='#treat'>Treatments</a></li>
					<li><a data-toggle='tab' href='#treat'>Patient</a></li>
					<li class='dropdown'>
						<a class='dropdown-toggle' data-toggle='dropdown' href='#'>Menu 1 <span class='caret'></span></a>
						<ul class='dropdown-menu'>
							<li><a data-toggle='tab' href='#menu1'>Submenu 1-1</a></li>
							<li><a data-toggle='tab' href='#menu1'>Submenu 1-2</a></li>
							<li><a data-toggle='tab' href='#menu1'>Submenu 1-3</a></li>                        
						</ul>
					</li>
				</ul>
				<div class='tab-content'>
					<div id='menu1' class='tab-pane fade in active'>
					  <h3>HOME</h3>
					  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
					<div id='vitals' class='tab-pane fade'>
						<h3>Patient Vitals</h3>
					  	<table class='table table-striped table-hover'>
							<thead>
								<tr>
									<th>Time</th>
									<th>LOC/GCS</abbr></th>
									<th><abbr title='Heart Rate/Pulse (BPM)'>HR</abbr></th>
									<th><abbr title='Respiration Rate'>Respirations</abbr></th>
									<th><abbr title='Blood Pressure (Systolic/Diastolic MM HG)'>BP</abbr></th>
									<th><abbr title=''>Perfusion</abbr></th>
									<th><abbr title=''>Pupils</abbr></th>
									<th><abbr title='Pulse Oximetery/O2 Sat'>SpO2</abbr></th>
									<th><abbr title='Blood Glucose'>BG</abbr></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>10:29:22</td>
									<td>AOx4/15</td>
									<td>60</td>
									<td>16</td>
									<td>120/80</td>
									<td></td>
									<td></td>
									<td>99%</td>
									<td>80</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
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
					<form class='form-horizontal' action='/action_page.php'>
						<div class='form-group'>
							<label class='col-sm-2' for='Hospital'>Hospital:</label>
							<div class='col-sm-10'>
								<select class='form-control' id='hospital'>
									<option value='0'>Inova Fairfax</option>
									<option value='2'>Inova Loudoun</option>
									<option value='1'>Inova Fairoaks</option>
								</select>
							</div>
						</div>
						<div class='form-group'>
							<div class='col-sm-12'>
								<center>
									<label class='checkbox-inline'><input type='checkbox' value=''>Trauama Alert</label>
									<label class='checkbox-inline'><input type='checkbox' value=''>Stroke Alert</label>
									<label class='checkbox-inline'><input type='checkbox' value=''>STEMI Alert</label>
									<label class='checkbox-inline'><input type='checkbox' value=''>CPR Alert</label>
								</center>
							</div>
						</div>
						<div class='form-group'>								
							<div class='col-sm-offset-9'>
								<button type='submit' class='btn btn-default'>Notify</button>
							</div>
						</div>
					</form>
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