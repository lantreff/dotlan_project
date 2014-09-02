<?php

$MODUL_NAME = "meeting";
include_once("../../../global.php");
include("../functions.php");
include("meeting_functions.php");
//$output .=  $event_id;
$user_id = $CURRENT_USER->id;

include('header.php');

if ($DARF["view"]){

if ($DARF["edit"]){
  if($_GET["action"] == "anw"){
    anw_chg_anw($_GET["id"],$_GET["user_id"],1);
  }elseif($_GET["action"] == "abw"){
   anw_chg_anw($_GET["id"],$_GET["user_id"],2);
  }
}

if($_POST["submit"] == "ich komme NICHT"){
  $output .= anw_del($_GET["id"],$user_id);
}elseif($_POST["submit"] == "wahrscheinlichkeit ändern"){
  if($_POST["wahrscheinlichkeit"] < 0 || $_POST["wahrscheinlichkeit"] > 100){
    $output .=  "<h1>samma, kannsu garnüx???? zwischen 0 und 100 du depp!!!</h1>";
  }else
    $output .= anw_chg_wahr($_POST["wahrscheinlichkeit"],$_GET["id"],$user_id);
}elseif($_POST["submit"] == "ich komme zum Meeting"){
  if($_POST["wahrscheinlichkeit"] < 0 || $_POST["wahrscheinlichkeit"] > 100){
    $output .=  "<h1>samma, kannsu garnüx???? zwischen 0 und 100 du depp!!!</h1>";
  }else
    $output .= anw_add($_POST["wahrscheinlichkeit"],$_GET["id"],$user_id);
}



if(mysql_result(mysql_query("SELECT gewesen FROM project_meeting_liste WHERE ID = ".$_GET["id"]." LIMIT 1;"),0,"gewesen") == 0){
$output .='
<form action="meetings_anwesenheitsliste.php?id='.$_GET["id"].'" method="POST">
';

$query = mysql_query("SELECT * FROM project_meeting_anwesenheit WHERE user_id = ".$CURRENT_USER->id." AND meeting_id = ".$_GET["id"].";");
if(mysql_num_rows($query)){ // wenn user schon in liste ...
  $output .=  '<input class="okbuttons" type="submit" name="submit" value="ich komme NICHT"><br>';
  $output .=  '<input class="okbuttons" type="submit" name="submit" value="wahrscheinlichkeit ändern"> <input class="editbox" type="text" name="wahrscheinlichkeit" value="'.mysql_result($query,0,"wahrscheinlichkeit").'" size="3" style="text-align:right;">%';
}else{
  $output .=  '<input class="okbuttons" type="submit" name="submit" value="ich komme zum Meeting"> mit <input class="editbox" type="text" name="wahrscheinlichkeit" size="3" style="text-align:right;">%iger Wahrscheinlichkeit!';
}
$output .='
</form>
';
}else $gewesen = 1;
 $output .='
<table class="maincontent">
	'.anw_liste($_GET["id"],$gewesen,$DARF['edit']).'
 </table>
</td>';


} 
$PAGE->render($output);
?>
