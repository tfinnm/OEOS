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
					<th>Department</th>
					<th>Options</th>
				  </tr>
				</thead>
				<tbody>";
					
					$sql = "SELECT * FROM units";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$sql2 = "SELECT * FROM departments WHERE ID = '".$row["deptID"]."'";
							$result2 = $conn->query($sql2);
							$dept = "";
							if ($result2->num_rows > 0) {
								while($row2 = $result2->fetch_assoc()) {
									$dept = $row2["Name"];
								}
							}
							echo "
							<tr>
								<td>".$row["ID"]."</td>
								<td>".$row["longName"]." &lt".$row["uname"]."&gt</td>
								<td>".$dept."</td>
								<td><span onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_PRIMARY,
						title: 'Edit Unit &#34".$row["longName"]."&quot',
						message: '<form></form>',
						buttons: [{
							label: 'Cancel',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});\" class='glyphicon glyphicon-edit'></span>
					
					<span onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_PRIMARY,
						title: 'Secuirty Options For Unit &#34".$row["longName"]."&quot',
						message: '<form></form>',
						buttons: [{
							label: 'Cancel',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});\" class='glyphicon glyphicon-lock'></span>

					<span onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_WARNING,
						title: 'Confirm Deletion',
						message: 'Are you Sure you want to delete the unit account &#34".$row["longName"]."&quot?',
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