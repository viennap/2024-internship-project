<?php
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

echo $_SERVER['REQUEST_URI'];
$str_arr = explode (",", $_SERVER['REQUEST_URI']);
$path_count =  count($str_arr);

if ($path_count == "13")
{
    if (trim($str_arr['0'])!= '/LIVE_VIEWER_SITE/rest.php?circles')
	{
        echo trim($str_arr['0']);
		echo 'Secret Key Compromised\n';
	}
	else
	{
		echo 'Secret Key Okay';

		$VIN = trim($str_arr['1']);
		$GpsTime = trim($str_arr['2']);
		$SysTime = trim($str_arr['3']);
		$Latitude = trim($str_arr['4']);
		$Longitude = trim($str_arr['5']);
		$Altitude = trim($str_arr['6']);
		$Status = trim($str_arr['7']);
		$CANSpeed = trim($str_arr['8']);
		$CANAcceleration = trim($str_arr['9']);
		$CANRelativeSpeed = trim($str_arr['10']);
		$CANRelativeDistance = trim($str_arr['11']);
		$ACCStatus = trim($str_arr['12']);
		echo $VIN;
        	echo $username;
		$conn = new mysqli($servername, $username, $password, $db);
		if ($conn->connect_error)
		{
          		echo $conn->connect_error;
			die("Connection failed: " . $conn->connect_error);
		}
		echo "Connected successfully\n";

        	$sql = "INSERT INTO LIVE_VIEWER_VEHICLE_DATA (VIN, GpsTime, SysTime, Latitude, Longitude, Altitude, Status, CANSpeed, CANAcceleration, CANRelativeSpeed, CANRelativeDistance, ACCStatus) VALUES ('$VIN', $GpsTime , $SysTime, $Latitude, $Longitude, $Altitude, '$Status', $CANSpeed, $CANAcceleration, $CANRelativeSpeed, $CANRelativeDistance, $ACCStatus)";
		echo $sql;

		if ($conn->query($sql) === TRUE)
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
?>
