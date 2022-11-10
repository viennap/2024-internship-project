<html>
<head>
<title>Vest Tracking Test</title>
</head>
<body>
<center>

<form action='' method='post'>
<h2>Assign/Unassign vest and car</h2>
VestKey(*): <input type='text' name='VestKeyC'>
CarKey(*): <input type='text' name='CarKey'>
<input type='submit' value='Check current CarStatusView' name='checkCarStatusView' class="button">
<input type='submit' value='Assign a vest to a car' name='assignVestCar' class="button">
<input type='submit' value='Unassign a vest from a car' name='unassignVestCar' class="button">
</form>


<?php
echo "<br>";
echo "<a href=http://ransom.isis.vanderbilt.edu/vest_tracking_home.php> go home </a>";
echo "<br>";
if(array_key_exists('checkCarStatusView', $_POST)) {
                checkCarStatusView();
        }
	else if(array_key_exists('assignVestCar', $_POST)) {
                assignVestCar();
        }
        else if(array_key_exists('unassignVestCar', $_POST)) {
                unassignVestCar();
        }

function checkCarStatusView() {
        echo "This is check current CarStatusView that is selected";
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
	echo "<h3>Show CarStatusView</h3>";
        $sql_check_car_view_res = "select * from CarStatusView";
        $check_car_view_res = $conn->query($sql_check_car_view_res);
        echo "<table border=1>";
        echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($check_car_view_res->num_rows > 0) {
                while($row = $check_car_view_res->fetch_assoc()) {
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

}

function assignVestCar() {
	echo "This is assign a vest to a car that is selected";
	$VestKeyC = empty($_POST['VestKeyC'])?die("Please input the VestKey"):$_POST['VestKeyC'];
        $CarKey = empty($_POST['CarKey'])?die("Please input the CarKey"):$_POST['CarKey'];
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
	$a_sql_1 = "UPDATE Cars as c
		SET c.VestKey = $VestKeyC
		WHERE c.CarKey = $CarKey";
	$conn->query($a_sql_1);
	$a_sql_2 = "INSERT INTO CarStatus (CarKey, VestKey, CarStatusKey)
		VALUES ($CarKey, $VestKeyC, ( select CarStatusTypes.CarStatusKey from CarStatusTypes where CarStatusTypes.CarStatusShort like 'Driving') )";
	$conn->query($a_sql_2);
	$a_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
		VALUES ($VestKeyC, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Driving') )";
	$conn->query($a_sql_3);
	echo "<br>";
	echo "assign a vest to a car success!";
	echo "<br>";
	echo "<br>";
	echo "<h3>Show VestStatusView</h3>";
	$sql_assign_res = "select * from VestStatusView where VestKey = $VestKeyC";
	$assign_res = $conn->query($sql_assign_res);
	echo "<table border=1>";
	echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
	echo"<tr>";

	if ($assign_res->num_rows > 0) {
    		while($row = $assign_res->fetch_assoc()) {
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
	echo "<h3>Show CarStatusView</h3>";
        $sql_assign_car_res = "select * from CarStatusView where CarKey = $CarKey";
        $assign_car_res = $conn->query($sql_assign_car_res);
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
}

function unassignVestCar() {
	echo "This is unassign a vest from a car that is selected";
	$VestKeyC = empty($_POST['VestKeyC'])?die("Please input the VestKey"):$_POST['VestKeyC'];
	$CarKey = empty($_POST['CarKey'])?die("Please input the CarKey"):$_POST['CarKey'];
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
	$una_sql_1 = "UPDATE Cars as c
		SET c.VestKey = NULL
		WHERE c.CarKey = $CarKey
		AND c.VestKey = $VestKeyC";
	$conn->query($una_sql_1);
	$una_sql_2 = "INSERT INTO CarStatus (CarKey, VestKey, CarStatusKey)
		VALUES ($CarKey, NULL, ( select CarStatusTypes.CarStatusKey from CarStatusTypes where CarStatusTypes.CarStatusShort like 'At Fence') )";
	$conn->query($una_sql_2);
	//$una_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
	//	VALUES ($VestKeyC, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Done Driving') )";
	//$conn->query($una_sql_3);
	$una_sql_4 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                VALUES ($VestKeyC, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Go Inside') )";
        $conn->query($una_sql_4);

	echo "<br>";
	echo "unassign a vest from a car success!";
	echo "<br>";
	echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_unassign_res = "select * from VestStatusView where VestKey = $VestKeyC";
        $unassign_res = $conn->query($sql_unassign_res);

        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($unassign_res->num_rows > 0) {
                while($row = $unassign_res->fetch_assoc()) {
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
        echo "<h3>Show CarStatusView</h3>";
        $sql_unassign_car_res = "select * from CarStatusView where CarKey = $CarKey";
        $unassign_car_res = $conn->query($sql_unassign_car_res);
        echo "<table border=1>";
        echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($unassign_car_res->num_rows > 0) {
                while($row = $unassign_car_res->fetch_assoc()) {
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
}


?>

</center>
</body>
</html>
