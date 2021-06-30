<?php 




$time1 = new DateTime('2020-10-22');
$time2 = new DateTime('2020-10-23');
$interval = $time1->diff($time2);
echo $interval->format('%d gun(d)');












?>