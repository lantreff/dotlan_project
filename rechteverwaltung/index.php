<?
#########################################################################
# Rechte-Verwaltungsmodul for dotlan                                	#
#                                                                      	#
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              	#
#                                                                      	#
# admin/Rechteverwaltung/index.php - Version 1.0                       	#
#########################################################################
$version = 'Version 1.0';
$dev_link = 'http://development.serious-networx.net/?page_id=15';

include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Projekt Rechteverwaltung");
$event_id		= $EVENT->next;			// ID des anstehenden Event's

// auslesen der einzelnen Werte die über die Adresszeile übergeben werden
	$id				= $_GET['id'];
////////////////////////////////////////////////

// Sortierung //
// Variablen für die Sortierfunktion
	$sort			= "name"; // Standardfeld das zum Sortieren genutzt wird
	$order			= "ASC"; // oder DESC | Sortierung aufwerts, abwerts

	if (IsSet ($_GET['sort'] ) )
	{
		$sort		= $_GET['sort'];
	}
	if (IsSet ($_GET['order'] ) )
	{
		$order		= $_GET['order'];
	}
////////////////////////////////////////////////


 /*###########################################################################################
Admin PAGE
*/


if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_nopermission"));  // Ist der angemeldete Benutzer Globaler Admin oder hat er über die Rechteverwaltung Berechtigungen dann darf er die Seite eehen sonst Error-Message.
else
{
		$a = 'shortbarbit';
		$a1 = 'shortbarlink';

	if($DARF_PROJEKT_VIEW || $ADMIN->check(GLOBAL_ADMIN))
	{ //$ADMIN

			$output .= "<a name='top' >

				<a href='/admin/projekt/'>Projekt</a>
				&raquo;
				<a href='/admin/projekt/rechteverwaltung'>Rechteverwaltung</a>
				&raquo; ".$_GET['action']."
				<hr class='newsline' width='100%' noshade=''>
				<br />

				";

							if($DARF_PROJEKT_ADD || $DARF_PROJEKT_EDIT)
			{
			$output .= "
	<table  width='10%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
	  <tbody>
			<tr class='shortbarrow'>";
			if($DARF_PROJEKT_ADD )
			{
				$output .= "
				<td width='".$breite."' class='".$a."'><a href='admin.php' class='".$a1."'>Bereiche verwalten</a></td>";
			}
		}
		$output .= "
		
			</tr>
		</tbody>
	</table>
	<br>";

		if($_GET['hide'] != 1) // solange die variable "hide" ungleich eins ist wird die Standardmaske angezeigt. Ist der Wert eins dann wird diese Maske ausgeblendet, um z.B. die Editmaske anzuzeigen oder um Meldungen auf der Seite auszugeben.
		{
			$sql_list_orga = $DB->query("
											SELECT
												*
											FROM
												user_orga
									");
			$output .= "
							<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr>
										<td width='50'   class='msghead' align='center'>
											<b>Vorname</b>
										</td>
										<td  width='100'  class='msghead' align='center'>
											<b>Name</b>
										</td>
										<td width='350'  class='msghead' align='center'>
											<b>&Uuml;bersicht</b>
										</td>";

										if($DARF_PROJEKT_EDIT )
										{ //  Admin
											$output .="
												<td width='20' class='msghead' align='center'>
													<b>admin</b>
												</td>";
										}
										$output .="
											</tr>";

			$iCounter = 0;
			while($out_list_orga = $DB->fetch_array($sql_list_orga))
			{// begin while
				$id = $out_list_orga['user_id'];

				$out_orga_data =
						$DB->fetch_array(
								$DB->query("
												SELECT
													*
												FROM
													user
												WHERE
													`id` = '".$id."';
											")
										);
					$output .= "

						<tr class=\"msgrow".(($i%2)?1:2)."\">

							<td >
										".$out_orga_data['vorname']."
									</td>
									<td>
										".$out_orga_data['nachname']."

									</td>
									";
									$sql_user_rechte_main = $DB->query("
														SELECT `r`.`name`, `r`.`id`
														FROM `project_rights_user_rights` AS `ur`
														LEFT OUTER JOIN `project_rights_rights` AS `r` ON `r`.`id`=`ur`.`right_id`
														WHERE `ur`.`user_id`= '".$out_orga_data['id']."'
														ORDER BY `r`.`".$sort."` ".$order.";
												");
						$output .="<td align='center' title='";
						while($out_user_rechte_main = $DB->fetch_array($sql_user_rechte_main))
						{// begin while

									$output .=" Darf ".$out_user_rechte_main['name']." \n";

						}
						$output .= "'>
									 Hier mit der Maus hin :-)</td>
									";
									if($DARF_PROJEKT_EDIT  )
									{
										$output .= "
													<td align='center'>
												<a href='?hide=1&action=edit&id=".$out_orga_data['id']."' target='_parent'> <img src='/images/projekt/16/edit.png' title='Details anzeigen/&auml;ndern' > </a>

										</td>";
									}
				$output .= "
							</tr>
							";

				
				$i++;
			}
			$output .= "			</tbody>
						</table>

						<br />";







		}  // hide ende
		if($_GET['hide'] == "1")
		{

		/////////////////////////////////////////////// ADD ///////////////////////////////////////////////
			if($_GET['action'] == 'add' )
			{
				if (!$DARF_PROJEKT_EDIT ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

					if($_GET['action'] == 'add' and $_GET['comand'] == 'senden'){


						$sql_rechte = $DB->query("
									SELECT *
									FROM `project_rights_rights`
									GROUP BY `bereich`
									ORDER BY `name` ASC;
								");
					}

			}
		/////////////////////////////////////////////// ADD ENDE ///////////////////////////////////////////////

		/////////////////////////////////////////////// EDIT ///////////////////////////////////////////////

			if($_GET['action'] == 'edit' )
			{
				if (!$DARF_PROJEKT_EDIT ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

				if($_GET['action'] == 'edit' and $_GET['comand'] == 'senden')
				{


					$sql_rechte = $DB->query("
								SELECT *
								FROM `project_rights_rights`
								GROUP BY `bereich`
								ORDER BY `name` ASC;
							");
					if( mysql_num_rows($sql_rechte) != 0)
					{

						while($out_sql_rechte = $DB->fetch_array($sql_rechte))
						{// begin while
							$test1_edit = 1;
							$test2_edit = 0;


							$bereiche = explode("_",$out_sql_rechte['name']);

							$sql_user_bereich_rechte = $DB->query("
																SELECT *
																FROM `project_rights_rights`
																WHERE `name` LIKE '%projekt_".$bereiche[1]."%'
																ORDER BY `name` ASC;
															");

							while($out_user_bereich_rechte = $DB->fetch_array($sql_user_bereich_rechte))
							{// begin while
								$test1_edit = 0;
								$test2_edit = 0;
								$test3_edit = 0;

												$rechte_user_id  = $_GET['id'];
												$rechte_right_id = $out_user_bereich_rechte['id'];

								$user_bereich_rechte = explode("_",$out_user_bereich_rechte['name']);
								//$output .= "<br><br>2__".$out_user_bereich_rechte['name']." Test1 --> ".$test1." Test2 --> ".$test2." Test3 --> ".$test3;


								$sql_user_rechte = $DB->query("
																SELECT `r`.`name`, `r`.`id`
																FROM `project_rights_user_rights` AS `ur`
																LEFT OUTER JOIN `project_rights_rights` AS `r` ON `r`.`id`=`ur`.`right_id`
																WHERE `ur`.`user_id`= '".$id."'
																AND `r`.`name` LIKE '%projekt_".$bereiche[1]."_".$user_bereich_rechte[2]."%'
																ORDER BY `r`.`name` ASC;
														");

								while($out_user_rechte = $DB->fetch_array($sql_user_rechte))
								{// begin while
									$test3_edit = 3;
									$test1_edit = 1;
									$test2_edit = 2;
									$user_rechte = explode("_",$out_user_rechte['name']);

									if($out_user_rechte['name'] == $out_user_bereich_rechte['name'])
									{

										if(isset ($_POST[$out_user_rechte['name']]))
										{
											//$update = =$DB->query(" UPDATE `project_rights_user_rights` SET `right_id` =  \'$rechte_user_id\'  WHERE `project_rights_user_rights`.`user_id` = \'$rechte_user_id\' AND `project_rights_user_rights`.`right_id` =  \'$rechte_user_id\' LIMIT 1 ;");

											// $output .="<br> ".$out_user_rechte['name']." must be UPDATED the user_id: ".$rechte_user_id." with the right_id: ".$rechte_right_id;
										}
										if(!isset ($_POST[$out_user_rechte['name']]))
										{

											$del =$DB->query("DELETE FROM `project_rights_user_rights` WHERE `user_id` = '".$rechte_user_id."' AND `right_id` = '".$rechte_right_id."' LIMIT 1 ");

											// $output .="<br> ".$out_user_rechte['name']." must be DELEDE the user_id: ".$rechte_user_id." with the right_id: ".$rechte_right_id;
										}
									}
								}
								if($test1_edit  == 0 and $test2_edit == 0 and $test3_edit == 0)
								{

									if(isset ($_POST[$out_user_bereich_rechte['name']]))
									{


										$insert_edit=$DB->query("INSERT INTO `project_rights_user_rights` (`user_id`, `right_id`) VALUES ( '".$rechte_user_id."', '".$rechte_right_id."');");

										// $output .="<br> ".$out_user_bereich_rechte['name']." must be ADDED the user_id: ".$rechte_user_id." with the right_id: ".$rechte_right_id;
									}
									if(!isset ($_POST[$out_user_bereich_rechte['name']]))
									{
										// $output .="<br> ".$out_user_bereich_rechte['name']." must be NOTHING";
									}
								}
							}
						}
					}
							$output .= "<br> Rechte wurde aktualisiert !! <br> ";
					//$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/?hide=1&action=edit&id=".$rechte_user_id."'>";
				}
				$output .= "
				<form name='editorgarechte' action='?hide=1&action=".$_GET['action']."&comand=senden&id=".$_GET['id']."' method='POST'>
					<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
							<tr>
								<td class='msghead'>
									<b>Benutzerrechte</b>
								</td>
							</tr>
							<tr >
								<td  >";
								$out_orga =
									$DB->fetch_array(
											$DB->query("
															SELECT *
															FROM `user`
															WHERE `id`='".$_GET['id']."';
														")
													);
						$output .= "<b>".$out_orga['vorname']." (".$out_orga['nick'].") ".$out_orga['nachname']."</b>";


						$output .= "
								</td>
							</tr>";

							$sql_rechte = $DB->query("
														SELECT `name`
														FROM `project_rights_rights`
														GROUP BY `bereich`
														ORDER BY `name` ASC;
													");
								if( mysql_num_rows($sql_rechte) != 0)
								{
						$output .="
							<tr >
								<td class='msghead'>
									<b>Aktivierte Rechte:</b>
								</td>
							</tr>
							<tr>
								<td>
									<table  width='100%' cellspacing='0' cellpadding='0' border='0'>
										<tbody>
											<tr valign=bottom>
											<td  class='msghead3'>
											Bereich
											</td>
											";
												$sql_rechte_namen = $DB->query("
																				SELECT *
																				FROM `project_rights_rights`
																				GROUP BY recht
																				ORDER BY recht ASC;
																			");

												while($out_rechte_namen = $DB->fetch_array($sql_rechte_namen))
												{// begin while
												$name= str_split($out_rechte_namen['recht']);

$output .="											
												
												<td width='5' align='center' class='msghead3'>";
				foreach($name as &$b) {
$output .= strtoupper($b)."<br>" ;
				}
												
				
$output .="										</td>
												";
												}
$output .="												
												
											</tr>";
							while($out_sql_rechte = $DB->fetch_array($sql_rechte))
							{// begin while
								$test1 = 1;
								$test2 = 0;


								$bereiche = explode("_",$out_sql_rechte['name']);
								//$output .= "<br><br>1_".$out_sql_rechte['name']." Test1 --> ".$test1." Test2 --> ".$test2." Test3 --> ".$test3;

								$output .="
											<tr class=\"msgrow".(($i%2)?1:2)."\">
												<td style='border-bottom: 1px solid #FFFFFF;'>
													".$bereiche[1]."
												</td>";
												$sql_rechte_namen1 = $DB->query("
																				SELECT *
																				FROM `project_rights_rights`
																				GROUP BY recht
																				ORDER BY recht ASC;
																			");
												
																			
																									
												while($out_rechte_namen1 = $DB->fetch_array($sql_rechte_namen1))
												{// begin while
												
												$sql_user_bereich_rechte = $DB->query("
																				SELECT *
																				FROM `project_rights_rights`
																				WHERE `name` LIKE '%projekt_".$bereiche[1]."_".$out_rechte_namen1['recht']."%'
																				ORDER BY `recht` ASC;
																			");
												
												

												while($out_user_bereich_rechte = $DB->fetch_array($sql_user_bereich_rechte))
												{// begin while
												
												
													$user_bereich_rechte = explode("_",$out_user_bereich_rechte['name']);
													//$output .= "<br><br>2__".$out_user_bereich_rechte['name']." Test1 --> ".$test1." Test2 --> ".$test2." Test3 --> ".$test3;


													$sql_user_rechte = $DB->query("
																					SELECT `r`.`name`, `r`.`recht`
																					FROM `project_rights_user_rights` AS `ur`
																					LEFT OUTER JOIN `project_rights_rights` AS `r` ON `r`.`id`=`ur`.`right_id`
																					WHERE `ur`.`user_id`= '".$id."'
																					AND `r`.`name` LIKE '%projekt_".$bereiche[1]."_".$out_rechte_namen1['recht']."%'
																					ORDER BY `r`.`recht` ASC;
																			");

													while($out_user_rechte = $DB->fetch_array($sql_user_rechte))
													{// begin while
														
														$user_rechte = explode("_",$out_user_rechte['name']);

														if($out_user_rechte['name'] == $out_user_bereich_rechte['name'])
															{
															//$output .= " <br><br>3_____ ".$out_user_rechte['name']." == ".$out_user_bereich_rechte['name']." Test1 --> ".$test1." Test2 --> ".$test2." Test3 --> ".$test3;
															$output .="

															<td class='msgrow' align='center' style='border-left: 1px solid #FFFFFF; border-bottom: 1px solid #FFFFFF;'>
																<input type='checkbox' name='".$out_user_bereich_rechte['name']."' value='".$out_user_bereich_rechte['id']."' checked>
															</td>";

															}



													}
												
													
													if(mysql_num_rows($sql_user_rechte) == 0)
													{
													//$output .= " <br>4_____ ".$out_user_rechte['name']." == ".$out_user_bereich_rechte['name'];
													$output .="

													<td class='msgrow' align='center' style='border-left: 1px solid #FFFFFF; border-bottom: 1px solid #FFFFFF;'>
													<input type='checkbox' name='".$out_user_bereich_rechte['name']."' value='".$out_user_bereich_rechte['id']."' >
													</td>";
													}	
													
												
											

												}
												if(mysql_num_rows($sql_user_bereich_rechte) == 0)
													{
													//$output .= " <br>4_____ ".$out_user_rechte['name']." == ".$out_user_bereich_rechte['name'];
													$output .="

													<td class='msgrow' align='center' style='border-left: 1px solid #FFFFFF; border-bottom: 1px solid #FFFFFF;'>
													&nbsp;
													</td>";
													}	
												
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
						</tbody>
					</table>
					<input name='senden' value='Rechte aktualisieren' type='submit'><br>
					<p><a href='/admin/projekt/rechteverwaltung/'>Zur&uuml;ck zur &Uuml;bersicht</a></p>
				</form>";



			}
		/////////////////////////////////////////////// EDIT ENDE ///////////////////////////////////////////////

		} // Hide = 1 ENDE

	} // ENDE darf den Inhalt der Seite sehen

} // ADMIN PAGE ENDE
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>