<?php
########################################################################
# Maxlan Card Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2014 Jens Broens <jens@broens.de>                      #
#                                                                      #
# Version 0.1                                                          #
########################################################################

$MODUL_NAME = "card";
include("function_card.php");
//$output .= "TEST ".  $event_id;

include('header.php');

// <daten des Gesuchten Users>
	$u_id 		= security_number_int_input($_POST['team'],"","");
	$nick  		= security_string_input($_POST['nick']);
	$vorname  	= security_string_input($_POST['vorname']);
	$nachname  	= security_string_input($_POST['nachname']);
	$strasse  	= security_string_input($_POST['strasse']);
	$plz  		= security_number_int_input($_POST['plz'],"","");
	$ort  		= security_string_input($_POST['ort']);
	$geb  		= security_number_int_input($_POST['geb'],"","");
	$personr  	= security_number_int_input($_POST['personr'],"","");
//</daten des gesuchten Users>
$v_id 			= security_number_int_input($_POST['v_id'],"","");
$besitzer 		= security_string_input($_POST['besitzer']);
$id 			= security_number_int_input($_GET['id'],"","");
$bezeichnung 	= security_string_input($_POST['bezeichnung']);
$id_v_user		= security_number_int_input($_GET['v_id_user'],"","");
$kat			= security_string_input($_POST['kategorie']);
$kat1			= security_string_input($_POST['kategorie1']);
$iCount = 0;

if(!$DARF["create_cards"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$sql_user = $DB->query("SELECT * FROM user WHERE id = '".$u_id."'");



if ($_GET['action'] == "add"){

$output .= "
	<form name='team' ACTION='new_maxlan_card.php?action=addpic' METHOD=POST onSubmit='return checkSubmit()'>

	<table width='100%' cellspacing='0' cellpadding='0' border='0'>
	  <tr height='1'><td><img src='/images/pixel.gif' alt='' width='1' height='1' border='0' /></td></tr>
	  <tr valign='top'>
		<td class='rahmen_msg'><table cellspacing='0' cellpadding='0' border='0' width='100%'><tr><td class='rahmen_msg2' style='background-image:url('/styles/dotlan-net3/menubanner1.gif');'><table cellspacing='0' cellpadding='0' border='0' width='100%'><tr><td class='rahmen_msgtitle'>Benutzer Auswahl</td></tr></table></td></tr></table></td>
	  </tr>
	  <tr height='1'><td><img src='/images/pixel.gif' alt='' width='1' height='1' border='0' /></td></tr>
	  <tr valign='top'>
		<td class='rahmen_msg'><table cellspacing='1' cellpadding='3' class='msg2' width='100%'>
		  <tr class='msgrow1'>

			<td width='25%'><b>Benutzer:</b></td>
			<td width='75%'>
			  <div id='divsearch' style='width:100%; display:none;'>
				<table cellspacing='0' cellpadding='0' width='100%' border='0'>
				  <tr>
					<td width='100%'><input type='text' id='insearch' name='search' size='15' style='width:100%;'></td><td>&nbsp;</td>
					<td><input type='button' value='Suchen' onClick='javascript:searchUser();'></td>
				  </tr>

				</table>
			  </div>
			  <div id='divselect' style='width:100%; display:none;'>
				<table cellspacing='1' cellpadding='1' width='100%' border='0'>
				  <tr>
					<td width='100%'><select id='inselect' name='team' style='width:100%'></select></td><td>&nbsp;</td>
					<td><input type='button' value='X' onClick='javascript:clearSearch();'></td>
				 </tr>
			   </table>

			  </div>
			  <noscript>
				<b>Javascript is needed for UserSearch</b>
			  </noscript>
			</td>
		  </tr>
	   </table></td>
	  </tr>
	 <tr>
	 </tr>
	  <tr valign='top'>
		<td class='rahmen_msg'><table width='100%' cellspacing='1' cellpadding='3' class='msg2'>
		  <tr class='msgrow2'>
			<td colspan='2' align='center' width='100%'><br /><input type='submit' value=' W E I T E R ' />&nbsp;&nbsp;<input type='reset' value=' Zur&uuml;cksetzen ' /><br /><br /></td>
		  </tr>
		</table></td>
	  </tr>

	</table>
	</form>
	   ";

$output .= "
<iframe frameborder='0' style='width:0px; height:0px;' src='about:blank' id='operasucks'></iframe>
<script type='text/javascript' src='/user/xmlusersearch.js'></script>
<script type='text/javascript'>
<!--

// define variables needed by xmlusersearch.js
var inselect	= document.getElementById('inselect');
var divselect	= document.getElementById('divselect');
var insearch	= document.getElementById('insearch');
var divsearch	= document.getElementById('divsearch');
var xmllink	= '/user/?do=xmlsearch';

function checkSubmit()
{
    if(insearch.value != '') {
    	searchUser();
    	return false;
    }
    if(inselect.length == 0 || inselect.options[0].value == '' || inselect.options[0].value == '0') {
    	alert('Es wurde kein Benutzer gewaehlt');
    	return false;
    }
}

initUserSearch('0');

//-->
</script>

	";
}

if ($action == "addpic"){

$u_id 		= security_number_int_input($_POST['team'],"","");
$sql_user = $DB->query("SELECT * FROM user WHERE id = '".$u_id."'");
$output .= "<form name='team' ACTION='new_maxlan_card.php?action=uploadpic' METHOD=POST enctype='multipart/form-data'>";
while ($user = mysql_fetch_array($sql_user)){

	$output .='<h3>Benutzerdaten für Karte</h3>';
	$output .='<br><b>Benutzer:</b> '.$user['vorname'].' <i>"'.$user['nick'].'"</i> '.$user['nachname'];
	$output .='<br><b>User-ID:</b> '.sprintf("%04d",$user['id']);
	$output .='<br><b>Geburtstdatum:</b> '.birthday2german($user['geb']);
}
$output .= '<br><br><h3>Bild für Benutzer hochladen</h3>';
$output .= '<i>Format für Bild 2:3 Format (Breite:Höhe), Größe optimal: 257px (Breite) x 386px (Höhe), größere Bilder werden automatisch verkleinert.<br><br>';
$output .= '<INPUT ID="Forms Edit Field8" TYPE="file" NAME="upload" VALUE="" SIZE=100 class="register_editbox"><br><br>';
$output .= "<tr valign='top'><td class='rahmen_msg'><table width='100%' cellspacing='1' cellpadding='3' class='msg2'><tr class='msgrow2'><td colspan='2' align='center' width='100%'><br /><input type='submit' value='Bild hochladen' />&nbsp;&nbsp;<input type='reset' value=' Zur&uuml;cksetzen ' /><br /><br /></td></tr></table></td></tr>";
$output .= '<INPUT TYPE = "HIDDEN" NAME = "user" VALUE = "'.$u_id.'">';
$output .= '</form>';
}

if ($action == "uploadpic"){

$u_id 		= security_number_int_input($_POST['user'],"","");
$sql_user = $DB->query("SELECT * FROM user WHERE id = '".$u_id."'");
$file_name = upload_userpic($u_id);
$output .= "<form name='team' ACTION='new_maxlan_card.php?action=createcard' METHOD=POST >";
while ($user = mysql_fetch_array($sql_user)){

	$output .='<h3>Benutzerdaten für Karte</h3>';
	$output .='<br><b>Benutzer:</b> '.$user['vorname'].' <i>"'.$user['nick'].'"</i> '.$user['nachname'];
	$output .='<br><b>User-ID:</b> '.sprintf("%04d",$user['id']);
	$output .='<br><b>Geburtstdatum:</b> '.birthday2german($user['geb']);
}
$output .= '<br><br><h3>Bild für Benutzer</h3>';
$output .= '<img src ="./userpics/'.$file_name.'">';
$output .= "<tr valign='top'><td class='rahmen_msg'><table width='100%' cellspacing='1' cellpadding='3' class='msg2'><tr class='msgrow2'><td colspan='2' align='center' width='100%'><br /><input type='submit' value='Karte neu bestellen' />&nbsp;&nbsp;<br /><br /></td></tr></table></td></tr>";
$output .= '<INPUT TYPE = "HIDDEN" NAME = "user" VALUE = "'.$u_id.'">';
$output .= '<INPUT TYPE = "HIDDEN" NAME = "filename" VALUE = "'.$file_name.'">';
$output .= '</form>';
}

if ($action == "createcard"){

$u_id 		= security_number_int_input($_POST['user'],"","");
$filename   = mysql_real_escape_string($_POST['filename']);
$sql_user = $DB->query("SELECT * FROM user WHERE id = '".$u_id."'");

$lastorder = time();
$card_query = mysql_query("SELECT * FROM project_card WHERE user_ID = '{$u_id}'");
if (mysql_num_rows($card_query) == 0){
	$insert_card = mysql_query("INSERT INTO project_card SET user_ID = '{$u_id}', pic_hash = '{$filename}', last_order_date = '{$lastorder}', card_status = 0");
} else {
	$update_card = mysql_query("UPDATE project_card SET pic_hash = '{$filename}', last_order_date = '{$lastorder}', card_status = 0 WHERE user_ID = '{$u_id}'");
}
$output .='<h2>Karte wurde bestellt</h2><br><br>';
while ($user = mysql_fetch_array($sql_user)){

	$output .='<h3>Benutzerdaten für Karte</h3>';
	$output .='<br><b>Benutzer:</b> '.$user['vorname'].' <i>"'.$user['nick'].'"</i> '.$user['nachname'];
	$output .='<br><b>User-ID:</b> '.sprintf("%04d",$user['id']);
	$output .='<br><b>Geburtstdatum:</b> '.birthday2german($user['geb']);
}
$output .= '<br><br><h3>Bild für Benutzer</h3>';
$output .= '<img src ="./userpics/'.$filename.'">';
$output .= '<meta http-equiv="refresh" content="5; URL=index.php">';
$PAGE->render($output);
exit();
}

// $module_admin_check ENDE

$PAGE->render($output);
?>
