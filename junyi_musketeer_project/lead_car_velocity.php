<?php

$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "junyi_musketeer_team";

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error)
{
				echo $conn->connect_error;
				die("Connection failed: " . $conn->connect_error);
}

function getLeadVin() {
	return "4";
}

echo getLeadVin();

?>
