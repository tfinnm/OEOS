<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
if (isset($_SESSION["incident"]) && $_SESSION["incident"] != null) {
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
if ($conn->connect_error) {
	// connection failed error goes here
}
$out = "
								<table class='table'>
									<thead>
										<tr>
											<th>Name</th>
											<th>Talkgroup</th>
											<th>Channel</th>
										</tr>
									</thead>
									<tbody>";
										$sql = "SELECT * FROM radiocomms where IncidentID = ".$_SESSION["incident"];
										$result3 = $conn->query($sql);
										if ($result3->num_rows > 0) {
											// output data of each row
											while($row3 = $result3->fetch_assoc()) {
												$out .= "
													<tr>
														<td>".$row3["Name"]."</td>
														<td>".$row3["Talkgroup"]."</td>
														<td>".$row3["Channel"]."</td>
													</tr>
												";
											}
										}
									$out .= "
									</tbody>
								</table>";

$out = str_replace(array("\n", "\r"), '', $out);

if ($_SESSION["pushHash"]["Radio"] == null or $_SESSION["pushHash"]["Radio"] != crc32($out)) {

	$_SESSION["pushHash"]["Radio"] = crc32($out);
	echo "data: ".$out." \n\n";
	flush();
	
}
$conn->close();
}
?>