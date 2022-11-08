<html>
<head>
<title>Vest Tracking---Assign/Unassign Vests and Cars</title>
</head>
<body>
<center>

<form action='' method='post'>
VestKey: <input type='text' name='VestKey'>
CarKey: <input type='text' name='CarKey'>
<input type='submit' value='Assign a vest to a car' name='assign' class="button">
<input type='submit' value='Unassign a vest from a car' name='unassign' class="button">
</form>

<?php

        // get shared config information outside of this file
        $portalconfig = include_once ('../../config.php');
        // set connection based on outside information
        $conn = new mysqli($portalconfig['hostname'],
                $portalconfig['username'],
                $portalconfig['password'],
                $portalconfig['database']);



echo "<br>";
print_r("VestKey:");
echo $_POST['VestKey'];
echo "<br>";
print_r("CarKey:");
echo $_POST['CarKey'];
echo "<br>";
if(array_key_exists('assign', $_POST)) {
            assign();
        }
        else if(array_key_exists('unassign', $_POST)) {
            unassign();
	}

function assign() {
	echo "This is assign that is selected";
	$VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $CarKey = empty($_POST['CarKey'])?die("Please input the CarKey"):$_POST['CarKey'];	
	$a_sql_1 = "UPDATE Cars as c
		SET c.VestKey = $VestKey
		WHERE c.CarKey = $CarKey";
	$conn->query($a_sql_1);
	$a_sql_2 = "INSERT INTO CarStatus (CarKey, VestKey, CarStatusKey)
		VALUES ($CarKey, $VestKey, ( select CarStatusTypes.CarStatusKey from CarStatusTypes where CarStatusTypes.CarStatusShort like 'Driving') )";
	$conn->query($a_sql_2);
	$a_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
		VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Driving') )";
	$conn->query($a_sql_3);
	echo "<br>";
	echo "assign success!";
	echo "<br>";
	echo "<br>";
	echo "<h3>Show VestStatusView</h3>";
	$sql_assign_res = "select * from VestStatusView where VestKey = $VestKey";
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

function unassign() {
	echo "This is unassign that is selected";
	$VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
	$CarKey = empty($_POST['CarKey'])?die("Please input the CarKey"):$_POST['CarKey'];
	$una_sql_1 = "UPDATE Cars as c
		SET c.VestKey = NULL
		WHERE c.CarKey = $CarKey
		AND c.VestKey = $VestKey";
	$conn->query($una_sql_1); 
	$una_sql_2 = "INSERT INTO CarStatus (CarKey, VestKey, CarStatusKey)
		VALUES ($CarKey, NULL, ( select CarStatusTypes.CarStatusKey from CarStatusTypes where CarStatusTypes.CarStatusShort like 'At Fence') )";
	$conn->query($una_sql_2);
	$una_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
		VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Done Driving') )";
	$conn->query($una_sql_3);
	echo "<br>";
	echo "unassign success!";
	echo "<br>";
	echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_unassign_res = "select * from VestStatusView where VestKey = $VestKey";
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
