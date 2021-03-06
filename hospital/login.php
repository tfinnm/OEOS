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
			include_once("../options.php");
			
			echo "<h1><abbr title='Open Emergency Operations Suite'><b style='color:white;'>O</b><b style='color:red;'>EO</b><b style='color:white;'>S</b></abbr><br><small>".$name."</small><br><b>Hospital Portal</b></h1>
			<br/>";
			
			if (isset($_SESSION["loggedin"])) {
				die("<script>location.href = '.'</script>");
			}
			if (isset($_GET["error"]) && $_GET["error"] == "login") {
				echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> Invalid Username/Password Combo. [ECode: Auth-HT401]
			</div>";
			} elseif (isset($_GET["error"]) && $_GET["error"] == "logout") {
				echo "
			<div class=\"alert alert-success alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Success:</strong> Successfully Logged Out.
			</div>";
			} elseif (isset($_GET["error"]) && $_GET["error"] == "pswrdcng") {
				echo "
			<div class=\"alert alert-success alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Success:</strong> Password Changed Successfully.
			</div>";
			} elseif (isset($_GET["error"]) && $_GET["error"] == "server") {
				echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> A Server Error has Occured. [ECode: Auth-HT500]
			</div>";
			} elseif (isset($_GET["error"]) && $_GET["error"] == "notlogged") {
				echo "
			<div class=\"alert alert-warning alert-dismissible\">
				<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
				<strong>Error:</strong> Authentication required to access system. [ECode: Auth-HT403]
			</div>";
			}
			?>
			<br/>
			<div class="well well-lg">
				<h2>Login</h2>
				<form class="form-horizontal" method="post" action="">
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Username:</label>
						<div class="col-sm-10">
							<input required type="text" class="form-control" id="usrnm" name="usrnm" placeholder="Enter username">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Password:</label>
						<div class="col-sm-10">
							<input required type="password" class="form-control" id="pwd" name="pswrd" placeholder="Enter password">
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-default">Log In</button>
					</div>
				</form>
				<br>
				<a href="../login">Back to OEOS Login</a>
			</div>
		</center>
	</div>
</body>
</html>

<?php
include("../db.php");
session_start();
if (isset($_POST["usrnm"]) && isset($_POST["pswrd"])) {
$user = $_POST["usrnm"];
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
    die("<script>location.href = '?error=server'</script>");
} 
$sql = "SELECT * FROM hospitals where uname = '$user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$pswrd = $row["upass"];
		$id = $row["ID"];
    }
} else {
    die("<script>location.href = '?error=login'</script>");
}
$conn->close();
	if (password_verify( $_POST["pswrd"] , $pswrd )) {
	//	session_start();
		$_SESSION["hospital"] = $id;
		echo ("<script>location.href = '.'</script>");
	}else {
		die("<script>location.href = '?error=login'</script>");
	}
}
?>