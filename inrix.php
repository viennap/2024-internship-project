<?php
// open the file in a binary mode
$name = '/isis/home/hanw/circles/visual/by_date/20221118/fig/0-heatmap.png';
$fp = fopen($name, 'rb');

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($name));

// dump the picture and stop the script
fpassthru($fp);
exit;
?>
