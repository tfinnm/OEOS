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
					<th>Username</th>
					<th>Position</th>
					<th>Permissions</th>
					<th>Options</th>
				  </tr>
				</thead>
				<tbody>";
					
					$sql = "SELECT * FROM personel";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$permissions = "";
							if ($row["perm.assign"] == 1) {
								$permissions .= "<abbr title = 'Assign/Dispatch'><span class='glyphicon glyphicon-phone-alt'> </span></abbr> ";
							}
							if ($row["perm.ems"] == 1) {
								$permissions .= "<abbr title = 'EMS'><span class='glyphicon glyphicon-heart'> </span></abbr> ";
							}
							if ($row["perm.selfassign"] == 1) {
								$permissions .= "<abbr title = 'Self-Assign/Self-Dispatch'><span class='glyphicon glyphicon-check'> </span></abbr> ";
							}
							if ($row["perm.command"] == 1) {
								$permissions .= "<abbr title = 'Assume Command'><span class='glyphicon glyphicon-flag'> </span></abbr> ";
							}
							if ($row["perm.manageusers"] == 1) {
								$permissions .= "<abbr title = 'Manage Users'><span class='glyphicon glyphicon-user'> </span></abbr> ";
							}
							if ($row["perm.manageunits"] == 1) {
								$permissions .= "<abbr title = 'Manage Units'><span class='glyphicon glyphicon-road'> </span></abbr> ";
							}
							if ($row["perm.manageperms"] == 1) {
								$permissions .= "<abbr title = 'Manage Permissions'><span class='glyphicon glyphicon-cog'> </span></abbr> ";
							}
							if ($row["perm.managedepts"] == 1) {
								$permissions .= "<abbr title = 'Manage Departments'><span class='glyphicon glyphicon-home'> </span></abbr> ";
							}
							echo "
							<tr>
								<td>".$row["ID"]."</td>
								<td>".$row["name"]." &lt".$row["uname"]."&gt</td>
								<td>".$row["rankname"]."</td>
								<td>".$permissions."</td>
								<td><span onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_PRIMARY,
						title: 'Edit User &#34".$row["name"]."&quot',
						message: '<form></form>',
						buttons: [{
							label: 'Cancel',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});\" class='glyphicon glyphicon-edit'></span> ";
					
					if ($_SESSION["permissions"]["mperms"]) {

						echo "<span onclick=\"BootstrapDialog.show({
							type: BootstrapDialog.TYPE_PRIMARY,
							title: 'Permissions For User &#34".$row["name"]."&quot',
							message: '<form></form>',
							buttons: [{
								label: 'Cancel',
								action: function(dialogItself){
									dialogItself.close();
								}
							}]
						});\" class='glyphicon glyphicon-cog'></span> ";
						
					}
					
					echo "<span onclick=\"BootstrapDialog.show({
						type: BootstrapDialog.TYPE_PRIMARY,
						title: 'Secuirty Options For User &#34".$row["name"]."&quot',
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
						message: 'Are you Sure you want to delete the user account &#34".$row["name"]."&quot?',
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