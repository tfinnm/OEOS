<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		include("header.php");
		bootlibs();
	?>
</head>
<body style="background:black;">
	<div class="container">
		<center>
			<?php
			include_once("options.php");
			
			echo "<h1><abbr title='Open Emergency Operations Suite'><b style='color:white;'>O</b><b style='color:red;'>EO</b><b style='color:white;'>S</b></abbr><br><small>".$name."</small></h1>
			<br/>";
			
			if (isset($_GET["error"]) && $_GET["error"] == "login") {
				echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> Invalid Username/Password Combo. [ECode: SServ-HT401]
			</div>";
			}
			?>
			<br/>
			<div class="well well-lg">
				<h2>Account Self-Serivce Portal</h2>
				<form class="form-horizontal" method="post" action="">
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Username:</label>
						<div class="col-sm-10">
							<input required type="text" class="form-control" id="usrnm" name="usrnm" placeholder="Enter username">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Old Password:</label>
						<div class="col-sm-10">
							<input required type="password" class="form-control" id="pwd" name="opswrd" placeholder="Enter Old password">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">New Password:</label>
						<div class="col-sm-10">
							<input required type="password" class="form-control" id="pwd" name="npswrd" placeholder="Enter New password">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="acctype">Account Type:</label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="acctype" value="unit" required>Unit</label>
							<label class="radio-inline"><input type="radio" name="acctype" value="user" required>User</label>
							<label class="radio-inline"><input type="radio" name="acctype" value="hospital" required>Hospital</label>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-default">Change Password</button>
					</div>
				</form>
				<a href="login">Back to Login</a>
			</div>
		</center>
	</div>
</body>
</html>

<?php
include("db.php");
session_start();
if (isset($_POST["usrnm"]) && isset($_POST["opswrd"]) && isset($_POST["npswrd"])) {
$user = $_POST["usrnm"];
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
$sql = "";
echo $_POST["acctype"];
if ($_POST["acctype"] == "unit") {
	$sql = "SELECT * FROM units where uname = '$user'";
}
if ($_POST["acctype"] == "user") {
	$sql = "SELECT * FROM personel where uname = '$user'";
}
if ($_POST["acctype"] == "hospital") {
	$sql = "SELECT * FROM hospitals where uname = '$user'";
}
$result = $conn->query($sql);
$pswrd="";
$id="";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		$pswrd = $row["upass"];
		$id = $row["ID"];
    }
} else {
    die("<script>location.href = '?error=login'</script>");
}
if (password_verify( $_POST["opswrd"] , $pswrd )) {
	$newpass = password_hash($_POST["npswrd"], PASSWORD_DEFAULT);
	$sql2 = "";
	if ($_POST["acctype"] == "unit") {
		$sql2 = "UPDATE units SET upass = '".$newpass."' WHERE ID = ".$id;
	}
	if ($_POST["acctype"] == "user") {
		$sql2 = "UPDATE personel SET upass = '".$newpass."' WHERE ID = ".$id;
	}
	if ($_POST["acctype"] == "hospital") {
		$sql2 = "UPDATE hospitals SET upass = '".$newpass."' WHERE ID = ".$id;
	}
	if ($conn->query($sql2) === TRUE) {
		echo ("<script>location.href = 'login?error=pswrdcng'</script>");
	} else {
		echo "Error updating record: " . $conn->error;
	}
}else {
	die("<script>location.href = '?error=login'</script>");
}
$conn->close();
}
?>