<?php
function bootlibs() {
	$termMode=false;
	if ($termMode) {
	print "
		<meta charset=\"utf-8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<link rel=\"stylesheet\" href=\"libraries/bootstrap-386-dist/css/bootstrap.min.css\">
		<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
		<script src=\"libraries/bootstrap-386-dist/js/bootstrap.min.js\"></script>
		<script src=\"libraries/dialogs/js/bootstrap-dialog.min.js\"></script>
		<link rel=\"stylesheet\" href=\"libraries/dialogs/css/bootstrap-dialog.min.css\">
	";
	} else {
		print "
		<meta charset=\"utf-8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<link rel=\"stylesheet\" href=\"libraries/bootstrap-3.4.1-dist/css/bootstrap.min.css\">
		<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
		<script src=\"libraries/bootstrap-3.4.1-dist/js/bootstrap.min.js\"></script>
		<script src=\"libraries/dialogs/js/bootstrap-dialog.min.js\"></script>
		<link rel=\"stylesheet\" href=\"libraries/dialogs/css/bootstrap-dialog.min.css\">
	";
	}
}
function topbar($admin = false) {
	session_start();
	include("db.php");
	include("options.php");
	if (!isset($_SESSION["loggedin"])) {
		die("<script>location.href = 'login.php?error=notlogged'</script>");
	}
	include("permissions.php");
	if (!isset($_SESSION["permissions"])) {
		getPermissions();
	}
		
	$iscommand = false;
	if ($_SESSION["incident"] != null) {
		$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
		$sql = "SELECT * FROM incidents where ID = ".$_SESSION["incident"];
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if ($row["commandUnitID"] == $_SESSION["UnitID"]) {
					$iscommand = true;
				}
			}
		}
		$conn->close();
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
					<a class=\"navbar-brand\" href=\".\"><abbr title=\"Open Emergency Operations Suite\"><b style=\"color:white;\">O</b><b style=\"color:red;\">EO</b><b style=\"color:white;\">S</b></abbr> <small>".$name."</small></a>
				</div>
				<div class=\"collapse navbar-collapse\" id=\"myNavbar\">
					<ul class=\"nav navbar-nav\">
						<li><a href=\".\">Home</a></li>
		";
		if ($_SESSION["permissions"]["assign"]) {
			echo "		<li><a href=\"dispatch\">Dispatch Board</a></li>";
		}
		$commandmayday = "";
		if ($iscommand) {
			echo "		<li><a href=\"unitboard\">Command Board</a></li><li><a href=\"worksheet\">Tactical Worksheets</a></li>";
			$commandmayday = "					BootstrapDialog.show({
						type: BootstrapDialog.TYPE_DANGER,
						title: 'MAYDAY!',
						message: 'Mayday Declared on your incident, please acknowledge!',
						buttons: [{
							label: 'Acknowledge',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});";
		}
		if ($_SESSION["permissions"]["ems"]) {
			echo "		<li><a href=\"ems\">EMS</a></li>";
		}
		if ($_SESSION["permissions"]["admin"]) {
			echo "		<li><a href=\"adminside\">Admin</a></li>";
		}
		print "		</ul>
					<script>
						function sendMayday(){
							var ajax = new XMLHttpRequest();
							ajax.open('POST', '/mayday.php', true);
							ajax.send();
						}
					</script>
					<ul class=\"nav navbar-nav navbar-right\">
						<li><a onclick=\"sendMayday()\" href=\"javascript:void(0)\" style=\"background:red;color:white\"><b>!!! MAYDAY !!!</b></a></li>
					</ul>
				</div>
			</div>
		</nav>";
		if ($admin) {
			if (!$_SESSION["permissions"]["admin"]) {
				die("<script>location.href = 'index.php?error=notadmin'</script>");
			}
			echo "<div class='container-fluid'> <div class='row'> <div class='col-sm-2 well' style='background-color: #f1f1f1;'>
					<h2>Admin Panel</h2>
					<ul class='nav nav-pills nav-stacked'>
					<li><a href='adminside'>Admin Home</a></li>";
					if ($_SESSION["permissions"]["musers"]) {
						echo "<li><a href='usermanage'>User Management</a></li>";
					}
					if ($_SESSION["permissions"]["munits"]) {
						echo "<li><a href='unitmanage'>Unit Management</a></li>";
					}
					if ($_SESSION["permissions"]["mdepts"]) {
						echo "<li><a href='deptmanage'>Department Management</a></li>";
					}
					echo "</ul><br>
			</div>";
		}
		
		echo "<div id='maydayBanner'></div>
		<div id='notifBanner'></div>
		<script>
			if(typeof(EventSource) !== 'undefined') {
				var source = new EventSource('push/dispatch.php');
				source.onmessage = function(event) {
					const audio = new Audio('resources/dispatch.mp3');
					audio.play();
					setTimeout(function(){
						window.location.href = window.location.href;
						window.location.reload();
					}, 9000);
				};
			} else {
				document.write(\"<meta http-equiv='refresh' content='5'>\");
			}
			if(typeof(EventSource) !== 'undefined') {
				var source10 = new EventSource('push/recieveMayday.php');
				source10.onmessage = function(event) {
					document.getElementById('maydayBanner').innerHTML = '<div class=\"alert alert-danger alert-dismissible\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>MAYDAY! MAYDAY! MAYDAY!</strong> A Mayday has been declared on your incident.</div>';
					".$commandmayday."
					new Audio('resources/mayday.mp3').play();
				};
			}
			if(typeof(EventSource) !== 'undefined') {
				var source11 = new EventSource('push/recieveNotification.php');
				source11.onmessage = function(event) {
					var notifi = event.data.split(/\\r?\\n/);
					document.getElementById('notifBanner').innerHTML += '<div class=\"alert alert-info alert-dismissible\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>'+event.data.split(/\\r?\\n/)[1]+'</div>';
					switch (event.data.split(/\\r?\\n/)[0]) {
						case '-1':
							new Audio('resources/cadUpdate.mp3').play();
							break;
						case '0':
							new Audio('resources/emergency.mp3').play();
							break;
						case '1':
							new Audio('resources/alerttone1.mp3').play();
							break;
						case '2':
							new Audio('resources/alerttone3.mp3').play();
							break;
					}
				};
			}
		</script>";
	
	//echo $_SERVER['REQUEST_URI'];
}
?>