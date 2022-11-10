<html>
<head>
<title>Vest Tracking (readonly)</title>
</head>
<body>
<center>

<?php
	echo "<h1>Vest and Vehicle quick assignment status (readonly)</h1>";

	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);

	//echo "<br>";
	
	$sql_drivers_get_ready_orange = "select * from VestStatusView where VestStatusKey=3 and RouteKey=1";
	$drivers_get_ready_res_orange = $conn->query($sql_drivers_get_ready_orange);

	$sql_drivers_get_ready_yellow = "select * from VestStatusView where VestStatusKey=3 and RouteKey=2";
        $drivers_get_ready_res_yellow = $conn->query($sql_drivers_get_ready_yellow);

	echo "<h2>Drivers Get Ready Reminder</h2>";
	echo "<h3>Orange Route</h3>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_get_ready_res_orange->num_rows > 0) {
                while($row = $drivers_get_ready_res_orange->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
	echo "</table>";

	echo "<h3>Yellow Route</h3>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_get_ready_res_yellow->num_rows > 0) {
                while($row = $drivers_get_ready_res_yellow->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";


	$sql_drivers_go_downstairs_orange = "select * from VestStatusView where VestStatusKey=4 and RouteKey=1";
	$drivers_go_downstairs_res_orange = $conn->query($sql_drivers_go_downstairs_orange);
	
	$sql_drivers_go_downstairs_yellow = "select * from VestStatusView where VestStatusKey=4 and RouteKey=2";
	$drivers_go_downstairs_res_yellow = $conn->query($sql_drivers_go_downstairs_yellow);

	echo "<h2>Drivers Go Downstairs Reminder</h2>";
	echo "<h3>Orange Route</h3>";
	echo "<table border=1>";
	echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
	echo"<tr>";

	if ($drivers_go_downstairs_res_orange->num_rows > 0) {
    		while($row = $drivers_go_downstairs_res_orange->fetch_assoc()) {
			echo"<tr>";
			echo"<td>".$row[VestKey]."</td>";
			echo"<td>".$row[RouteColor]." </td>";
			echo"<td>".$row[TeamName]." </td>";
			echo"<td>".$row[DriverFirstName]." </td>";
			echo"<td>".$row[DriverLastName]." </td>";
			echo"<td>".$row[VestStatusString]."</td>";
			echo"<td>".$row[RouteKey]." </td>";
			echo"<td>".$row[DriverKey]." </td>";
			echo"<td>".$row[VestStatusKey]." </td>";
			echo"<td>".$row[Modified]." </td>";
			echo"</tr>";

		
		}
	}
	echo "</table>";

	echo "<h3>Yellow Route</h3>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_go_downstairs_res_yellow->num_rows > 0) {
                while($row = $drivers_go_downstairs_res_yellow->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";

                
                }
        }
        echo "</table>";

	$sql_drivers_go_upstairs_orange = "select * from VestStatusView where VestStatusKey=10 and RouteKey=1";
        $drivers_go_upstairs_res_orange = $conn->query($sql_drivers_go_upstairs_orange);

        $sql_drivers_go_upstairs_yellow = "select * from VestStatusView where VestStatusKey=10 and RouteKey=2";
        $drivers_go_upstairs_res_yellow = $conn->query($sql_drivers_go_upstairs_yellow);

        echo "<h2>Drivers Finished Driving and Go Upstairs Reminder</h2>";
        echo "<h3>Orange Route</h3>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_go_upstairs_res_orange->num_rows > 0) {
                while($row = $drivers_go_upstairs_res_orange->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
	echo "</table>";

	echo "<h3>Yellow Route</h3>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_go_upstairs_res_yellow->num_rows > 0) {
                while($row = $drivers_go_upstairs_res_yellow->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";
        
        
                }
        }
        echo "</table>";

	
	$sql_drivers_assigned = "select * from VestStatusView where VestStatusKey=1 order by RouteKey";
        $drivers_assigned_res = $conn->query($sql_drivers_assigned);

        echo "<h2>Drivers Assigned and Stay Upstairs freely</h2>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_assigned_res->num_rows > 0) {
                while($row = $drivers_assigned_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                } 
        }
        echo "</table>";


        $sql_assign_car_res = "select * from CarStatusView order by RouteKey";
        $assign_car_res = $conn->query($sql_assign_car_res);
	//echo "<br>";
	echo "<p></p>";
	echo "<h2>Cars that are Currently Assigned</h2>";
        echo "<table border=1>";
        echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($assign_car_res->num_rows > 0) {
                while($row = $assign_car_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[CarKey]."</td>";
                        echo"<td>".$row[CarStatusString]." </td>";
                        echo"<td>".$row[VestKey]." </td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[CarStatusKey]."</td>";
                        echo"<td>".$row[IsActive]." </td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";

     
                }
        }
	echo "</table>";

?>

</center>
</body>
</html>
