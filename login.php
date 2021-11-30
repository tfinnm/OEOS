<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
</head>
<body style="background:black;">
	<div class="container">
		<center>
			<h1><abbr title="Open Emergency Operations Suite"><b style="color:white;">O</b><b style="color:red;">EO</b><b style="color:white;">S</b></abbr><br><small>Canton County</small></h1>
			<br/>
			<?php
			session_start();
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
						<div class="wrapper">
							<canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
							<script>
								var canvas = document.getElementById('signature-pad');
								var signaturePad = new SignaturePad(canvas, {
									backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
								});
							</script>
							<p><b>Sign Here</b> (Patient Signiture)</p>
						</div>
						<button type="submit" class="btn btn-default">Log In</button>
					</div>
				</form>
			</div>
		</center>
	</div>
</body>
</html>

<?php
include("db.php");
session_start();
if (isset($_POST["usrnm"]) && isset($_POST["pswrd"])) {
$user = $_POST["usrnm"];
// Create connection
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
// Check connection
if ($conn->connect_error) {
    die("<script>location.href = '?error=server'</script>");
} 
$sql = "SELECT * FROM units where uname = '$user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$pswrd = $row["upass"];
		$id = $row["ID"];
		$incident = $row["incidentID"];
    }
} else {
    die("<script>location.href = '?error=login'</script>");
}
$conn->close();
	if (password_verify( $_POST["pswrd"] , $pswrd )) {
	//	session_start();
		$_SESSION["loggedin"] = true;
		$_SESSION["UnitID"] = $id;
		$_SESSION["incident"] = $incident;
		echo ("<script>location.href = '.'</script>");
	}else {
		die("<script>location.href = '?error=login'</script>");
	}
}
?>