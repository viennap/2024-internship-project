<html>
<head>
<title>Vest Tracking_Drivers Assign</title>
</head>
<body>
<center>


<form action='' method='post'>
<h2>Initialize a driver</h2>
Firstname(*): <input type='text' name='Firstname'>
Middlename: <input type='text' name='Middlename'>
Lastname(*): <input type='text' name='Lastname'>
Suffix: <input type='text' name='Suffix'>
PhoneNumber: <input type='text' name='PhoneNumber'>
Email(*): <input type='text' name='Email'>
DriverKey(*): <input type='text' name='DriverKey'>
<input type='submit' value='Check current drivers' name='checkDrivers' class="button">
<input type='submit' value='Initialize a driver' name='initialDriver' class="button">
<input type='submit' value='Delete a driver(only need to input DriverKey)' name='deleteDriver' class="button">
<input type='submit' value='Driver check-in' name='DriverCheckIn' class="button">
<input type='submit' value='Driver check-out' name='DriverCheckOut' class="button">

<h2>Assign/Unassign driver and route</h2>
DriverKey(*): <input type='text' name='DriverKeyR'>
RouteKey(*): <input type='text' name='RouteKey'>
<input type='submit' value='Check current drivers route training' name='checkTraining' class="button">
<input type='submit' value='Assign driver to a trained route' name='assignRouteDriver' class="button">
<input type='submit' value='Unassign driver from route' name='unassignRouteDriver' class="button">

<h2>Assign/Unassign driver and vest</h2>
DriverKey(*): <input type='text' name='DriverKeyV'>
VestKey(*): <input type='text' name='VestKey'>
TeamName(input only when initial a vest): <input type='text' name='TeamName'>
<input type='submit' value='Check current available vests' name='checkVests' class="button">
<input type='submit' value='Initialize a vest' name='initialVest' class="button">
<input type='submit' value='Delete a vest(only need to input VestKey)' name='deleteVest' class="button">
<input type='submit' value='Assign a vest to a driver' name='assignVestDriver' class="button">
<input type='submit' value='Unassign vest from driver' name='unassignVestDriver' class="button">

<h2>Change driver status</h2>
DriverKey(*): <input type='text' name='DriverKeyS'>
<input type='submit' value='Check current VestStatusView' name='checkVestStatusView' class="button">
<input type='submit' value='Get driver ready' name='driverReady' class="button">
<input type='submit' value='Get driver downstairs' name='driverDownstairs' class="button">

<h2>Assign/Unassign vest and car</h2>
VestKey(*): <input type='text' name='VestKeyC'>
CarKey(*): <input type='text' name='CarKey'>
<input type='submit' value='Check current CarStatusView' name='checkCarStatusView' class="button">
<input type='submit' value='Assign a vest to a car' name='assignVestCar' class="button">
<input type='submit' value='Unassign a vest from a car' name='unassignVestCar' class="button">

</form>


<?php


if(array_key_exists('checkDrivers', $_POST)) {
        	checkDrivers();
}
	else if(array_key_exists('initialDriver', $_POST)) {
                initialDriver();
        }
        else if(array_key_exists('deleteDriver', $_POST)) {
		deleteDriver();
	}
	else if(array_key_exists('DriverCheckIn', $_POST)) {
                DriverCheckIn();
        }
        else if(array_key_exists('DriverCheckOut', $_POST)) {
                DriverCheckOut();
        }
	else if(array_key_exists('checkVests', $_POST)) {
                checkVests();
	}
        else if(array_key_exists('initialVest', $_POST)) {
                initialVest();
        }
        else if(array_key_exists('deleteVest', $_POST)) {
                deleteVest();
        }
        else if(array_key_exists('assignVestDriver', $_POST)) {
		assignVestDriver();
	}
	else if(array_key_exists('unassignVestDriver', $_POST)) {
		unassignVestDriver();
	}
  	else if(array_key_exists('checkTraining', $_POST)) {
                checkTraining();
        }
        else if(array_key_exists('assignRouteDriver', $_POST)) {
		assignRouteDriver();
	}
	else if(array_key_exists('unassignRouteDriver', $_POST)) {
                unassignRouteDriver();
	}
	else if(array_key_exists('checkVestStatusView', $_POST)) {
                checkVestStatusView();
        }
        else if(array_key_exists('driverReady', $_POST)) {
                driverReady();
        }
        else if(array_key_exists('driverDownstairs', $_POST)) {
                driverDownstairs();
        }
        else if(array_key_exists('checkCarStatusView', $_POST)) {
                checkCarStatusView();
        }
	else if(array_key_exists('assignVestCar', $_POST)) {
                assignVestCar();
        }
        else if(array_key_exists('unassignVestCar', $_POST)) {
                unassignVestCar();
        }


function checkDrivers() {
        echo "This is Check current drivers that is selected";
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
	$check_d_sql ="select * from Drivers";
	$check_d_res = $conn->query($check_d_sql);
	echo "<br>";
	echo "<h3>Show current Drivers</h3>";
        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>Firstname</td><td>Middlename</td><td>Lastname</td><td>Suffix</td><td>PhoneNumer</td><td>Email</td><td>VestKey</td><td>IsActive</td><td>Modified</td></tr>";
        echo"<tr>";
        if ($check_d_res->num_rows > 0) {
                while($row = $check_d_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[Firstname]." </td>";
                        echo"<td>".$row[Middlename]." </td>";
                        echo"<td>".$row[Lastname]." </td>";
                        echo"<td>".$row[Suffix]." </td>";
                        echo"<td>".$row[PhoneNumer]."</td>";
                        echo"<td>".$row[Email]." </td>";
                        echo"<td>".$row[VestKey]." </td>";
                        echo"<td>".$row[IsActive]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";
}

function initialDriver() {
        echo "This is Initialize a driver that is selected";
        $Firstname = empty($_POST['Firstname'])?die("Please input the Firstname"):$_POST['Firstname'];
        $Middlename = empty($_POST['Middlename'])?NULL:$_POST['Middlename'];
        $Lastname = empty($_POST['Lastname'])?die("Please input the Lastname"):$_POST['Lastname'];
        $Suffix = empty($_POST['Suffix'])?NULL:$_POST['Suffix'];
        $PhoneNumber = empty($_POST['PhoneNumber'])?NULL:$_POST['PhoneNumber'];
        $Email = empty($_POST['Email'])?die("Please input the Email"):$_POST['Email'];
        $DriverKey = empty($_POST['DriverKey'])?die("Please input the DriverKey"):$_POST['DriverKey'];
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
        $ini_d_sql =  "INSERT IGNORE INTO Drivers (Firstname, Middlename, Lastname, Suffix, PhoneNumber, Email, DriverKey )
			VALUES ('$Firstname', '$Middlename', '$Lastname', '$Suffix', '$PhoneNumber', '$Email', $DriverKey)";
	$conn->query($ini_d_sql);
        echo "<br>";
	echo "Driver initialization success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show Initialized Driver Information</h3>";
        $sql_ini_d_res = "select * from Drivers order by Modified DESC limit 1";
        $ini_d_res = $conn->query($sql_ini_d_res);
        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>Firstname</td><td>Middlename</td><td>Lastname</td><td>Suffix</td><td>PhoneNumer</td><td>Email</td><td>VestKey</td><td>IsActive</td><td>Modified</td></tr>";
        echo"<tr>";
	if ($ini_d_res->num_rows > 0) {
                while($row = $ini_d_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[Firstname]." </td>";
                        echo"<td>".$row[Middlename]." </td>";
                        echo"<td>".$row[Lastname]." </td>";
                        echo"<td>".$row[Suffix]." </td>";
                        echo"<td>".$row[PhoneNumer]."</td>";
                        echo"<td>".$row[Email]." </td>";
                        echo"<td>".$row[VestKey]." </td>";
                        echo"<td>".$row[IsActive]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";
}

function deleteDriver() {
	echo "This is Delete a driver that is selected";
	$DriverKey = empty($_POST['DriverKey'])?die("Please input the DriverKey"):$_POST['DriverKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $delete_sql =  "DELETE from Drivers where DriverKey = $DriverKey";
        $conn->query($delete_sql);
        echo "<br>";
        echo "Delete driver success!";
}

function DriverCheckIn() {
        echo "This is driver check-in that is selected";
        $DriverKey = empty($_POST['DriverKey'])?die("Please input the DriverKey"):$_POST['DriverKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $check_in_sql =  "UPDATE Drivers SET IsActive = 1 where DriverKey = $DriverKey";
        $conn->query($check_in_sql);
        echo "<br>";
	echo "Driver check-in success!";
	echo "<br>";
        echo "<br>";
        echo "<h3>Show Driver Check-in Information</h3>";
        $sql_d_check_in_res = "select * from Drivers order by Modified DESC limit 1";
        $d_check_in_res = $conn->query($sql_d_check_in_res);
        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>Firstname</td><td>Middlename</td><td>Lastname</td><td>Suffix</td><td>PhoneNumer</td><td>Email</td><td>VestKey</td><td>IsActive</td><td>Modified</td></tr>";
        echo"<tr>";
        if ($d_check_in_res->num_rows > 0) {
                while($row = $d_check_in_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[Firstname]." </td>";
                        echo"<td>".$row[Middlename]." </td>";
                        echo"<td>".$row[Lastname]." </td>";
                        echo"<td>".$row[Suffix]." </td>";
                        echo"<td>".$row[PhoneNumer]."</td>";
                        echo"<td>".$row[Email]." </td>";
                        echo"<td>".$row[VestKey]." </td>";
                        echo"<td>".$row[IsActive]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";
}

function DriverCheckOut() {
        echo "This is driver check-out that is selected";
        $DriverKey = empty($_POST['DriverKey'])?die("Please input the DriverKey"):$_POST['DriverKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $check_out_sql =  "UPDATE Drivers SET IsActive = 0 where DriverKey = $DriverKey";
        $conn->query($check_out_sql);
        echo "<br>";
        echo "Driver check-out success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show Driver Check-out Information</h3>";
        $sql_d_check_out_res = "select * from Drivers order by Modified DESC limit 1";
        $d_check_out_res = $conn->query($sql_d_check_out_res);
        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>Firstname</td><td>Middlename</td><td>Lastname</td><td>Suffix</td><td>PhoneNumer</td><td>Email</td><td>VestKey</td><td>IsActive</td><td>Modified</td></tr>";
        echo"<tr>";
        if ($d_check_out_res->num_rows > 0) {
                while($row = $d_check_out_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[Firstname]." </td>";
                        echo"<td>".$row[Middlename]." </td>";
                        echo"<td>".$row[Lastname]." </td>";
                        echo"<td>".$row[Suffix]." </td>";
                        echo"<td>".$row[PhoneNumer]."</td>";
                        echo"<td>".$row[Email]." </td>";
                        echo"<td>".$row[VestKey]." </td>";
                        echo"<td>".$row[IsActive]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";
}


function checkVests() {
        echo "This is Check current availabe Vests that is selected";
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $check_v_sql ="select * from Vests where DriverKey is NULL";
        $check_v_res = $conn->query($check_v_sql);
        echo "<br>";
        echo "<h3>Show current available Vests</h3>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>DriverKey</td><td>RouteKey</td><td>TeamName</td><td>Modified</td></tr>";
        echo"<tr>";
        if ($check_v_res->num_rows > 0) {
                while($row = $check_v_res->fetch_assoc()) {
			echo"<tr>";
			echo"<td>".$row[VestKey]."</td>";
			echo"<td>".$row[DriverKey]."</td>";
			echo"<td>".$row[RouteKey]."</td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";
}

function initialVest() {
        echo "This is Initialize a vest that is selected";
        $VestKey = empty($_POST['VestKey'])?die("Please input the Firstname"):$_POST['VestKey'];
        $TeamName = empty($_POST['TeamName'])?die("Please input the TeamName"):$_POST['TeamName'];
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
        $ini_v_sql =  "INSERT IGNORE INTO Vests (VestKey,  RouteKey, TeamName )
                        VALUES ($VeatKey, 1, '$TeamName')";
        $conn->query($ini_v_sql);
        echo "<br>";
        echo "Vest initialization success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show Initialized Vest Information</h3>";
        $sql_ini_v_res = "select * from Vests order by Modified DESC limit 1";
        $ini_v_res = $conn->query($sql_ini_v_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>DriverKey</td><td>RouteKey</td><td>TeamName</td><td>Modified</td></tr>";
        echo"<tr>";
        if ($ini_v_res->num_rows > 0) {
                while($row = $ini_v_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[RouteKey]."</td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";
}

function deleteVest() {
        echo "This is Delete a vest that is selected";
        $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $delete_v_sql =  "DELETE from Vests where VestKey = $VestKey";
        $conn->query($delete_v_sql);
        echo "<br>";
        echo "Delete vest success!";
}


function assignVestDriver() {
	echo "This is assign a vest to a driver that is selected";      
	$DriverKeyV = empty($_POST['DriverKeyV'])?die("Please input the DriverKey"):$_POST['DriverKeyV'];
        $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $a_vd_sql_1 = "UPDATE Drivers SET VestKey = $VestKey WHERE DriverKey = $DriverKeyV";
	$conn->query($a_vd_sql_1);
	$a_vd_sql_2 = "UPDATE Vests SET DriverKey = $DriverKeyV,RouteKey = (SELECT RouteKey from Training WHERE DriverKey = $DriverKeyV) WHERE VestKey = $VestKey";
        $conn->query($a_vd_sql_2);
	$a_vd_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'VestAssigned') )";
	$conn->query($a_vd_sql_3);
	echo "<br>";
        echo "Assign a vest to a driver success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
	$sql_assign_vd_res = "select * from VestStatusView where VestKey = $VestKey";
	$assign_vd_res = $conn->query($sql_assign_vd_res);
	echo "<table border=1>";
	echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
	echo"<tr>";

	if ($assign_vd_res->num_rows > 0) {
    		while($row = $assign_vd_res->fetch_assoc()) {
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

}

function unassignVestDriver() {
	echo "This is unassign vest from driver that is selected";
	$DriverKeyV = empty($_POST['DriverKeyV'])?die("Please input the DriverKey"):$_POST['DriverKeyV'];
        $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $una_vd_sql_1 = "UPDATE Drivers SET VestKey = NULL WHERE DriverKey = $DriverKeyV";
	$conn->query($una_vd_sql_1);
	$una_vd_sql_2 = "UPDATE Vests SET DriverKey = NULL,RouteKey = 1 WHERE VestKey = $VestKey";
        $conn->query($una_vd_sql_2);
        echo "<br>";
	echo "Unassign vest from driver success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_unassign_vd_res = "select * from VestStatusView where VestKey = $VestKey";
        $unassign_vd_res = $conn->query($sql_unassign_vd_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($unassign_vd_res->num_rows > 0) {
                while($row = $unassign_vd_res->fetch_assoc()) {
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

}


function checkTraining() {
	echo "This is check current drivers route training that is selected";
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
	echo "<h3>Show Current Drivers Route Training</h3>";
        $sql_check_training_res = "select * from Training";
        $check_training_res = $conn->query($sql_check_training_res);

        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>RouteKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($check_training_res->num_rows > 0) {
                while($row = $check_training_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";
                }

        }
        echo "</table>";
}


function assignRouteDriver() {
        echo "This is assign driver to a trained route that is selected";
        $DriverKeyR = empty($_POST['DriverKeyR'])?die("Please input the DriverKey"):$_POST['DriverKeyR'];
        $RouteKey = empty($_POST['RouteKey'])?die("Please input the RouteKey"):$_POST['RouteKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $a_rd_sql = "INSERT IGNORE INTO Training (DriverKey, RouteKey) VALUES ($DriverKeyR,$RouteKey)";
	$conn->query($a_rd_sql);
	echo "<br>";
        echo "Assign driver to a trained route success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show Driver Route Training</h3>";
        $sql_assign_rd_res = "select * from Training where DriverKey = $DriverKeyR";
        $assign_rd_res = $conn->query($sql_assign_rd_res);

        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>RouteKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($assign_rd_res->num_rows > 0) {
                while($row = $assign_rd_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";
                }

        }
        echo "</table>";
}


function unassignRouteDriver() {
        echo "This is unassign driver from route that is selected";
        $DriverKeyR = empty($_POST['DriverKeyR'])?die("Please input the DriverKey"):$_POST['DriverKeyR'];
        $RouteKey = empty($_POST['RouteKey'])?die("Please input the RouteKey"):$_POST['RouteKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $una_rd_sql = "DELETE from Training where DriverKey = $DriverKeyR and RouteKey = $RouteKey ";
        $conn->query($una_rd_sql);
	echo "<br>";
        echo "Unassign driver from route success!";
}

function checkVestStatusView() {
	echo "This is check current VestStatusView that is selected";
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
	echo "<h3>Show VestStatusView</h3>";
        $sql_check_vest_view_res = "select * from VestStatusView order by TeamName,VestStatusKey";
        $check_vest_view_res = $conn->query($sql_check_vest_view_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($check_vest_view_res->num_rows > 0) {
                while($row = $check_vest_view_res->fetch_assoc()) {
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
}

function driverReady() {
	echo "This is get driver ready that is selected";
	$DriverKeyS = empty($_POST['DriverKeyS'])?die("Please input the DriverKey"):$_POST['DriverKeyS'];
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
	$sql_ready = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                VALUES ((select VestKey from Vests where DriverKey = $DriverKeyS), ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Be Ready') )";
	$conn->query($sql_ready);
	echo "<br>";
        echo "Driver gets ready!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_ready_res = "select * from VestStatusView where VestKey = (select VestKey from Vests where DriverKey = $DriverKeyS)";
        $ready_res = $conn->query($sql_ready_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($ready_res->num_rows > 0) {
                while($row = $ready_res->fetch_assoc()) {
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

}

function driverDownstairs() {
        echo "This is get driver downstairs that is selected";
        $DriverKeyS = empty($_POST['DriverKeyS'])?die("Please input the DriverKey"):$_POST['DriverKeyS'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $sql_downstairs = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                VALUES ((select VestKey from Vests where DriverKey = $DriverKeyS), ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Go Downstairs') )";
        $conn->query($sql_downstairs);
        echo "<br>";
        echo "Driver goes downstairs!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_downstairs_res = "select * from VestStatusView where VestKey = (select VestKey from Vests where DriverKey = $DriverKeyS)";
        $downstairs_res = $conn->query($sql_downstairs_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($downstairs_res->num_rows > 0) {
                while($row = $downstairs_res->fetch_assoc()) {
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
	$una_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
		VALUES ($VestKeyC, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Done Driving') )";
	$conn->query($una_sql_3);
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
