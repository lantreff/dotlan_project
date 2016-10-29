<?php
//session_start();

error_reporting(5);

require_once './../../lib/class/database.class.php';
include_once("../../../../global.php");
include("./../../functions.php");
require_once './../../../../config.php';

$config = $global['database'];

$datenbank = new DataBase($config['server'], $config['username'], $config['password'], $config['database']);
$db = $datenbank->getPDO();

if(isset($_GET['method']))
{
	$method = $_GET['method'];
}
else 
{
	$method = "getentries";
}


switch($method)
{
	case "getentries":
		require("methods/getentries.php");
		break;
	case "save":
		require("methods/save.php");
		break;
}