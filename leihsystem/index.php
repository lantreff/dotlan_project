<?php
#########################################################################
# Verleih Modul for dotlan                                 			   	#
#                                                                      	#
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              	#
#																		#
#########################################################################

$MODUL_NAME = "leihsystem";
include_once("../../../global.php");
include("../functions.php");
include("functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Leihsystem");
<<<<<<< HEAD
/*
if (isset($_POST['user_id']))
=======

//$data = $DB->query_first("SELECT * FROM user WHERE id = '".$user_id."'  LIMIT 1");
$leihID 	= $_GET['leihID'];
$id_leihe 	= $_POST['id_leihe'];
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


$rueck_a_ids 	= $_POST["rueck_a_ids"];
$rueck_g_ids	= $_POST["rueck_g_ids"];
if($_POST['kategorie'] == 1)
>>>>>>> refs/remotes/origin/master
{
$user_id = $_POST['user_id'];
}
elseif(isset($_GET['user_id']))
{
$user_id = $_GET['user_id'];
}
else
{
$user_id = $_GET['user_id_leihe'];
}
*/
/*###########################################################################################
Admin PAGE
*/


if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
 		if($DARF["view"])
		{ //$ADMIN

			include('header.php');
			
				
			if($_GET['hide'] != 1)
			{ // hide

				$output .= "

						<table class='msg'  width='100%' cellspacing='0' cellpadding='0' border='0'>
							<tbody>
								<tr valign='top'>
									<td width='50%'  class='msghead'>
										&nbsp;Vorhandene Artikel
									</td>
									<td width='50%' class='msghead'>
										&nbsp;Verliehene Artikel
									</td>

								</tr>

								<tr valign='top'>



									<!-- Noch da?? -->";


					$output .= "	<td style='border-right: solid 1px #C33333;border-top: solid 1px #C33333;'>

									<table width='100%' cellspacing='1' cellpadding='2' border='0' >
											<tbody>".
												
												leih_list_artikel().
												leih_list_gruppen();

												
						

						$output .= "	</tbody>
									</table>




								</td>";





						$output .= "					<!-- Wääg?? -->";


					$output .= "	<td style='border-top: solid 1px #C33333;'>

									<table  cellspacing='1' cellpadding='2' border='0' width='100%' >
											<tbody>".
												
												leih_list_verliehene_artikel($event_id).
												leih_list_verliehene_gruppen($event_id);

								


						$output .= "		</tbody>
									</table>




								</td>";


					$output .= "			</tr>


							</tbody>
							</table>
						";










		}  // hide ende


}
if($_GET['hide'] == "1")
{
	if($_GET['action'] == 'rueckgabe')
	{ // begin if($_GET['action']

			if($_POST["rueck_a_ids"]){	
				foreach($rueck_a_ids as $aid)
				{
					mysql_query(	"UPDATE project_equipment SET `ausleihe` = '0' WHERE `id` = ".$aid." ");
					mysql_query(	"UPDATE project_leih_leihe SET `rueckgabe_datum` = '".$datum."'  WHERE `id_leih_artikel` = ".$aid." " );
					$sql_group_data = mysql_query("SELECT * FROM project_equipment_equip_group WHERE id_equipment = '".$aid."'");
				$sql_group_data = mysql_query("SELECT * FROM project_equipment_equip_group WHERE id_equipment = '".$aid."'");
				while($out_group_data = mysql_fetch_array($sql_group_data))
				{// begin while
					mysql_query(	"UPDATE `project_equipment_groups` SET `ausleihe` = '0'  WHERE `id` = ".$out_group_data['id_group'].";" );
				}
				}
			}
			if($_POST["rueck_g_ids"]){
				foreach($rueck_g_ids as $gid)
				{
					mysql_query(	"UPDATE project_equipment_groups SET `ausleihe` = '0' WHERE `id` = ".$gid." ");
					mysql_query(	"UPDATE project_leih_leihe SET `rueckgabe_datum` = '".$datum."'  WHERE `id_leih_gruppe` = ".$gid." " );
				$sql_artikel_data = mysql_query("SELECT * FROM project_equipment_equip_group WHERE id_group = '".$gid."'");
				while($out_artikel_data = mysql_fetch_array($sql_artikel_data))
				{// begin while
					mysql_query(	"UPDATE `project_equipment` SET `ausleihe` = '0'  WHERE `id` = ".$out_artikel_data['id_equipment'].";" );
				}	
			
				}
			}
			$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."'>";
	} // end if($_GET['action']


	
	
	
	if($_GET['action'] == 'user_id_leihe')
	{
		// eingegebene ID des Benutezrs der Leihen oder zurückgeben will
		$eingabe_user_id = $_POST['user_id_leihe'];
		//$output .="1. Prüfen ob rückgabe oder neue Leihe!<BR>";
		$output .= leih_check_rueck_or_new($eingabe_user_id,$event_id);
		//Daten des Users Prüfen
		//$output .= leih_show_user_data($eingabe_user_id);
	}
	
	
	if($_GET['action'] == 'leihe')
	{	
		//$output .="3. Neue Leihe <BR>";
		//$output .="3.1 Daten des Users Speichern <BR>";
		if($_GET['add'] != 1)
		{
			$meldung = leih_save_user_data($_POST);
			$output .= leih_new_leihe($_POST['user_id'],$event_id,$dir);
		}
		//$output .="3.2 Artikel für Leihe Einlesen <BR>";
		
		if($_GET['add'] == 1)
		{	$id_art 	= preg_replace('![^0-9]!', '', 	$_POST['id_artikel']);
			$id_drop 	= preg_replace('![^0-9]!', '', 	$_POST['drop_id_artikel']);
			$id_grp		= preg_replace('![^0-9]!', '', 	$_POST['id_gruppe']);
			
			if(isset($_POST['id_artikel']) && $id_art > 0)
			{
				
				if(check_is_leihartikel($_POST) == TRUE)
				{	
					$meldung = leih_save_leih_data($_POST,$CURRENT_USER->id,$event_id,$datum);
					//$PAGE->redirect($dir."?hide=1&action=leihe&add=0&user_id=".$_POST['user_id'],$PAGE->sitetitle,$meldung);
				}
				else
				{
					$output .= "<h2 align='center' style='color:RED;'>1 Artikel ist kein Leihartikel, bereits verliehen oder bereits in der Leihliste!</h2>";
				}
			}
			if(isset($_POST['drop_id_artikel']) && $id_drop > 0)
			{
				
				if(check_is_leihartikel($_POST) == TRUE)
				{	
					$meldung = leih_save_leih_data($_POST,$CURRENT_USER->id,$event_id,$datum);
					//$PAGE->redirect($dir."?hide=1&action=leihe&add=0&user_id=".$_POST['user_id'],$PAGE->sitetitle,$meldung);
				}
				else
				{
					$output .= "<h2 align='center' style='color:RED;'>2 Artikel ist kein Leihartikel, bereits verliehen oder bereits in der Leihliste!</h2>";
				}
			}
			if(isset($_POST['id_gruppe']) && $id_grp > 0)
			{
			
				
				 if(check_is_gruppenartikel($_POST) == TRUE)
				{	
					$meldung = leih_save_leih_data($_POST,$CURRENT_USER->id,$event_id,$datum);
					//$PAGE->redirect($dir."?hide=1&action=leihe&add=0&user_id=".$user_id,$PAGE->sitetitle,$meldung);
				}
				else
				{
					$output .= "<h2 align='center' style='color:RED;'>3 Gruppe ist kein Leihartikel, bereits verliehen oder bereits in der Leihliste!</h2>";
				}
				
				
			}
			
			$output .= leih_new_leihe($_POST['user_id'],$event_id,$dir);
		}
	
		if($_GET['save'] == 1)
		{	
			//$output .="3.2 Artikel für Leihe in Leih liste eintragen <BR>";
			$meldung = leih_save_leih_data_final($_GET['user_id'],$CURRENT_USER->id,$event_id,$datum);
			$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);
		}
	}
	
	
	if($_GET['action'] == 'del')
	{
		if($_GET['comand'] == 'temp_leihe')
		{
			if($_GET['id_artikel'])
			{
				$sql_del = "DELETE FROM project_leih_leihe_temp WHERE id_leih_artikel = '".$_GET['id_artikel']."' AND id_leih_user = '".$_GET['user_id']."' AND event_id = '".$event_id."' AND rueckgabe_datum = '0000-00-00 00:00:00'"; 
			}
			if($_GET['id_gruppe'])
			{
				$sql_del = "DELETE FROM project_leih_leihe_temp WHERE id_leih_gruppe = '".$_GET['id_gruppe']."' AND id_leih_user = '".$_GET['user_id']."' AND event_id = '".$event_id."' AND rueckgabe_datum = '0000-00-00 00:00:00'"; 
			}
				
				
				mysql_query($sql_del);
				
				$output .= leih_new_leihe($_GET['user_id'],$event_id,$dir);
				//$PAGE->redirect($dir."?hide=1&action=leihe&add=0&user_id=".$_GET['user_id'],$PAGE->sitetitle,$meldung);
		}
	}
	

/*	if($_GET['action'] == 'NEW_Leihe')
	{

		$sql_user = mysql_query("SELECT * FROM user WHERE id = '".$u_id."'");


		if($_GET['comand'] == 'senden1')

		{ 
			$output .= "<meta http-equiv='refresh' content='0;  URL=export.php?e_id=".$id_user."&v_id=".$CURRENT_USER->id."' >";

		}
		if ($_GET['hide1'] != 1)
		{
		$output .= "
		<form method='post' name='team' action='?hide=1&hide1=1&action=NEW_Leihe' onSubmit='return checkSubmit()'>

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
		</form>
		</table>
		   ";
		} 
	   while($out_sql_user = mysql_fetch_array($sql_user))
	   {
		//$id_user = $out_sql_user['id'];;
		//echo "<br> User ID: ".$id_user;
		$output .= "
		<form method='post' name='leihe' onSubmit='Weiterleiten()' action='export.php' target='_blank' >

		<table cellspacing='1' cellpadding='1' width='100%' border='0'>
			<tr>
				<td><b>Nick:</b></td>
				<td><input type='text' name='nick' value='".$out_sql_user['nick']."' size='25'></td>
				<td><b>Vorname:</b></td>
				<td><input type='text' name='vorname' value='".$out_sql_user['vorname']."' size='25'></td>
				<td><b>Nachname:</b></td>
				<td><input type='text' name='nachname' value='".$out_sql_user['nachname']."' size='25'></td>
			</tr>
			<tr>
				<td><b>Strasse:</b></td>
				<td><input type='text' name='strasse' value='".$out_sql_user['strasse']."' size='25'></td>
				<td><b>PLZ:</b></td>
				<td><input type='text' name='plz' value='".$out_sql_user['plz']."' size='25'></td>
				<td><b>Ort:</b></td>
				<td><input type='text' name='ort' value='".$out_sql_user['wohnort']."' size='25'></td>
			</tr>
			<tr>
				<td><b>Geb.Dat:</b></td>
				<td><input type='text' name='geb' value='".$out_sql_user['geb']."' size='25'></td>
				<td><b>UserID:</b></td>
				<td><input type='text' name='userid' value='".$out_sql_user['id']."' size='25'></td>
			</tr>
			<tr>
			<td colspan='6'>&nbsp;
			</td>
		</table>


		<table width='100%' cellspacing='1' cellpadding='2' border='0'>
			<tbody>
				<tr>
					<td class='msghead'>
						<b>Artikel</b>
					</td>
					<td class='msghead'>
						<b>Artikel Gruppen</b>
					</td>
				</tr>
				<tr>
					<td width='50%'>
						<table width='100%' cellspacing='1' cellpadding='2' border='0'>
							<tbody>";

								while($out_nicht_leihe = mysql_fetch_array($sql_leihsystem_nicht_verliehen))
								{// begin while
								$output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
												<td width='100%'  class='shortbarbit_left'>
													<input type='checkbox' name='leih_ids[]' value='".$out_nicht_leihe['id']."' onclick='countChecks(this)'>".$out_nicht_leihe['bezeichnung']."
												</td>
											</tr>";
									$i++;
								} // end while

			$output .= "	</tbody>
						</table>
					</td>
					<td valign='top'>
					<table width='100%' cellspacing='1' cellpadding='2' border='0'>
							<tbody>";

								while($out_leih_groups = mysql_fetch_array($sql_leih_groups))
								{// begin while
								$output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
												<td width='100%'  class='shortbarbit_left'>
													<input type='checkbox' name='group_ids[]' value='".$out_leih_groups['eg_group_id']."' onclick='countChecks(this)'>".$out_leih_groups['eg_group_bezeichnung']." 
												</td>
											</tr>";
									$i++;
								} // end while

			$output .= "
						</tbody>
					</table>		
					</td>
				</tr>
			</tbody>
		</table>
				
		<input type='submit' value=' W E I T E R ' />
		</form>";

				$sql_leih_artikel = mysql_query("SELECT * FROM  project_leih_leihe AS l INNER JOIN project_equipment AS e ON l.id_leih_artikel = e.id WHERE l.id_leih_user = '".$u_id."'  AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
				if(mysql_num_rows($sql_leih_artikel) != 0)
				{
						$output .= "
						<br />
						<h3>Der User hat folgendes ausgeliehen<h3>
						<table  width='100%' cellspacing='1' cellpadding='2' border='0'>
												<tbody>";

						while($out_leih_user_artikel = mysql_fetch_array($sql_leih_artikel))
							{// begin while

							//$out_leih_user_artikel = mysql_fetch_array(mysql_query("SELECT * FROM project_leih_article WHERE id = ".$out_leih_user_artikel['id_article'].""));
							$output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
											<td  class='shortbarbit_left'>
												".$out_leih_user_artikel['bezeichnung']."
											</td>
										</tr>";
							$i++;
							} // end while

							$output .= "		</tbody>
										</table>";
				}



		}
			$output .= "
				</form>
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
	*/


}

/*###########################################################################################
ENDE Admin PAGE
*/

}
//$output.= "<meta http-equiv='refresh' content='60; URL=index.php' /> ";
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
