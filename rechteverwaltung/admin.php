<?
#########################################################################
# Rechte-Verwaltungsmodul for dotlan                                  #
#                                                                        #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>                #
#                                                                        #
#########################################################################
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Projekt Rechteverwaltung");
$event_id    = $EVENT->next;      // ID des anstehenden Event's

// auslesen der einzelnen Werte die über die Adresszeile übergeben werden
$id      = $_GET['id'];
$name    = security_number_int_input($_POST['name'],"","");
$rechte  = security_string_input($_POST['rechte']);
$bereich = !empty($_POST["bereich1"]) ? security_string_input($_POST['bereich1']) : security_string_input($_POST['bereich']);
////////////////////////////////////////////////

// Sortierung //
// Variablen für die Sortierfunktion
$sort       = "name"; // Standardfeld das zum Sortieren genutzt wird
$order      = "ASC"; // oder DESC | Sortierung aufwerts, abwerts

if (IsSet ($_GET['sort'])){
  $sort    = $_GET['sort'];
}
if (IsSet ($_GET['order'])){
  $order    = $_GET['order'];
}
$breite = "150";

###########################################################################################
/*
Admin PAGE
*/

if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_nopermission"));  

$a = 'shortbarbit';
$a1 = 'shortbarlink';

if($_GET['action'] == 'add'){
  $a = 'shortbarbitselect';
  $b = 'shortbarbit';
  $c = 'shortbarbit';
  $d = 'shortbarbit';

  $a1 = 'shortbarlinkselect';
  $b1 = 'shortbarlink';
  $c1 = 'shortbarlink';
  $d1 = 'shortbarlink';
}

if($DARF_PROJEKT_VIEW || $ADMIN->check(GLOBAL_ADMIN)){ //$ADMIN
  $output .= "<a name='top' >
      <a href='/admin/projekt/'>Administration</a>
      &raquo;
      <a href='/admin/projekt/rechteverwaltung'>Rechteverwaltung</a>
      &raquo; ".$_GET['action']."
      <hr class='newsline' width='100%' noshade=''>
      <br />";

  if($DARF_PROJEKT_ADD){
    $output .= "<table  width='10%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
      <tbody>
        <tr class='shortbarrow'>
          <td width='".$breite."' class='".$a."'><a href='?hide=1&action=add' class='".$a1."'>Recht Anlegen</a></td>
        </tr>
      </tbody>
    </table>
    <br>";
  }

  if($_GET['hide'] != 1){ // hide
    $output .="
        <table class='msg2' width='40%' cellspacing='1' cellpadding='2' border='0'>
          <tbody>
            <tr>
              <td class=\"msghead\">Bereich</td>
              <td class=\"msghead\">Rechte</td>";
    if($DARF_PROJEKT_EDIT) $output .= "<td class=\"msghead\">admin</td>";
    $output .= "</tr>";

    $sql_list_bereich = $DB->query("SELECT bereich FROM project_rights_rights GROUP BY bereich");

    $i = 0; 
    while($out_list_bereich = $DB->fetch_array($sql_list_bereich)){// begin while
      $output .= "
            <tr class=\"msgrow".(($i%2)?1:2)."\">
              <td valign='top'>".$out_list_bereich['bereich']."</td>
              <td valign='top' align='right'>";

      $sql_list_recht = $DB->query("SELECT * FROM project_rights_rights WHERE bereich = '".$out_list_bereich['bereich']."' ");
      while($out_list_recht = $DB->fetch_array($sql_list_recht)){// begin while
        $recht = $out_list_recht['recht'];
        $output .= $recht." <a href='?hide=1&action=del&id=".$out_list_recht['id']."' target='_parent'><img src='../images/16/editdelete.png' title='".$recht." l&ouml;schen'></a><br>";
      }
          
      $output .="</td>";
      
      if($DARF_PROJEKT_EDIT){
        $sql_list_recht_id = $DB->query_one("SELECT id FROM project_rights_rights WHERE bereich = '".$out_list_bereich['bereich']."' LIMIT 1");
        $output .= "<td width='5' valign='top' align='right'><a href='?hide=1&action=edit&id=".$sql_list_recht_id."' target='_parent'><img src='../images/16/edit.png' title='Recht &auml;ndern' ></a></td>";
      }
      $i++;
    }
    $output .= "
            </tr>
          </tbody>
        </table>";
  } // ENDE hide
} // ENDE darf den Inhalt der Seite sehen

if($_GET['hide'] == "1"){
  ##################
  # Recht loeschen
  ##################
  if($_GET['action'] == 'del'){
    if(!$DARF_PROJEKT_DEL) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

    if($_GET['comand'] == 'senden'){
      $DB->query("DELETE FROM project_rights_user_rights WHERE right_id = '".$_GET['id']."'");
      $DB->query("DELETE FROM project_rights_rights WHERE id = '".$_GET['id']."'");
      $output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/admin.php'>";
    }

    $new_id = $_GET['id'];
    $recht = $DB->query_one("SELECT recht FROM project_rights_rights WHERE id = '".$new_id."' LIMIT 1");
    $output .=" 
        <h2 style='color:RED;'>Achtung!!!!<h2>
        <br />

        <p>Sind Sie sicher?
        <br>
        Das Recht: 
        <font style='color:RED;'>".$recht[2]."</font> des Bereiches ".ucfirst($recht[1])." l&ouml;schen?</p>
        <br />
        <a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>
        <input value='l&ouml;schen' type='button'></a>
         \t
        <a href='/admin/projekt/rechteverwaltung/admin.php' target='_parent'>
        <input value='Zur&uuml;ck' type='button'></a>";
  }

  ##################
  # Recht hinzufuegen
  ##################
  if($_GET['action'] == 'add'){
    if(!$DARF_PROJEKT_ADD) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

    if($_GET['action'] == 'add' && $_GET['comand'] == 'senden'){
      $output .= "Daten wurden gesendet";
      $all_rights = $_POST['recht'];
      foreach($all_rights as &$a ) {
        $DB->query("INSERT INTO `project_rights_rights` (bereich, recht) VALUES ('".$bereich."', '".$a."')");
      }
      $output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/admin.php'>";
    }

    $output .= "
            <form id='formular' name='addip' action='?hide=1&action=add&comand=senden' method='POST' >
            <table id='dyntable' class='msg2' width='60%' cellspacing='1' cellpadding='2' border='0'>
              <tbody>
                <tr>
                  <td width='150' class='msghead'>Bereich</td>
                  <td width='150' class='msghead'>Recht</td>
                </tr>
                <tr class='msgrow1'>
                  <td valign='top'><select name='bereich'>
                    <option value='1'>w&auml;hlen</option>";

    $sql_list_bereich = $DB->query("SELECT bereich FROM project_rights_rights GROUP BY bereich ASC");
    while($out_list_bereich = $DB->fetch_array($sql_list_bereich)){// begin while
      $output .= "<option value='".$out_list_bereich['bereich']."'>".$out_list_bereich['bereich']."</option>";
    }

    $output .="
                    </select>
                    oder neu eintragen
                    <input name='bereich1' value='".$_GET['add_bereich']."' size='13' type='text' maxlength='25'>
                  </td>
                  <td id='recht_felder'>
                    <input name='recht[]' value='' type='text' maxlength='30'><br>
                    <a href='#' onClick='document.getElementById(\"recht_felder\").innerHTML = \"<input name=recht[] type=text maxlength=30><br>\"+document.getElementById(\"recht_felder\").innerHTML;'>Feld hinzufügen</a>
                  </td>
                </tr>
              </tbody>
            </table>
            <input name='senden' value='Daten senden' type='submit'> \t
            <br /><br /><a href='/admin/projekt/rechteverwaltung/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
            </form>";
  }

  ##################
  # Recht editieren
  ##################
  if($_GET['action'] == 'edit'){
    if (!$DARF_PROJEKT_EDIT) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

    if($_GET['action'] == 'edit' && $_GET['comand'] == 'senden'){
      $all_rights = $_POST['rechte'];
      $all_ids = $_POST['rechte_ids'];
      
      foreach($all_ids as $recht_id) {
        $DB->query("UPDATE project_rights_rights SET `bereich` = '".$bereich."', recht = '".$all_rights[$recht_id]."' WHERE `id` = '".$recht_id."'");
      }
      $output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/admin.php'>";
    }

    $out_edit_rechteverwaltung = $DB->fetch_array($DB->query("SELECT * FROM project_rights_rights WHERE id = ".$id.""));
    $output .= "
          <form name='editip' action='?hide=1&action=edit&comand=senden&id=".$id."' method='POST'>
          <table class='msg2' width='60%' cellspacing='1' cellpadding='2' border='0'>
            <tbody>
              <tr>
                <td width='150' class='msghead'>Bereich</td>
                <td width='150' class='msghead'>Recht</td>
              </tr>
              <tr class='msgrow1'>
                <td valign='top'><select name='bereich'>
                  <option value='1'>w&auml;hlen</option>";

    $sql_list_bereich = $DB->query("SELECT bereich FROM project_rights_rights GROUP BY bereich ASC");
    while($out_list_bereich = $DB->fetch_array($sql_list_bereich)){// begin while
      $output .="<option value='".$out_list_bereich['bereich']."'>".$out_list_bereich['bereich']."</option>";
    }

    $output .="   </select>
                  oder neu eintragen
                  <input name='bereich1' value='".$out_edit_rechteverwaltung['bereich']."' size='13' type='text' maxlength='25'>
                </td>
                <td>";

    $sql_list_rechte = $DB->query("SELECT * FROM project_rights_rights WHERE bereich = '".$out_edit_rechteverwaltung['bereich']."' ");
    while($out_list_rechte = $DB->fetch_array($sql_list_rechte)){// begin while
$recht = explode("_", $out_list_rechte['name']);
//        $output .="<input name='rechte[]' value='".$out_list_rechte['recht']."' type='text' maxlength='30'>
      $output .="<input name='rechte[".$out_list_rechte['id']."]' value='".$recht[2]."' type='text' maxlength='30'>
                 <a href='?hide=1&action=del&id=".$out_list_rechte['id']."' target='_parent'><img src='../images/16/editdelete.png' title='Recht l&ouml;schen'></a>
                 <br>
                 <input name='rechte_ids[]' value='".$out_list_rechte['id']."' type='hidden'>";
    }

    $output .="   <br>
                  <a href='?hide=1&action=add&add_bereich=".$out_edit_rechteverwaltung['bereich']."'><input type='button' value='Neues Recht hinzuf&uuml;gen' /></a>
                </td>
              </tr>
            </tbody>
          </table>

          <input name='senden' value='Daten senden' type='submit'> \t
          <br/><br/><a href='/admin/projekt/rechteverwaltung/admin.php' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
          </form>";
  }
}
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
