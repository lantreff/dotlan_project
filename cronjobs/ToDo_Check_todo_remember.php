<?php

//0       16      *       *       *       php -f  /var/www/vhosts/maxlan.de/httpdocs/admin/projekt/cronjobs/ToDo_Check_todo_remember.php

include_once("../../../global.php");
include("../functions.php");
include("../todo/todo_functions.php");
global $global;

$email 	= $global['email'];
$sitename 	= $global['sitename'];

$timestamp_1 = time() - (7 * 24 * 60 * 60); // 1 Woche
$wochen_1	= date( "Y-m-d H:i:s", $timestamp_1);  

$timestamp_2 = time() - (14 * 24 * 60 * 60); // 2 Wochen
$wochen_2	= date( "Y-m-d H:i:s", $timestamp_2);  


$sql_gruppen =  mysql_query("SELECT * FROM project_todo_gruppen");
$sql_orga	 =  mysql_query("SELECT * FROM user_orga");

while($mail_orga = mysql_fetch_array($sql_orga))
{	
$sql_3_orga = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = 0 AND event_id = '".$event_id."' AND end > '".$wochen_2."' AND prio = '3' AND status <> 11 ");
$sql_4_orga = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = 0 AND event_id = '".$event_id."' AND end > '".$wochen_1."' AND prio = '4' AND status <> 11 ");
$sql_5_orga = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = 0 AND event_id = '".$event_id."' AND prio = 5 AND status <> 11 ");
$sql_3_orga1 = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = 0 AND event_id = '".$event_id."' AND end > '".$wochen_2."' AND prio = '3' AND status <> 11 ");
$sql_4_orga1 = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = 0 AND event_id = '".$event_id."' AND end > '".$wochen_1."' AND prio = '4' AND status <> 11 ");
$sql_5_orga1 = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = 0 AND event_id = '".$event_id."' AND prio = 5 AND status <> 11 ");
	
	if(mysql_num_rows($sql_3_orga)>0) { send_mail_table($sql_3_orga,$sql_3_orga1); }
	if(mysql_num_rows($sql_4_orga)>0) { send_mail_table($sql_4_orga,$sql_4_orga1); }
	if(mysql_num_rows($sql_5_orga)>0) { send_mail_table($sql_5_orga,$sql_5_orga1); }
	//ECHO out_table($sql_3_orga,$DARF);
}

while($mail_gruppen = mysql_fetch_array($sql_gruppen))
{	
$sql_3_gruppe = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND end > '".$wochen_2."' AND prio = '3' AND status <> 11 ");
$sql_4_gruppe = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND end > '".$wochen_1."' AND prio = '4' AND status <> 11 ");
$sql_5_gruppe = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND prio = 5 AND status <> 11 ");
$sql_3_gruppe1 = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND end > '".$wochen_2."' AND prio = '3' AND status <> 11 ");
$sql_4_gruppe1 = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND end > '".$wochen_1."' AND prio = '4' AND status <> 11 ");
$sql_5_gruppe1 = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND prio = 5 AND status <> 11 ");
	
	if(mysql_num_rows($sql_3_gruppe)>0) { send_mail_table($sql_3_gruppe,$sql_3_gruppe1); }
	if(mysql_num_rows($sql_4_gruppe)>0) { send_mail_table($sql_4_gruppe,$sql_4_gruppe1); }
	if(mysql_num_rows($sql_5_gruppe)>0) { send_mail_table($sql_5_gruppe,$sql_5_gruppe1); }
	//ECHO out_table($sql_3_gruppe,$DARF);
}

?>
