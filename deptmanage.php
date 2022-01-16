<?php

	include("header.php");
	topbar(true);
	include("db.php");
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	if ($conn->connect_error) {
		echo "
				<div class=\"alert alert-warning alert-dismissible\">
					<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
					<strong>Error:</strong> A Server Error has Occured. [ECode: UAdmin-HT500A]
				</div>";
	} 
	echo"
		<div class='col-sm-10'>
			<table class='table table-striped table-hover table-condensed'>
				<thead>
				  <tr>
					<th>UID</th>
					<th>Name</th>
					<th>Options</th>
				  </tr>
				</thead>
				<tbody>";
					
					$sql = "SELECT * FROM departments";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$deptColor = "default";
							switch ($row["Color"]) {
								case 0:
									break;
								case 1:
									$deptColor = "danger";
									break;
								case 2:
									$deptColor = "info";
									break;
								case 3:
									$deptColor = "info";
									break;
								case 4:
									$deptColor = "success";
									break;
								case 5:
									$deptColor = "warning";
									break;
								case 6:
									$deptColor = "basic";
									break;
							}
							echo "
							<tr class='".$deptColor."'>
								<td>".$row["ID"]."</td>
								<td>".$row["Name"]."</td>
								<td>
					<span onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_WARNING,
						title: 'Confirm Deletion',
						message: 'Are you Sure you want to delete the department &#34".$row["Name"]."&quot?',
						buttons: [{
							label: 'Yes/Confirm',
							action: function(dialogItself){
								dialogItself.close();
							}
						},
						{
							label: 'No/Cancel',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});\" class='glyphicon glyphicon-trash'></span></td>
							</tr>";
						}
					}
					
			echo"</tbody>
			</table>
		</div>
	</div>
</div>";
?>