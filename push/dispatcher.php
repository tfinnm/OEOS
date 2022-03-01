<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//$myfile = fopen("reload.txt", "r") or die("");
//fclose($myfile);
//unlink("reload.txt");
//echo "data: " . uniqid() . " \n\n";
session_start();
if ($_SESSION["dispatchrIncident"] != $_SESSION["incident"]) {
	echo "data: ".$_SESSION["incident"]." \n\n";
}
flush();
?>