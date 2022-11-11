<html>

    <head>

        <title>Current drivers</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

        <style>
        * { box-sizing: border-box; }
        .column { float: left; width: 50%; padding: 10px; }
        .row:after { content: ""; display: table; clear: both; }
        </style>

    </head>


    <body>
    <center>

        <div class="row">
            <h1> Current driver list </h1>
        </div>

        <div class="row">

            <div class="column">
                <?php
                header("refresh: 60;");
                $timestamp=time();
                echo "Last refresh:  ";
                echo date("Y-m-d H:i:s", $timestamp);
                ?>
            </div>

            <div class="column">
                <a href="http://ransom.isis.vanderbilt.edu/home.html#drivers" class=button> Go Home </a>
            </div>

        </div>

        <?php
            $sqlinfo = require_once('../config.php');
            $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);

            $check_d_sql ="select * from Drivers";
            $check_d_res = $conn->query($check_d_sql);

                echo "<table border=1>";
                echo "<tr><td>DriverKey</td><td>Firstname</td><td>Middlename</td><td>Lastname</td><td>Suffix</td><td>PhoneNumber</td><td>Email</td><td>VestKey</td><td>IsActive</td><td>Modified</td></tr>";
                echo "<tr>";
                if ($check_d_res->num_rows > 0) {
                        while($row = $check_d_res->fetch_assoc()) {
                                echo"<tr>";
                                echo"<td>".$row['DriverKey']."</td>";
                                echo"<td>".$row['Firstname']." </td>";
                                echo"<td>".$row['Middlename']." </td>";
                                echo"<td>".$row['Lastname']." </td>";
                                echo"<td>".$row['Suffix']." </td>";
                                echo"<td>".$row['PhoneNumber']."</td>";
                                echo"<td>".$row['Email']." </td>";
                                echo"<td>".$row['VestKey']." </td>";
                                echo"<td>".$row['IsActive']." </td>";
                                echo"<td>".$row['Modified']." </td>";
                                echo"</tr>";


                        }
                }
                echo "</table>";

        ?>

    </center>
    </body>
    
</html>
