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
	
	$sql = "INSERT INTO `project_equipment` (id, invnr, bezeichnung, seriennummer, hersteller, category, besitzer, details, zusatzinfo, lagerort, kiste, ist_leihartikel, ist_kiste) 
			VALUES 	(NULL, '".$daten['invnr']."', '".$bezeichnung."', '".$daten['seriennummer']."', '".$daten['hersteller']."', '".$category."', '".$daten['besitzer']."', '".nl2br($daten['details'])."', '".$daten['zusatzinfo']."', '".$daten['lagerort']."', '".$daten['kiste']."', '".$daten['ist_leihartikel']."', '".$daten['ist_kiste']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
## /ADD ##

function equipment_edit($daten,$id)
{
	if($daten['category1'] != "" )
	{
		$category = $daten['category1'];
	}else
	{
		$category = $daten['category'];
	}
	if($daten['bezeichnung1'] != "" )
	{
		$bezeichnung = $daten['bezeichnung1'];
	}else
	{
		$bezeichnung = $daten['bezeichnung'];
	}
	$sql = "UPDATE project_equipment SET  `invnr` = '".$daten['invnr']."', `bezeichnung` = '".$bezeichnung."', `seriennummer` = '".$daten['seriennummer']."', `besitzer` = '".$daten['besitzer']."', `details` = '".nl2br($daten['details'])."', `zusatzinfo` = '".$daten['zusatzinfo']."', `hersteller` = '".$daten['hersteller']."', `category` = '".$category."', `lagerort` = '".$daten['lagerort']."', `kiste` = '".$daten['kiste']."', `ist_leihartikel` = '".$daten['ist_leihartikel']."' WHERE `id` = ".$id." ";
	$out =  mysql_query( $sql); 	
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
function addeq2kiste($id_kiste,$id)
{
	echo $sql = "UPDATE project_equipment SET  `kiste` = '".$id."' WHERE `id` = ".$id_kiste." ";
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
	$sql = "INSERT INTO `project_equipment_lagerort` (id, bezeichnung, details) 
			VALUES 	(NULL, '".$daten['bezeichnung']."', '".$daten['details']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
function equipment_edit_lagerort($daten,$id)
{
	$sql = "UPDATE project_equipment_lagerort SET  `bezeichnung` = '".$daten['bezeichnung']."', `details` = '".$daten['details']."' WHERE `id` = ".$id." ";
	$out =  mysql_query( $sql); 	
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
## KISTEN ##
function equipment_show_kisten()
{
	$sql = "SELECT * FROM `project_equipment` WHERE ist_kiste = '1'  ORDER BY `bezeichnung` ASC";
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
												Lagerort/Beh&aumllter
											</td>
											<td width='50' class='msghead'>
												Leihartikel?
											</td>
											";
											if($DARF["edit"] || $DARF["del"] )
											{
												$output .= "
													<td width='60' class='msghead'>	";

													if($DARF["add"] )
														{
												$output .= "
														<a href='?hide=1&action=add&add_cat=".$show_article[$group_by]."&bezeichnung1=".$_GET['bezeichnung1']."' >
															<img src='../images/16/db_add.png' title='Artikel in der Kategorie ".$show_article[$group_by]." mit Bezeichnung ".$_GET['bezeichnung1']." anlegen' >
														</a>
														<a href='barcode.php?size=29&category=".$show_article[$group_by]."' target='_NEW'>
															<img src='../images/16/barcode.png' title='Barcode 29mm in der Kategorie ".$show_article[$group_by]." drucken!'>
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
							$farbe = "#e6e6e6";

						}
						else
						{
							$currentRowClass = "msgrow1";
							$farbe = "#ffffff";
						}
								//onclick="document.location = "\"?hide=1&action=anzeigen&id='.$out_show_article['id'].'\";"
								$output .= "
								<tr ";
								$output .= ' onclick="document.location = \'?hide=1&action=anzeigen&id='.$out_show_article['id'].'\' ";  ';
								$output .= ' onmouseover="this.style.background=\'#c33333\'; this.style.cursor=\'pointer\';" ';
								$output .= ' onmouseout="this.style.background=\''.$farbe.'\'" ';
								$output .= ' title="Klicken um Details des Artikels anzuzeigen" class="'.$currentRowClass.'">';
$output .= "								
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
													</tr>";
												if($out_show_article['hersteller']){
										$output .= "<tr>
														<td><b>Hersteller</b></td>
														<td>".$out_show_article['hersteller']."</td>
													</tr>";
												}
												if($out_show_article['seriennummer']){
										$output .= "<tr>
														<td><b>Seriennummer</b></td>
														<td>".$out_show_article['seriennummer']."</td>
													</tr>";
												}
										/*		if($out_show_article['bezeichnung']){
										$output .= "<tr>
														<td><b>Beh&auml;lter:</b></td>
														<td>".$out_kiste['bezeichnung']."</td>
													</tr>";
												}
										*/
												if($out_show_article['zusatzinfo']){
										$output .= "<tr>
														<td><b>Zusatz Info</b></td>
														<td>".$out_show_article['zusatzinfo']."</td>
													</tr>";
												}
$output .= "			
													
												</tbody>
										</table>
									</td>

									<td>
										".$out_show_article['besitzer']."
									</td>
									<td>
										".$out_lagerort['bezeichnung']."
										<br>
										".$out_kiste['bezeichnung']."
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
												<a href='barcode.php?size=29&kiste=".$out_show_article['id']."' target='_NEW'>
													<img src='../images/16/barcode.png' title='Barcode 29mm des Beh&auml;lters Drucken!'>
												</a>
												";
											}
											else
											{
												$output .= "
												<a href='barcode.php?size=29&id=".$out_show_article['id']."' target='_NEW'>
													<img src='../images/16/barcode.png' title='Barcode 29mm Drucken!'>
												</a>
												<a href='barcode.php?size=12&id=".$out_show_article['id']."' target='_NEW'>
													<img src='../images/16/barcode.png' title='Barcode 12mm Drucken!'>
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
		if(mysql_num_rows($sql_show_article) > 0)
		{
		$kiste = list_equipment_single($id);
		//$show_article =  mysql_fetch_array( mysql_query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$show_cat."' AND bezeichnung = '".$bezeichnung1."' ORDER BY invnr ASC"));
		$output .= "
				<table  cellspacing='1' cellpadding='2' border='0' class='shortbar'>
					 <tbody>
						<tr class='shortbarrow'>
							<td width='150' class='shortbarbit'><a href='index.php?hide=1&hide1=1&action=eqtokiste&do=1&kiste=".$id."' class='shortbarbitlink'>Artikel dem Beh&auml;lter hinzuf&uuml;gen</a></td>
						";
						
						if($DARF["edit"] )
						{	
							$output .= "<td width='25' class='shortbarbit'>
											<a  href='barcode.php?size=29&kiste=".$kiste['id']."' target='_parent'>
												<img src='../images/16/barcode.png' title='Barcode 29mm des Beh&auml;lters ".$kiste['bezeichnung']." drucken' ></a>
										</td>";
						}
						
		$output .= "
						</tr>
					</tbody>
				</table>
				";

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
													<td width='60' class='msghead'>	";
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
							$farbe = "#e6e6e6";

						}
						else
						{
							$currentRowClass = "msgrow1";
							$farbe = "#ffffff";
						}
								//onclick="document.location = "\"?hide=1&action=anzeigen&id='.$out_show_article['id'].'\";"
								$output .= "
								<tr ";
								$output .= ' onclick="document.location = \'?hide=1&action=anzeigen&id='.$out_show_article['id'].'\' ";  ';
								$output .= ' onmouseover="this.style.background=\'#c33333\'; this.style.cursor=\'pointer\';" ';
								$output .= ' onmouseout="this.style.background=\''.$farbe.'\'" ';
								$output .= ' title="Klicken um Details des Artikels anzuzeigen" class="'.$currentRowClass.'">';
$output .= "								
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
														<td><b>Beh&auml;lter:</b></td>
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
											<a href='barcode.php?size=29&id=".$out_show_article['id']."' target='_NEW'>
												<img src='../images/16/barcode.png' title='Barcode 29mm Drucken!'>
											</a>
											<a href='barcode.php?size=12&id=".$out_show_article['id']."' target='_NEW'>
												<img src='../images/16/barcode.png' title='Barcode 12mm Drucken!'>
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
							</table>";
		}
		else
		{
			$output .= "<p align='center'> Keine Daten gefunden!</p>";
		}
		
				$output .= "							
							<br />
							<a href='index.php?hide=1&action=kisten'> Zur&uuml;ck</a>";
							
				return $output;

}


function show_kiste_inhalt($id)
{

	$sql = mysql_query("SELECT * FROM `project_equipment` WHERE kiste = '".$id."' ");
	$kiste = list_equipment_single($id);
	
	$output .= "
				<table>
					<tbody>
						<tr>
							<td colspan='2' class='msghead'>
								Inhalt ".$kiste['bezeichnung']."
							</td>
						</tr>";
						while($out =  mysql_fetch_array($sql))
						{
	$output .= "
						<tr>
							<td>
								".$out['bezeichnung']."
							</td>
							<td>
								eq".sprintf("%06d",$out['id'])."
							</td>
						</tr>
	";					}
						
	$output .= "						
					</tbody>
				</table>
				<br>
	";
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
							$farbe = "#e6e6e6";

						}
						else
						{
							$currentRowClass = "msgrow1";
							$farbe = "#ffffff";
						}
								//onclick="document.location = "\"?hide=1&action=anzeigen&id='.$out_show_article['id'].'\";"
								$output .= "
								<tr ";
								$output .= ' onclick="document.location = \'?hide=1&action=anzeigen&id='.$out_show_article['id'].'\' ";  ';
								$output .= ' onmouseover="this.style.background=\'#c33333\'; this.style.cursor=\'pointer\';" ';
								$output .= ' onmouseout="this.style.background=\''.$farbe.'\'" ';
								$output .= ' title="Klicken um Details des Artikels anzuzeigen" class="'.$currentRowClass.'">';
$output .= "								
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
														<td><b>Beh&auml;lter:</b></td>
														<td>".$out_kiste['bezeichnung']."</td>
													</tr>
													<!-- <tr>
														<td><b>Zusatz Info</b></td>
														<td>".$out_show_article['zusatzinfo']."</td>
													</tr> -->
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
											<a href='barcode.php?size=29&id=".$out_show_article['id']."' target='_NEW'>
												<img src='../images/16/barcode.png' title='Barcode 29mm Drucken!'>
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