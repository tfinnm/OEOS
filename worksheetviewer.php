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
	$sql = "SELECT * FROM tacticalworksheets where ID = ".$_GET["form"];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo"<div class='container-fluid'>
				<div class='row'>
					<div class='col-sm-4'>
						<div class='panel panel-primary'>
							<div class='panel-heading'>Incident Sketch</div>
							<div class='panel-body'>
							<center>
								Map/sketch goes here
							</center>
						</div>
					</div>
					Side Form Here
				</div>
				<div class='col-sm-8'>
					Main Form Here
				</div>
			</div>";
		}
	} else {
		echo("Worksheet Not Found");
	}
?>