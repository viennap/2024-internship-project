<html>
    
    <head>

        <title>Vest tracking view</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

        <style>
            * { box-sizing: border-box; }
            .column { float: left; width: 33.33%; padding: 10px; }
            .row:after { content: ""; display: table; clear: both; }
        </style>

    </head>


    <body>
    <center>

        <div class="row">
            <h1> Vest tracking view </h1>
        </div>

        <div class="row">

            <div class="column" style="width: 50%;">
                <?php
                header("refresh: 60;");
                $timestamp=time();
                echo "Last refresh:  ";
                echo date("Y-m-d H:i:s", $timestamp);
                ?>
            </div>

            <div class="column" style="width: 50%;">
                <a href="http://ransom.isis.vanderbilt.edu/home.html#status" class=button> Go home </a>
            </div>

        </div>

        <?php
            header("refresh: 60;");
            $sqlinfo = require_once('/var/www/config.php');
            $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
            $sql_drivers_assigned_yellow = "select * from VestStatusView";
            $drivers_assigned_res_yellow = $conn->query($sql_drivers_assigned_yellow);

                echo "<table border=1>";
                echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
                echo"<tr>";

                if ($drivers_assigned_res_yellow->num_rows > 0) {
                        while($row = $drivers_assigned_res_yellow->fetch_assoc()) {
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
        ?>

    </center>
    </body>

</html>
