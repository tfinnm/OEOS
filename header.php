<?php

	session_start();
	include_once("db.php");
	if (!isset($_SESSION["loggedin"])) {
		die("<script>location.href = 'login.php?error=notlogged'</script>");
	}
	include("permissions.php");
	if (!isset($_SESSION["permissions"])) {
		getPermissions();
	}
		
	$command = false;
	$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
	$sql = "SELECT * FROM incidents where ID = ".$_SESSION["incident"];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if ($row["commandUnitID"] == $_SESSION["UnitID"]) {
				$command = true;
			}
		}
	}
	$conn->close();
		
	print "
		<meta charset=\"utf-8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css\">
		<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
		<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js\"></script>
		<script src=\"libraries/dialogs/js/bootstrap-dialog.min.js\"></script>
		<link rel=\"stylesheet\" href=\"libraries/dialogs/css/bootstrap-dialog.min.css\">
	";
	
	print "
		<nav class=\"navbar navbar-inverse\">
			<div class=\"container-fluid\">
				<div class=\"navbar-header\">
					<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#myNavbar\">
						<span class=\"icon-bar\"></span>
						<span class=\"icon-bar\"></span>
						<span class=\"icon-bar\"></span>                        
					</button>
					<a class=\"navbar-brand\" href=\".\"><abbr title=\"Open Emergency Operations Suite\"><b style=\"color:white;\">O</b><b style=\"color:red;\">EO</b><b style=\"color:white;\">S</b></abbr> <small>Canton County</small></a>
				</div>
				<div class=\"collapse navbar-collapse\" id=\"myNavbar\">
					<ul class=\"nav navbar-nav\">
						<li><a href=\".\">Home</a></li>
		";
		if ($_SESSION["permissions"]["assign"]) {
			echo "		<li><a href=\"dispatch\">Dispatch Board</a></li>";
		}
		$commandmayday = "";
		if ($command) {
			echo "		<li><a href=\"unitboard\">Unit Board</a></li>";
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
		print "		</ul>
					<!-- <form class=\"navbar-form navbar-right\" role=\"search\">
						<div class=\"form-group input-group\">
							<input type=\"text\" class=\"form-control\" placeholder=\"Search..\">
							<span class=\"input-group-btn\">
								<button class=\"btn btn-default\" type=\"button\">
									<span class=\"glyphicon glyphicon-search\"></span>
								</button>
							</span>        
						</div>
					</form> -->
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
		</nav>
		<div id='maydayBanner'></div>
		<div id='notifBanner'></div>
		<script>
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
					document.getElementById('notifBanner').innerHTML += '<div class=\"alert alert-info alert-dismissible\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>'+event.data+'</div>';
					switch (event.data.split(/\\r?\\n/)[0]) {
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

?>