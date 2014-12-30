<?php
## Equipment ## 
function list_equipment()
{
	$sql = "SELECT * FROM `project_equipment`";
	$out =  mysql_query($sql);
	return $out;
}
function list_equipment_single($id)
{
	$sql = "SELECT * FROM `project_equipment` WHERE id = '".$id."' ";
	$out =  mysql_fetch_array(mysql_query($sql));
	return $out;
}

## ADD ##
function equipment_add($daten)
{
	if($daten['category1'] <> "" )
	{
		$category = $daten['category1'];
	}else
	{
		$category = $daten['category'];
	}
	if($daten['bezeichnung1'] <> "" )
	{
		$bezeichnung = $daten['bezeichnung1'];
	}else
	{
		$bezeichnung = $daten['bezeichnung'];
	}
	
	$sql = "INSERT INTO `project_equipment` (id, invnr, bezeichnung, hersteller, category, besitzer, details, zusatzinfo, lagerort, kiste, ist_leihartikel, ist_kiste) 
			VALUES 	(NULL, '".$daten['invnr']."', '".$bezeichnung."', '".$daten['hersteller']."', '".$category."', '".$daten['besitzer']."', '".$daten['details']."', '".$daten['zusatzinfo']."', '".$daten['lagerort']."', '".$daten['kiste']."', '".$daten['ist_leihartikel']."', '".$daten['ist_kiste']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
## /ADD ##

function equipment_edit($daten,$id)
{
	if($daten['category1'] <> "" )
	{
		$category = $daten['category1'];
	}else
	{
		$category = $daten['category'];
	}
	if($daten['bezeichnung1'] <> "" )
	{
		$bezeichnung = $daten['bezeichnung1'];
	}else
	{
		$bezeichnung = $daten['bezeichnung'];
	}
	$sql = "UPDATE project_equipment SET  `invnr` = '".$daten['invnr']."', `bezeichnung` = '".$bezeichnung."', `besitzer` = '".$daten['besitzer']."', `details` = '".$daten['details']."', `zusatzinfo` = '".$daten['zusatzinfo']."', `hersteller` = '".$daten['hersteller']."', `category` = '".$category."', `lagerort` = '".$daten['lagerort']."', `kiste` = '".$daten['kiste']."', `ist_leihartikel` = '".$daten['ist_leihartikel']."' WHERE `id` = ".$id." ";
	$out =  mysql_query( $sql); 	
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}

## LAGERORT ##
function list_equipment_lagerort_single($id)
{
	$sql = "SELECT * FROM `project_equipment_lagerort` WHERE id = '".$id."' ";
	$out =  mysql_fetch_array(mysql_query($sql));
	return $out;
}
function equipment_add_lagerort($daten)
{
	$sql = "INSERT INTO `project_equipment_lagerort` (id, bezeichnung) 
			VALUES 	(NULL, '".$daten['bezeichnung']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
function equipment_edit_lagerort($daten,$id)
{
	$sql = "UPDATE project_equipment_lagerort SET  `bezeichnung` = '".$daten['bezeichnung']."' WHERE `id` = ".$id." ";
	$out =  mysql_query( $sql); 	
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
## KISTEN ##
function equipment_show_kisten()
{
	$sql = "SELECT * FROM `project_equipment` WHERE ist_kiste = '1' ";
	$out =  mysql_query($sql);
	return $out;
}

## Show ##

function show($group_by,$show_cat,$bezeichnung1,$DARF)
{

		$sql_show_article = mysql_query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$show_cat."' AND bezeichnung = '".$bezeichnung1."' ORDER BY invnr ASC");
		$show_article =  mysql_fetch_array( mysql_query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$show_cat."' AND bezeichnung = '".$bezeichnung1."' ORDER BY invnr ASC"));


				$output .= "<h1 style='margin: 5px 0px 5px;'>
								<a name='".$show_article[$group_by]."'><b>".$show_article[$group_by]."</b></a> - <a href='#top'>top</a>
							</h1>";
				
				$output .= "	<table  class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
									<tbody>
										<tr>
											<td width='350'  class='msghead'>
												Details
											</td>
											<td width='100' class='msghead'>
												Besitzer
											</td>
											<td width='100' class='msghead'>
												Lagerort
											</td>
											<td width='50' class='msghead'>
												Leihartikel?
											</td>
											";
											if($DARF["edit"] || $DARF["del"] )
											{
												$output .= "
													<td width='45' class='msghead'>	";

													if($DARF["add"] )
														{
												$output .= "
														<a href='?hide=1&action=add&add_cat=".$show_article[$group_by]."' >
															<img src='../images/16/db_add.png' title='Artikel in der Kategorie ".$show_article[$group_by]." anlegen' >
														</a>
														<a href='barcode.php?category=".$out_show_article['category']."' target='_NEW'>
															<img src='../images/16/printmgr.png' title='Barcode in der Kategorie ".$show_article[$group_by]." drucken!'>
														</a>
														";
														}
														$output .= "
														</td>";
											}
									$output .="
										</tr>
								";
					$iCounter = 1;
				//$num_rows = mysql_num_rows($out_list_bezeichnung);
				while($out_show_article = mysql_fetch_array($sql_show_article))
					{// begin while
						
						if(is_numeric($out_show_article['lagerort']) )
						{
							$out_lagerort 	= mysql_fetch_array(mysql_query("SELECT * FROM project_equipment_lagerort WHERE id = ".$out_show_article['lagerort']." "));
						}
						if(is_numeric($out_show_article['kiste']))
						{
							$out_kiste		= mysql_fetch_array(mysql_query("SELECT * FROM project_equipment WHERE id = ".$out_show_article['kiste'].""));
						}
						if($iCounter % 2 == 0)
						{
							$currentRowClass = "msgrow2";

						}
						else
						{
							$currentRowClass = "msgrow1";
						}


								$output .= "

								<tr class='".$currentRowClass."' title='".$out_show_article['details']."'>
									<td>
										<table >
												<tbody>
													<tr>
														<td><b>Bezeichnung:</b></td>
														<td>".$out_show_article['bezeichnung']."</td>
													</tr>
													<tr>
														<td><b>Inventar-Nr.</b></td>
														<td>eq".sprintf("%06d",$out_show_article['id'])."</td>
													</tr>
													<tr>
														<td><b>Hersteller</b></td>
														<td>".$out_show_article['hersteller']."</td>
													</tr>
													<tr>
														<td><b>Kiste:</b></td>
														<td>".$out_kiste['bezeichnung']."</td>
													</tr>
												</tbody>
										</table>
									</td>

									<td>
										".$out_show_article['besitzer']."
									</td>
									<td>
										".$out_lagerort['bezeichnung']."
									</td>
									<td align='center'>";
									if($out_show_article['ist_leihartikel'] == 1)
									{
										$output .= " <b> JA </b>";
									}
									else
									{
										$output .= " <b> NEIN </b>";
									}
$output .= "									
									</td>
									";

									if($DARF["edit"] || $DARF["del"] )
									{
										$output .= "

										<td >";

										if($DARF["edit"] )
										{
											$output .= "

											<a href='?hide=1&action=edit&id=".$out_show_article['id']."' target='_parent'>
												<img src='../images/16/edit.png' title='Details anzeigen/&auml;ndern' ></a>
												";
										}
										if($DARF["del"] )
										{
											$output .= "
											<a href='?hide=1&action=del&id=".$out_show_article['id']."' target='_parent'>
												<img src='../images/16/editdelete.png' title='".$out_show_article['invnr']." l&ouml;schen'>
											</a>
											";
										}
										if($DARF["edit"] )
										{
											if($out_show_article['ist_kiste'] == 1)
											{
												$output .= "
												<a href='barcode_kiste.php?id=".$out_show_article['id']."' target='_NEW'>
													<img src='../images/16/printmgr.png' title='Barcode der Kiste Drucken!'>
												</a>
												";
											}
											else
											{
												$output .= "
												<a href='barcode.php?id=".$out_show_article['id']."' target='_NEW'>
													<img src='../images/16/printmgr.png' title='Barcode Drucken!'>
												</a>
												";
											}
										}
										$output .= "
										</td>";
									}
									$output .="
								</tr>
								";

				$iCounter ++;
				} // end while

				$output .= "			</tbody>
							</table>
							<br />
							<a href='?view=equipment&group_by=".$group_by."#".$show_article['category']."'> Zur&uuml;ck zu ".$show_article['category']."</a>";
							
				return $output;

}

function show_kiste($id,$DARF)
{
		$sql_show_article = mysql_query("SELECT * FROM project_equipment WHERE kiste = '".$id."' ");
		$kiste = list_equipment_single($id);
		//$show_article =  mysql_fetch_array( mysql_query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$show_cat."' AND bezeichnung = '".$bezeichnung1."' ORDER BY invnr ASC"));


				$output .= "<h1 style='margin: 5px 0px 5px;'>
								<a name='".$kiste['bezeichnung']."'><b>".$kiste['bezeichnung']."</b></a> - <a href='#top'>top</a>
							</h1>";
				
				$output .= "	<table  class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
									<tbody>
										<tr>
											<td width='350'  class='msghead'>
												Details
											</td>
											<td width='100' class='msghead'>
												Besitzer
											</td>
											<td width='100' class='msghead'>
												Lagerort
											</td>
											<td width='50' class='msghead'>
												Leihartikel?
											</td>
											";
											if($DARF["edit"] || $DARF["del"] )
											{
												$output .= "
													<td width='45' class='msghead'>	";
													$output .= "
														</td>";
											}
									$output .="
										</tr>
								";
					$iCounter = 1;
				//$num_rows = mysql_num_rows($out_list_bezeichnung);
				while($out_show_article = mysql_fetch_array($sql_show_article))
					{// begin while
						
						if(is_numeric($out_show_article['lagerort']) )
						{
							$out_lagerort 	= mysql_fetch_array(mysql_query("SELECT * FROM project_equipment_lagerort WHERE id = ".$out_show_article['lagerort']." "));
						}
						if(is_numeric($out_show_article['kiste']))
						{
							$out_kiste		= mysql_fetch_array(mysql_query("SELECT * FROM project_equipment WHERE id = ".$out_show_article['kiste'].""));
						}
						if($iCounter % 2 == 0)
						{
							$currentRowClass = "msgrow2";

						}
						else
						{
							$currentRowClass = "msgrow1";
						}


								$output .= "

								<tr class='".$currentRowClass."' title='".$out_show_article['details']."'>
									<td>
										<table >
												<tbody>
													<tr>
														<td><b>Bezeichnung:</b></td>
														<td>".$out_show_article['bezeichnung']."</td>
													</tr>
													<tr>
														<td><b>Inventar-Nr.</b></td>
														<td>eq".sprintf("%06d",$out_show_article['id'])."</td>
													</tr>
													<tr>
														<td><b>Hersteller</b></td>
														<td>".$out_show_article['hersteller']."</td>
													</tr>
													<tr>
														<td><b>Kiste:</b></td>
														<td>".$out_kiste['bezeichnung']."</td>
													</tr>
												</tbody>
										</table>
									</td>

									<td>
										".$out_show_article['besitzer']."
									</td>
									<td>
										".$out_lagerort['bezeichnung']."
									</td>
									<td align='center'>";
									if($out_show_article['ist_leihartikel'] == 1)
									{
										$output .= " <b> JA </b>";
									}
									else
									{
										$output .= " <b> NEIN </b>";
									}
$output .= "									
									</td>
									";

									if($DARF["edit"] || $DARF["del"] )
									{
										$output .= "

										<td >";

										if($DARF["edit"] )
										{
											$output .= "

											<a href='?hide=1&action=edit&id=".$out_show_article['id']."' target='_parent'>
												<img src='../images/16/edit.png' title='Details anzeigen/&auml;ndern' ></a>
												";
										}
										if($DARF["del"] )
										{
											$output .= "
											<a href='?hide=1&action=del&id=".$out_show_article['id']."' target='_parent'>
												<img src='../images/16/editdelete.png' title='".$out_show_article['invnr']." l&ouml;schen'>
											</a>
											";
										}
										if($DARF["edit"] )
										{
											$output .= "
											<a href='barcode.php?id=".$out_show_article['id']."' target='_NEW'>
												<img src='../images/16/printmgr.png' title='Barcode Drucken!'>
											</a>
											";
										}
										$output .= "
										</td>";
									}
									$output .="
								</tr>
								";

				$iCounter ++;
				} // end while

				$output .= "			</tbody>
							</table>
							<br />
							<a href='?view=equipment&group_by=".$group_by."#".$show_article['category']."'> Zur&uuml;ck zu ".$show_article['category']."</a>";
							
				return $output;

}

## Equipment ###

function show_equipment($id,$DARF)
{
		$sql_show_article = mysql_query("SELECT * FROM project_equipment WHERE id = '".$id."' ");
		$kiste = list_equipment_single($id);
		//$show_article =  mysql_fetch_array( mysql_query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$show_cat."' AND bezeichnung = '".$bezeichnung1."' ORDER BY invnr ASC"));


				$output .= "<h1 style='margin: 5px 0px 5px;'>
								<a name='".$kiste['bezeichnung']."'><b>".$kiste['bezeichnung']."</b></a> - <a href='#top'>top</a>
							</h1>";
				
				$output .= "	<table  class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
									<tbody>
										<tr>
											<td width='350'  class='msghead'>
												Details
											</td>
											<td width='100' class='msghead'>
												Besitzer
											</td>
											<td width='100' class='msghead'>
												Lagerort
											</td>
											<td width='50' class='msghead'>
												Leihartikel?
											</td>
											";
											if($DARF["edit"] || $DARF["del"] )
											{
												$output .= "
													<td width='45' class='msghead'>	";

													
														$output .= "
														</td>";
											}
									$output .="
										</tr>
								";
					$iCounter = 1;
				//$num_rows = mysql_num_rows($out_list_bezeichnung);
				while($out_show_article = mysql_fetch_array($sql_show_article))
					{// begin while
						
						if(is_numeric($out_show_article['lagerort']) )
						{
							$out_lagerort 	= mysql_fetch_array(mysql_query("SELECT * FROM project_equipment_lagerort WHERE id = ".$out_show_article['lagerort']." "));
						}
						if(is_numeric($out_show_article['kiste']))
						{
							$out_kiste		= mysql_fetch_array(mysql_query("SELECT * FROM project_equipment WHERE id = ".$out_show_article['kiste'].""));
						}
						if($iCounter % 2 == 0)
						{
							$currentRowClass = "msgrow2";

						}
						else
						{
							$currentRowClass = "msgrow1";
						}


								$output .= "

								<tr class='".$currentRowClass."' title='".$out_show_article['details']."'>
									<td>
										<table >
												<tbody>
													<tr>
														<td><b>Bezeichnung:</b></td>
														<td>".$out_show_article['bezeichnung']."</td>
													</tr>
													<tr>
														<td><b>Inventar-Nr.</b></td>
														<td>eq".sprintf("%06d",$out_show_article['id'])."</td>
													</tr>
													<tr>
														<td><b>Hersteller</b></td>
														<td>".$out_show_article['hersteller']."</td>
													</tr>
													<tr>
														<td><b>Kiste:</b></td>
														<td>".$out_kiste['bezeichnung']."</td>
													</tr>
												</tbody>
										</table>
									</td>

									<td>
										".$out_show_article['besitzer']."
									</td>
									<td>
										".$out_lagerort['bezeichnung']."
									</td>
									<td align='center'>";
									if($out_show_article['ist_leihartikel'] == 1)
									{
										$output .= " <b> JA </b>";
									}
									else
									{
										$output .= " <b> NEIN </b>";
									}
$output .= "									
									</td>
									";

									if($DARF["edit"] || $DARF["del"] )
									{
										$output .= "

										<td >";

										if($DARF["edit"] )
										{
											$output .= "

											<a href='?hide=1&action=edit&id=".$out_show_article['id']."' target='_parent'>
												<img src='../images/16/edit.png' title='Details anzeigen/&auml;ndern' ></a>
												";
										}
										if($DARF["del"] )
										{
											$output .= "
											<a href='?hide=1&action=del&id=".$out_show_article['id']."' target='_parent'>
												<img src='../images/16/editdelete.png' title='".$out_show_article['invnr']." l&ouml;schen'>
											</a>
											";
										}
										if($DARF["edit"] )
										{
											$output .= "
											<a href='barcode.php?id=".$out_show_article['id']."' target='_NEW'>
												<img src='../images/16/printmgr.png' title='Barcode Drucken!'>
											</a>
											";
										}
										$output .= "
										</td>";
									}
									$output .="
								</tr>
								";

				$iCounter ++;
				} // end while

				$output .= "			</tbody>
							</table>
							<br />
							<a href='?view=equipment&group_by=".$group_by."#".$show_article['category']."'> Zur&uuml;ck zu ".$show_article['category']."</a>";
							
				return $output;

}
?>