<!DOCTYPE html>
<html>
<?php
	include("header.php");
	bootlibs();
?>
<body style="background:black;">
	<center>
			<?php
			include_once("options.php");
			
			$ecode = http_response_code();
			$eex = "";
			$etrouble = "";
			if ($ecode == "404") {
				$eex = ": Page Not Found";
				$etrouble = "<h3 style='color:grey;'>Double Check Your Spelling, or Go Back</h3>";
			}
			if ($ecode == "200") {
				$eex = ": Everything Is Fine";
				$etrouble = "<h3 style='color:grey;'>Why Are You Here?</h3>";
			}
			echo "<h1><abbr title='Open Emergency Operations Suite'><b style='color:white;'>O</b><b style='color:red;'>EO</b><b style='color:white;'>S</b></abbr><br><small>".$name."</small></h1>
			<br/>
			<br/>
			<br/>
			<div>			
				<h1 style='color:grey;'>Error ".$ecode.$eex."</h1>
				".$etrouble."
				<br><br>
				<p style='color:grey;'>Your Administrator's Contact:</p>
				<p style='color:grey;'>Name: ".$adminname."</p>
				<p style='color:grey;'>Email: ".$adminemail."</p>
				<p style='color:grey;'>Phone: ".$adminphone."</p>
			</div>
		</center>
</body>
</html>";