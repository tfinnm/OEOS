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
<body  onload="startTime();" id="bodybg" style="background:black;">
	<div class="container">
		<center>
			<?php
			include_once("options.php");
			include("db.php");
			echo "<h1><abbr title='Open Emergency Operations Suite'><b style='color:white;'>O</b><b style='color:red;'>EO</b><b style='color:white;'>S</b></abbr><br><small>".$name."</small></h1>
			<br/><br/>";
			if (isset($_POST["units"])) {
				$sname = "Station Alert Board";
				if (isset($_POST["name"]) &&  ($_POST["name"] != "")) {
					$sname = $_POST["name"];
				}
				echo "
			<div class='well well-lg'>
				<h2>".$sname."</h2>
				<hr>
				<div id='clock' style='clear: both'></div><br>
				<div id='incidents' style='outline:thick solid black;'>No Active Incidents</div>
			</div>
		</center>
	</div>";
			} else {
				$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
				$units = "";
				$sql = "SELECT * FROM units WHERE assignable = '1'";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$dept = "Unkown Department";
						$sql2 = "SELECT * FROM departments WHERE ID = '".$row["deptID"]."'";
						$result2 = $conn->query($sql2);
						if ($result2->num_rows > 0) {
							while($row2 = $result2->fetch_assoc()) {
								$dept = $row2["Name"];
							}
						}
						$units .= "<option value='".$row["ID"]."'><b>".$row["longName"]." | ".$dept."</b></option>";
					}
				}
				$conn->close();
			echo "
			<div class='well well-lg'>
				<h2>Station Board</h2>
				<form class='form-horizontal' method='post'>
					<div class='form-group'>
						<label class='control-label col-sm-2' for='email'>Station Name:</label>
						<div class='col-sm-10'>
							<input type='text' class='form-control' id='usrnm' name='name' placeholder='Enter Station Name'>
						</div>
					</div>
					<div class='form-group'>
						<label class='control-label col-sm-2' for='pwd'>Select Units:</label>
						<div class='col-sm-10'>
							<select class='form-control' name='units[]' id='units' required multiple>
								".$units."
							</select>
						</div>
					</div>
					<div class='form-group'>
						<button type='submit' class='btn btn-default'>Open Station Board</button>
					</div>
				</form>
				<a href='login'>Back to Login</a>
			</div>
		</center>
	</div>";
	}
	?>
</body>
</html>
<script>
	function startTime() {
	  const today = new Date();
	  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
	  let d = today.toLocaleDateString(undefined, options);
	  let h = today.getHours();
	  let m = today.getMinutes();
	  let ap = 'AM';
	  if (h >12) {
		  h -= 12;
		  ap = 'PM';
	  }
	  m = checkTime(m);
	  document.getElementById('clock').innerHTML = "<h1 style='text-align: left;'>" + d + "<span style='float: right;'>" + h + ":" + m + " " + ap + "</span></h1>";
	  setTimeout(startTime, 1000);
	}
	function checkTime(i) {
		if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
		return i;
	}
	function flashScreen() {
		var flash = true;
		var flashes = 0;
		var flasher = setInterval(function(){
			if (flash) 
				document.getElementById('bodybg').style.background = 'red';
			else 
				document.getElementById('bodybg').style.background = 'black';
			flash = !flash;
			flashes++;
			if (flashes > 20) {
				clearInterval(flasher);
				document.getElementById('bodybg').style.background = 'black';
			}
		}, 750);
	}
	var lastrecieved = "";
	if(typeof(EventSource) !== 'undefined') {
		<?php
		 $urlparams = "?this=that";
		 if (isset($_POST["units"])) {
			foreach ($_POST["units"] as $unit) {
				$urlparams.="&unit[]=".$unit;
			}
		 }
		echo "var source = new EventSource('push/stationNewCall.php".$urlparams."');";
		?>
		source.onmessage = function(event) {
			if (event.data != lastrecieved && event.data != "") {
				console.log(event.data);
				lastrecieved = event.data;
				flashScreen();
				const audio = new Audio('resources/dispatch.mp3');
				audio.volume = 1;
				audio.play();
				audio.addEventListener('ended', function() {
					var msg = new SpeechSynthesisUtterance();
					msg.text = event.data;
					msg.rate = 1.5;
					msg.pitch = 0.5;
					window.speechSynthesis.speak(msg);
				}, false);				
			}
		};
	}
	if(typeof(EventSource) !== 'undefined') {
		<?php
		 $urlparams = "?force=true";
		 if (isset($_POST["units"])) {
			foreach ($_POST["units"] as $unit) {
				$urlparams.="&unit[]=".$unit;
			}
		 }
		echo "var source = new EventSource('push/stationCalls.php".$urlparams."');";
		?>
		source.onmessage = function(event) {
			document.getElementById('incidents').innerHTML = event.data;
		};
	}
	if (document.documentElement.requestFullscreen) {
		document.documentElement.requestFullscreen();
	} else if (document.documentElement.webkitRequestFullscreen) { /* Safari */
		document.documentElement.webkitRequestFullscreen();
	} else if (document.documentElement.msRequestFullscreen) { /* IE11 */
		document.documentElement.msRequestFullscreen();
	}
</script>