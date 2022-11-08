<?php
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

echo $_SERVER['REQUEST_URI'];
$str_arr = explode (",", $_SERVER['REQUEST_URI']);
$path_count =  count($str_arr);

if ($path_count == "19")
{
    if (trim($str_arr['0'])!= '/FACT_VEHICLE_PING/rest.php?circles')
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
                $Position = trim($str_arr['7']);
                $CANSpeed = trim($str_arr['8']);
                $CANAcceleration = trim($str_arr['9']);
                $CANRelativeSpeed = trim($str_arr['10']);
                $CANRelativeDistance = trim($str_arr['11']);
                $CANLeftRelVel = trim($str_arr['12']);
                $CANLeftYaw = trim($str_arr['13']);
                $CANRightRelVel = trim($str_arr['14']);
                $CANRightYaw = trim($str_arr['15']);
                $ACCSpeedSetting = trim($str_arr['16']);
                $ACCStatus = trim($str_arr['17']);
                $IsWB = trim($str_arr['18']);
                echo $VIN;
                echo $username;
                $conn = new mysqli($servername, $username, $password, $db);
                if ($conn->connect_error)
                {
                        echo $conn->connect_error;
                        die("Connection failed: " . $conn->connect_error);
                }
                echo "Connected successfully\n";

                $sql = "INSERT INTO FACT_VEHICLE_PING (vin, gpstime, systime, latitude, longitude, status, position, velocity, acceleration, relative_leadervel, relative_distance, left_relvel, left_yaw, right_relvel, right_yaw, acc_speed_setting, acc_status, is_wb) VALUES ('$VIN', $GpsTime , $SysTime, $Latitude, $Longitude, '$Status', $Position, $CANSpeed, $CANAcceleration, $CANRelativeSpeed, $CANRelativeDistance, $CANLeftRelVel, $CANLeftYaw, $CANRightRelVel, $CANRightYaw, $ACCSpeedSetting, $ACCStatus, $IsWB)";
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
