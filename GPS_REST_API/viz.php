<?php

$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error)
{
    echo $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT(VIN) FROM GPSDB";

$result = $conn->query($sql) ; 


echo "<br>";

$select_tag = "<option value='VIN'>Select a VIN</option>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row['VIN']. PHP_EOL;
        echo "<br>";
        if ($row['VIN'] <> 'circles')
        {
            $select_tag = $select_tag."<option value='".$row['VIN']."'>".$row['VIN']."</option>";
        }
    }
} 


?>



<html>

<head>
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
        $('#cars').change(function(){
                //Selected value
                var inputValue = $(this).val();
                if(inputValue != 'VIN')
                {
                    alert("Vin select is "+inputValue);
                
                //Ajax for calling php function
                $.post('viz.php', { dropdownValue: inputValue }, function(data){
                       // alert('ajax completed. Response:  '+data);
                              
                    //do after submission operation in DOM
                      });
                }
                });
        });
</script-->
<title>

GPS Coordinates Visualization
</title>
</head>

<form name="add" method="post" action="map.php">
<label for="cars">Choose a car:</label>
<select name="cars" id="cars">
<?php echo $select_tag ?>
</select>
<input type='submit' name='submit'/>
</form>



</html>
