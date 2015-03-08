<?php
include_once("../../../global.php");
include("../functions.php");
include("dienstplan_function.php");
//$output .= "TEST ".  $event_id;
$output .="
<style type='text/css'> #header { display:none } #shortmenu { display:none } #banner { display:none } #imgWrapper0 { display:none } #werbebanner { display:none }  </style>
";
$event_id = $_GET['event'];


  $plan = mysql_result($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$event_id."' ORDER BY plan_name LIMIT 1"),0,"plan_name");

 if (mysql_num_rows($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$event_id."'")) != 0)
{
$plan = mysql_real_escape_string($_POST["plan_name"]);
if(empty($plan)) $plan = mysql_real_escape_string($_GET["plan"]);
if(empty($plan)){
  $plan = mysql_result($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$event_id."' ORDER BY plan_name LIMIT 1"),0,"plan_name");
}
}
else{

$output .= "<h2>Kein Plan zum Event!</h2>";
}

if($_GET["a"] == "add"){
  $id = mysql_real_escape_string($_GET["std"]) + 1;
  if(strlen($id) == 1) $id = "0".$id;

  if($_GET["x"] > -1){
    $alt = mysql_result(mysql_query("SELECT id_$id FROM project_dienstplan WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$event_id."'"),0,"id_$id");

    $bla = explode(",",$alt);
    $bla[$_GET["x"]] = mysql_real_escape_string($user_id);
    $neu = implode(",",$bla);
  }else $neu = mysql_real_escape_string($user_id);

  $sql = "UPDATE project_dienstplan SET ";
  $sql .= "id_".$id." = '".$neu."' ";
  $sql .= " WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$event_id."'";
	
	$DB->query($sql);
}

if($_GET["a"] == "del"){
  $id = mysql_real_escape_string($_GET["std"]) + 1;
  if(strlen($id) == 1) $id = "0".$id;

  if($_GET["x"] > -1){
    $alt = mysql_result(mysql_query("SELECT id_$id FROM project_dienstplan WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$event_id."'"),0,"id_$id");

    $bla = explode(",",$alt);
    $bla[$_GET["x"]] = "-1";
    $neu = implode(",",$bla);
  }else $neu = "-1";

  $sql = "UPDATE project_dienstplan SET ";
  $sql .= "id_".$id." = '$neu' ";
  $sql .= " WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$event_id."'";
	
	$DB->query($sql);
}

$tag = array();
$query = $DB->query("SELECT * FROM project_dienstplan WHERE plan_name = '$plan' AND event_id = '".$event_id."'");
while($row =  $DB->fetch_array($query)){
  
  $tag1 = $row["tag"];
  $tag[$tag1][0] = $row["id_01"];
  $tag[$tag1][1] = $row["id_02"];
  $tag[$tag1][2] = $row["id_03"];
  $tag[$tag1][3] = $row["id_04"];
  $tag[$tag1][4] = $row["id_05"];
  $tag[$tag1][5] = $row["id_06"];
  $tag[$tag1][6] = $row["id_07"];
  $tag[$tag1][7] = $row["id_08"];
  $tag[$tag1][8] = $row["id_09"];
  $tag[$tag1][9] = $row["id_10"];
  $tag[$tag1][10] = $row["id_11"];
  $tag[$tag1][11] = $row["id_12"];
  $tag[$tag1][12] = $row["id_13"];
  $tag[$tag1][13] = $row["id_14"];
  $tag[$tag1][14] = $row["id_15"];
  $tag[$tag1][15] = $row["id_16"];
  $tag[$tag1][16] = $row["id_17"];
  $tag[$tag1][17] = $row["id_18"];
  $tag[$tag1][18] = $row["id_19"];
  $tag[$tag1][19] = $row["id_20"];
  $tag[$tag1][20] = $row["id_21"];
  $tag[$tag1][21] = $row["id_22"];
  $tag[$tag1][22] = $row["id_23"];
  $tag[$tag1][23] = $row["id_24"];
}


 $output .="<table class='maincontent'>";

// Maintable do not edit html upon //


 $output .="<tr>";
 $output .="<td>";
 $output .="<table>";
 $output .="<tr>";
 $output .="<td class='maintd'><b>Plan ".$plan.":</b>";
 $output .="</tr>";
$output .="<tr>";
 $output .="<td>";

 //$output .="<form action='index.php' method='post'>";
 $output .="<table width='850'>";
   $output .="<tr>";
     $output .="<td class='anwesenheit_top' width='100'>&nbsp;</td>";
     $output .="<td class='anwesenheit_top' width='250'><b>Freitag</b></td>";
     $output .="<td class='anwesenheit_top' width='250'><b>Samstag</b></td>";
     $output .="<td class='anwesenheit_top' width='250'><b>Sonntag</b></td>";
   $output .="</tr>";


for($i=0;$i<24;$i++){
  if(strlen($i) == 1) $std = "0".$i;
  else $std = $i;
  $std1 = $std + 1;
  if(strlen($std1) == 1) $std1 = "0".$std1;

  $equal = bcmod($i, 2);
  if ($equal == 0) {
    $class= 'class="anwesenheit1"';
  } else {
    $class= 'class="anwesenheit2"';
  }

  if(strstr($tag[1][$i],",")){
    $bla = explode(",",$tag[1][$i]);
    $x=1;
    foreach($bla as $blubb){
      if($blubb == "-1") $x=0;
    }
    if($x == 1) $color_freitag = "#FFFFFF";
    else $color_freitag = "#FFFFFF";
  }else{
    if($tag[1][$i] == 0) $color_freitag = "#FFFFFF";
    elseif($tag[1][$i] > 0) $color_freitag = "#FFFFFF";
    else $color_freitag = "#FFFFFF";
  }

  if(strstr($tag[2][$i],",")){
    $bla = explode(",",$tag[2][$i]);
    $x=1;
    foreach($bla as $blubb){
      if($blubb == "-1") $x=0;
    }
    if($x == 1) $color_samstag = "#FFFFFF";
    else $color_samstag = "#FFFFFF";
  }else{
    if($tag[2][$i] == 0) $color_samstag = "#FFFFFF";
    elseif($tag[2][$i] > 0) $color_samstag = "#FFFFFF";
    else $color_samstag = "#FFFFFF";
  }

  if(strstr($tag[3][$i],",")){
    $bla = explode(",",$tag[3][$i]);
    $x=1;
    foreach($bla as $blubb){
      if($blubb == "-1") $x=0;
    }
    if($x == 1) $color_sonntag = "#FFFFFF";
    else $color_sonntag = "#FFFFFF";
  }else{
    if($tag[3][$i] == 0) $color_sonntag = "#FFFFFF";
    elseif($tag[3][$i] > 0) $color_sonntag = "#FFFFFF";
    else $color_sonntag = "#FFFFFF";
  }

  $output .="
  <tr>
    <td $class><b>".$std.":00 - ".$std1.":00</b></td>
    <td $class style='background-color: $color_freitag'>";

  if(strstr($tag[1][$i],",")){
    $bla = explode(",",$tag[1][$i]);
    $x=0;
    foreach($bla as $blubb){
      if($blubb == -1) $output .="<a href='index.php?plan=$plan&tag=1&std=$i&x=$x&a=add'>noch frei - hier klicken</a><br>";
      else{
        $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
        $output .="<div style='width: 100%; height: 100%; background-color: #FFFFFF'>";
        $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")<br>";

      
        $output .="</div>";
      }
      $x++;
    }
  }else{
    if($tag[1][$i] == -1) $output .="-- niemand --";
    elseif($tag[1][$i] == 0) $output .="-- niemand --";
    else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[1][$i]."' LIMIT 1");
      $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";
  
      
    }
  }

  $output .="</td>
    <td $class style='background-color: $color_samstag'>";

  if(strstr($tag[2][$i],",")){
    $bla = explode(",",$tag[2][$i]);
    $x=0;
    foreach($bla as $blubb){
      if($blubb == -1) $output .="-- niemand --";
      else{
        $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
        $output .="<div style='width: 100%; height: 100%; background-color: #FFFFFF'>";
        $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")<br>";

        
        $output .="</div>";
      }
      $x++;
    }
  }else{
    if($tag[2][$i] == -1) $output .="-- niemand --";
    elseif($tag[2][$i] == 0) $output .="-- niemand --";
    else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[2][$i]."' LIMIT 1");
      $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";
  
   
    }
  }
  
  $output .="</td>
    <td $class style='background-color: $color_sonntag'>";

  if(strstr($tag[3][$i],",")){
    $bla = explode(",",$tag[3][$i]);
    $x=0;
    foreach($bla as $blubb){
      if($blubb == -1) $output .="-- niemand --";
      else{
        $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
        $output .="<div style='width: 100%; height: 100%; background-color: #FFFFFF'>";
         $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")<br>";

       
        $output .="</div>";
      }
      $x++;
    }
  }else{
    if($tag[3][$i] == -1) $output .="-- niemand --";
    elseif($tag[3][$i] == 0) $output .="-- niemand --";
    else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[3][$i]."' LIMIT 1");
       $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";
  
      
    }
  }

  $output .="</td>";
  $output .="</tr>";
}

 $output .="</table>";
 //$output .="<input type='hidden' name='plan_name' value='".$plan."'>";
 //$output .="<input type='submit' name='commit' value='speichern'>";
 //$output .="</form>";

 $output .="</td>";
 $output .="</tr>";
 $output .="</table>";
// Maintable do not edit html below //

$PAGE->render($output);
?>