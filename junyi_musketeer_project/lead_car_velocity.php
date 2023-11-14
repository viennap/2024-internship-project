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

function getLeadVin($conn) {
	$sql = "select * from JUNYI_CONFIG where name = 'lead_vin'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row["value"];
	}
	return "NONE";
}

echo getLeadVin($conn);

?>
