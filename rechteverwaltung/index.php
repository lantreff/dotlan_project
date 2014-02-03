<?php
#########################################################################
# Rechte-Verwaltungsmodul for dotlan                                  #
#                                                                        #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>                #
#                                                                        #
# admin/Rechteverwaltung/index.php - Version 1.0                         #
#########################################################################
$MODUL_NAME = "rechteverwaltung";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Projekt Rechteverwaltung");

###########################################################################################
if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));  // Ist der angemeldete Benutzer Globaler Admin oder hat er über die Rechteverwaltung Berechtigungen dann darf er die Seite eehen sonst Error-Message.

$a = 'shortbarbit';
$a1 = 'shortbarlink';

$output .= "<a name='top' >
      <a href='/admin/projekt/'>Projekt</a>
      &raquo;
      <a href='/admin/projekt/rechteverwaltung'>Rechteverwaltung</a>
      &raquo; ".$_GET['action']."
      <hr class='newsline' width='100%' noshade=''>
      <br />";

if($DARF["add"] || $DARF["edit"]){
  $output .= "
<table  width='10%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
  <tbody>
    <tr class='shortbarrow'>";
  if($DARF["add"]){
    $output .= "<td width='".$breite."' class='".$a."'><a href='admin.php' class='".$a1."'>Bereiche verwalten</a></td>";
  }
}
$output .= "
    </tr>
  </tbody>
</table>
<br>";

if($_GET['hide'] != 1){ // solange die variable "hide" ungleich eins ist wird die Standardmaske angezeigt. Ist der Wert eins dann wird diese Maske ausgeblendet, um z.B. die Editmaske anzuzeigen oder um Meldungen auf der Seite auszugeben.
  $output .= "
            <table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
              <tbody>
                <tr>
                  <td width='50' class='msghead' align='center'><b>Vorname</b></td>
                  <td  width='100' class='msghead' align='center'><b>Name</b></td>
                  <td width='350'  class='msghead' align='center'><b>&Uuml;bersicht</b></td>";

  if($DARF["edit"] ){
    $output .="   <td width='20' class='msghead' align='center'><b>admin</b></td>";
  }
  $output .="   </tr>";

  $i = 0;
  $sql_list_orga = $DB->query("SELECT vorname, nachname, u.id AS id FROM user AS u, user_orga AS o WHERE o.user_id = u.id ORDER BY vorname");
  while($out_orga_data = $DB->fetch_array($sql_list_orga)){// begin while
    $output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
                  <td>".$out_orga_data['vorname']."</td>
                  <td>".$out_orga_data['nachname']."</td>";

    $sql_user_rechte_main = $DB->query("SELECT bereich, recht
                          FROM `project_rights_user_rights` AS `ur`
                          LEFT OUTER JOIN `project_rights_rights` AS `r` ON `r`.`id`=`ur`.`right_id`
                          WHERE `ur`.`user_id`= '".$out_orga_data['id']."'
                          ORDER BY bereich;");

    $output .= "<td align='center' title='";
    while($out_user_rechte_main = $DB->fetch_array($sql_user_rechte_main)){// begin while
      $output .=" Darf ".$out_user_rechte_main['bereich']." => ".$out_user_rechte_main['recht']." \n";
    }
    $output .= "'>Hier mit der Maus hin :-)</td>";

    if($DARF["edit"]){
      $output .= "<td align='center'><a href='?hide=1&action=edit&id=".$out_orga_data['id']."' target='_parent'> <img src='../images/16/edit.png' title='Details anzeigen/&auml;ndern' > </a></td>";
    }

    $output .= "</tr>";
    $i++;
  }
  $output .= "</tbody>
          </table>

          <br />";
}  // hide ende

if($_GET['hide'] == "1"){
  /////////////////////////////////////////////// EDIT ///////////////////////////////////////////////
  if($_GET['action'] == 'edit'){
    if (!$DARF["edit"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

    ####
    # Rechte wurden editiert und werden jetzt gespeichert ...
    ####
    if($_GET['action'] == 'edit' and $_GET['comand'] == 'senden'){
      $DB->query("DELETE FROM project_rights_user_rights WHERE user_id = '".$_GET["id"]."'");
      foreach($rechte as $r_id){
        $DB->query("INSERT INTO project_rights_user_rights SET user_id = '".$_GET["id"]."', right_id = '".$r_id."'");
      }
      $output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/'>";
    }

    ####
    # Auflistung der Rechte-Tabelle
    ####
    $orga = $DB->query_first("SELECT vorname, nachname, nick FROM user WHERE id = '".$_GET["id"]."' LIMIT 1");

    $output .= "
        <form action='?hide=1&action=edit&comand=senden&id=".$_GET["id"]."' method='POST'>
          <table class='msg2' border='0' cellpadding='2' width='100%' cellspacing='1'>
            <tbody>
              <tr>
                <td class='msghead'><b>Benutzerrechte</b></td>
              </tr>
              <tr>
                <td><b>".$orga["vorname"]." (".$orga["nick"].") ".$orga["nachname"]."</b></td>
              </tr>
              <tr>
                <td class='msghead'><b>Aktivierte Rechte:</b></td>
              </tr>
              <tr>
                <td>"; // Outer Table

    $output .= "
          <table border='0' cellpadding='0' width='100%' cellspacing='0'>
            <tbody>
              <tr valign='bottom'>
                <td class='msghead3'>Bereich</td>";

    // Kopfzeile
    $recht_to_spalte = array(); // Array zur Zuordnung der richtigen Spalte
    $query = $DB->query("SELECT recht FROM project_rights_rights GROUP BY recht");
    while($row = $DB->fetch_array($query)){
      $recht_to_spalte[] = $row["recht"];
      $recht = implode("<br>",str_split(strtoupper($row["recht"])));
      $output .= "<td class='msghead3' style='border-left: 1px solid #FFFFFF;' align='center' width='25'>".$recht."</td>";
    }
    $output .= "
              </tr>";

    // Alle Rechte einsammeln, die der User derzeit hat
    $alle_user_rechte = array();
    $query = $DB->query("SELECT right_id FROM project_rights_user_rights WHERE user_id = '".$_GET["id"]."'");
    while($row = $DB->fetch_array($query)) $alle_user_rechte[] = $row["right_id"];

    // Zeilen mit Bereichen und Rechten
    $i = 0;
    $query_bereich = $DB->query("SELECT bereich FROM project_rights_rights GROUP BY bereich");
    while($row_bereich = $DB->fetch_array($query_bereich)){
      $output .= "
              <tr class=\"msgrow".(($i%2)?1:2)."\">
                <td style='border-bottom: 1px solid #FFFFFF;'>".$row_bereich["bereich"]."</td>";

      $rechte = array();
      $query = $DB->query("SELECT id, recht FROM project_rights_rights WHERE bereich = '".$row_bereich["bereich"]."'");
      while($row = $DB->fetch_array($query)) $rechte[$row["id"]] = $row["recht"];

      foreach($recht_to_spalte as $recht){
        if(in_array($recht,$rechte)){
          $recht_id = array_search($recht,$rechte);

          if(in_array($recht_id,$alle_user_rechte)) $checked = "checked='checked'";
          else $checked = "";

          $output .= "<td class='msgrow' style='border-left: 1px solid #FFFFFF; border-bottom: 1px solid #FFFFFF;' align='center'>
                        <input name='rechte[]' value='".$recht_id."' ".$checked." type='checkbox' id='".$recht."_".$recht_id."'>
                      </td>";
        }else{
          $output .= "<td class='msgrow' style='border-left: 1px solid #FFFFFF; border-bottom: 1px solid #FFFFFF;' align='center'>&nbsp;</td>";
        }
      }
      $output .= "</tr>";
      $i++;
    }

    // Select all - Zeile
    $output .= "<script>
                  function select_all(recht){
                    var state = document.getElementById(recht).checked;
                    var boxes = document.getElementsByTagName('input');
                    var regex = new RegExp('^'+recht+'_');

                    for(var i=0; i<boxes.length; i++){
                      if(boxes[i].type == 'checkbox' && boxes[i].id.match(regex)){
                        boxes[i].checked = state;
                      }
                    }
                  }
                </script>";
    $output .= "<tr class='msgrow2'>
                  <td style='border-bottom: 1px solid #FFFFFF;'>&nbsp;</td>";
    foreach($recht_to_spalte as $recht){
      $output .= "<td class='msgrow' style='border-left: 1px solid #FFFFFF; border-bottom: 1px solid #FFFFFF;' align='center'>
                    <input type='checkbox' id='".$recht."' onClick='select_all(\"".$recht."\");'>
                  </td>";
    }
    $output .= "</tr>";

    $output .= "
            </tbody>
          </table>";

    $output .= "</td>
              </tr>
            </tbody>
          </table>
          <input name='senden' value='Rechte aktualisieren' type='submit'><br>
          <p><a href='/admin/projekt/rechteverwaltung/'>Zurück zur Übersicht</a></p>
        </form>"; // Outer Table
  }
}

$PAGE->render($output);
?>
