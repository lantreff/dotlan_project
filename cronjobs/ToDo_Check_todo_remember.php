<?php
//crontab -e
//0       16      *       *       *       /usr/bin/lynx -source http://www.maxlan.de/admin/projekt/cronjobs/ToDo_Check_todo_remember.php 2>&1

include_once("../../../global.php");
include("../functions.php");
include("../todo/todo_functions.php");
global $global;

$email 	= $global['email'];
$sitename 	= $global['sitename'];

$sql_gruppen =  mysql_query("SELECT * FROM project_todo_gruppen");
$sql_orga	 =  mysql_query("SELECT * FROM user_orga");


while($mail_orga = mysql_fetch_array($sql_orga))
{	ECHO "<br>U: ".$mail_orga['user_id'];
$sql_3_orga = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = '0' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 14 AND prio = '3' AND status <> '11' ");
$sql_4_orga = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = '0' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 7 AND prio = '4' AND status <> '11' ");
$sql_5_orga = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = '0' AND event_id = '".$event_id."' AND prio = '5' AND status <> '11' ");

$sql_3_orga1 = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = '0' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 14 AND prio = '3' AND status <> '11' ");
$sql_4_orga1 = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = '0' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 7 AND prio = '4' AND status <> '11' ");
$sql_5_orga1 = mysql_query("SELECT * FROM `project_todo` WHERE bearbeiter = '".$mail_orga['user_id']."' AND gruppe = '0' AND event_id = '".$event_id."' AND prio = '5' AND status <> '11' ");
	
	if(mysql_num_rows($sql_3_orga)>0) { send_mail_table($sql_3_orga,$sql_3_orga1); }
	if(mysql_num_rows($sql_4_orga)>0) { send_mail_table($sql_4_orga,$sql_4_orga1); }
	if(mysql_num_rows($sql_5_orga)>0) { send_mail_table($sql_5_orga,$sql_5_orga1); }
	//if(mysql_num_rows($sql_3_orga)>0) {ECHO out_table($sql_3_orga,$DARF);}
}

while($mail_gruppen = mysql_fetch_array($sql_gruppen))
{ECHO "<br>G: ".$mail_gruppen['id']." ".$mail_gruppen['bezeichnung'];	
$sql_3_gruppe = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 14 AND prio = '3' AND status <> '11' ");
$sql_4_gruppe = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 7 AND prio = '4' AND status <> '11' ");
$sql_5_gruppe = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND prio = '5' AND status <> 11 ");

$sql_3_gruppe1 = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 14 AND prio = '3' AND status <> '11' ");
$sql_4_gruppe1 = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND DATEDIFF(end, CURRENT_TIMESTAMP) <= 7 AND prio = '4' AND status <> '11' ");
$sql_5_gruppe1 = mysql_query("SELECT * FROM `project_todo` WHERE gruppe = '".$mail_gruppen['id']."' AND event_id = '".$event_id."' AND prio = '5' AND status <> '11' ");
	
	if(mysql_num_rows($sql_3_gruppe)>0) { send_mail_table($sql_3_gruppe,$sql_3_gruppe1); }
	if(mysql_num_rows($sql_4_gruppe)>0) { send_mail_table($sql_4_gruppe,$sql_4_gruppe1); }
	if(mysql_num_rows($sql_5_gruppe)>0) { send_mail_table($sql_5_gruppe,$sql_5_gruppe1); }
	//f(mysql_num_rows($sql_3_gruppe)>0) {ECHO out_table($sql_3_gruppe,$DARF);}
}

?>
