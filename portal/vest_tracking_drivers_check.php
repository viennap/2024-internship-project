<html>
<head>
<title>Drivers Check</title>
</head>
<body>
<center>

<?php
header("refresh: 60;");
$timestamp=time();
echo date("Y-m-d H:i:s", $timestamp);
echo "<br>";
echo "<a href=http://ransom.isis.vanderbilt.edu/vest_tracking_home.php> go home </a>";
$conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
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

?>

</center>
</body>
</html>
