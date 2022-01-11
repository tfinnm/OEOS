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
		echo"<div class='container-fluid'>
			<div class='row'>
				<div class='col-sm-4'>
					<div class='panel panel-primary'>
						<div class='panel-heading'>Create Worksheet</div>
						<div class='panel-body'>
							<form class='form-horizontal' action='/action_page.php'>
								<div class='form-group'>
									<label class='col-sm-2' for='tone'>Template:</label>
									<div class='col-sm-7'>
										<select class='form-control' id='tone'>";
											$sql = "SELECT * FROM worksheettemplates;";
											$result = $conn->query($sql);
											if ($result->num_rows > 0) {
												while($row = $result->fetch_assoc()) {
													echo "<option value='".$row["ID"]."'>".$row["Name"]."</option>";
												}
											}
										echo "</select>
									</div>
									<div class='col-sm-2'>
										<button type='submit' class='btn btn-default'>Create</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class='col-sm-8'>
					<div class='panel panel-primary'>
						<div class='panel-heading'>Load Worksheet</div>
						<div class='panel-body'>
							<table class='table table-striped table-hover'>
							<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Options</th>
							<tr>
							</thead>
							<tbody>";
								$sql = "SELECT * FROM tacticalworksheets where Incident = ".$_SESSION["incident"];
								$result = $conn->query($sql);
								if ($result->num_rows > 0) {
									while($row = $result->fetch_assoc()) {
										echo "
										<tr>
											<td><a href='worksheetviewer?form=".$row["ID"]."'>".$row["ID"]."</a></td>
											<td><a href='worksheetviewer?form=".$row["ID"]."'>".$row["Name"]."</a></td>
											<td>- - -</td>
										</tr>";
									}
								} else {
									echo "
										<tr>
											<td>No Worksheets Found</td>
										</tr>";
								}
							echo"</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>";
?>