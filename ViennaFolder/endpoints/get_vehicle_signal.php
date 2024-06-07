<?php
//declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$time_speed = array(1701389314.333874,
  1701389316.330543,
  1701389318.331073,
  1701389320.333315,
  1701389322.330893,
  1701389324.330345,
  1701389326.331383,
  1701389328.33033,
  1701389330.330672,
  1701389332.333329,
  1701389334.331528,
  1701389336.331134,
  1701389338.331494,
  1701389340.331244,
  1701389342.329876,
  1701389344.331,
  1701389346.331778,
  1701389348.329675,
  1701389350.329441,
  1701389352.33114,
  1701389354.329204,
  1701389356.336824,
  1701389358.331271,
  1701389360.330447,
  1701389362.330709,
  1701389364.330177,
  1701389366.328819,
  1701389368.329246,
  1701389370.328743,
  1701389372.329097,
  1701389374.329531,
  1701389376.330457,
  1701389378.330179,
  1701389380.330864,
  1701389382.33007,
  1701389384.330514,
  1701389386.330317,
  1701389388.329472,
  1701389390.330113,
  1701389392.329445,
  1701389394.330265,
  1701389396.329443,
  1701389398.329187,
  1701389400.329373,
  1701389402.329646,
  1701389404.328629,
  1701389406.329424,
  1701389408.328639,
  1701389410.328867,
  1701389412.328451,
  1701389414.328528,
  1701389416.329102,
  1701389418.328163,
  1701389420.328495,
  1701389422.329196,
  1701389424.328595,
  1701389426.329997,
  1701389428.328251,
  1701389430.327962,
  1701389432.327843,
  1701389434.327549,
  1701389436.327277,
  1701389438.335036,
  1701389440.327297,
  1701389442.327921,
  1701389444.327303,
  1701389446.327062,
  1701389448.327995,
  1701389450.328215,
  1701389452.327395,
  1701389454.32763,
  1701389456.326926,
  1701389458.327221,
  1701389460.326899,
  1701389462.326734,
  1701389464.32668,
  1701389466.326798,
  1701389468.327809,
  1701389470.326684,
  1701389472.327814,
  1701389474.327067,
  1701389476.327449,
  1701389478.327681,
  1701389480.327299,
  1701389482.327582,
  1701389484.327537,
  1701389486.327229,
  1701389488.327665,
  1701389490.327728,
  1701389492.328101,
  1701389494.326639,
  1701389496.328876,
  1701389498.326709,
  1701389500.32665,
  1701389502.327375);
  
$average_speed = array(16.46,
  19.66,
  20.59,
  18.73,
  18.7625,
  21.922500000000003,
  24.37,
  26.3775,
  28.0625,
  28.1125,
  25.252499999999998,
  22.7175,
  20.4525,
  11.855,
  9.5,
  15.6525,
  25.7875,
  37.745000000000005,
  46.477500000000006,
  52.4075,
  54.940000000000005,
  55.8175,
  55.1775,
  53.9675,
  53.352500000000006,
  53.192499999999995,
  54.042500000000004,
  55.615,
  56.09,
  56.4375,
  56.3025,
  56.89,
  56.395,
  56.515,
  56.61,
  56.9375,
  57.0625,
  57.1225,
  57.330000000000005,
  57.627500000000005,
  57.612500000000004,
  56.5175,
  54.4025,
  51.315,
  51.06750000000001,
  55.5675,
  55.74250000000001,
  51.175000000000004,
  45.6625,
  39.830000000000005,
  29.134999999999998,
  18.865000000000002,
  17.2825,
  23.637500000000003,
  29.165000000000003,
  32.31,
  31.540000000000003,
  28.405,
  25.727500000000003,
  24.352500000000003,
  25.707500000000003,
  27.875000000000004,
  30.325000000000003,
  32.925,
  34.61,
  35.5675,
  35.995,
  35.9375,
  34.935,
  35.865,
  37.4675,
  37.6775,
  35.845,
  35.665,
  36.1075,
  36.4575,
  37.06,
  37.917500000000004,
  36.605000000000004,
  33.785000000000004,
  28.2975,
  16.92,
  10.02,
  0.0,
  3.9225000000000003,
  6.0925,
  4.654999999999999,
  3.1500000000000004,
  7.55,
  8.100000000000001,
  5.2875,
  3.9675000000000002,
  3.95,
  2.5949999999999998,
  2.7025);

$time_steer = array(1701389314.322654, 1701389315.322122, 1701389316.322084, 1701389317.322757, 1701389318.322319, 1701389319.321811, 1701389320.323865, 1701389321.323933, 1701389322.321998, 1701389323.32771, 1701389324.322651, 1701389325.32305, 1701389326.323029, 1701389327.322428, 1701389328.32315, 1701389329.322929, 1701389330.322561, 1701389331.322461, 1701389332.329315, 1701389333.323198, 1701389334.322671, 1701389335.323436, 1701389336.323543, 1701389337.323001, 1701389338.323582, 1701389339.32361, 1701389340.323114, 1701389341.323055, 1701389342.323171, 1701389343.323986, 1701389344.323523, 1701389345.323593, 1701389346.3236, 1701389347.324035, 1701389348.323694, 1701389349.323305, 1701389350.32352, 1701389351.324592, 1701389352.323714, 1701389353.323835, 1701389354.32367, 1701389355.323816, 1701389356.336824, 1701389357.324151, 1701389358.323862, 1701389359.32377, 1701389360.323897, 1701389361.324044, 1701389362.324018, 1701389363.324855, 1701389364.324231, 1701389365.324198, 1701389366.324224, 1701389367.327624, 1701389368.326371, 1701389369.324841, 1701389370.32446, 1701389371.324634, 1701389372.324514, 1701389373.324664, 1701389374.324623, 1701389375.325249, 1701389376.325025, 1701389377.326401, 1701389378.325234, 1701389379.325808, 1701389380.330864, 1701389381.325397, 1701389382.324799, 1701389383.326158, 1701389384.325171, 1701389385.325618, 1701389386.325398, 1701389387.325385, 1701389388.325431, 1701389389.325673, 1701389390.325812, 1701389391.329235, 1701389392.32554, 1701389393.326193, 1701389394.325779, 1701389395.325865, 1701389396.326154, 1701389397.325879, 1701389398.325869, 1701389399.32627, 1701389400.326183, 1701389401.326023, 1701389402.32593, 1701389403.325837, 1701389404.325996, 1701389405.326787, 1701389406.326739, 1701389407.326068, 1701389408.326784, 1701389409.32636, 1701389410.326889, 1701389411.326455, 1701389412.326432, 1701389413.326544, 1701389414.327985, 1701389415.326742, 1701389416.327414, 1701389417.32741, 1701389418.326678, 1701389419.328304, 1701389420.327353, 1701389421.32731, 1701389422.327987, 1701389423.327405, 1701389424.326973, 1701389425.327286, 1701389426.329997, 1701389427.340409, 1701389428.327234, 1701389429.329444, 1701389430.32754, 1701389431.32719, 1701389432.327524, 1701389433.328089, 1701389434.327549, 1701389435.327819, 1701389436.327479, 1701389437.327671, 1701389438.335036, 1701389439.32784, 1701389440.328092, 1701389441.32794, 1701389442.327921, 1701389443.32777, 1701389444.328156, 1701389445.328142, 1701389446.328236, 1701389447.328185, 1701389448.328268, 1701389449.330172, 1701389450.328215, 1701389451.328741, 1701389452.329034, 1701389453.328207, 1701389454.328472, 1701389455.328707, 1701389456.328865, 1701389457.328956, 1701389458.329096, 1701389459.328591, 1701389460.328739, 1701389461.328641, 1701389462.328606, 1701389463.32881, 1701389464.328905, 1701389465.32921, 1701389466.328931, 1701389467.329937, 1701389468.329528, 1701389469.330388, 1701389470.329263, 1701389471.329013, 1701389472.329016, 1701389473.331238, 1701389474.329788, 1701389475.331501, 1701389476.330029, 1701389477.329512, 1701389478.330217, 1701389479.329685, 1701389480.329835, 1701389481.329593, 1701389482.331571, 1701389483.330062, 1701389484.330441, 1701389485.329739, 1701389486.329842, 1701389487.329787, 1701389488.329802, 1701389489.329967, 1701389490.330036, 1701389491.330133, 1701389492.3309, 1701389493.329977, 1701389494.330625, 1701389495.33034, 1701389496.332203, 1701389497.331315, 1701389498.330466, 1701389499.331385, 1701389500.331335, 1701389501.330698, 1701389502.330729);

$message_steer = array(-9.8, -28.3, -39.7, -52.1, -60.1, -47.400000000000006, -20.1, -14.9, -8.200000000000001, -1.3, 4.0, 2.5, -9.3, -9.3, -1.5, 2.3000000000000003, 0.1, 0.1, 1.1, 0.5, 0.30000000000000004, -0.1, -1.9000000000000001, -9.3, -18.6, 13.9, 99.4, 245.4, 403.70000000000005, 433.40000000000003, 274.3, 107.0, 38.400000000000006, 8.5, 12.100000000000001, 9.3, 4.3, 4.3, 3.9000000000000004, 6.300000000000001, 5.5, 5.4, 5.1000000000000005, 4.800000000000001, 3.7, 5.2, 5.5, 5.4, 4.4, 4.3, 0.6000000000000001, 0.6000000000000001, 2.1, 3.3000000000000003, 4.800000000000001, 4.6000000000000005, 4.4, 2.4000000000000004, 2.1, 2.7, 3.1, 4.0, 5.300000000000001, 3.6, 2.0, 1.9000000000000001, 3.2, 4.0, 4.3, 3.8000000000000003, 5.4, 5.5, 6.6000000000000005, 6.4, 9.700000000000001, 10.5, 11.4, 7.4, 9.5, 10.0, 11.8, 10.200000000000001, 9.8, 6.5, 3.5, 10.200000000000001, 13.700000000000001, 19.700000000000003, 13.200000000000001, 12.5, 4.800000000000001, 4.0, 3.7, 3.1, 3.5, 2.7, 2.3000000000000003, 3.1, 11.4, 9.600000000000001, -6.5, -74.3, -207.9, -254.8, -252.0, -76.4, -14.100000000000001, 1.4000000000000001, 5.7, 3.7, -8.3, -25.8, -41.400000000000006, -49.5, -50.0, -15.700000000000001, 65.60000000000001, 108.7, 117.2, 90.30000000000001, 27.1, -5.4, -9.600000000000001, -3.7, 5.5, 1.2000000000000002, -0.6000000000000001, 0.5, 5.9, 6.800000000000001, 5.7, 3.6, 3.2, 3.1, 8.3, 5.4, 1.9000000000000001, 1.9000000000000001, 6.800000000000001, 6.5, 4.800000000000001, 4.3, 4.2, 4.7, 4.4, 4.2, 4.3, 3.7, 1.8, 1.8, 5.4, 3.1, 3.1, 2.4000000000000004, 7.5, 6.800000000000001, 3.9000000000000004, 5.2, 8.5, 8.700000000000001, -2.8000000000000003, -14.3, 32.0, 162.0, 241.3, 171.5, 40.6, -206.60000000000002, -404.20000000000005, -496.90000000000003, -494.8, -303.0, 42.6, 70.0, 42.400000000000006, 42.300000000000004, -79.80000000000001, -118.10000000000001, -20.5, 259.1, 430.3, 438.5, 459.90000000000003, 566.1, 551.6, 419.3, 308.6, 180.10000000000002, 213.5);

if (array_key_exists("trajectory_id", $_GET) and array_key_exists("signal_name", $_GET)) {
  $trajectory_id = $_GET["trajectory_id"];
	$signal_name = $_GET["signal_name"];
  $result = NULL;

  if ($signal_name == "speed") {
    $result = array("time" => $time_speed, "signal" => $average_speed);
  } elseif ($signal_name == "steer") {
    $result = array("time" => $time_steer, "signal" => $message_steer);
  } else {
    die("Invalid signal name.");
  }
  

  $result = array("time" => $time, "signal" => $signal);
  
  header("Content-Type: application/json");
  echo json_encode($result);
}
else {
  die("No trajectory id or signal name supplied!");
}
?>
