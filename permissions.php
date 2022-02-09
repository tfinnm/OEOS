<?php
	function getPermissions()
	{
		$perms = array(
			"selfassign" => false,
			"ems" => false,
			"assign" => false,
			"admin" => false,
			"musers" => false,
			"munits" => false,
			"mperms" => false,
			"mdepts" => false,
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
				if ($row["perm.ems"] == "1") {
					$perms["ems"] = true;
				}
				if ($row["perm.selfassign"] == "1") {
					$perms["selfassign"] = true;
				}
				if ($row["perm.manageusers"] == "1") {
					$perms["admin"] = true;
					$perms["musers"] = true;
				}
				if ($row["perm.manageunits"] == "1") {
					$perms["admin"] = true;
					$perms["munits"] = true;
				}
				if ($row["perm.manageperms"] == "1") {
					$perms["admin"] = true;
					$perms["mperms"] = true;
				}
				if ($row["perm.managedepts"] == "1") {
					$perms["admin"] = true;
					$perms["mdepts"] = true;
				}
			}
		}
		$conn->close();
		$_SESSION["permissions"] = $perms;
	}
?>