<?php

require_once './../../lib/class/database.class.php';
include_once("../../../../global.php");
include("./../../functions.php");
require_once './../../../../config.php';

error_reporting(5);

$config = $global['database'];

$datenbank = new DataBase($config['server'], $config['username'], $config['password'], $config['database']);
$db = $datenbank->getPDO();


$rechtemanagment = new rechtesystem($db,$_SESSION);

echo "<hr>";
//var_dump($rechtemanagment->CheckRecht("kontakte", 'edit'));
//print_r($ADMIN);