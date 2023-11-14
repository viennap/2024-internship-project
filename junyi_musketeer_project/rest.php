<?php
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "junyi_musketeer_team";

echo $_SERVER['REQUEST_URI'];
$str_arr = explode (",", $_SERVER['REQUEST_URI']);
$path_count =  count($str_arr);

if ($path_count == "11")
{
    if (trim($str_arr['0'])!= '/junyi_musketeer_project/rest.php?circles')
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
                $Status = trim($str_arr['6']);
                $CANSpeed = trim($str_arr['7']);
                $CANAcceleration = trim($str_arr['8']);
                $ACCStatus = trim($str_arr['9']);
                $ACCSpeedSetting = trim($str_arr['10']);
                echo $VIN;
                echo $username;
                $conn = new mysqli($servername, $username, $password, $db);
                if ($conn->connect_error)
                {
                        echo $conn->connect_error;
                        die("Connection failed: " . $conn->connect_error);
                }
                echo "Connected successfully\n";

                $stmt = $conn->prepare("INSERT INTO JUNYI_FACT_VEHICLE_PING (vin, gpstime, systime, latitude, longitude, status, velocity, acceleration, acc_status, acc_speed_setting) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
								$stmt->bind_param("siiddsddii", $VIN, $GpsTime , $SysTime, $Latitude, $Longitude, $Status, $CANSpeed, $CANAcceleration, $ACCStatus, $ACCSpeedSetting);

                if ($stmt->execute() === TRUE)
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
