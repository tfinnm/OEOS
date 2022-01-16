<?php

	include("header.php");
	topbar(true);
	include("db.php");
	include("options.php");
	echo"
		<div class='col-sm-10'>
		<center>
			<h2>OEOS Admin Panel</h2>
		</center>
		<h4>Read Only Settings: (Configured in options.php)</h4>
		<p>Agency/Region Name: ".$name."</p>
		<p>Administrator Name: ".$adminname."</p>
		<p>Administrator Email: ".$adminemail."</p>
		<p>Administrator Phone: ".$adminphone."</p>
		<br>
		<h4>Read Only Settings: (Configured in db.php)</h4>
		<p>SQL Server: ".$db_server."</p>
		<p>SQL Database: ".$db_db."</p>
		<p>SQL Username: ".$db_user."</p>
		<p>SQL Password: ********</p>
		</div>
	</div>
</div>";
?>