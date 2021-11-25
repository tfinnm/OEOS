<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//$myfile = fopen("reload.txt", "r") or die("");
//fclose($myfile);
//unlink("reload.txt");
//echo "data: " . uniqid() . " \n\n";
session_start();
include("../db.php");
$conn = new mysqli($db_server, $db_user, $db_password, $db_db);
if ($conn->connect_error) {
	// connection failed error goes here
}
$out = "";		
$out .= "<table class='table table-striped table-hover table-condensed'>
    <thead>
      <tr>
        <th>Time Out</th>
        <th>Type</th>
        <th>Address</th>
		<th>Details</th>
		<th>Options</th>
      </tr>
    </thead>
    <tbody>";
$sql = "SELECT * FROM incidents where active = 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$permdis = "";
		$currdis = ">Assign";
		if ($row["ID"] == $_SESSION["incident"]) {
			$currdis = "disabled>Assigned";
		}
		if (!($_SESSION["permissions"]["selfassign"])) {
			$permdis = "disabled ";
		}
		$sql2 = "SELECT * FROM maydays WHERE Incident = '".$row["ID"]."' AND Active = '1'";
		$result2 = $conn->query($sql2);
		$maydaytableindicator = "";
		if ($result2->num_rows > 0) {
			while($row2 = $result2->fetch_assoc()) {
				$maydaytableindicator = "class='danger'";
			}
		}
		$out .= "<tr ".$maydaytableindicator.">
			<td>".explode(" ",$row["timeOut"])[1]."</td>
			<td>".$row["type"]."</td>
			<td>".$row["address"]."</td>
			<td>".substr($row["details"],0,40)."</td>
			<td><a href='?assign=".$row["ID"]."'><button class='btn btn-success' ".$permdis.$currdis."</button></a></td>
		</tr>";
    }
} else {
    $out .= "<tr><td>No Active Incidents</td><td></td><td></td><td></td><td></td></tr>";
}
$out .= "</tbody>
  </table>";

$out = str_replace(array("\n", "\r"), '', $out);

if ($_SESSION["pushHash"]["CallList"] == null or $_SESSION["pushHash"]["CallList"] != crc32($out)) {

	$_SESSION["pushHash"]["CallList"] = crc32($out);
	echo "data: ".$out." \n\n";
	flush();
	
}
$conn->close();
?>