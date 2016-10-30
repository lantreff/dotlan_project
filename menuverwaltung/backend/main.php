<?php
//session_start();

header('Content-Type: application/json;charset=utf-8');

error_reporting(5);

require_once './../../lib/class/database.class.php';
include_once("../../../../global.php");
include("./../../functions.php");
require_once './../../../../config.php';

$config = $global['database'];

$datenbank = new DataBase($config['server'], $config['username'], $config['password'], $config['database']);
$db = $datenbank->getPDO();


$rechtemanagment = new rechtesystem($db, $_SESSION);


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
	case "checkright":
		
		if($rechtemanagment->CheckRecht('menuverwaltung', 'show'))
		{
			$arr['login'] = true;
		}
		else 
		{
			$arr['login'] = false;
		}
		echo json_encode($arr);
		break;
}