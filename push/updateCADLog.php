<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();
include("../db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
if ($conn->connect_error) {
	// connection failed error goes here
}
$out = "
								<table class='table'>
									<thead>
										<tr>
											<th>Incident Log</th>
										</tr>
									</thead>
									<tbody>";
										$sql = "SELECT * FROM Events where Incident = ".$_SESSION["incident"]." ORDER BY ID  DESC";
										$result3 = $conn->query($sql);
										if ($result3->num_rows > 0) {
											// output data of each row
											while($row3 = $result3->fetch_assoc()) {
												$out .= "
													<tr>
														<td>".explode(" ",$row3["time"])[1]."  |  ".$row3["Event"]."</td>
													</tr>
												";
											}
										}
									$out .= "
									</tbody>
								</table>";

$out = str_replace(array("\n", "\r"), '', $out);
echo "data: ".$out." \n\n";

if ($_SESSION["pushHash"]["CallLog"] == null or $_SESSION["pushHash"]["CallLog"] != crc32($out)) {

	$_SESSION["pushHash"]["CallLog"] = crc32($out);
	flush();
	
}
$conn->close();
?>