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
	$method = "getmenus";
}


switch($method)
{
	case "getmenus":
		require("methods/getmenus.php");
		break;
	case "getentry":
		require("methods/getentry.php");
		break;
	case "saveanordnung":
		require("methods/saveanordnung.php");
		break;
	case "saveeintrag":
		require("methods/saveeintrag.php");
		break;
	case "insertmenu":
		require("methods/insertmenu.php");
		break;
	case "deleteentry":
		require("methods/deleteentry.php");
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