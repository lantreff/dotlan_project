<?
#########################################################################
# Kontakt-Verwaltungsmodul for dotlan                               	#
#                                                                      	#
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              	#
#                                                                      	#
# admin/kontakte/index.php                              				#
#########################################################################
$version = 'Version 1.4';
$dev_link = 'http://development.serious-networx.net/?page_id=15';

$iCounter = 0;

$MODUL_NAME = "kontakte";
include_once("../../../global.php");
include("../functions.php");


$PAGE->sitetitle = $PAGE->htmltitle = _("Kontakte");

$event_id		= $EVENT->next;			// ID des anstehenden Event's

// auslesen der einzelnen Werte die ¸ber die Adresszeile ¸bergeben werden
	$id				= $_GET['id'];
////////////////////////////////////////////////

// auslesen der Eingabefelder f¸r 'edit' | 'add' | 'usw...'
	$p_name			= security_string_input($_POST['p_name']);
	$p_vorname		= security_string_input($_POST['p_vorname']);
	$p_geb			= security_number_int_input($_POST['p_geb'],"","");
	$p_geschlecht	= security_string_input($_POST['p_geschlecht']);
	$p_email		= security_string_input($_POST['p_email']);
	$p_mobil		= security_number_int_input($_POST['p_mobil'],"","");
	$p_tel			= security_number_int_input($_POST['p_tel'],"","");
	$p_str			= security_string_input($_POST['p_str']);
	$p_hnr			= security_number_int_input($_POST['p_hnr'],"","");
	$p_plz			= security_number_int_input($_POST['p_plz'],"","");
	$p_ort			= security_string_input($_POST['p_ort']);
	$fa_name		= security_string_input($_POST['fa_name']);
	$fa_funktion	= security_string_input($_POST['fa_funktion']);
	$fa_email		= security_string_input($_POST['fa_email']);
	$fa_mobil		= security_number_int_input($_POST['fa_mobil'],"","");
	$fa_tel			= security_number_int_input($_POST['fa_tel'],"","");
	$fa_str			= security_string_input($_POST['fa_str']);
	$fa_hnr			= security_number_int_input($_POST['fa_hnr'],"","");
	$fa_plz			= security_number_int_input($_POST['fa_plz'],"","");
	$land			= security_string_input($_POST['land']);
	$fa_ort			= security_string_input($_POST['fa_ort']);
	$info			= security_string_input($_POST['info']);
	$fa_formular	= security_string_input($_POST['fa_formular']);
////////////////////////////////////////////////

// Sortierung //
// Variablen f¸r die Sortierfunktion
	$sort			= "p_name"; // Standardfeld das zum Sortieren genutzt wird
	$order			= "ASC"; // oder DESC | Sortierung aufwerts, abwerts

	if (IsSet ($_GET['sort'] ) )
	{
		$sort		= security_string_input($_GET['sort']);
	}
	if (IsSet ($_GET['order'] ) )
	{
		$order		= security_string_input($_GET['order']);
	}
////////////////////////////////////////////////


 /*###########################################################################################
Admin PAGE
*/


if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{

	if($DARF["view"])
	{ //$ADMIN

			$output .= "<a name='top' >

				<a href='/admin/projekt/'>Projekt</a>
				&raquo;
				<a href='/admin/projekt/kontakte'>Kontakte</a>
				&raquo; ".$_GET['action']."
				<hr class='newsline' width='100%' noshade=''>
				<br />

				";


		if($_GET['hide'] != 1) // solange die variable "hide" ungleich eins ist wird die Standardmaske angezeigt. Ist der Wert eins dann wird diese Maske ausgeblendet, um z.B. die Editmaske anzuzeigen oder um Meldungen auf der Seite auszugeben.
		{

			 if (IsSet ($_POST['suche'] ) )  // nur wenn im fled suchen etwas eingegeben wurde wird in den eingetragenen spalten gesucht. diese kˆnnen um noch weitere Erg‰nzt werden, dies kann einfach duch ein "OR" getrennt geschehen
			 {
				$sql_list_category = $DB->query("
													SELECT

														*
													FROM

														project_contact_contacts
													WHERE
														`p_name` 		LIKE  '%".$_POST['suche']."%' OR
														`p_vorname` 	LIKE  '%".$_POST['suche']."%' OR
														`fa_name` 		LIKE  '%".$_POST['suche']."%' OR
														`fa_funktion` 	LIKE  '%".$_POST['suche']."%'

												");
			}
			else
			{

				$sql_list_category = $DB->query("
													SELECT
														*
													FROM
														project_contact_contacts
													ORDER BY

														".$sort."
														".$order."


												");
			}
			
			if($DARF["add"])
			{
				$output .= "
					<table width='50%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
						<tbody>
							<tr class='shortbarrow'>
								<td width='24%' class='shortbarbit'><a href='?hide=1&action=add' class='shortbarlink'>Neu Anlegen</a></td>
							</tr>
						</tbody>
					</table>
						<hr>
					";
			}


			$output .= "
				<form name='suche' action='' method='POST'>
					<input name='suche'  style='width: 25%;' type='text' maxlength='50'>
					<input name='senden' value='Suchen' type='submit'>
				</form>


			<br>
			";
			$output .= "
				<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
					<tbody>
						<tr>
							<td width='70'    class='msghead' align='center'>";
							 if ( $_GET['order'] == "ASC" && $_GET['sort'] == "p_vorname"  )
								{
									$output .= "<b>Vorname</b> <a href='?sort=p_vorname&order=DESC' > <img src='/images/projekt/16/minisort2.gif' alt='Sortieren nach Vorname' border='0'/></a>";
								}
								else{
									$output .= "<b>Vorname</b> <a href='?sort=p_vorname&order=ASC' > <img src='/images/projekt/16/minisort.gif' alt='Sortieren nach Vorname' border='0'/> </a>";
									}

							$output .= "
							</td>
							<td width='80'  class='msghead' align='center'>
							";
							 if ( $_GET['order'] == "ASC"  && $_GET['sort'] == "p_name")
								{
									$output .= "<b>Nachname</b> <a href='?sort=p_name&order=DESC' > <img src='/images/projekt/16/minisort2.gif' alt='Sortieren nach Nachname' border='0'/> </a>";
								}
								else{
									$output .= "<b>Nachname</b> <a href='?sort=p_name&order=ASC' > <img src='/images/projekt/16/minisort.gif' alt='Sortieren nach Nachname' border='0'/> </a>";
									}

							$output .= "

							</td>
							<td   class='msghead' align='center'>
								";
							 if ( $_GET['order'] == "ASC"  && $_GET['sort'] == "fa_name")
								{
									$output .= "<b>Firma</b> <a href='?sort=fa_name&order=DESC' > <img src='/images/projekt/16/minisort2.gif' alt='Sortieren nach Firma' border='0'/> </a>";
								}
								else{
									$output .= "<b>Firma</b> <a href='?sort=fa_name&order=ASC' > <img src='/images/projekt/16/minisort.gif' alt='Sortieren nach Firma' border='0'/>  </a>";
									}

							$output .= "

							</td>
							<td  class='msghead' align='center'>
								";
							 if ( $_GET['order'] == "ASC"  && $_GET['sort'] == "fa_funktion")
								{
									$output .= "<b>Funktion</b> <a href='?sort=fa_funktion&order=DESC' > <img src='/images/projekt/16/minisort2.gif' alt='Sortieren nach Funktion' border='0'/> </a>";
								}
								else
								{
									$output .= "<b>Funktion</b> <a href='?sort=fa_funktion&order=ASC' > <img src='/images/projekt/16/minisort.gif' alt='Sortieren nach Funktion' border='0'/> </a>";
								}
							$output .= "
							</td>";
							if($DARF["edit"]  or $DARF["del"] )
							{ //  Admin
							$output .="
							<td width='40' class='msghead' align='center'>
								<b>admin</b>
							</td>";
							}
							$output .="
						</tr>";

					
					while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
					if($iCounter % 2 == 0)
						{
							$currentRowClass = "msgrow1";

						}
						else
						{
							$currentRowClass = "msgrow2";
						}
	
						$output .= "
						<tr class='".$currentRowClass."'>
							<td >
								".$out_list_category['p_vorname']."
							</td>
							<td>
								".$out_list_category['p_name']."
							</td>
							<td>
								".$out_list_category['fa_name']."
							</td>
							<td>
								".$out_list_category['fa_funktion']."
							</td>";
							if($DARF["edit"]  or $DARF["del"] )
							{
								$output .= "
								<td align='center'>";
									if($DARF["edit"] )
									{
										$output .= "
										<a href='?hide=1&action=edit&id=".$out_list_category['contactid']."' target='_parent'> <img src='/images/projekt/16/edit.png' title='Details anzeigen/&auml;ndern' > </a>
										";
									}
									if($DARF["del"] )
									{
										$output .= "
										<a href='?hide=1&action=del&id=".$out_list_category['contactid']."' target='_parent'> <img src='/images/projekt/16/editdelete.png' title='Kontakt lˆschen' > </a>
										";
									}
								$output .= "
								</td>";
							}
					$output .= "
						</tr>";
					$iCounter ++;
				}
				$output .= "
					</tbody>
				</table>

				<br />";
		}  // hide ende
		if($_GET['hide'] == "1")
		{
		/////////////////////////////////////////////// DEL ///////////////////////////////////////////////

			if($_GET['action'] == 'del')
			{
				if (!$DARF["del"] ) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

					if($_GET['comand'] == 'senden')

				{
					$del=$DB->query("DELETE FROM project_contact_contacts WHERE contactid = '".$_GET['id']."'");
					$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/kontakte/'>";
				}


				$new_id = $_GET['id'];
				$out_list_name = $DB->fetch_array($DB->query("SELECT * FROM project_contact_contacts WHERE contactid = '".$new_id."' LIMIT 1"));

			$output .="

						<h2 style='color:RED;'>Achtung!!!!<h2>
						<br />

						<p>Sind Sie sich sicher das

						<font style='color:RED;'>".$out_list_name['p_vorname']." ".$out_list_name['p_name']."</font>
						gel&ouml;scht werden soll? </p>

						<br />
						<a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>
						<input value='l&ouml;schen' type='button'></a>
						 \t
						<a href='/admin/projekt/kontakte/' target='_parent'>
						<input value='Zur&uuml;ck' type='button'></a>





					";




			}
		/////////////////////////////////////////////// ENDE DEL ///////////////////////////////////////////////

		/////////////////////////////////////////////// ADD / EDIT ///////////////////////////////////////////////

			if($_GET['action'] == 'add' or $_GET['action'] == 'edit' )
			{
				if ( (!$DARF["add"] and $_GET['action'] == 'add'  ) or (!$DARF["edit"] and $_GET['action'] == 'edit')) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

				if($_GET['action'] == 'edit'){
					$out_edit = $DB->fetch_array( $DB->query("SELECT * FROM project_contact_contacts WHERE contactid = ".$id."") );
				}

				if($_GET['action'] == 'add' and $_GET['comand'] == 'senden'){
					$insert=$DB->query
						("INSERT INTO

							`project_contact_contacts`
							(
								`contactid`,
								`p_name`,
								`p_vorname`,
								`p_geb`,
								`p_geb_tag`,
								`p_geb_monat`,
								`p_geb_jahr`,
								`p_geschlecht`,
								`p_email`,
								`p_mobil`,
								`p_tel`,
								`p_str`,
								`p_hnr`,
								`p_plz`,
								`p_ort`,
								`fa_name`,
								`fa_funktion`,
								`fa_email`,
								`fa_mobil`,
								`fa_tel`,
								`fa_str`,
								`fa_hnr`,
								`fa_plz`,
								`land`,
								`fa_ort`,
								`info`,
								`fa_formular`
							)

							VALUES
							(
								NULL,
								'".$p_name."',
								'".$p_vorname."',
								'".$p_geb."',
								'".$p_geb_tag."',
								'".$p_geb_monat."',
								'".$p_geb_jahr."',
								'".$p_geschlecht."',
								'".$p_email."',
								'".$p_mobil."',
								'".$p_tel."',
								'".$p_str."',
								'".$p_hnr."',
								'".$p_plz."',
								'".$p_ort."',
								'".$fa_name."',
								'".$fa_funktion."',
								'".$fa_email."',
								'".$fa_mobil."',
								'".$fa_tel."',
								'".$fa_str."',
								'".$fa_hnr."',
								'".$fa_plz."',
								'".$land."',
								'".$fa_ort."',
								'".$info."',
								'".$fa_formular."'
							)

						");
						if ($security_return == 1)
							{
								$output .= "<font style='color:RED; font-size:14px;'> Fehler bei der Verarbeitung einiger Variablen!!!</font>";
								$output .= "&nbsp; <BR>";
								//$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/kontakte/?hide=1&action=edit&id=".$id."'>";
							}
						else
							{
								$output .= "<font style='color:GREEN; font-size:14px;'>Daten wurden ge&auml;ndert</font>";
								$output .= "&nbsp; <BR>";
								$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/kontakte'>";
							}
					
				}
				if($_GET['action'] == 'edit' and $_GET['comand'] == 'senden'){

					$update =$DB->query
							("
								UPDATE
									`project_contact_contacts`
								SET
									`p_name` 		=   \"$p_name\",
									`p_vorname` 	=   \"$p_vorname\",
									`p_geb` 		=   \"$p_geb\",
									`p_geb_tag` 		=   \"$p_geb_tag\",
									`p_geb_monat` 		=   \"$p_geb_monat\",
									`p_geb_jahr` 		=   \"$p_geb_jahr\",
									`p_geschlecht` 	=   \"$p_geschlecht\",
									`p_email` 		=   \"$p_email\",
									`p_mobil` 		=   \"$p_mobil\",
									`p_tel` 		=   \"$p_tel\",
									`p_str`		 	=   \"$p_str\",
									`p_hnr` 		=   \"$p_hnr\",
									`p_plz` 		=   \"$p_plz\",
									`p_ort` 		=   \"$p_ort\",
									`fa_name`	 	=   \"$fa_name\",
									`fa_funktion` 	=   \"$fa_funktion\",
									`fa_email` 		=   \"$fa_email\",
									`fa_mobil` 		=   \"$fa_mobil\",
									`fa_tel` 		=   \"$fa_tel\",
									`fa_str` 		=   \"$fa_str\",
									`fa_hnr` 		=   \"$fa_hnr\",
									`fa_plz` 		=   \"$fa_plz\",
									`fa_ort` 		=   \"$fa_ort\",
									`land` 			=   \"$land\",
									`info` 			=   \"$info\",
									`fa_formular` 	=   \"$fa_formular\"
								WHERE
									`contactid` = \"$id\"
							");
							
							if ($security_return == 1)
							{
								$output .= "<font style='color:RED; font-size:14px;'> Fehler bei der Verarbeitung einiger Variablen!!!</font>"; 
								$output .= "&nbsp; <BR>";
								//$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/kontakte/?hide=1&action=edit&id=".$id."'>";
							}
							else
							{
								$output .= "<font style='color:GREEN; font-size:14px;'>Daten wurden ge&auml;ndert</font>";
								$output .= "&nbsp; <BR>";
								$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/kontakte/?hide=1&action=edit&id=".$id."'>";
							}
							//
				}

				if ($DARF["edit"]  or  $DARF["add"])
				{
					$output .="
							<form name='addkontakt' action='?hide=1&action=".$_GET['action']."&comand=senden&id=".$out_edit['contactid']."' method='POST'>
							";
				}
					$output .="
						<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>

								<tr>
								<td class='msghead'>
									<b>Kontaktdaten (Privat)</b>
								</td>
								</tr>

								<tr>
									<td valign='top'>
										<table  width='100%' cellspacing='1' cellpadding='2' border='0'>
											<tr >

												<td  width='150' >
													Vorname & Nachname:
												</td >
												<td  width='475'>

													<input name='p_vorname' style='width: 48%;' type='text' maxlength='50' value='".$out_edit['p_vorname']."' > <input name='p_name' style='width: 48%;' type='text' maxlength='50' value='".$out_edit['p_name']."' >

												</td>
											</tr>
											<tr>
												<td >
													Geburtsdatum:

												</td>
												<td>
												<!--	<input name='p_geb' style='width: 18%;' type='text' maxlength='10' value='".$out_edit['p_geb']."'> JJJJ-MM-TT -->

													<select name='p_geb_tag'>";
														for($date_laufer = 1; $date_laufer <= 31; $date_laufer ++)
														{
															if($date_laufer == $out_edit['p_geb_tag'])
															{
																$output .="<option selected>".$date_laufer."</option>";
															}
															else
															{
																$output .="<option>".$date_laufer."</option>";
															}
														}

										$output .="	</select>";

										$output .="	<select name='p_geb_monat'>";

														$date_monat  = array("FEHLER","Januar","Februar","M‰rz","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");

														for($date_laufer_a = 1; $date_laufer_a <= 12; $date_laufer_a ++)
														{
															if($date_laufer1 == $out_edit['p_geb_monat'])
															{
																$output .="		<option value='".$date_laufer_a."' selected>".$date_monat[$date_laufer_a]."</option>";
															}
															else
															{
																$output .="		<option value='".$date_laufer_a."'>".$date_monat[$date_laufer_a]."</option>";
															}
														}

										$output .="	</select>";

										$output .="	<input type='text' value='".$out_edit['p_geb_jahr']."' maxlength='4' size='4' name='p_geb_jahr'><span style='color:#ff0000;' class='small'></span>
												</td>

											</tr>
											<tr>
												<td  >
													Geschlecht:
												</td>
												<td>
												<select name='p_geschlecht' style='width: 18%;'>
													<option value='0'>----</option>";
												if($out_edit['p_geschlecht'] == 'M')
												{
												$output .="<option selected value='M'>m‰nnlich</option>
														   <option 			value='W'>weiblich</option>";
												}
												if($out_edit['p_geschlecht'] == 'W')
												{
												$output .="<option selected value='W'>weiblich</option>
														   <option 			value='M'>m‰nnlich</option>";
												}
												if($out_edit['p_geschlecht'] != 'W' and $out_edit['p_geschlecht'] != 'M')
												{
												$output .="
													<option value='M'>m‰nnlich</option>
													<option value='W'>weiblich</option>";
												}
												$output .="
												</select>
												</td>
											</tr>
											<tr style='height: 5px;'>

											</tr>
											<tr>
												<td  >
													E-Mail:
												</td>
												<td >

													<input name='p_email' style='width: 98%;' type='text' maxlength='50' value='".$out_edit['p_email']."'>
												</td>

											</tr>
											<tr >
												<td >
													Mobil:
												</td>
												<td >
													<input name='p_mobil'  style='width: 98%;' type='text' maxlength='50' value='".$out_edit['p_mobil']."'>
												</td>

											</tr>
											<tr >
												<td >
													Tel.:
												</td>

												<td >
													<input name='p_tel' style='width: 98%;' type='text' maxlength='50' value='".$out_edit['p_tel']."'>
												</td>

											</tr>
											<tr style='height: 5px;'>

											</tr>

											<tr >
												<td >
													Straﬂe & Hausnr.:
												</td>
												<td >

													<input name='p_str' style='width: 87%;' type='text' maxlength='50' value='".$out_edit['p_str']."'> <input name='p_hnr' style='width: 9%;' type='text' maxlength='5' value='".$out_edit['p_hnr']."'>
												</td>

											</tr>
											<tr >
												<td >
													PLZ & Ort:

												</td>
												<td >
													<input name='p_plz'  style='width: 9%;' type='text' maxlength='5' value='".$out_edit['p_plz']."'> <input name='p_ort'  style='width: 87%;' type='text' maxlength='50' value='".$out_edit['p_ort']."'>

												</td>
											</tr>
											<tr style='height: 5px;'>


											</tr>
										</table>
									</td>
								</tr>
								<tr>

								<td class='msghead'>
									<b>Kontaktdaten (Gesch&auml;ftlich)</b>
								</td>
								</tr>

									<tr>
									<td valign='top'>
										<table width='100%'>
											<tr >
												<td  width='150' >

													Firma:
												</td >
												<td  width='475'>

													<input name='fa_name' style='width: 98%;' type='text' maxlength='50' value='".$out_edit['fa_name']."'>
												</td>
											</tr>
											<tr>

												<td  >
													Funktion:
												</td>
												<td>

													<input name='fa_funktion' style='width: 98%;' type='text' maxlength='50' value='".$out_edit['fa_funktion']."'>
												</td>
											</tr>
											<tr style='height: 5px;'>


											</tr>
											<tr>
												<td  >
													E-Mail:

												</td>
												<td >
													<input name='fa_email'  style='width: 98%;' type='text' maxlength='50' value='".$out_edit['fa_email']."'>
												</td>

											</tr>
											<tr >
												<td >
													Mobil:

												</td>
												<td >
													<input name='fa_mobil'  style='width: 98%;' type='text' maxlength='50' value='".$out_edit['fa_mobil']."'>
												</td>

											</tr>
											<tr >
												<td >
													Tel.:

												</td>
												<td >
													<input name='fa_tel'   style='width: 98%;' type='text' maxlength='50' value='".$out_edit['fa_tel']."'>
												</td>

											</tr>
											<tr style='height: 5px;'>


											</tr>
											<tr >
												<td >
													Straﬂe & Hausnr.:
												</td>

												<td >
													<input name='fa_str' style='width: 87%;' type='text' maxlength='50' value='".$out_edit['fa_str']."'>  <input name='fa_hnr'   style='width: 9%;' type='text' maxlength='5' value='".$out_edit['fa_hnr']."'>
												</td>

											</tr>
											<tr >
												<td >
													PLZ & Ort:

												</td>
												<td >
													<input name='fa_plz'  style='width: 9%;' type='text' maxlength='5' value='".$out_edit['fa_plz']."'>  <input name='fa_ort'  style='width: 87%;' type='text' maxlength='50' value='".$out_edit['fa_ort']."'>

												</td>
											</tr>
											<tr >
												<td >
													Land:

												</td>
												<td >
													<select name='land' style='width: '>
															<option value='1'>w&auml;hlen</option>";
																$sql_list_land = $DB->query("SELECT * FROM project_countryTable ORDER BY name ASC");
																while($out_list_land = $DB->fetch_array($sql_list_land))
																{// begin while
																	if( $out_list_land['id'] == $out_edit['land'] )
																	{
																	$output .="
																			<option value='".$out_list_land['id']."' selected>
																			".$out_list_land['name']."
																			</option>";
																	}
																	else
																	{
																	
																		$output .="
																			<option value='".$out_list_land['id']."'>
																			".$out_list_land['name']."
																			</option>";
																	
																	}
																}
																	$output .="
													</select>
												</td>
											</tr>
											<tr style='height: 5px;'>


											</tr>
										</table>
									</td>
								</tr>

								<tr>
								<tr>
									<td class='msghead'>
										<b>Informationen:</b>
									</td>

									<td>

									</td>
								</tr>

								<tr>
									<td class='msgrow'>
										<textarea name='info' wrap='hard' rows='15' cols='30' style='width: 100%;' >".$out_edit['info']."</textarea>
									</td>


								</tr>
							</tbody>
						</table>";

					if ($DARF["edit"] or $DARF["add"])
					{
					$output .="
								<input name='senden' value='Daten senden' type='submit'>
								</form>";
					}
			}

		/////////////////////////////////////////////// ENDE ADD / EDIT ///////////////////////////////////////////////

		} // Hide = eins ENDE

	} // ENDE darf den Inhalt der Seite sehen

} // ADMIN ENDE
$PAGE->render(utf8_decode(utf8_encode($output) )); // Ausgabe des gesamten Seiten inhaltes ¸ber das Dotlan-System
?>
