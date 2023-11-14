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

function getLeadVelocity($conn) {
	$vin = getLeadVin($conn);
	$stmt = $conn->prepare("select * from JUNYI_FACT_VEHICLE_PING WHERE vin=? order by systime desc limit 1");
	$stmt->bind_param("s", $vin);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return json_encode($row);
	}
	return '{}';
}

$result = getLeadVelocity($conn);
echo $result;

?>
