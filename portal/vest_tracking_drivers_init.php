<html>
<head>
<title>Initialize a Driver</title>
</head>
<body>
<center>

<form action='' method='post'>
<h2>Initialize a Driver</h2>
Firstname(*): <input type='text' name='Firstname'>
Middlename: <input type='text' name='Middlename'>
Lastname(*): <input type='text' name='Lastname'>
Suffix: <input type='text' name='Suffix'>
PhoneNumber: <input type='text' name='PhoneNumber'>
Email(*): <input type='text' name='Email'>
DriverKey(*): <input type='text' name='DriverKey'>
<input type='submit' value='Initialize a driver' name='initialDriver' class="button">
<input type='submit' value='Delete a driver(only need to input DriverKey)' name='deleteDriver' class="button">
</form>

<?php
echo "<br>";
echo "<a href=./vest_tracking_home.php> go home </a>";
echo "<br>";
if(array_key_exists('initialDriver', $_POST)) {
        	initialDriver();
}
	else if(array_key_exists('deleteDriver', $_POST)) {
                deleteDriver();
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
	$ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
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
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
        $delete_sql =  "DELETE from Drivers where DriverKey = $DriverKey";
        $conn->query($delete_sql);
        echo "<br>";
        echo "Delete driver success!";
}


?>

</center>
</body>
</html>
