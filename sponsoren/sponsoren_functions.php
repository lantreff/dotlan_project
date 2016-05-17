<?php
function sponsoren_list_sponsor($event_id)
{
	$sql = mysql_query("SELECT * FROM project_sponsoren_artikel AS a LEFT JOIN project_sponsoren AS s ON s.id = a.s_id WHERE a.event_id = '".$event_id."' GROUP BY a.s_id ORDER BY s.name,a.s_id,a.sp_art_name ASC");
	return $sql;
}
function sponsoren_list_artikel_by_sponsor($sponsor_id)
{
	$sql = mysql_query("SELECT * FROM project_sponsoren_artikel WHERE s_id = '".$sponsor_id."' ORDER BY s_id,sp_art_name ASC");
	return $sql;
}
function sponsoren_list_sponsor_single($sponsor_id)
{
	$out = mysql_fetch_array( mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$sponsor_id."'"));
	return $out;
}
function sponsoren_ges_wert($anz,$wert)
{
	$ges_wert = ( $anz * $wert );
	
	return $ges_wert." €";
}

function sponsoren_show($id,$DARF,$selectet_event_id)
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
																";
																$out_user_selected = mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$out_edit['admin']." ") );
																	
																		if( $out_edit['admin'] ==   $out_user_selected['id'])
																		{


																		$output .= $out_user_selected['vorname']." '".$out_user_selected['nick']."' ".$out_user_selected['nachname'];

																		}
																		else
																		{
																		$output .= "keiner!";
																		}
																		$output .="<a href='?hide=1&action=edit&id=".$out_edit['id']."&event=".$_GET['event']."' target='_parent'>
																		<img src='{BASEDIR}images/icons/pencil.png' title='Details anzeigen/&auml;ndern' ></a>";
																$output .= "
																
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
																".$out_edit['name']."
															</td>
														</tr>
														<tr style='height: 5px;'>

														</tr>
														<tr >
															<td width='30%'>
																E-Mail:
															</td>
															<td width='70%'>
																".$out_edit['email']."
															</td>
														</tr>
														<tr >
															<td width='30%'>
																Tel.:
															</td>

															<td width='70%'>
																".$out_edit['tel']."
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
																				".$out_edit['str']." ".$out_edit['hnr']."
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
																			".$out_edit['plz']."
																			</td>
																			<td>&nbsp;</td>
																			<td width='100%'>
																				".$out_edit['ort']."
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
															<td width='70%'>";
																		$sql_list_land = mysql_query("SELECT * FROM project_countryTable WHERE id = '".$out_edit['land']."'");
																		$out_list_land = mysql_fetch_array($sql_list_land);
																		
																			if( mysql_num_rows($sql_list_land ) > 0 )
																			{
																			$output .= "".$out_list_land['name']."";
																			}
																			else
																			{
																			
																				$output .="Kein Land eingetragen!";
																			
																			}
																		
																			$output .="
															</td>
														</tr>
														<tr >
															<td width='30%'>
																Marke:
															</td>
															<td width='70%'>
																".$out_edit['marke']."
															</td>
														</tr>
														<tr style='height: 5px;'>


														</tr>
														
														<tr >
															<td width='30%'>
																Website:
															</td>
															<td width='70%'>
																".$out_edit['homepage']."
															</td>
														</tr>
														<tr >
															<td width='30%'>
																Kontaktformular:
															</td>
															<td width='70%'>
																".$out_edit['formular']."
															</td>
														</tr>
													</tbody>
												</table>
											</td>
					
										</tr>
										<!--
										<tr>
											<td class='msgrow1' width='200' colspan='2' align='right'>
												<input name='senden' value='Sponsorendaten speichern' type='submit'>
											</td>
										</tr>
										-->
							</form>
										";
											if($_GET['action'] == 'show')
										{
											$output .="
								<tr>
											<td class='msgrow1' colspan='2'>
												&nbsp;
											</td>
										</tr>
								";
										
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

									$output .="
										<tr>
											<td class='msghead' colspan='2'>
												<b>Status:</b>
											</td>
										</tr>

											";
													if( $DARF["edit"] )
														{ // EDIT
														$output .="
													<tr>
														<form name='editstats' action='?hide=1&action=edit_stats&comand=senden&event_id=".$selectet_event_id."&id=".$out_edit['id']."' method='POST'>
															<td class='msgrow1' colspan='2'>
																<table width='100%'>
																	<tbody>
																		<tr>
																			<td>
																				<input name='comment' value=''  style='width: 70%;' type='text' >
																				<select name='status' style='width:80px;' >
																					<option value='undef' selected='selected'>undef</option>
																					<option value='Angeschrieben'>Angeschrieben</option>
																					<option value='Zusage'>Zusage</option>
																					<option value='Absage'>Absage</option>
																					<option value='keine Antwort'>keine Antwort</option>
																					<option value='Soll Geschenk bekommen'>Soll Geschenk bekommen</option>
																					<option value='Hat Geschenk bekommen'>Hat Geschenk bekommen</option>
																					<option value='Hat Bericht bekommen'>Hat Bericht bekommen</option>
																					<option value='News geschrieben'>News geschrieben</option>
																					<option value='Ware erhalten'>Ware erhalten</option>
																					<option value='Zurückgestellt'>Zurückgestellt</option>
																				</select>
																			</td>
																			<td  align='right'>
																				<input name='senden' value='Status hinzuf&uuml;gen' type='submit' style='text-align:right;' >
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</form>
													</tr>
														";
													}
													
													$sql_stats_event = mysql_query("
												SELECT
														*
												FROM
													project_sponsoren_stats
												WHERE
													s_id 		= '".$out_edit['id']."'
												AND
													event_id = '".$selectet_event_id."'
												ORDER BY
													date DESC,
													time DESC
											");
											
											$out_event1 = mysql_fetch_array( mysql_query("
												SELECT
														*
												FROM
													events
												WHERE
													id 		= '".$selectet_event_id."'
												
											")
											);
								
								if( mysql_num_rows($sql_stats_event) != 0)
								{
								$output .="
										<tr >
											<td class='msghead' colspan='2'>
												<b>Status-Liste:</b>
											</td>
										</tr>
										<tr>
											<td class='msgrow1' colspan='2'>
												<table width='100%'>
													<tbody>
														<tr>
															<td class='msgrow2' >
																<b>".$out_event1['name']."</b>
															</td>
														</tr>
														<tr>
															<td class='msgrow1'>

																<table  width='100%' cellspacing='0' cellpadding='0' border='0'>
																<tbody>";

														while($out_sql_stats = mysql_fetch_array($sql_stats_event))
														{// begin while
															$out_stats_user = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = '".$out_sql_stats['u_id']."' "));
															$out_stat_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$out_sql_stats['s_id']."' "));

																	$bg_color = "#FFFFFF";
																
															if($out_sql_stats['status'] ==  'Absage')
																	{
																		$bg_color = "#FF0000";
																	}
																if($out_sql_stats['status'] ==  'keine Antwort')
																	{
																		$bg_color = "#FF0000";
																	}
																if($out_sql_stats['status'] ==  'Zusage')
																	{
																		$bg_color = "#90EE90";
																	}
																if($out_sql_stats['status'] ==  'Zurückgestellt')
																	{
																		$bg_color = "#8B4513";
																	}
																if($out_sql_stats['status'] ==  'undef')
																	{
																		$bg_color = "#FFFFFF";
																	}
																if($out_sql_stats['status'] ==  'News geschrieben')
																	{
																		$bg_color = "#00FF00";
																	}
																if($out_sql_stats['status'] ==  'Soll Geschenk bekommen')
																	{
																		$bg_color = "#ADD8E6";
																	}
																if($out_sql_stats['status'] ==  'Hat Geschenk bekommen')
																	{
																		$bg_color = "#0000CD";
																	}	
																if($out_sql_stats['status'] ==  'Angeschrieben')
																	{
																		$bg_color = "#FFA500";
																	}
																if($out_sql_stats['status'] ==  'Hat Bericht bekommen')
																	{
																		$bg_color = "#8B008B";
																	}
																if($out_sql_stats['status'] ==  'Ware erhalten')
																	{
																		$bg_color = "#088A08";
																	}		
																
																$status_text = wordwrap( $out_sql_stats['comment'], 100, '<br>', true );

															$output .= "
																	<tr style=' background-color: ".$bg_color.";'>
																		<td  height='18' style=' color:#000000;'>";
															$output .= "	<b>".$out_sql_stats['status'].":</b> ".$status_text." - ".$out_stats_user['vorname']." ".$out_stats_user['nachname']." (".$out_stats_user['nick'].") - ".date_mysql2german($out_sql_stats['date'])." - ".$out_sql_stats['time']." Uhr
																		</td>";

															if($DARF["del"])
															{ // Global Admin
																$output .="
																		<td align='right' >
																			<a href='?hide=1&action=del_stats&id=".$out_sql_stats['id']."&sponsor=".$out_stat_name['name']."' target='_parent'>
																			<img src='{BASEDIR}images/icons/delete.png' title='löschen'/></a>
																		</td>";
															}
															$output .="</tr>";

														}

										$output .="				</tbody>
															</table>
														</td>
													</tr>";
										
										
											$output .="
													</tbody>
												</table>
											</td>
										</tr>
													";
									}
										$output .="
								<tr>
											<td class='msgrow1' colspan='2'>
												&nbsp;
											</td>
										</tr>
								";
/////////////////////////////////////////////////////////////

								$output .="

										<tr>
											<td class='msghead' colspan='2'>
												<b>Neue ToDo's:</b>
											</td>
										</tr>
										<tr  >
											<td class='msgrow1' colspan='2' >
												<table width='100%'>
													<tbody>
														<tr>
															<td class='msghead'>
																ToDo
															</td>";
									
$output .="													<td  class='msghead'>

															</td>
														</tr>
														<tr>
															<form name='edittodo' action='?hide=1&action=edit_todo&comand=senden&event_id=".$selectet_event_id."&id=".$out_edit['id']."' method='POST'>
																<td class='msgrow1' >
																	<input name='sp_todo_todo' value=''  style='width: 100%;' type='text' >
																</td>";
									
$output .="														<td class='msgrow1' align='right'>
																	<input name='senden' value='ToDo hinzuf&uuml;gen' type='submit' style='text-align:right;' >
																</td>
															</form>
																";

												$output .="
														</tr>
													</tbody>
												</table>
											</td>
										</tr>";
										
														$sql_todo = mysql_query("
																					SELECT
																							*
																					FROM
																						project_sponsoren_todo
																					WHERE
																						s_id = '".$out_edit['id']."'
																					AND
																						event_id = '".$selectet_event_id."'
																					ORDER BY
																						date ASC,
																						time ASC
																				");
									if( mysql_num_rows($sql_todo) != 0)
									{
							$output .="	<tr >
											<td class='msghead' colspan='2'>
												<b>ToDo's:</b>
											</td>
										</tr>
										<tr>
											<td class='msgrow1' colspan='3'>

												<table  width='100%' cellspacing='0' cellpadding='0' border='0'>
													<tbody>";

														$i=1;
														while($out_sql_todo = mysql_fetch_array($sql_todo))
														{// begin while
															$out_stats_user = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = '".$out_sql_todo['u_id']."' "));
															$out_stat_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$out_sql_todo['s_id']."' "));

															$output .="<tr >";
															$output .= "
																		<td  class=\"msgrow".(($i%2)?1:2)."\"  height='18'>";
															$output .= "	".$out_sql_todo['sp_todo_todo']." ";
															$output .= "</td>";

															if($DARF["edit"])
															{ // Global Admin
																$checked = "Offen";
																if($out_sql_todo['checked'] == 1)
																{
																	$checked = "Erledigt";
																}
																$output .="
																		<td width='60' class=\"msgrow".(($i%2)?1:2)."\" align='left' width='20' >
																			<a href='?hide=1&action=edit_todo_checked&id=".$out_sql_todo['id']."&checked=".$out_sql_todo['checked']."&s_id=".$out_sql_todo['s_id']."' target='_parent'>
																			$checked </a>
																		</td>";
															}
															if($DARF["del"])
															{ // Global Admin
																$output .="
																		<td width='20' class=\"msgrow".(($i%2)?1:2)."\" align='right'>
																			<a  href='?hide=1&action=del_todo&id=".$out_sql_todo['id']."' target='_parent'>
																			<img src='{BASEDIR}images/icons/delete.png' title='löschen'/></a>
																		</td>";
															}
															
															$output .="</tr>";
														$i++;
														}

										$output .="	</tbody>
												</table>



											</td>
										</tr>";
									}


													
													
//////////////////////////////////////////////////////////////													
								$output .="
								<tr>
											<td class='msgrow1' colspan='2'>
												&nbsp;
											</td>
										</tr>
								";
							
								$output .="

										<tr>
											<td class='msghead' colspan='2'>
												<b>Gesponserte Artikel:</b>
											</td>
										</tr>
										<tr  >
											<td class='msgrow1' colspan='2' >
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
									if($out_edit['marke'])
									{															
$output .="													<td  class='msghead'>
																Marke
															</td>";
									}
$output .="													<td  class='msghead'>

															</td>
														</tr>
														<tr>
															<form name='editart' action='?hide=1&action=edit_art&comand=senden&event_id=".$selectet_event_id."&id=".$out_edit['id']."' method='POST'>
																<td class='msgrow1' >
																	<input name='sp_art_anz' value=''  style='width: 100%;' type='text' >
																</td>
																<td class='msgrow1'>
																	 <input name='sp_art_name' value=''  style='width: 100%;' type='text'>
																</td>
																<td class='msgrow1'>
																	 <input name='sp_art_wert' value=''  style='width: 50%;' type='text' >
																</td>";
																
									if($out_edit['marke'])
									{
$output .="														<td class='msgrow1'>
																				<select name='sp_art_marke'' >
																				<option value='' selected='selected'>Marke W&auml;hlen</option>
																					
																";
																$a = explode(",",$out_edit['marke']);
																
																foreach($a AS $marke ){
																
$output .="																<option value='$marke' >$marke</option>";
																}
																	 
$output .="														</td>";
									}
$output .="														<td class='msgrow1' align='right'>
																	<input name='senden' value='Artikel hinzuf&uuml;gen' type='submit' style='text-align:right;' >
																</td>
															</form>
																";

												$output .="
														</tr>
													</tbody>
												</table>
											</td>
										</tr>";
										
														$sql_artikel = mysql_query("
																					SELECT
																							*
																					FROM
																						project_sponsoren_artikel
																					WHERE
																						s_id = '".$out_edit['id']."'
																					AND
																						event_id = '".$selectet_event_id."'
																					ORDER BY
																						date DESC,
																						time DESC
																				");
									if( mysql_num_rows($sql_artikel) != 0)
									{
							$output .="											<tr >
											<td class='msghead' colspan='2'>
												<b>Gesponserte Artikel-Liste:</b>
											</td>
										</tr>
														
										<tr>
															<td class='msgrow2' >
																<b>".$out_event1['name']."</b>
															</td>
														</tr>
										<tr>
											<td class='msgrow1' colspan='3'>

												<table  width='100%' cellspacing='0' cellpadding='0' border='0'>
													<tbody>
														<tr>
															<td class='msghead'>
																Anzahl
															</td>
															<td class='msghead'>
																Artikel
															</td>
															<td class='msghead'>
																Preis
															</td>
															<td class='msghead'>
																Marke/Hersteller
															</td>
															<td class='msghead' width='80'>
																admin
															</td>
														</tr>
															
															
													";

														$i=1;
														while($out_sql_artikel = mysql_fetch_array($sql_artikel))
														{// begin while
															$out_stats_user = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = '".$out_sql_artikel['u_id']."' "));
															$out_stat_name = mysql_fetch_array(mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$out_sql_artikel['s_id']."' "));

															$output .="<tr class=\"msgrow".(($i%2)?1:2)."\">";															
															$output .= "<td>";
															$output .= $out_sql_artikel['sp_art_anz'];
															$output .= "</td>";
															$output .= "<td>";
															$output .= $out_sql_artikel['sp_art_name'];
															$output .= "</td>";
															$output .= "<td>";
															$output .= $out_sql_artikel['sp_art_wert']." &euro; ";
															$output .= "</td>";
															$output .= "<td>";
															if($out_sql_artikel['sp_art_marke']){
															
															$output .= ucfirst($out_sql_artikel['sp_art_marke'])."";
															}
															
															$output .= "</td>
																		<td >";

															if($DARF["edit"])
															{ // Global Admin
																$output .="
																		
																			<a href='?hide=1&action=edit_artikel&a_id=".$out_sql_artikel['id']."&s_id=".$out_sql_artikel['s_id']."' target='_parent'>
																			<img src='../images/16/lists.png' title='Artikel &auml;ndern'></a>
																		";
															}
															if($DARF["del"])
															{ // Global Admin
																$output .="
																		
																			<a href='?hide=1&action=del_artikel&id=".$out_sql_artikel['id']."' target='_parent'>
																			<img src='{BASEDIR}images/icons/delete.png' title='löschen'/></a>
																		";
															}
															$output .="</td>
															</tr>";
														$i++;
														}

										$output .="	</tbody>
												</table>



											</td>
										</tr>";
									}
									
									
									


									} // IF IS EDIT ENDE

					$output .= "
								</tbody>
							</table>";
							
	return $output;
	
}
?>