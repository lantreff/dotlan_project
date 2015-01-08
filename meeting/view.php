<?php
########################################################################
# Meeting Modul for dotlan             			                   	   #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# Version 1.0                                                          #
########################################################################
//*
$MODUL_NAME = "meeting";
include_once("../../../global.php");
include("../functions.php");
//$output .= "TEST ".  $event_id;
//
function meeting_list($event_id){
global $DB;

$query = $DB->query("SELECT * FROM project_meeting_liste WHERE event_id = '".$event_id."' ORDER BY datum DESC;");
$output .=  '<tr><td width="100" class="msghead"><b>Titel&nbsp;</b></td><td class="msghead"><b>Datum / Uhrzeit&nbsp;</b></td>';
if($edit || $del)
  $output .=  '<td class="msghead" nowrap="nowrap"><b>Action&nbsp;</b></td>';
$output .=  '<td></td></tr>';
if(mysql_num_rows($query) != 0)
{
while ($row = mysql_fetch_array($query)){
$output .=  '<tr class="msgrow'.(($i%2)?1:2).'" >';
  $date_ger =  time2german($row["datum"]);
  $date = explode(" ", $date_ger);
$output .=  '<td nowrap="nowrap"><a target="_NEW" href="/admin/projekt/meeting">'.$row["titel"].'</a></td><td nowrap="nowrap">'.$date[0].'<br>'.$date[1].' </td>';

$output .=  '</tr>';
$i++;
}
}
return $output;
}

$output .= '<table>';
$output .=  meeting_list($event_id);
$output .= '</table>';


$PAGE->render($output);
?>