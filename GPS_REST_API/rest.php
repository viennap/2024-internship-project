<?php
//$method = $_SERVER['REQUEST_METHOD'];
//$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
//$input = json_decode(file_get_contents('php://input'),true);

//$servername = "engr-sprinkle01s.catnet.arizona.edu";
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

//mysqli_set_charset($link,'utf8');
//echo $_SERVER['PATH_INFO'];
echo $_SERVER['REQUEST_URI'];
$str_arr = explode (",", $_SERVER['REQUEST_URI']);
// printed out this string to debug Secret Key Compromised
//print_r($str_arr);
$path_count =  count($str_arr);
//echo $path_count;

if ($path_count == "8")
{
	//$secret_key = array_shift($str_arr);
    if (trim($str_arr['0'])!= '/GPS_REST_API/rest.php?circles')
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
		
		echo $VIN;
//		echo "\n";	
		// Create connection
        echo $username;
		$conn = new mysqli($servername, $username, $password, $db);
        //$conn = mysqli_connect($servername, $username, $password, $db);
		// Check connection
  //      if ($dbconnect->connect_error) {
  //       die("Database connection failed: " . $dbconnect->connect_error);
   //     }
		if ($conn->connect_error)
		{
          echo $conn->connect_error;       
		  die("Connection failed: " . $conn->connect_error);
		}
		echo "Connected successfully\n";
		
        $sql = "INSERT INTO GPSDB (VIN, GpsTime, SysTime, Latitude, Longitude, Altitude, Status) VALUES ('$VIN', $GpsTime , $SysTime, $Latitude, $Longitude, $Altitude, '$Status')";
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

<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php echo '<p>Hello World</p>'; ?> 
 </body>
</html>
