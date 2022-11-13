<html>
    <head>

        <title>Change driver status</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/css/main.css" />
        <noscript><link rel="stylesheet" href="/assets/css/noscript.css" /></noscript>

        <style>
            * { box-sizing: border-box; }
            .column { float: left; width: 25%; padding: 10px; }
            .row:after { content: ""; display: table; clear: both; }
            @media screen and (max-width: 600px) { .column { width: 100%; } }
        </style>

    </head>

    <body>
    <center>
        
        <div class="row">
            <h1> Change driver status </h1>
        </div>

        <div class="row">
                <a href="http://ransom.isis.vanderbilt.edu/home.html#drivers" class=button> Go home </a>
        </div>

        <form action='' method='post'>
            <div class="row">
                <div class="column" style="width: 30%"><br></div>
                <div class="column" style="width: 40%">
                    VestKey(*): <input type='text' name='VestKey'>
                </div>
                <div class="column" style="width: 30%"><br></div>
	    </div>
	     <div class="row">
                <div class="column" style="width: 30%"><br></div>
                <div class="column" style="width: 40%">
                    <input type='submit' value='Check current VestStatusView' name='checkVestStatusView' class="button">
                </div>
                <div class="column" style="width: 30%"><br></div>
            </div>
            <div class="row">
                <div class="column" style="width: 12.5%"><br></div>
                <div class="column">
                    <input type='submit' value='Set driver free' name='driverFree' class="button">
                </div>
                <div class="column">
                    <input type='submit' value='Get driver ready' name='driverReady' class="button">
                </div>
                <div class="column">
                    <input type='submit' value='Get driver downstairs' name='driverDownstairs' class="button">
                </div>
                <div class="column" style="width: 12.5%"><br></div>
            </div>
	     <div class="row">
                <div class="column" style="width: 12.5%"><br></div>
                <div class="column">
                    <input type='submit' value='An orange driver can drive now' name='orangeDrive' class="button">
                </div>
                <div class="column">
                    <input type='submit' value='A yellow driver can drive now' name='yellowDrive' class="button">
                </div>
                <div class="column" style="width: 12.5%"><br></div>
            </div>
        </form>


        <?php
            echo "<br>";
            echo "<a href=/home.html> go home </a>";
            echo "<br>";
            if(array_key_exists('checkVestStatusView', $_POST)) {
                        checkVestStatusView();
            }
                else if(array_key_exists('driverReady', $_POST)) {
                            driverReady();
                    }
                    else if(array_key_exists('driverDownstairs', $_POST)) {
                    driverDownstairs();
                }
		else if(array_key_exists('driverFree', $_POST)) {
                    driverFree();
		}
		else if(array_key_exists('orangeDrive', $_POST)) {
                    orangeDrive();
                }
                else if(array_key_exists('yellowDrive', $_POST)) {
                    yellowDrive();
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
                } }
                echo "</table>";
            }
		
	    function driverFree() {
                echo "This is set driver free that is selected";
                $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $sql_free = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                            VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'VestAssigned') )";
                $conn->query($sql_free);
                echo "<br>";
                echo "Driver back to free!";
                echo "<br>";
                echo "<br>";
                echo "<h3>Show VestStatusView</h3>";
                $sql_free_res = "select * from VestStatusView where VestKey = $VestKey";
                $free_res = $conn->query($sql_free_res);
                echo "<table border=1>";
                echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
                echo"<tr>";

                if ($free_res->num_rows > 0) {
                        while($row = $free_res->fetch_assoc()) {
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
                } }
                echo "</table>";
	    }

            function driverReady() {
                echo "This is get driver ready that is selected";
                $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $sql_ready = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                            VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Be Ready') )";
                $conn->query($sql_ready);
                echo "<br>";
                echo "Driver gets ready!";
                echo "<br>";
                echo "<br>";
                echo "<h3>Show VestStatusView</h3>";
                $sql_ready_res = "select * from VestStatusView where VestKey = $VestKey";
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
                } }
                echo "</table>";
            }

            function driverDownstairs() {
                echo "This is get driver downstairs that is selected";
                $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $sql_downstairs = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                        VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Go Downstairs') )";
                $conn->query($sql_downstairs);
                echo "<br>";
                echo "Driver goes downstairs!";
                echo "<br>";
                echo "<br>";
                echo "<h3>Show VestStatusView</h3>";
                $sql_downstairs_res = "select * from VestStatusView where VestKey = $VestKey";
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
                } }
                echo "</table>";
            }
	function orangeDrive() {
                echo "This is An orange driver can drive now that is selected";
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $sql_orange_drive = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                            VALUES ((select VestKey from VestStatusView where RouteKey = 1 and VestStatusKey=3 order by Modified limit 1), ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Go Downstairs') )";
                $conn->query($sql_orange_drive);
                echo "<br>";
                echo "An orange driver can Go Downstairs and drive now!";
        }

        function yellowDrive() {
                echo "This is a yellow driver can drive now that is selected";
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $sql_yellow_drive = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                            VALUES ((select VestKey from VestStatusView where RouteKey = 2 and VestStatusKey=3 order by Modified limit 1), ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Go Downstairs') )";
                $conn->query($sql_yellow_drive);
                echo "<br>";
                echo "A yellow driver can Go Downstairs and drive now!";

        }

        ?>

    </center>
    </body>
</html>
