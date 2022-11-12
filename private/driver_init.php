<html>
    
    <head>

        <title>Initialize driver</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

        <style>
            * { box-sizing: border-box; }
            .column { float: left; width: 40%; padding: 10px; }
            .row:after { content: ""; display: table; clear: both; }
            @media screen and (max-width: 600px) { .column { width: 100%; } }
        </style>

    </head>

    <body>
    <center>
        
        <div class="row">
            <h1> Initialize a driver </h1>
        </div>

        <div class="row">
                <a href="https://ransom.isis.vanderbilt.edu/home.html#drivers" class=button> Go home </a>
        </div>
        
        <form action='' method='post'>
            <div class="row">
                <div class="column" style="width: 10%"><br></div>
                <div class="column">
                    Firstname(*): <input type='text' name='Firstname'>
                    Middlename: <input type='text' name='Middlename'>
                    Lastname(*): <input type='text' name='Lastname'>
                    Suffix: <input type='text' name='Suffix'>
                </div>
                <div class="column">
                    Phone Number: <input type='text' name='PhoneNumber'>
                    Email(*): <input type='text' name='Email'>
                    DriverKey(*): <input type='text' name='DriverKey'>
                </div>
                <div class="column" style="width: 10%"><br></div>
            </div>
            <div class="row">
                <input type='submit' value='Initialize driver' name='initialDriver' class="button primary">
                <input type='submit' value='Delete driver (only input DriverKey)' name='deleteDriver' class="button">
            </div>
        </form>
        

        <?php
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
                    $sqlinfo = require_once('/var/www/config.php');
                    $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                    $delete_sql =  "DELETE from Drivers where DriverKey = $DriverKey";
                    $conn->query($delete_sql);
                    echo "<br>";
                    echo "Delete driver success!";
            }


        ?>

    </center>
    </body>
    
</html>
