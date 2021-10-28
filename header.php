<?php

	session_start();
	if (!isset($_SESSION["loggedin"])) {
		die("<script>location.href = 'login.php?error=notlogged'</script>");
	}
	include("permissions.php");
	if (!isset($_SESSION["permissions"])) {
		getPermissions();
	}
		
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
			echo "		<li><a href=\"unitboard\">Dispatch Board</a></li>";
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
					<ul class=\"nav navbar-nav navbar-right\">
						<li><a onclick=\"sendMayday()\" href=\"javascript:void(0)\" style=\"background:red;color:white\"><b>!!! MAYDAY !!!</b></a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div id='maydayBanner'></div>
		<script>
			function sendMayday(){
				var ajax = new XMLHttpRequest();
				ajax.open('POST', 'mayday', true);
				ajax.send();
			}
		</script>
		<script>
			if(typeof(EventSource) !== 'undefined') {
				var source = new EventSource('recieveMayday.php');
				source.onmessage = function(event) {
					document.getElementById('maydayBanner').innerHTML = '<div class=\"alert alert-danger alert-dismissible\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>MAYDAY! MAYDAY! MAYDAY!</strong> A Mayday has been declared on your incident.</div>';
					BootstrapDialog.show({
						type: BootstrapDialog.TYPE_DANGER,
						title: 'MAYDAY!',
						message: 'Mayday Declared on your incident, please acknowledge!',
						buttons: [{
							label: 'Acknowledge',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});
					new Audio('resources/mayday.mp3').play();
				};
			}
		</script>";
	
	//echo $_SERVER['REQUEST_URI'];

?>