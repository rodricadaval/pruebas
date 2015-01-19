<?php

$year = 2014;
$month = 11;
if($month % 2 === 0){
	$desde = date("Y-m-d", mktime(0, 0, 0, $month-5, 1, $year));
	$hasta = date("Y-m-d", mktime(0, 0, 0, $month-3, 0, $year));
}
else{
	$desde = date("Y-m-d", mktime(0, 0, 0, $month-4, 1, $year));
	$hasta = date("Y-m-d", mktime(0, 0, 0, $month-2, 0, $year));
}

echo $desde;
echo "<br>";
echo "<br>";
echo $hasta;

?>
