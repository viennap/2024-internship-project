<?php
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


if (array_key_exists("PASS", $_GET) && array_key_exists("DATA", $_GET))
{
  $PASS=$_GET["PASS"];
  $DATA=json_decode($_GET["DATA"], True);
  if ($PASS != "circles")
	{
    echo $PASS;
    echo 'Secret Key Compromised\n';
	}
	else
	{
    echo 'Secret Key Okay';

    $conn = new mysqli($servername, $username, $password, $db);
		if ($conn->connect_error)
		{
      echo $conn->connect_error;
      die("Connection failed: " . $conn->connect_error);
		}
		echo "Connected successfully\n";

    $sql = "INSERT INTO piStatus (vin, wlan0_mac, SSID, wlan0_upload_rate_kb, wlan0_download_rate_kb, wlan0_ip, eth0_ip, update_time, server_time, battery_voltage, external_power, free_ram, available_ram, total_ram, total_memory_available, total_memory, total_memory_used_percent, cpu_load_1min, cpu_load_5min, cpu_load_15min) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $statement = $conn->prepare($sql);
    $server_time = time();
    $statement->bind_param('sssddssdddisssssdddd', $DATA["vin"], $DATA["wlan0_mac"], $DATA["ssid"], $DATA["wlan0_upload_rate_kb"], $DATA["wlan0_download_rate_kb"], $DATA["wlan0_ip"], $DATA["eth0_ip"], $DATA["update_time"], $server_time, $DATA["battery_voltage"], $DATA["external_power"], $DATA["free_ram"], $DATA["available_ram"], $DATA["total_ram"], $DATA["total_memory_available"], $DATA["total_memory"], $DATA["total_memory_used_percent"], $DATA["cpu_load_1min"], $DATA["cpu_load_5min"], $DATA["cpu_load_15min"]);

		if ($statement->execute() === TRUE)
		{
			echo "\nNew record created successfully\n";
		}
		else
		{
			echo "Error: " . $sql . "<br>" . $conn->error;
			echo "\n";
		}

		$conn->close();
	}
}
else {
  echo "Certain arguments not found!";
  echo var_dump($_GET);
  echo $_GET["PASS"];
  echo $_GET["DATA"];
}
?>
