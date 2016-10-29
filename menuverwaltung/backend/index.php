<?php
error_reporting(5);

$array = json_decode($input,true);
echo json_last_error_msg();
$array = $array["user"];

foreach($array as $eintrag)
{
	print_r($eintrag);
	echo "<hr>";
}