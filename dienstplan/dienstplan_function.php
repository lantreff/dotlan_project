<?php

$MODUL_NAME = "Dienstplan";
$freeze_meldung = "<h3>Freeze *brrr* - &Auml;nderungen am Plan nicht mehr m&ouml;glich!!!</h3>";

// sql abfragen
$sql_event_ids = $DB->query("SELECT * FROM events ORDER BY begin DESC");
if(!$DARF['edit_freeze']) $freeze = mysql_result($DB->query("SELECT freeze FROM project_dienstplan WHERE event_id = '".$event_id."'"),0,"freeze");
else $freeze = 0;

// Timestamp in Datum umwandeln
function throwDatum($Timestamp) {
$Tag = strftime ("%d. ", $Timestamp);
$month = strftime ("%m", $Timestamp);
$Jahr = strftime (" %Y", $Timestamp);
$Stunden = strftime ("%H", $Timestamp);
$Minuten = strftime ("%M", $Timestamp);
$Monatsnamen = array("Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
$month = $month - 1;
$Monat = $Monatsnamen[$month];
$Datum = $Tag.$Monat.$Jahr." | ".$Stunden.":".$Minuten;
return $Datum;
}

// Timestamp in Datum umwandeln
function throwDatumEng($Timestamp) {
$Tag = strftime ("%d", $Timestamp);
$month = strftime ("%m", $Timestamp);
$Jahr = strftime ("%Y", $Timestamp);
$Stunden = strftime ("%H", $Timestamp);
$Minuten = strftime ("%M", $Timestamp);
$Datum = $Jahr.'-'.$month.'-'.$Tag.' '.$Stunden.':'.$Minuten.':00';
return $Datum;
}


function get_google_cal_link($typ,$id){
global $global;

  if($typ == "projekt"){
    $query = mysql_query("SELECT ad_level, name, location, DATE_FORMAT(von - INTERVAL 2 HOUR,'%Y%m%dT%H%i%sZ') AS von, DATE_FORMAT(bis - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM projekte WHERE id = '".$id."' LIMIT 1");
    if($_SESSION['ad_level'] < mysql_result($query,0,"ad_level")) die("Nicht ausrechend Bereichtigung");
    $name = mysql_result($query,0,"name");
    $von = mysql_result($query,0,"von");
    $bis = mysql_result($query,0,"bis");
    $wo = mysql_result($query,0,"location");
  }elseif($typ == "Dienstplan"){
    $query = mysql_query("SELECT adresse, DATE_FORMAT(datum  - INTERVAL 2 HOUR, '%Y%m%dT%H%i%sZ') AS datum, DATE_FORMAT(datum  - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM project_dienstplan WHERE id = '".$id."' LIMIT 1");
    $name = $global['sitename']." - Dienstplan";
    $von = mysql_result($query,0,"datum");
    $bis = mysql_result($query,0,"bis");
    $wo = str_replace(array("\n","\r",",")," ",umlaute_ersetzen(mysql_result($query,0,"adresse")));
  }else return false;

  return 'https://www.google.com/calendar/render?action=TEMPLATE&text='.$name.'&dates='.$von.'/'.$bis.'&sprop=website:'.$_SERVER["SERVER_NAME"].'&trp=true&location='.$wo;
}

function get_cal_links($typ,$id){
  if(empty($typ) || empty($id) || !is_numeric($id)) return false;
  return "<a href='ical.php?typ=".$typ."&id=".$id."'><img border=0 src='../images/16/ical.png' height='15' title='iCal herunterladen'></a> <a href='".get_google_cal_link($typ,$id)."' target='_blank'><img border=0 src='../images/16/google_calendar.png' height='15' title='Zu google-Kalender hinzuf&uuml;gen'></a>";
}

function show_avatar($userid,$height){
  $img = "http://".$_SERVER["SERVER_NAME"]."/images/avatar/tn_".$userid.".jpg";
  
if(@fopen($img, "r")){

  $output .= "<a class='info'><img src='$img' height='$height'><span><img src='$img'></span></a>";
  return $output;
}
else{
  return false;
}
}
function sonderzeichen($string)
{
 $string = str_replace("ä", "ae", $string);
 $string = str_replace("ü", "ue", $string);
 $string = str_replace("ö", "oe", $string);
 $string = str_replace("Ä", "Ae", $string);
 $string = str_replace("Ü", "Ue", $string);
 $string = str_replace("Ö", "Oe", $string);
 $string = str_replace("ß", "ss", $string);
 $string = str_replace("´", "", $string);
 return $string;
}

function copy_plan($event_id){
	$event_id_alt = ($event_id - 1);
	
	$query = mysql_query("SELECT * FROM `project_dienstplan`  WHERE event_id = '$event_id_alt' GROUP BY plan_name ");
	 while($row = mysql_fetch_array($query)){
		 
		mysql_query("INSERT INTO project_dienstplan (event_id, plan_name, tag, doppelt_erlaubt, id_01, id_02, id_03, id_04, id_05, id_06, id_07, id_08, id_09, id_10, id_11, id_12, id_13, id_14, id_15, id_16, id_17, id_18, id_19, id_20, id_21, id_22, id_23, id_24) VALUES ('".$event_id."','".$row['plan_name']."','1','".$row['doppelt_erlaubt']."','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1')");
		mysql_query("INSERT INTO project_dienstplan (event_id, plan_name, tag, doppelt_erlaubt, id_01, id_02, id_03, id_04, id_05, id_06, id_07, id_08, id_09, id_10, id_11, id_12, id_13, id_14, id_15, id_16, id_17, id_18, id_19, id_20, id_21, id_22, id_23, id_24) VALUES ('".$event_id."','".$row['plan_name']."','2','".$row['doppelt_erlaubt']."','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1')");
		mysql_query("INSERT INTO project_dienstplan (event_id, plan_name, tag, doppelt_erlaubt, id_01, id_02, id_03, id_04, id_05, id_06, id_07, id_08, id_09, id_10, id_11, id_12, id_13, id_14, id_15, id_16, id_17, id_18, id_19, id_20, id_21, id_22, id_23, id_24) VALUES ('".$event_id."','".$row['plan_name']."','3','".$row['doppelt_erlaubt']."','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1')");
	 }
		
}
?>
