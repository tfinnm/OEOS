<?php

	session_start();
	if (!isset($_SESSION["loggedin"])) {
		die("<script>location.href = 'login.php?error=notlogged'</script>");
	}
		
	print "
		<meta charset=\"utf-8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css\">
		<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
		<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js\"></script>
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
						<li><a href=\".\">Unit Mgmt</a></li>
						<li><a href=\"#\">Command Board</a></li>
						<li><a href=\"products\">Unit Board</a></li>
						<li><a href=\"news\">Dispatch</a></li>
						<li><a href=\"#\">EMS</a></li>
					</ul>
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
						<li><a href=\"#\" style=\"background:red;color:white\"><b>!!! MAYDAY !!!</b></a></li>
					</ul>
				</div>
			</div>
		</nav>
	";
	
	//echo $_SERVER['REQUEST_URI'];

?>