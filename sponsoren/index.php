<?php
########################################################################
# Sponsorenverwaltung Modul for dotlan                                 #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <c.egbers@servious-networx.net>  #
#                                                                      #
# admin/sponsoren/index.php					                           #
########################################################################

$MODUL_NAME = "sponsoren";
include_once("../../../global.php");
include("../functions.php");
include("sponsoren_functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Sponsorenverwaltung"); // Tietel der als THML-Überschrift der Seite angezeigt wird

//$event_id		= $EVENT->next;			// ID des anstehenden Event's
$date 			= date("Y.m.d");
$time			= date("H:i:s");

// auslesen der einzelnen Werte die über die Adresszeile übergeben werden
	$id				= $_GET['id'];
	$sponsor		= $_GET['sponsor'];
	$n_id 			= $_GET['n_id'];
////////////////////////////////////////////////

// auslesen der Eingabefelder für 'edit' | 'add' | 'usw...'
	$name 			= security_string_input($_POST['name']);
	$str			= security_string_input($_POST['str']);
	$hnr			= security_number_int_input($_POST['hnr'],"","");
	$plz			= security_number_int_input($_POST['plz'],"","");
	$ort			= security_string_input($_POST['ort']);
	$homepage		= security_string_input($_POST['homepage']);
	$email			= security_string_input($_POST['email']);
	$tel			= security_number_int_input($_POST['tel'],"","");
	$land			= security_number_int_input($_POST['land'],"","");
	$marke			= security_string_input($_POST['marke']);
	$formular		= security_string_input($_POST['formular']);
	$status			= security_string_input($_POST['status']);
	$comment		= security_string_input($_POST['comment']);
	$add_kontakt_id = security_number_int_input($_POST['add_kontakt_id'],"","");
////////////////////////////////////////////////

// Sponsoren Artikel auslesen
	$sp_art_anz		= security_number_int_input($_POST['sp_art_anz'],"","");
	$sp_art_name	= security_string_input($_POST['sp_art_name']);
	$sp_art_wert	= security_number_int_input(str_replace(',','.',$_POST['sp_art_wert']),"","");
	$sp_art_marke	= security_string_input($_POST['sp_art_marke']);
////////////////////////////////////////////////

// Sponsoren ToDo's auslesen
	$sp_todo_todo	= security_string_input($_POST['sp_todo_todo']);
////////////////////////////////////////////////

// Sortierung //
// Variablen für die Sortierfunktion
	$sort			= "name"; // Standardfeld das zum Sortieren genutzt wird
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

	function date_mysql2german($date1) {
    	$d    =    explode("-",$date1);
	return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}

if (isset($_POST['event']))
{
$selectet_event_id = $_POST['event'];
}
elseif(isset($_GET['event']))
{
$selectet_event_id = $_GET['event'];
}
else
{
$selectet_event_id = $event_id;
}
 /*###########################################################################################
Admin PAGE
*/


if(!$DARF["view"]) $PAGE->error_die(html::template("error_nopermission"));

else
{
		$a = 'shortbarbit';
		$a1 = 'shortbarlink';

		if($_GET['action'] == 'add')
			{
				$a = 'shortbarbitselect';

				$a1 = 'shortbarlinkselect';


			}



	if($DARF["view"])
	{ //$ADMIN

			$output .= "<a name='top' >
				<a href='".$global['project_path']."'>Projekt</a>
				&raquo;
				<a href='index.php'>Sponsoren</a>
				&raquo; ".$_GET['action']."
				<hr class='newsline' width='100%' noshade=''>
				<br />";

			if($DARF["add"])
			{
	$output .= "
				<table width='100%' cellspacing='1' cellpadding='2' border='0'>
	<tr>
		<td>

				<table width='50%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
				  <tbody>
						<tr class='shortbarrow'>
							<td width='25%' class='".$a."'><a href='?hide=1&action=add' class='".$a1."'>Sponsor Anlegen</a></td>
							<td width='2%' class='shortbarbitselect'>&nbsp;</td>
							<td width='24%' class='shortbarbit'><a href='export.php' target='_new' class='shortbarlink'>export</a></td>
						
						
						</tr>
					</tbody>
				</table>
			</td>
			<td>
				
				<table cellspacing='1' cellpadding='2' border='0' align='right'>
				<tr>
				<td align='right'>";

				$sql_event_ids 				= list_events();
				$out_historie_event			= list_event_data($selectet_event_id);//mysql_fetch_array(mysql_query("SELECT * FROM events WHERE id = ".$selectet_event_id.""));						

				$output .= "<form name='change_event' action='' method='POST'>				
								<select name='event' onChange='document.change_event.submit()'>
									<option value='1'>w&auml;hle das Event !</option>";
								while($out_event_ids = mysql_fetch_array($sql_event_ids))
								{// begin While Historie
									if	($out_event_ids['id'] == $selectet_event_id)
									{
						$output .= "					
									<option selected value='".$out_event_ids['id']."'>".$out_event_ids['name']."</option>";
									
									}else
									{
									
					$output .= "					
									<option value='".$out_event_ids['id']."'>".$out_event_ids['name']."</option>";
									}
								}
								
				$output .= "									
							</select>
									<!-- <input name='senden' value='Event wechseln' type='submit'> -->
							</form>
						</td>
						</tr>
					</table>
				
				
						
					</td>
				</table>
				<hr>

				";
			}


		if($_GET['hide'] != 1) // solange die variable "hide" ungleich eins ist wird die Standardmaske angezeigt. Ist der Wert eins dann wird diese Maske ausgeblendet, um z.B. die Editmaske anzuzeigen oder um Meldungen auf der Seite auszugeben.
		{ // hide


			if (IsSet ($_POST['suche'] ) ) // nur wenn im fled suchen etwas eingegeben wurde wird in den eingetragenen spalten gesucht. diese können um noch weitere Ergänzt werden, dies kann einfach duch ein "OR" getrennt geschehen
			 {
				$sql_sponsor = mysql_query("
													SELECT

														*
													FROM

														project_sponsoren
													WHERE
														`name` 		LIKE  '%".$_POST['suche']."%' OR
														`homepage` 	LIKE  '%".$_POST['suche']."%' OR
														`email` 	LIKE  '%".$_POST['suche']."%' OR
														`formular` 	LIKE  '%".$_POST['suche']."%'

												");
				}
				else {

				$sql_sponsor = mysql_query("
													SELECT
														*
													FROM
														project_sponsoren
													ORDER BY

														".$sort."
														".$order."


												");
				}


			$output .= "
			<form name='suche' action='' method='POST'>
				<input name='suche'  style='width: 25%;' type='text'>
				<input name='senden' value='Suchen' type='submit'>
			</form>

			<br>
					<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
							<tbody>
								<tr >
									<td   class='msghead' align='center'>
										";
											 if ( $_GET['order'] == "ASC" && $_GET['sort'] == "name" )
												{
													$output .= "<b>Sponsor</b> <a href='?sort=name&order=DESC' > <img src='../images/16/minisort2.gif' alt='Sortieren nach Sponsor' border='0'/> </a>";
												}
												else{
													$output .= "<b>Sponsor</b> <a href='?sort=name&order=ASC' > <img src='../images/16/minisort.gif' alt='Sortieren nach Sponsor' border='0'/> </a>";
													}

											$output .= "
									</td>
									<td   class='msghead' align='center'>
											<b>Ansprechpartner</b>
									</td>
									<td   class='msghead' align='center'>
											<b>Status</b>
									</td>
									<td   class='msghead' align='center'>
											<b>Warenwert</b>
									</td>
									<td   class='msghead' align='center'>
										";
											 if ( $_GET['order'] == "ASC"  && $_GET['sort'] == "admin" )
												{
													$output .= "<b>Verantwortlicher</b> <a href='?sort=admin&order=DESC' > <img src='../images/16/minisort2.gif' alt='Sortieren nach Verantwortlicher' border='0'/> </a>";
												}
												else{
													$output .= "<b>Verantwortlicher</b> <a href='?sort=admin&order=ASC' > <img src='../images/16/minisort.gif' alt='Sortieren nach Verantwortlicher' border='0'/> </a>";
													}

											$output .= "
									</td>
									";
						if($DARF["edit"] || $DARF["del"])
						{ //  Admin
							$output .="
								<td width='50' class='msghead' align='center'>
									<b>admin</b>
								</td>";
						}
						$output .="
							</tr>";






/********************************************************************************************************/
/*								Output DB Querry */



			$iCount = 0;
			while($out = mysql_fetch_array($sql_sponsor))
			{// begin while
			
							if($iCount % 2 == 0)
							{
								$currentRowClass = "msgrow2";
								$farbe = $styles[$style]['msgrow2'];

							}
							else
							{
								$currentRowClass = "msgrow1";
								$farbe = $styles[$style]['msgrow1'];
							}
							$out_Apartnet = 
								 mysql_fetch_array(
													mysql_query("
																	SELECT
																			*
																	FROM
																		project_contact_contacts
																	WHERE
																		sponsor_id = '".$out['id']."'
																	LIMIT
																		1;
																")
												);

				
								$output .= "
								<tr  VALIGN=TOP ";
								$output .= ' onclick="document.location = \'?hide=1&action=show&id='.$out['id'].'&event='.$selectet_event_id.'\' ";  ';
								$output .= ' onmouseover="this.style.background=\''.$styles[$style]['msg_over'].'\'; this.style.cursor=\'pointer\';" ';
								$output .= ' onmouseout="this.style.background=\''.$farbe.'\'" ';
								$output .= ' title="Klicken um Details des Artikels anzuzeigen" class="'.$currentRowClass.'">';
						$output .= "
									<td  >
									<a name='".$out['name']."'>
										<a target='_blank' href='".$out['homepage']."'>".$out['name']."</a>
										<br>
										
										
										";
										
							if($out['marke'] != NULL)
							{
								$teile = explode(",", $out['marke']);
								foreach($teile as $list_marke)
									{
																				
										$output .= "&nbsp;".$list_marke." <br>";
									}	
							}
$output .="										
									
									</td>
									<td  >
									<a>
										<a href='mailto:".$out_Apartnet['fa_email']."'>".$out_Apartnet['p_vorname']." ".$out_Apartnet['p_name']."</a>
									</td>
									<td align='center'>";
									$sql_status_main = mysql_query("
																		SELECT
																				*
																		FROM
																			project_sponsoren_stats
																		WHERE
																			s_id = '".$out['id']."'
																			AND
																			event_id = '".$selectet_event_id."'
																		ORDER BY
																			date DESC,
																			time DESC
																		LIMIT
																			1;
																	");
										if( mysql_num_rows($sql_status_main) != 0)
										{


											while($out_status_main = mysql_fetch_array($sql_status_main))
															{// begin while
															$out_stats_user = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = '".$out_status_main['u_id']."' "));
																$out_stat_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$out_status_main['s_id']."' "));

																$status_text = wordwrap( $out_status_main['comment'], 100, '<br>', true );
																
																$bg_color ="'FFFFFF";
																
																if($out_status_main['status'] ==  'Absage')
																	{
																		$bg_color = "#FF0000";
																	}
																if($out_status_main['status'] ==  'keine Antwort')
																	{
																		$bg_color = "#FF0000";
																	}
																if($out_status_main['status'] ==  'Zusage')
																	{
																		$bg_color = "#90EE90";
																	}
																if($out_status_main['status'] ==  'Zurückgestellt')
																	{
																		$bg_color = "#8B4513";
																	}
																if($out_status_main['status'] ==  'undef')
																	{
																		$bg_color = "#FFFFFF";
																	}
																if($out_status_main['status'] ==  'News geschrieben')
																	{
																		$bg_color = "#00FF00";
																	}
																if($out_status_main['status'] ==  'Soll Geschenk bekommen')
																	{
																		$bg_color = "#ADD8E6";
																	}
																if($out_status_main['status'] ==  'Hat Geschenk bekommen')
																	{
																		$bg_color = "#0000CD";
																	}	
																if($out_status_main['status'] ==  'Angeschrieben')
																	{
																		$bg_color = "#FFA500";
																	}
																if($out_status_main['status'] ==  'Hat Bericht bekommen')
																	{
																		$bg_color = "#8B008B";
																	}
																if($out_status_main['status'] ==  'Ware erhalten')
																	{
																		$bg_color = "#088A08";
																	}																	

																$output .= " <p style='margin-top: 0px; margin-bottom: 0px; height:18px; width:18px;  background-color: ".$bg_color."' 
																				title='".$out_status_main['status']." : ".$out_status_main['comment']." - ".$out_stats_user['vorname']." ".$out_stats_user['nachname']." (".$out_stats_user['nick'].") - ".date_mysql2german($out_status_main['date'])." - ".$out_status_main['time']." Uhr'
																				alt='".$out_status_main['status']." : ".$out_status_main['comment']." - ".$out_stats_user['vorname']." ".$out_stats_user['nachname']." (".$out_stats_user['nick'].") - ".date_mysql2german($out_status_main['date'])." - ".$out_status_main['time']." Uhr'
																			></p>
";

															}



										}


						$output .= "
									</td>									
									<td align='right'>
									";
								if($out['marke'] != '')
								{
								$i = 0;
								$a = 1;	
								$teile1 = explode(",", $out['marke']);
								foreach($teile1 as $list_marke)
									{ 									
										$sql_warenwert = mysql_query("SELECT * FROM project_sponsoren_artikel WHERE ( s_id = '".$out['id']."' AND sp_art_marke = '".trim($list_marke," ")."') AND event_id = '".$selectet_event_id."' ");
										//$sql_warenwert = mysql_query("SELECT * FROM project_sponsoren_artikel WHERE s_id = '".$out['id']."' AND sp_art_marke = '".$list_marke."' AND event_id = '".$selectet_event_id."' ");
									if( mysql_num_rows($sql_warenwert) != 0)
										{
												$output .= "	<table  width='100%' border='0'>
																<tbody>";
															
											while($out_sponsor_warenwert = mysql_fetch_array($sql_warenwert))
											{ 
												$warenwert[$out['id']][$i] = $warenwert[$out['id']][$i] + ( $out_sponsor_warenwert['sp_art_anz'] * $out_sponsor_warenwert['sp_art_wert']) ;
												
											}
											if($warenwert[$out['id']][$i] > 0)
											{	
												$euro = number_format($warenwert[$out['id']][$i], 2, ',', '.');
												
												$output .= "		<tr class=\"msgrow".(($a%2)?1:2)."\">
																		<td align='left'>".ucfirst($list_marke)."</td>
																		<td width='25%' align='right'>".$euro." Euro </td>
																	</tr>";
												
											}
											$gesamt[$out['id']] =  $gesamt[$out['id']] + $warenwert[$out['id']][$i];
											
									$output .= "				</tbody>
															</table>";
										}
									
									
									
										$i++;
										$a++;
									}
								}
									$sql_warenwert1 = mysql_query("SELECT * FROM project_sponsoren_artikel WHERE s_id = ".$out['id']." AND sp_art_marke = '' AND event_id = ".$selectet_event_id." ");
									if( mysql_num_rows($sql_warenwert1) != 0)
										{ 
											$output .= "<table  width='100%'  border='0'>
																<tbody>";
											$b = 1;					
											while($out_sponsor_warenwert1 = mysql_fetch_array($sql_warenwert1))
											{
												$warenwert1[$out['id']] = ( $out_sponsor_warenwert1['sp_art_wert'] * $out_sponsor_warenwert1['sp_art_anz']) ;

												if($warenwert1[$out['id']] > 0)
												{	
													$euro1 = number_format($warenwert1[$out['id']], 2, ',', '.');
													//$output .= "".ucfirst($out_sponsor_warenwert1['sp_art_name'])." ".$euro1." Euro<br>";
													$output .= "	<tr class=\"msgrow".(($b%2)?1:2)."\">
																		<td align='left'>".ucfirst($out_sponsor_warenwert1['sp_art_name'])."</td>
																		<td width='25%' align='right'>".$euro1." Euro </td>
																	</tr>";
	
												}
												$gesamt[$out['id']] = ( $gesamt[$out['id']] + $warenwert1[$out['id']]);
												$b++;
											}
											$output .= "				</tbody>
															</table>";
											
											
											
										}
									
									if($gesamt[$out['id']]){
										
										$gesamt_euro = number_format($gesamt[$out['id']], 2, ',', '.');
										$output .= "<hr>Gesamt: ".$gesamt_euro." Euro<br>";
									}
								$output .= "
									</td>

									</td>
									<td align='right'>";
									$sql_admin_z = mysql_query("SELECT * FROM user WHERE id = '".$out['admin']."' ");
									while ($out_admin_z = mysql_fetch_array($sql_admin_z))
									{
										$output .= " ".$out_admin_z['vorname']." (".$out_admin_z['nick'].") ".$out_admin_z['nachname']."";
									}
										$output .= "
									</td>
									";
						if($DARF["edit"] || $DARF["del"])
						{ // Admin
							$output .="
									<td align='center'>";
							if( $DARF["edit"] )
							{ // EDIT
							$output .="<a href='?hide=1&action=edit&id=".$out['id']."&event=".$selectet_event_id."' target='_parent'>
										<img src='{BASEDIR}images/icons/pencil.png' title='Details anzeigen/&auml;ndern' ></a>";
							}
							if( $DARF["del"])
							{ // DEL
							$output .="<a href='?hide=1&action=del&id=".$out['id']."' target='_parent'>
										<img src='{BASEDIR}images/icons/delete.png' title='L&ouml;schen'></a>";
							}
							$output .= "</td>";
						}
						$output .= "</tr>";
				$iCount ++;
			} // end while





/********************************************************************************************************/




	$output .= "
					</tbody>
					</table>
				";



		}  // hide !=1 ende
		if($_GET['hide'] == "1")
		{
/////////////////////////////////////////////// DEL ///////////////////////////////////////////////
			if($_GET['action'] == 'del')
			{
				if (!$DARF["del"]) $PAGE->error_die(html::template("error_nopermission"));

				if($_GET['comand'] == 'senden')
				{
					$del01=mysql_query("DELETE FROM project_sponsoren WHERE id = '".$_GET['id']."'");
					$del02=mysql_query("DELETE FROM project_sponsoren_stats WHERE s_id = '".$_GET['id']."'");
					$del03=mysql_query("DELETE FROM project_sponsoren_artikel WHERE s_id = '".$_GET['id']."'");

					$sql_contact_id = mysql_query("SELECT * FROM project_contact_contacts WHERE sponsor_id = '".$_GET['id']."' LIMIT 1");

					while($out_contact_id = mysql_fetch_array( $sql_contact_id ))
					{
						$contact_id = $out_contact_id['contactid'];
					$update=mysql_query("
											UPDATE
												project_contact_contacts
											SET
												`sponsor_id` =  \"0\"
											WHERE
												`contactid` = \"$contact_id\"
										");

					}

					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."'>";
				}

				 $new_id = $_GET['id'];
				  $out_list_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$new_id."' LIMIT 1"));
					$output .="
						<h2 style='color:RED;'>Achtung!!!!<h2>
						<br />

						<p>Sind Sie sich sicher das
						<font style='color:RED;'>".$out_list_name['name']."</font> gel&ouml;scht werden soll?</p>
						<br />
						<a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>";



			$output .="
						<input value='L&ouml;schen' type='button'></a>
						 \t
						<a href='/admin/projekt/sponsoren/' target='_parent'>
						<input value='Zur&uuml;ck' type='button'></a>

					";



			}

		/////////////////////////////////////////////// ENDE DEL ///////////////////////////////////////////////

		/////////////////////////////////////////////// DEL_NAME ///////////////////////////////////////////////
			if($_GET['action'] == 'del_name')
			{
				if (!$DARF["del"]) $PAGE->error_die(html::template("error_nopermission"));

					if($_GET['comand'] == 'senden')

				{
					$s_id = $_GET['n_id'];
					$new_id = $_GET['id'];
					$update=mysql_query("
											UPDATE
												project_contact_contacts
											SET
												`sponsor_id` =  \"0\"
											WHERE
												`contactid` = \"$new_id\"
										");
					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$s_id."'>";
				}

					$new_id = $_GET['id'];

				$out_list_name = mysql_fetch_array(mysql_query("SELECT * FROM project_contact_contacts WHERE contactid = '".$new_id."' LIMIT 1"));
				$s_id = $out_list_name['sponsor_id'];

				$output .="
							<h2 style='color:RED;'>Achtung!!!!<h2>
							<br />

							<p>Sind Sie sich sicher das
								<font style='color:RED;'>".$out_list_name['p_vorname']." ".$out_list_name['p_name']."</font> vom Sponsor  entfernt werden soll!
							</p>
							<br />
							<a href='?hide=1&action=del_name&comand=senden&id=".$new_id."&n_id=".$s_id."' target='_parent'>
							<input value='L&ouml;schen' type='button'></a>
							 \t
							<a href='/admin/projekt/sponsoren/?hide=1&action=show&id=".$s_id."' target='_parent'>
							<input value='Zur&uuml;ck' type='button'></a>

						";



			}

		/////////////////////////////////////////////// ENDE DEL_NAME ///////////////////////////////////////////////

		/////////////////////////////////////////////// ADD / EDIT ///////////////////////////////////////////////

			if($_GET['action'] == 'add' || $_GET['action'] == 'edit' )
			{
				if ( (!$DARF["add"] && $_GET['action'] == 'add'  ) || (!$DARF["edit"] && $_GET['action'] == 'edit')) $PAGE->error_die(html::template("error_nopermission"));

				if($_GET['action'] == 'edit')
					{
						$out_edit = mysql_fetch_array(
														mysql_query
															("
																SELECT
																	*
																FROM
																	`project_sponsoren`
																WHERE
																	id = ".$id."
															")
													);
					}

					if($_GET['action'] == 'add' &&  $_GET['comand'] == 'senden')
						{

							$insert=mysql_query	("
													INSERT INTO
														`project_sponsoren`
															(
																id,
																name,
																str,
																hnr,
																plz,
																ort,
																kommentar,
																homepage,
																wert,
																admin,
																email,
																tel,
																formular,
																land,
																marke
															)
													VALUES
														(
															NULL,
															'".$name."',
															'".$str."',
															'".$hnr."',
															'".$plz."',
															'".$ort."',
															'".$kommentar."',
															'".$homepage."',
															'".$wert."',
															'".$admin."',
															'".$email."',
															'".$tel."',
															'".$formular."',
															'".$land."',
															'".$marke."'
														);"
												);
							$output .= "Daten wurden gesendet";
							$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."'>";
						}
					if($_GET['action'] == 'edit' && $_GET['comand'] == 'senden')
						{
							$id = $_GET['id'];

							$update =mysql_query
									("
										UPDATE
											`project_sponsoren`
										SET
											`name` 			=   \"$name\",
											`str` 			=   \"$str\",
											`hnr` 			=   \"$hnr\",
											`plz` 			=   \"$plz\",
											`ort` 			=   \"$ort\",
											`kommentar` 	=   \"$kommentar\",
											`homepage` 		=   \"$homepage\",
											`wert`		 	=   \"$wert\",
											`admin` 		=   \"$admin\",
											`email` 		=   \"$email\",
											`tel` 			=   \"$tel\",
											`formular` 		=   \"$formular\",
											`land` 			=   \"$land\",
											`marke`	 		=   \"$marke\"
										WHERE
											`id` = \"$id\";
									");
							$output .= "Daten wurden ge&auml;ndert";
							$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=edit&id=".$out_edit['id']."'>";
						}

						$output .= "
					<form name='".$_GET['action']."sponsor' action='?hide=1&action=".$_GET['action']."&comand=senden&id=".$out_edit['id']."'' method='POST'>
							<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
									<tbody>
										<tr class='msgrow1' valign='top'>
											<td  class='msghead' colspan='2'>
												<table width='100%'>
													<tbody>
														<tr>
															<td>
																<b>Sponsorendaten</b>
															</td>
															<td align='right'>
																<b>Verantwortlicher:<b> \t
																<select name='admin' style='width:200px;'>
																				<option value='0'>----</option>";
																					$sql_list_verantw = mysql_query("SELECT * FROM user_orga");
																					while($out_list_verantw = mysql_fetch_array($sql_list_verantw))
																					{// begin

																						$out_list_user = mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$out_list_verantw['user_id']." ") );
																						if($_GET['action'] == 'edit')
																						{
																							$out_user_selected = mysql_fetch_array( mysql_query("SELECT * FROM project_sponsoren WHERE id = ".$out_edit['id']." ") );
																						}
																							if( $out_list_user['id'] ==   $out_user_selected['admin'])
																							{


																							$output .= "<option selected value='".$out_list_user['id']."'>".$out_list_user['vorname']." '".$out_list_user['nick']."' ".$out_list_user['nachname']."</option>";

																							}
																							else
																							{
																							$output .= "
																										<option value='".$out_list_user['id']."'>".$out_list_user['vorname']." '".$out_list_user['nick']."' ".$out_list_user['nachname']."</option>";
																							}


																					}
																						$output .= "
																</select>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr class='msgrow1' valign='top'>
											<td colspan='2'>
												<table width='100%'>
													<tbody>
														<tr>
															<td width='30%'>
																Firma:
															</td>
															<td width='70%'>
																<input name='name' style='width: 100%;' type='text'  value='".$out_edit['name']."'>
															</td>
														</tr>
														<tr style='height: 5px;'>

														</tr>
														<tr >
															<td width='30%'>
																E-Mail:
															</td>
															<td width='70%'>
																<input name='email' style='width: 100%;' type='text' value='".$out_edit['email']."'>
															</td>
														</tr>
														<tr >
															<td width='30%'>
																Tel.:
															</td>

															<td width='70%'>
																<input name='tel' style='width: 100%;' type='text' value='".$out_edit['tel']."'>
															</td>

														</tr>
														<tr style='height: 5px;'>

														</tr>

														<tr >
															<td width='30%'>
																Straße & Hausnr.:
															</td>
															<td width='70%'>
																<table width='100%' cellspacing='0' cellpadding='0' border='0'>
																	<tbody>
																		<tr>
																			<td width='100%'>
																				<input type='text' value='".$out_edit['str']."' style='width:100%' size='36' name='str'>
																			</td>
																			<td>&nbsp;</td>
																			<td>
																			<input type='text' value='".$out_edit['hnr']."' name='hnr' size='5'>
																			</td>
																			
																		</tr>
																	</tbody>
																</table>
															</td>

														</tr>
														<tr >
															<td width='30%'>
																PLZ & Ort:

															</td>
															<td width='70%'>
																<table width='100%' cellspacing='0' cellpadding='0' border='0'>
																	<tbody>
																		<tr>
																			<td>
																			<input type='text' value='".$out_edit['plz']."' name='plz' size='5'>
																			</td>
																			<td>&nbsp;</td>
																			<td width='100%'>
																				<input type='text' value='".$out_edit['ort']."' style='width:100%' size='36' name='ott'>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr >
															<td width='30%'>
																Land:

															</td>
															<td width='70%'>																
															<select name='land' style='width:100%'>
																	<option value='1'>w&auml;hlen</option>";
																		$sql_list_land = mysql_query("SELECT * FROM project_countryTable ORDER BY name ASC");
																		while($out_list_land = mysql_fetch_array($sql_list_land))
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
														<tr >
															<td width='30%'>
																Marke:
															</td>
															<td width='70%'>
																<input name='marke' style='width: 100%;' type='text'  value='".$out_edit['marke']."'>
															</td>
														</tr>
														<tr style='height: 5px;'>


														</tr>
														
														<tr >
															<td width='30%'>
																Website:
															</td>
															<td width='70%'>
																<input name='homepage' style='width: 100%;' type='text'  value='".$out_edit['homepage']."'>
															</td>
														</tr>
														<tr >
															<td width='30%'>
																Kontaktformular:
															</td>
															<td width='70%'>
																<input name='formular' style='width: 100%;' type='text'  value='".$out_edit['formular']."'>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
					
										</tr>
										<tr>
											<td class='msgrow1' width='200' colspan='2' align='right'>
												<input name='senden' value='Sponsorendaten speichern' type='submit'>
											</td>
										</tr>
							</form>
										";
											if($_GET['action'] == 'edit')
										{
											$output .="
								<tr>
											<td class='msgrow1' colspan='2'>
												&nbsp;
											</td>
										</tr>
								";
										$output .="
										<tr>
											<td class='msghead' width='200' colspan='2'>
												<b>Ansprechpartner:</b>
											</td>
										</tr>
										<tr>
											<form name='addname' action='?hide=1&action=add_name&comand=senden&id=".$out_edit['id']."' method='POST'>
												<td class='msgrow1' colspan='2'>
													<table width='100%'>
														<tbody>
															<tr>
																<td>
																	<select name='add_kontakt_id' style='width: '>
																		<option value='1'>w&auml;hlen</option>";
																			$sql_list_contact = mysql_query("SELECT * FROM project_contact_contacts WHERE sponsor_id = 0 ORDER BY p_vorname");
																			while($out_list_contact = mysql_fetch_array($sql_list_contact))
																			{// begin while
																				$output .="
																						<option value='".$out_list_contact['contactid']."'>
																							".$out_list_contact['p_vorname']."
																							".$out_list_contact['p_name']."
																							---->
																							".$out_list_contact['fa_name']."
																						</option>";
																			}
																				$output .="
																	</select>
																</td>
																<td align='right'>
																	<input name='senden' value='Ansprechpartner hinzuf&uuml;gen' type='submit' align='right'>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</form>
										</tr>";

										$sql_list_contact = mysql_query("SELECT * FROM project_contact_contacts WHERE sponsor_id = ".$out_edit['id']." ");
										if( mysql_num_rows($sql_list_contact) != 0)
									{
							$output .="
										<tr>
											<td class='msghead' colspan='2'>
												<b>Ansprechpartner-Liste:</b>
											</td>
										</tr>
										<tr>
											<td class='msgrow1'  colspan='2'>
												<table cellspacing='0' cellpadding='0' border='0' width='100%'>
												<tbody>

												   <tr>
													   <td class='msghead' >
															Name
														</td>
														<td class='msghead'>
															Telefon
														</td>
														<td class='msghead'>
															Mobil
													   </td> ";

														if($DARF["del"])
															{ // Global Admin
																$output .="<td class='msghead'> &nbsp; </td>";
													 }
												$output .="</tr>";

													$i=1;
													while($out_list_contact = mysql_fetch_array($sql_list_contact))
													{// begin while
														$output .="
														<tr>
															<td  class=\"msgrow".(($i%2)?1:2)."\">
																<a href='mailto:".$out_list_contact['fa_email']."'> ".$out_list_contact['p_vorname']." ".$out_list_contact['p_name']."</a>
															</td>
															<td  class=\"msgrow".(($i%2)?1:2)."\" >
																".$out_list_contact['fa_tel']."
															</td>
															<td  class=\"msgrow".(($i%2)?1:2)."\">
																".$out_list_contact['fa_mobil']."
															</td>";

														if($DARF["del"])
															{ // Global Admin
																$output .="
																		<td  class=\"msgrow".(($i%2)?1:2)."\" align='right'>
																			<a  href='?hide=1&action=del_name&id=".$out_list_contact['contactid']."' target='_parent'>
																			<img src='{BASEDIR}images/icons/delete.png' title='löschen'/></a>
																		</td>";
															}
												$output .="
														</tr>";
														$i++;
													}
														$output .="
														</tbody>
													</table>
											</td>
										</tr>";
									}
									
										$output .="
										<tr>
											<td class='msgrow1' colspan='2'>
												&nbsp;
											</td>
										</tr>
								";

								

									} // IF IS EDIT ENDE

					$output .= "
								</tbody>
							</table>";

			}

		/////////////////////////////////////////////// ENDE ADD / EDIT ///////////////////////////////////////////////

		/////////////////////////////////////////////// ADD_NAME ///////////////////////////////////////////////

			if($_GET['action'] == 'add_name')
			{

				if (!$DARF["add"]) $PAGE->error_die(html::template("error_nopermission"));

				if($_GET['comand'] == 'senden')

				{ $id = $_GET['id'];

						$update=mysql_query("
												UPDATE
													`project_contact_contacts`
												SET
													`sponsor_id` = \"$id\"
												WHERE
													`contactid` = \"$add_kontakt_id\";
											");


				$output .= "Daten wurden gesendet";
				$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$id."'>";

				}
			}
		/////////////////////////////////////////////// ENDE ADD_NAME ///////////////////////////////////////////////

		/////////////////////////////////////////////// EDIT_STATS ///////////////////////////////////////////////

			if($_GET['action'] == 'edit_stats')
			{
				if (!$DARF["edit"]) $PAGE->error_die(html::template("error_nopermission"));
				//$sql_edit_stats = mysql_query("SELECT * FROM project_sponsoren_stats WHERE s_id = ".$sid."");

				if($_GET['comand'] == 'senden')

				{

				$insert=mysql_query
								("
									INSERT INTO
										`project_sponsoren_stats`
											(
												id,
												u_id,
												s_id,
												date,
												time,
												status,
												comment,
												event_id
											)
									VALUES
										(
											NULL,
											'".$user_id."',
											'".$id."',
											'".$date."',
											'".$time."',
											'".$status."',
											'".$comment."',
											'".$_GET['event_id']."'
										);"
								);


					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$id."'>";

				}
			}

		/////////////////////////////////////////////// ENDE EDIT_STATS ///////////////////////////////////////////////

		/////////////////////////////////////////////// EDIT_ART ///////////////////////////////////////////////

			if($_GET['action'] == 'edit_art')
			{
				if (!$DARF["edit"]) $PAGE->error_die(html::template("error_nopermission"));


				if($_GET['comand'] == 'senden')

				{

				$insert=mysql_query
									("
										INSERT INTO
											`project_sponsoren_artikel`
												(
													id,
													s_id,
													date,
													time,
													sp_art_marke,
													sp_art_anz,
													sp_art_name,
													sp_art_wert,
													event_id
												)
											VALUES
												(
													NULL,
													'".$id."',
													'".$date."',
													'".$time."',
													'".$sp_art_marke."',
													'".$sp_art_anz."',
													'".$sp_art_name."',
													'".$sp_art_wert."',
													'".$_GET['event_id']."'
												);
									");


					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$id."'>";

				}
			}

		/////////////////////////////////////////////// ENDE EDIT_ART ///////////////////////////////////////////////

		/////////////////////////////////////////////// EDIT_GLOBAL ///////////////////////////////////////////////

			if($_GET['action'] == 'edit_global')
			{
				$wert			= $_POST['wert'];
				$admn			= $_POST['admin'];

				if (!$DARF["edit"]) $PAGE->error_die(html::template("error_nopermission"));
				$out_edit_sponsor = mysql_fetch_array( mysql_query("SELECT * FROM project_sponsoren WHERE id = ".$id."") );
				$sponsor_name = $out_edit_sponsor['name'];

				if($_GET['comand'] == 'senden')

				{

				$insert=mysql_query("
									UPDATE
										`project_sponsoren`
									SET
										`wert` 		= \"$wert\",
										`admin` 	= \"$admn\"
									WHERE
										`id` 		= \"$id\";

									");

					$output .= "Kommentar: ".$kommentar."<br>";
					$output .= "Wert: ".$wert."<br>";
					$output .= "admin: ".$admin."<br>";
					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."#".$sponsor_name."'>";

				}
			}

		/////////////////////////////////////////////// ENDE EDIT_GLOBAL ///////////////////////////////////////////////

		/////////////////////////////////////////////// DEL_STATS ///////////////////////////////////////////////

			if($_GET['action'] == 'del_stats')
			{
				if (!$DARF["del"]) $PAGE->error_die(html::template("error_nopermission"));

					if($_GET['comand'] == 'senden')

				{
					$del=mysql_query("DELETE FROM project_sponsoren_stats WHERE id = '".$_GET['id']."'");
					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$_GET['s_id']."'>";
				}

				 $new_id = $_GET['id'];
				 $out_list_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren_stats WHERE id = '".$new_id."' LIMIT 1"));
				 $out_list_u_name = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = '".$out_list_name['u_id']."' LIMIT 1"));

			$output .="

						<h2 style='color:RED;'>Achtung!!!!<h2>
						<br />

						<p>Sind Sie sich sicher das ".$out_list_name['status']." von ".$out_list_u_name['nick']." gel&ouml;scht werden soll?</p>
						<br />
						<a href='?hide=1&action=del_stats&comand=senden&id=".$new_id."&s_id=".$out_list_name['s_id']."&sponsor=".$sponsor."' target='_parent'>
						<input value='L&ouml;schen' type='button'></a>
						 \t
						<a href='/admin/projekt/sponsoren/?hide=1&action=show&id=".$out_list_name['s_id']."' target='_parent'>
						<input value='Zur&uuml;ck' type='button'></a>
					";
			}

		/////////////////////////////////////////////// ENDE DEL_STATS ///////////////////////////////////////////////

		/////////////////////////////////////////////// DEL_ARTIKEL ///////////////////////////////////////////////

			if($_GET['action'] == 'del_artikel')
			{
				if (!$DARF["del"]) $PAGE->error_die(html::template("error_nopermission"));

					if($_GET['comand'] == 'senden')

				{
					$out_list_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$_GET['s_id']."' LIMIT 1"));
					$del=mysql_query("DELETE FROM project_sponsoren_artikel WHERE id = '".$_GET['id']."'");
					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$out_list_name['id']."'>";
				}

				 $new_id = $_GET['id'];
				 $out_list_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren_artikel WHERE id = '".$new_id."' LIMIT 1"));

			$output .="

						<h2 style='color:RED;'>Achtung!!!!<h2>
						<br />

						<p>Sind Sie sich sicher das ".$out_list_name['sp_art_name']." gel&ouml;scht werden soll?</p>
						<br />
						<a href='?hide=1&action=del_artikel&comand=senden&id=".$out_list_name['id']."&s_id=".$out_list_name['s_id']."' target='_parent'>
						<input value='L&ouml;schen' type='button'></a>
						 \t
						<a href='/admin/projekt/sponsoren/?hide=1&action=show&id=".$out_list_name['s_id']."' target='_parent'>
						<input value='Zur&uuml;ck' type='button'></a>
					";
			}

		/////////////////////////////////////////////// ENDE DEL_ARTIKEL ///////////////////////////////////////////////
		
		/////////////////////////////////////////////// edit_ARTIKEL ///////////////////////////////////////////////

			if($_GET['action'] == 'edit_artikel')
			{
				$out_edit_marke = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren_artikel WHERE id = '".$_GET['a_id']."' LIMIT 1"));
				$out_marken = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$_GET['s_id']."' LIMIT 1"));
				
				if (!$DARF["edit"]) $PAGE->error_die(html::template("error_nopermission"));
				
					if($_GET['comand'] == 'senden')

				{
					$update=mysql_query("
										UPDATE 
											`project_sponsoren_artikel`
										SET
											`sp_art_marke` 	=  '".$sp_art_marke."',
											`sp_art_anz` 	=  '".$sp_art_anz."',
											`sp_art_name` 	=  '".$sp_art_name."',
											`sp_art_wert` 	=  '".$sp_art_wert."' 
										WHERE
											`id` ='".$_GET['a_id']."'
										
										");
					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$_GET['s_id']."'>";
				}

				$output .="

								<table width='100%'>
													<tbody>
														<tr>
															<td class='msghead' width='55'>
																Anzahl
															</td>
															<td  class='msghead'  width='400'>
																Artikelbezeichnung
															</td>
															<td  class='msghead'>
																Warenwert pro St&uuml;ck
															</td>";
																								
$output .="													<td  class='msghead'>
																Marke
															</td>";
									
$output .="													<td  class='msghead'>

															</td>
														</tr>
														<tr>
															<form name='editart' action='?hide=1&action=edit_artikel&comand=senden&s_id=".$out_marken['id']."&a_id=".$out_edit_marke['id']."' method='POST'>
																<td class='msgrow1' >
																	<input name='sp_art_anz' value='".$out_edit_marke['sp_art_anz']."'  style='width: 100%;' type='text' >
																</td>
																<td class='msgrow1'>
																	 <input name='sp_art_name' value='".$out_edit_marke['sp_art_name']."'  style='width: 100%;' type='text'>
																</td>
																<td class='msgrow1'>
																	 <input name='sp_art_wert' value='".$out_edit_marke['sp_art_wert']."'  style='width: 50%;' type='text' >
																</td>";
																
									
$output .="														<td class='msgrow1'>
																				<select name='sp_art_marke'' >
																				<option value='".$out_edit_marke['sp_art_marke']."' selected='selected'>".$out_edit_marke['sp_art_marke']."</option>
																					
																";
																$a = explode(",",$out_marken['marke']);
																
																foreach($a AS $marke ){
																
$output .="																<option value='$marke' >$marke</option>";
																}
																	 
$output .="														</td>";
									
$output .="														<td class='msgrow1' align='right'>
																	<input name='senden' value='Artikeldaten speichern' type='submit' style='text-align:right;' >
																</td>
															</form>
																";

												$output .="
														</tr>
													</tbody>
												</table>";
					
					
			}

		/////////////////////////////////////////////// ENDE edit_ARTIKEL ///////////////////////////////////////////////
		
		
		/////////////////////////////////////////////// ADD_TODO ///////////////////////////////////////////////

			if($_GET['action'] == 'edit_todo')
			{
				if (!$DARF["edit"]) $PAGE->error_die(html::template("error_nopermission"));


				if($_GET['comand'] == 'senden')

				{

				$insert=mysql_query
									("
										INSERT INTO
											`project_sponsoren_todo`
												(
													id,
													s_id,
													date,
													time,
													sp_todo_todo,
													checked,
													event_id
												)
											VALUES
												(
													NULL,
													'".$id."',
													'".$date."',
													'".$time."',
													'".$sp_todo_todo."',
													'0',
													'".$_GET['event_id']."'
												);
									");


					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$id."'>";

				}
			}

		/////////////////////////////////////////////// ENDE ADD_TODO //////////////////////////////////////////////

/////////////////////////////////////////////// EDIT_TODO ///////////////////////////////////////////////

			if($_GET['action'] == 'edit_todo_checked')
			{
				if (!$DARF["edit"]) $PAGE->error_die(html::template("error_nopermission"));


				$cheked = 1;
				 if($_GET['checked'] == 1)
				 {
					$cheked = 0;
				 }

				$insert=mysql_query(" UPDATE  `project_sponsoren_todo` SET  `checked` =  '$cheked' WHERE  `project_sponsoren_todo`.`id` =$id; ");


					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$_GET['s_id']."'>";

			}

		/////////////////////////////////////////////// ENDE EDIT_TODO ///////////////////////////////////////////////		

		/////////////////////////////////////////////// DEL_STATS ///////////////////////////////////////////////

			if($_GET['action'] == 'del_todo')
			{
				if (!$DARF["del"]) $PAGE->error_die(html::template("error_nopermission"));

					if($_GET['comand'] == 'senden')

				{
					$del=mysql_query("DELETE FROM project_sponsoren_todo WHERE id = '".$_GET['id']."'");
					$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."?hide=1&action=show&id=".$_GET['s_id']."'>";
				}

				 $new_id = $_GET['id'];
				 $out_list_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren_todo WHERE id = '".$new_id."' LIMIT 1"));
				

			$output .="

						<h2 style='color:RED;'>Achtung!!!!<h2>
						<br />

						<p>Sind Sie sich sicher das ".$out_list_name['sp_todo_todo']." gel&ouml;scht werden soll?</p>
						<br />
						<a href='?hide=1&action=del_todo&comand=senden&id=".$new_id."&s_id=".$out_list_name['s_id']."&sponsor=".$sponsor."' target='_parent'>
						<input value='L&ouml;schen' type='button'></a>
						 \t
						<a href='/admin/projekt/sponsoren/?hide=1&action=show&id=".$out_list_name['s_id']."' target='_parent'>
						<input value='Zur&uuml;ck' type='button'></a>
					";
			}

		/////////////////////////////////////////////// ENDE DEL_STATS ///////////////////////////////////////////////


///////////////////////////////////////////////   show  ///////////////////////////////////////////////

			if($_GET['action'] == 'show'  )
			{
				if (!$DARF["view"]) $PAGE->error_die(html::template("error_nopermission"));

				$output .= sponsoren_show($_GET['id'],$DARF,$selectet_event_id);

			}

		/////////////////////////////////////////////// ENDE view ///////////////////////////////////////////////		

		}// Hide = eins ENDE

	} // ENDE darf den Inhalt der Seite sehen

} // ADMIN ENDE
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>