<html>
    
    <head>

        <title>Vehicle tracking view</title>

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
            <h1> Vehicle tracking view </h1>
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
            $sql_assign_car_res_yellow = "select * from CarStatusView";
            $assign_car_res_yellow = $conn->query($sql_assign_car_res_yellow);

                echo "<table border=1>";
                echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
                echo"<tr>";

                if ($assign_car_res_yellow->num_rows > 0) {
                        while($row = $assign_car_res_yellow->fetch_assoc()) {
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
                } }
                echo "</table>";
        ?>

    </center>
    </body>
    
</html>
