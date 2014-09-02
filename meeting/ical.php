<?php
$MODUL_NAME = "meeting";
include_once("../../../global.php");
include("meeting_functions.php");
include("../functions.php");


if ($DARF["view"]){
  $typ = $_GET["typ"];
  $id = mysql_real_escape_string($_GET["id"]);
  if(empty($typ) || empty($id) || !is_numeric($id)) die("Es wurde kein Typ oder keine ID angegeben");

  if($typ == "projekt"){
    $query = mysql_query("SELECT , name, location, DATE_FORMAT(von - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS von, DATE_FORMAT(bis - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM projekte WHERE id = '".$id."' LIMIT 1");
    if(!$$DARF["view"]) die("Nicht ausrechend Bereichtigung");
    $name = mysql_result($query,0,"name");
    $von = mysql_result($query,0,"von");
    $bis = mysql_result($query,0,"bis");
    $wo = mysql_result($query,0,"location");
    $filename =  $global['sitename']."_termin_projekt_".time().".ics";
  }elseif($typ == "meeting"){
    $query = mysql_query("SELECT adresse, DATE_FORMAT(datum - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS datum, DATE_FORMAT(datum + INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM project_meeting_liste WHERE id = '".$id."' LIMIT 1");
	$name = $global['sitename']." Meeting";
    $von = mysql_result($query,0,"datum");
    $bis = mysql_result($query,0,"bis");
    $wo = str_replace(array("\n","\r",",")," ",mysql_result($query,0,"adresse"));
    $filename = $global['sitename']."_termin_meeting_".time().".ics";
  }else die("bla");

$output .= 	header("Content-Disposition: attachment; filename=".$filename);
$output .=  header("Connection: close");
$output .=  header("Content-Type: text/calendar; name=$filename");

  $output .=  "BEGIN:VCALENDAR\n";
  $output .=  "VERSION:2.0\n";
#  $output .=  "PRODID:-//maxlan projekt//\n";
  $output .=  "METHOD:PUBLISH\n";
  $output .=  "BEGIN:VTIMEZONE
TZID:Europe/Berlin
X-LIC-LOCATION:Europe/Berlin
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
DTSTART:19700329T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE\n";
  $output .=  "BEGIN:VEVENT\n";
  $output .=  "DTSTART:$von\n";
  $output .=  "DTEND:$bis\n";
  $output .=  "DTSTAMP:".date("Ymd")."T".date("His")."Z\n";
  $output .=  "LOCATION:$wo\n";
  $output .=  "SUMMARY:$name\n";
  $output .=  "END:VEVENT\n";
  $output .=  "END:VCALENDAR\n";
} 
$PAGE->render($output);
?>
