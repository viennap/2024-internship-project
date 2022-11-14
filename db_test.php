<html>
<head>
<title>The Latest piStatus information --- Details</title>
<meta http-equiv="refresh" content="10" >
</head>
<body>
<center>

<?php

$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

$GREEN='#74c451';
$GRAY='#999999';
$RED='#CD1337';
$SUPER_GRAY='#4e5255';
$ORANGE='#ec7331';
$YELLOW='#efe11a';

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error)
{
    echo $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
SELECT
wlan0_mac
,SSID
,wlan0_ip
,eth0_ip
,concat(wlan0_upload_rate_kb,' || ',wlan0_download_rate_kb) AS wlan0_up_download_kb
,dim_vehicle.vin
,battery_voltage
,external_power
,(100 - ROUND((CAST(REPLACE(tmp.available_ram,'G','') as DOUBLE)/CAST(REPLACE(tmp.total_ram,'G','') as DOUBLE))*100,0)) AS total_ram_used_percent
,total_memory_used_percent
,concat(cpu_load_1min,' || ',cpu_load_5min,' || ',cpu_load_15min) AS cpu_load_1_5_15min
,TIMESTAMPDIFF(MINUTE, SUBSTRING(CAST(FROM_UNIXTIME(update_time) AS NCHAR),1,19),current_timestamp()) current_update_min
,acc_button_wire_connected
,libpanda_git_hash
,log_message
,veh_id
FROM(SELECT * ,ROW_NUMBER() over ( PARTITION BY wlan0_mac ORDER BY update_time DESC) as rn From piStatus) as tmp join dim_vehicle on tmp.vin=dim_vehicle.vin WHERE tmp.rn =1 AND tmp.wlan0_mac IS NOT NULL ORDER BY update_time DESC
";

$result = $conn->query($sql);
echo $conn->error;
print_r("The Latest piStatus Information");

echo "<br>";
echo "<table border=1>";
echo "<tr><td>Car #</td><td>Pi_MAC</td><td>SSID</td><td>wlan0_ip</td><td>eth0_ip</td><td>wlan0_up_download_kb</td><td>VIN</td><td>battery_voltage</td><td>pwr</td><td>ram%</td><td>storage%</td><td>cpu_load_1_5_15min</td><td>update_min</td><td>wire</td><td>libpanda_git_hash</td><td>log_message</td></tr>";
echo"<tr>";

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    if ($row[current_update_min]<1){
      echo "<tr style='background-color: #74c451;'>";
    }
    elseif ( ($row[current_update_min]>=1) && ($row[current_update_min]<10) ){
      echo "<tr style='background-color:".$ORANGE.";'>";
    }
    else{
      echo "<tr style='background-color: ".$GRAY.";'>";
    }
	// echo"<tr>";
  echo"<td>".$row[veh_id]."</td>";
	echo"<td>".$row[wlan0_mac]."</td>";
	echo"<td>".$row[SSID]." </td>";
	echo"<td>".$row[wlan0_ip]." </td>";
	echo"<td>".$row[eth0_ip]." </td>";
	echo"<td>".$row[wlan0_up_download_kb]." </td>";
	echo"<td>".$row[vin]."</td>";
	echo"<td>".$row[battery_voltage]." </td>";
	echo"<td>".$row[external_power]." </td>";
	echo"<td>".$row[total_ram_used_percent]." </td>";
	echo"<td>".$row[total_memory_used_percent]." </td>";
	echo"<td>".$row[cpu_load_1_5_15min]." </td>";
	// echo"<td>".$row[current_update_min]." </td>";
  if($row[current_update_min]>720)
                              {
           echo "<td style='background-color: ".$SUPER_GRAY.";'>".$row[current_update_min]."</td>";

                         }
                         else
                         {
           echo "<td>".$row[current_update_min]."</td>";
                         }
  // echo"<td>".$row[acc_button_wire_connected]." </td>";
  if($row[acc_button_wire_connected]==0)
  {
    echo "<td style='background-color: ".$RED.";'>".$row[acc_button_wire_connected]."</td>";
  }
else{
  echo "<td>".$row[acc_button_wire_connected]."</td>";

  }
  echo"<td>".$row[veh_id]."</td>";
  echo"<td>".$row[libpanda_git_hash]." </td>";
  // echo"<td>".$row[log_message]." </td>";
  if(substr_compare($row[log_message],"These rosnodes are down",0,13)==0){
    echo"<td style='background-color: ".$RED.";'>".$row[log_message]." </td>";
  }
  elseif(substr_compare($row[log_message],"nominal state!",0,13) && $row[current_update_min]<1) {
    echo"<td style='background-color: ".$YELLOW.";'>".$row[log_message]." </td>";
  }
  elseif(substr_compare($row[log_message],"Rsync finished, shutting system down",0,23) && $row[current_update_min]>10) {
    echo"<td style='background-color: ".$YELLOW.";'>".$row[log_message]." </td>";
  }
  else{
    echo"<td>".$row[log_message]." </td>";
  }
  echo"<td>".$row[veh_id]."</td>";
	echo"</tr>";

    }
}

?>

</center>
</body>
</html>
