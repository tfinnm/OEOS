<?php
	function getPermissions()
	{
		$perms = array(
			"selfassign" => false,
			"assign" => false,
		);
		include("db.php");
		$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		// Check connection
		if ($conn->connect_error) {
			return;
		} 
		$sql = "SELECT * FROM personel where Unit = ".$_SESSION["UnitID"];
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if ($row["perm.assign"] == "1") {
					$perms["assign"] = true;
				}
				if ($row["perm.selfassign"] == "1") {
					$perms["selfassign"] = true;
				}
			}
		}
		$conn->close();
		$_SESSION["permissions"] = $perms;
	}
?>