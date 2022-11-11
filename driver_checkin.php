<html>
    
    <head>

        <title>Driver check in/out</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

        <style>
            * { box-sizing: border-box; }
            .column { float: left; width: 30%; padding: 10px; }
            .row:after { content: ""; display: table; clear: both; }
            @media screen and (max-width: 600px) { .column { width: 100%; } }
        </style>

    </head>

    <body>
    <center>
        
        <div class="row">
            <h1> Driver check in/out </h1>
        </div>

        <div class="row">
                <a href="http://ransom.isis.vanderbilt.edu/home.html#drivers" class=button> Go home </a>
        </div>

        <form action='' method='post'>
            <div class="row">
                <div class="column" style="width: 30%"><br></div>
                <div class="column" style="width: 40%">
                    DriverKey(*): <input type='text' name='DriverKey'>
                </div>
                <div class="column" style="width: 30%"><br></div>
            </div>
            <div class="row">
                <div class="column" style="width: 20%"><br></div>
                <div class="column">
                    <input type='submit' value='Check in' name='DriverCheckIn' class="button primary">
                </div>
                <div class="column">
                    <input type='submit' value='Check out' name='DriverCheckOut' class="button">
                </div>
                <div class="column" style="width: 20%"><br></div>
            </div>
        </form>

        
        <?php
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
                    $sqlinfo = require_once('/var/www/config.php');
                    $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
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
                    } }
                    echo "</table>";
            }

            function DriverCheckOut() {
                    echo "This is driver check-out that is selected";
                    $DriverKey = empty($_POST['DriverKey'])?die("Please input the DriverKey"):$_POST['DriverKey'];
                    $sqlinfo = require_once('/var/www/config.php');
                    $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
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
                    } }
                    echo "</table>";
            }
        ?>

</center>
</body>
</html>
