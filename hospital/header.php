<?php
function bootlibs() {
	$termMode=false;
	if ($termMode) {
	print "
		<meta charset=\"utf-8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<link rel=\"stylesheet\" href=\"../libraries/bootstrap-386-dist/css/bootstrap.min.css\">
		<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
		<script src=\"../libraries/bootstrap-386-dist/js/bootstrap.min.js\"></script>
		<script src=\"../libraries/dialogs/js/bootstrap-dialog.min.js\"></script>
		<link rel=\"stylesheet\" href=\"../libraries/dialogs/css/bootstrap-dialog.min.css\">
	";
	} else {
		print "
		<meta charset=\"utf-8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<link rel=\"stylesheet\" href=\"../libraries/bootstrap-3.4.1-dist/css/bootstrap.min.css\">
		<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
		<script src=\"../libraries/bootstrap-3.4.1-dist/js/bootstrap.min.js\"></script>
		<script src=\"../libraries/dialogs/js/bootstrap-dialog.min.js\"></script>
		<link rel=\"stylesheet\" href=\"../libraries/dialogs/css/bootstrap-dialog.min.css\">
	";
	}
}
function topbar($admin = false) {
	session_start();
	include("../db.php");
	include("../options.php");
	if (!isset($_SESSION["hospital"])) {
		die("<script>location.href = 'login.php?error=notlogged'</script>");
	}		
	bootlibs();
	
	print "
		<nav class=\"navbar navbar-inverse\">
			<div class=\"container-fluid\">
				<div class=\"navbar-header\">
					<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#myNavbar\">
						<span class=\"icon-bar\"></span>
						<span class=\"icon-bar\"></span>
						<span class=\"icon-bar\"></span>                        
					</button>
					<a class=\"navbar-brand\" href=\".\"><abbr title=\"Open Emergency Operations Suite\"><b style=\"color:white;\">O</b><b style=\"color:red;\">EO</b><b style=\"color:white;\">S</b></abbr> <small>".$name."</small>  <b>Hospital Portal</b></a>
				</div>
				<div class=\"collapse navbar-collapse\" id=\"myNavbar\">
					<ul class=\"nav navbar-nav\">
						<li><a href=\".\">Home</a></li>
					</ul>
					<ul class=\"nav navbar-nav navbar-right\">
						<li><a href=\"logout.php\">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>";
}
?>