<html>
<head>
<title>Driver Check-in/out</title>
</head>
<body>
<center>

<form action='' method='post'>
<h2>Driver Check-in/out</h2>
DriverKey(*): <input type='text' name='DriverKey'>
<input type='submit' value='Driver check-in' name='DriverCheckIn' class="button">
<input type='submit' value='Driver check-out' name='DriverCheckOut' class="button">
</form>

<?php
echo "<br>";
echo "<a href=./vest_tracking_home.php> go home </a>";
echo "<br>";
if(array_key_exists('DriverCheckIn', $_POST)) {
        	DriverCheckIn();
}
	else if(array_key_exists('DriverCheckOut', $_POST)) {
                DriverCheckOut();
	}

function DriverCheckIn() {
        echo "This is driver check-in that is selected";
        $DriverKey = empty($_POST['DriverKey'])?die("Please input the DriverKey"):$_POST['DriverKey'];
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
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
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
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


?>

</center>
</body>
</html>
