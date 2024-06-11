<?php
//declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$dict = array(); 

if (array_key_exists("start_time", $_GET) and array_key_exists("end_time", $_GET)) {
    $trajectory_id = "asdf";
    $start_time = 1701389314.4;
    $end_time = 1701389496.6;

    $latitude = array(36.368492126464844, 36.37989807128906);
    $longitude = array(-87.04999542236328,-87.05628967285156);
    
    $dict["trajectories"] = array(); 
    $dict["trajectories"][$trajectory_id] = array();
    $dict["trajectories"][$trajectory_id]["id"] = $trajectory_id;
    $dict["trajectories"][$trajectory_id]["start_time"] = $start_time; 
    $dict["trajectories"][$trajectory_id]["end_time"] = $end_time; 
    $dict["trajectories"][$trajectory_id]["latitude"] = $latitude;
    $dict["trajectories"][$trajectory_id]["longitude"] = $longitude;
    
    // array_push($dict["trajectories"][$trajectory_id], $latitude, $longitude, );
   
    $result = $dict; 

    header("Content-Type: application/json");
    echo json_encode($result);
}
else {
    die("No trajectory id supplied!");
}
?>