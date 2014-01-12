<?
#########################################################################
# Verleih Modul for dotlan                                 			   	#
#                                                                      	#
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              	#
#																		#
#########################################################################


include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Leihsystem");
//$EVENT->getevent($event_id);
$event_id = $EVENT->next;

//$data = $DB->query_first("SELECT * FROM user WHERE id = '".$user_id."'  LIMIT 1");
$leihID = $_GET['leihID'];
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

if($_POST['kategorie'] == 1)
{
	$kategorie = $kat1;

}
else
{
	$kategorie = $kat;

}

$id_user 	= $_POST['userid']; // id des Gesuchten users aus der DB

$sql_leihsystem = $DB->query("SELECT * FROM project_leih_article ORDER BY bezeichnung ASC");

$sql_leihsystem_nicht_verliehen = $DB->query("SELECT * FROM project_leih_article WHERE ausleihe != '1' ORDER BY  `kategorie` ,  `bezeichnung`  ASC");
$sql_leihsystem_verliehen = $DB->query("SELECT * FROM project_leih_article WHERE ausleihe = '1' ORDER BY u_id ASC");


 /*###########################################################################################
Admin PAGE
*/


if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

else
{
				$a = 'shortbarbit';
				$b = 'shortbarbit';
				$c = 'shortbarbit';
				$d = 'shortbarbit';


				$a1 = 'shortbarlink';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlink';
				$d1 = 'shortbarlink';

			if($_GET['action'] == 'add')
			{
				$a = 'shortbarbitselect';
				$b = 'shortbarbit';
				$c = 'shortbarbit';
				$d = 'shortbarbit';


				$a1 = 'shortbarlinkselect';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlink';
				$d1 = 'shortbarlink';


			}
			if($_GET['action'] == 'list_all')
			{
				$a = 'shortbarbit';
				$b = 'shortbarbitselect';
				$c = 'shortbarbit';
				$d = 'shortbarbit';


				$a1 = 'shortbarlink';
				$b1 = 'shortbarlinkselect';
				$c1 = 'shortbarlink';
				$d1 = 'shortbarlink';


			}
			if($_GET['action'] == 'NEW_Leihe')
			{
				$a = 'shortbarbit';
				$b = 'shortbarbit';
				$c = 'shortbarbitselect';
				$d = 'shortbarbit';


				$a1 = 'shortbarlink';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlinkselect';
				$d1 = 'shortbarlink';


			}
				if($_GET['action'] == 'rueckgabe')
			{
				$a = 'shortbarbit';
				$b = 'shortbarbit';
				$c = 'shortbarbit';
				$d = 'shortbarbitselect';


				$a1 = 'shortbarlink';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlink';
				$d1 = 'shortbarlinkselect';


			}



 		if($DARF_PROJEKT_VIEW)
		{ //$ADMIN

			$output .= "
				<a name='top' >
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='/admin/projekt/'>Projekt</a>
							&raquo;
						<a href='/admin/projekt/leihsystem'>Leihsystem</a>
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />";
					
				$output .= "
					<table width='100%' cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
							
							if($DARF_PROJEKT_ADD)
							{ //$ADMIN
							$output .= "
								<td width='20%' class='".$a."'><a href='?hide=1&action=add' class='".$a1."'>Artikel Anlegen</a></td>";
							}
							
							$output .= "
								<td width='20%' class='".$b."'><a href='?hide=1&action=list_all' class='".$b1."'>Artikel&uuml;bersicht</a></td>
								<td width='0' class='shortbarbitselect'>&nbsp;</td>
								<td width='20%' class='".$c."'><a href='?hide=1&action=NEW_Leihe' class='".$c1."'>Artikel verleihen</a></td>
								<td width='20%' class='".$d."'><a href='?hide=1&action=rueckgabe' class='".$d1."'>R&uuml;ckgabe</a></td>
								<td width='20%' class='shortbarbit'><a href='historie.php' class='shortbarlink'>Historie</a></td>
							</tr>
						</tbody>
					</table>
					<br />
				";
				
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


					$output .= "	<td style='border-right: solid 1px #99CC00;border-top: solid 1px #99CC00;'>

									<table width='100%' cellspacing='1' cellpadding='2' border='0' >
											<tbody>";


								while($out_nicht_leihe = $DB->fetch_array($sql_leihsystem_nicht_verliehen))
						{// begin while

								$output .= "<tr>
												<td  class=\"msgrow".(($i%2)?1:2)."\">
													".$out_nicht_leihe['bezeichnung']."
												</td>
											</tr>";

						$i++;

						} // end while

						$output .= "	</tbody>
									</table>




								</td>";





						$output .= "					<!-- Wääg?? -->";


					$output .= "	<td style='border-top: solid 1px #99CC00;'>

									<table  cellspacing='1' cellpadding='2' border='0'>
											<tbody>";

								while($out_leihe = $DB->fetch_array($sql_leihsystem_verliehen))
						{// begin while

						$out_user_artikel = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_leihe['u_id'].""));
						$output .= "<tr >
										<td width='50%'  class=\"msgrow".(($i%2)?1:2)."\" >
											".$out_leihe['bezeichnung']." an ".$out_user_artikel['nick']."
										</td>
									</tr>";
							$i++;
						} // end while


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
	if($_GET['action'] == 'del')

	{
			if($_GET['comand'] == 'senden')

		{
			$del=$DB->query("DELETE FROM project_leih_article WHERE id = '".$_GET['id']."'");
			$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/leihsystem/?hide=1&action=list_all'>";
		}

		 $new_id = $_GET['id'];
		 $out_list_name = $DB->fetch_array($DB->query("SELECT bezeichnung FROM project_leih_article WHERE id = '".$new_id."' LIMIT 1"));

	$output .="

				<h2 style='color:RED;'>Achtung!!!!<h2>
				<br />

				<p>Sind Sie sich sicher das
				<font style='color:RED;'>".$out_list_name['bezeichnung']."</font> gel&ouml;scht werden soll?</p>
				<br />
				<a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>
				<input value='l&ouml;schen' type='button'></a>
				 \t
				<a href='/admin/projekt/leihsystem/?hide=1&action=list_all' target='_parent'>
				<input value='Zur&uuml;ck' type='button'></a>




			";



	}

	if($_GET['action'] == 'add')
	{
		if($_GET['comand'] == 'senden')

		{
		$insert = $DB->query("INSERT INTO `project_leih_article` (`id`, `bezeichnung`,`v_id`, `kategorie`, `besitzer`) VALUES (NULL, '".$bezeichnung."', '".$besitzer."', '".$kategorie."', '".$besitzer."');");



		$output .= "Daten wurden gesendet";
		$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/leihsystem/'>
			";
		}

		$output .= "
				<form name='addartikle' action='?hide=1&action=add&comand=senden' method='POST'>
						<table width='100%' class='msg2' cellspacing='1' cellpadding='2' border='0'>
							<tbody>
								<tr class='shortbarrow'>
									<td class='shortbarbit_left_big'>
										Artikelbezeichnung
									</td>
									<td class='shortbarbit_left_big'>
										Besitzer
									</td>
									<td class='shortbarbit_left_big'>
										Kategorie
									</td>
								</tr>
								<tr class='shortbarrow'>
									<td  class='shortbarbit_left'>
										<input name='bezeichnung' value='' size='20' type='text' maxlength='100'>
									</td>
									<td  class='shortbarbit_left'>
										<input name='besitzer' value='' size='20' type='text' maxlength='100'>
									</td>
									<td  class='shortbarbit_left'>
									<select name='kategorie'>
									<option value='1'>w&auml;hlen</option>";

									$sql_list_category = $DB->query("SELECT kategorie FROM project_leih_article GROUP BY kategorie ASC");
						while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
									$output .="

									<option value='".$out_list_category['kategorie']."'>".$out_list_category['kategorie']."</option>";
					}

						$output .="
									</select>
									<br />
									oder neu eintragen
									<br />
									<input name='kategorie1' value='' size='20' type='text' maxlength='100'>
								</td>
							</tr>
						</tbody>
					</table>
								<input name='senden' value='Daten senden' type='submit'>
								<a href='/admin/projekt/leihsystem/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
			</form>";
	}



	if($_GET['action'] == 'edit')
	{
		$sql_edit_artikel = $DB->query("SELECT * FROM project_leih_article WHERE id = ".$id."");

		if($_GET['comand'] == 'senden')

		{

		$update=$DB->query(	"UPDATE project_leih_article SET `bezeichnung` = '".$bezeichnung."', `kategorie` = '".$kategorie."', `besitzer` = '".$besitzer."' WHERE `id` = ".$id.";");

		$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/leihsystem/?hide=1&action=list_all'>";

		}

			while($out_edit_artikel = $DB->fetch_array($sql_edit_artikel))
			{// begin while

			$output .= "<br />
				<form name='editartikle' action='?hide=1&action=edit&comand=senden&id=".$id."' method='POST'>
					<table width='100%' class='msg2' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
							<tr class='shortbarrow'>
								<td class='shortbarbit_left_big'>
									Artikelbezeichnung
								</td>
								<td class='shortbarbit_left_big'>
										Besitzer
								</td>
								<td class='shortbarbit_left_big'>
									Kategorie
								</td>
							</tr>
							<tr class='shortbarrow'>
								<td  class='shortbarbit_left'>
									<input name='bezeichnung' value='".$out_edit_artikel['bezeichnung']."' size='20' type='text' maxlength='100'>
								</td>
								<td  class='shortbarbit_left'>
									<input name='besitzer' value='".$out_edit_artikel['besitzer']."' size='30' type='text' maxlength='100'>
								</td>
								<td  class='shortbarbit_left'>

									<select name='kategorie'>
									<option value='1'>w&auml;hlen</option>";

									$sql_list_category = $DB->query("SELECT kategorie FROM project_leih_article GROUP BY kategorie ASC");
						while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
									$output .="

									<option value='".$out_list_category['kategorie']."'>".$out_list_category['kategorie']."</option>";
					}

						$output .="
									</select>
									<br />
									oder neu eintragen
									<br />
									<input name='kategorie1' value='".$out_edit_artikel['kategorie']."' size='20' type='text' maxlength='100'>
								</td>
							</tr>
						</tbody>
					</table>

				<input name='senden' value='Daten senden' type='submit'>\t
				<a href='?hide=1&action=del&id=".$id."' target='_parent'>
				<input value='l&ouml;schen' type='button'></a>  * !!!!!Achtung sofortiges L&ouml;schen!!! <br /><br />
					<a href='/admin/projekt/leihsystem/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
			</form>";
		} // end if($_GET['action']



	}




	if($_GET['action'] == 'rueckgabe')
	{ // begin if($_GET['action']

	 $sql_list_uID = $DB->query("SELECT u_id FROM project_leih_article WHERE u_id > 0 GROUP BY u_id");


		if($_GET['comand'] == 'senden')

		{ // begin if($_GET['comand']

		//$del_leih_user = $DB->query("DELETE FROM `project_leih_user` WHERE id = '".$id."' ");

		$anz_rueck_ids = count($rueck_ids);

		for($a=0;$a<$anz_rueck_ids;$a++)
		{
			$update=$DB->query(	"UPDATE project_leih_article SET `ausleihe` = '0', `u_id` = 'NULL' WHERE `id` = ".$rueck_ids[$a]." ");
			$update_leihe=$DB->query(	"UPDATE project_leih_leihe SET `rueckgabe_datum` = '".$datum."'  WHERE `id_leih_artikel` = ".$rueck_ids[$a]." " );
	
		}
		$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/leihsystem/'>";


		} // end if($_GET['comand']



					while($out_list_uID = $DB->fetch_array($sql_list_uID))
					{// begin while

				$out_username  = $DB->fetch_array($DB->query("SELECT * FROM project_leih_user WHERE id = '".$out_list_uID['u_id']."'"));

					$count = count($out_list_uID);

					$output .= "

			<h1>".$out_username['nick']."</h1>";

			$sql_list_article = $DB->query("SELECT * FROM project_leih_article WHERE u_id = '".$out_list_uID['u_id']."'");

			$output .= "
			<form name='".$out_username['nick']."' action='?hide=1&action=rueckgabe&comand=senden&id=".$out_username['id']."' method='POST'>
			<table class='msg' width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
							<tr>
								<td width='375' class='msghead'>
									Bezeichnung
								</td>
								<td class='msghead'>
									Kategorie
								</td>
								<td width='100' class='msghead'>
									Zur&uuml;ck
								</td>
							</tr>";



			while($out_list_article = $DB->fetch_array($sql_list_article))
					{// begin while
			$output .= "

							<tr class=\"msgrow".(($i%2)?1:2)."\">
								<td class='shortbarbit_left'>
									".$out_list_article['bezeichnung']."
								</td>
								<td class='shortbarbit_left'>
									".$out_list_article['kategorie']."
								</td>
								<td class='shortbarbit_left'>
									<input type='checkbox' name='rueck_ids[]' value='".$out_list_article['id']."'>
								</td>
							</tr>";
					$i++;

					} // end while


					$output .= "
				</tbody>
					</table>
					<input name='senden' value='Zur&uuml;ckgeben' type='submit'>
					</form>
					
					<br />
					<br />";
					} // end while



	} // end if($_GET['action']








	if($_GET['action'] == 'list_all')
	{ // begin if($_GET['action']

	  $sql_list = $DB->query("SELECT kategorie FROM project_leih_article GROUP BY kategorie");


		if($_GET['comand'] == 'senden')

		{ // begin if($_GET['comand']


		} // end if($_GET['comand']


					while($out_list = $DB->fetch_array($sql_list))
					{// begin while
						$count = count($out_list);
				$output .= "

			<h1>".$out_list['kategorie']."</h1>";

			$sql_list_kategorie = $DB->query("SELECT * FROM project_leih_article WHERE kategorie = '".$out_list['kategorie']."'");

			$output .= "
			<table width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
							<tr class='shortbarrow'>
								<td width='375' class='shortbarbit_left_big'>
									Bezeichnung
								</td>
								<td class='shortbarbit_left_big'>
									Besitzer
								</td>";
					if($DARF_PROJEKT_ADD || $DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL)
						{ //$ADMIN

						$output .= "
								<td width='50' class='shortbarbit_left_big'>
									admin
								</td>";
						}
						$output .="
							</tr>";



			while($out_list_kategorie = $DB->fetch_array($sql_list_kategorie))
					{// begin while

					$sql_list_besitzer = $DB->fetch_array( $DB->query("SELECT * FROM user WHERE id = '".$out_list_kategorie['besitzer']."'"));
			$output .= "

							<tr class=\"msgrow".(($i%2)?1:2)."\">
								<td class='shortbarbit_left'>
									".$out_list_kategorie['bezeichnung']."
								</td>
								<td class='shortbarbit_left'>
									".$out_list_kategorie['besitzer']."
								</td>";
						if($DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL)
						{ //$ADMIN

						$output .= "
								<td class='shortbarbit_left'>
								";
									if($DARF_PROJEKT_EDIT)
								{ //$ADMIN
						$output .= "
									<a href='?hide=1&action=edit&id=".$out_list_kategorie['id']."' target='_parent'>
									<img src='/images/projekt/16/edit.png' title='Artikel editieren' ></a>
								";
								}
								if($DARF_PROJEKT_DEL)
								{ //$ADMIN
						$output .= "
									<a href='?hide=1&action=del&id=".$out_list_kategorie['id']."' target='_parent'>
									<img src='/images/projekt/16/editdelete.png' title='Artikel löschen' ></a>
								";
								}
						$output .= "	
								</td>
							</tr>";
						}
						$i++;
					} // end while


					$output .= "
				</tbody>
					</table>";
					} // end while



	} // end if($_GET['action']




	if($_GET['action'] == 'NEW_Leihe')
	{

		$sql_user = $DB->query("SELECT * FROM user WHERE id = '".$u_id."'");


	if($_GET['comand'] == 'senden1')

	{ 
		$output .= "<meta http-equiv='refresh' content='0;  URL=export.php?e_id=".$id_user."&v_id=".$CURRENT_USER->id."' >";

	}
	
	$output .= "
	<form method='post' name='team' action='?hide=1&action=NEW_Leihe' onSubmit='return checkSubmit()'>

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
	   
   while($out_sql_user = $DB->fetch_array($sql_user))
   {
   		//$id_user = $out_sql_user['id'];;

//echo "<br> User ID: ".$id_user;
    $output .= "
	<form method='post' name='leihe' onSubmit='Weiterleiten()' action='export.php?leihID=".$out_sql_user['id']."&v_id=".$CURRENT_USER->id."' target='_blank' >

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
		<td colspan='6'>Artikel
		</td>
	</table>


	<table width='100%' cellspacing='1' cellpadding='2' border='0'>
											<tbody>";

								while($out_nicht_leihe = $DB->fetch_array($sql_leihsystem_nicht_verliehen))
						{// begin while
						$output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
										<td width='100%'  class='shortbarbit_left'>
											<input type='checkbox' name='leih_ids[]' value='".$out_nicht_leihe['id']."' onclick='countChecks(this)'>".$out_nicht_leihe['bezeichnung']."
										</td>
									</tr>";
							$i++;
						} // end while

						$output .= "		</tbody>
									</table>
									
							<input type='submit' value=' W E I T E R ' />
							</form>";

			$sql_leih_artikel = $DB->query("SELECT * FROM project_leih_article WHERE u_id = ".$u_id."");

					$output .= "
					<br />
					<h3>Der User hat folgendes ausgeliehen<h3>
					<table  width='100%' cellspacing='1' cellpadding='2' border='0'>
											<tbody>";

					while($out_leih_user_artikel = $DB->fetch_array($sql_leih_artikel))
						{// begin while

						//$out_leih_user_artikel = $DB->fetch_array($DB->query("SELECT * FROM project_leih_article WHERE id = ".$out_leih_user_artikel['id_article'].""));
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


}

/*###########################################################################################
ENDE Admin PAGE
*/

}
$output.= "<meta http-equiv='refresh' content='60; URL=index.php' /> ";
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>