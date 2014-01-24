<?php
include_once("../../../../global.php");
require_once("../../functions.php");
require_once("../../config.php");
$PAGE->sitetitle = $PAGE->htmltitle = _("Gästeserver");
$event_id = $EVENT->next;
$event_name = $DB->query_one("SELECT name FROM events WHERE id = '$event_id' LIMIT 1");

if($ADMIN->check(ADMIN_USER)){
  if(!empty($_POST["submit"])){
    $betreff = $_POST["betreff"];
    $text = $_POST["text"];  
    $mail = $_POST["mail"];
  
    foreach($mail as $id){
      $row = $DB->query_first("SELECT s.ip, s.name, u.nick, u.email FROM event_server AS s, user AS u WHERE s.user_id = u.id AND s.id = '$id' LIMIT 1");
      $ip =    $row[0];
      $name =  $row[1];
      $nick =  $row[2];
      $email = $row[3];
  
      $nachricht = str_replace("<nick>",$nick,$text);
      $nachricht = str_replace("<ip>",$ip,$nachricht);
      $nachricht = str_replace("<name>",$name,$nachricht);
  
      mail($email,$betreff,$nachricht,"From: maxlan <info@maxlan.de>");
      $output .= "E-Mail gesendet an: ".$nick." &lt;".$email."&gt; - ".$name." - ".$ip."<br>";
  
      # Als verschickt markieren
      $DB->query("UPDATE event_server SET intern = CONCAT(intern,' Mail verschickt') WHERE id = '".$id."' LIMIT 1");
  
      unset($ip,$name,$nick,$email,$nachricht);
    }
    $output .= "<br><br>";
  }

  $output .= "<script>";
  $output .= "  function check_all(){";
  $output .= "    boxes = document.getElementsByTagName(\"input\");";
  $output .= "    if(document.getElementById('box_checkall').checked == true){ ";
  $output .= "      var change = true; ";
  $output .= "    } else { ";
  $output .= "      var change = false; ";
  $output .= "    } ";
  $output .= "    for(var i=0; i<boxes.length; i++){ ";
  $output .= "      if(boxes[i].id.search(\"^chk_\") >= 0){ ";
  $output .= "        boxes[i].checked = change; ";
  $output .= "      }";
  $output .= "    }";
  $output .= "  }";
  $output .= "</script>";
  
  $output .= "<form action='index.php' method='POST'>";
  $output .= "<table>";
  $output .= "  <tr>";
  $output .= "    <th class='forumhead'>&nbsp;</th>";
  $output .= "    <th class='forumhead' width='80'>IP</th>";
  $output .= "    <th class='forumhead' width='100'>Name</th>";
  $output .= "    <th class='forumhead' width='300'>User</th>";
  $output .= "    <th class='forumhead' width='90'>Freigeschaltet</th>";
  $output .= "    <th class='forumhead' width='200'>Interne Notiz</th>";
  $output .= "  </tr>";
  
  $query = $DB->query("SELECT s.ip, s.name, u.nick, u.email, s.active, s.id, s.intern FROM event_server AS s, user AS u WHERE s.user_id = u.id AND s.event_id = '".$event_id."' ORDER BY INET_ATON(s.ip)");
  
  while($row = $DB->fetch_array($query)){
    $output .= "<tr>";
    $output .= "  <td align='center'>".($row[4] == 1 ? "<input id='chk_".$row[5]."' type='checkbox' name='mail[]' value='".$row[5]."'>" : "")."</td>";
    $output .= "  <td>".$row[0]."</td>";
    $output .= "  <td>".$row[1]."</td>";
    $output .= "  <td>".$row[2]." &lt;".$row[3]."&gt;</td>";
    $output .= "  <td align='center'>".($row[4] == 1 ? "<font color='#00FF00'>ja</font>" : "<font color='#FF0000'>nein</font>")."</td>";
    $output .= "  <td>".$row[6]."</td>";
    $output .= "</tr>";
  }
  
  $output .= "</table>";
  $output .= "<input type='checkbox' id='box_checkall' onClick='check_all();'> Alle ausw&auml;hlen";
  $output .= "<br><br><br>";
  $output .= "<table>";
  $output .= "  <tr>";
  $output .= "    <th>Betreff</th>";
  $output .= "    <td><input type='text' name='betreff' size=50 value='".$event_name." :: Serversettings'></td>";
  $output .= "  </tr>";
  $output .= "  <tr>";
  $output .= "    <th>Mail:</th>";
  $output .= "    <td><textarea name=text cols=70 rows=20>";
  $output .= $gaesteserver_mail;
  $output .= "    </textarea></td>";
  $output .= "  </tr>";
  $output .= "  <tr>";
  $output .= "    <td colspan=2 align='center'><input type=submit name=submit value='senden'></td>";
  $output .= "  </tr>";
  $output .= "</table>";
  $output .= "</form>";
}

$PAGE->render($output);
?>
