<?php
########################################################################
# Equipment Verwaltungs Modul for dotlan                               #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@cegbers.de>            #
#                                                                      #
########################################################################


$MODUL_NAME = "equipment";
include_once("../../../global.php");
include("../functions.php");
include("equipment_functions.php");
include("header.php");


$iCounter = 0;

$PAGE->sitetitle = $PAGE->htmltitle = _("Equipment");

$event_id = $EVENT->next;
$EVENT->getevent($event_id);


$bezeichnung		=  security_string_input($_POST['bezeichnung']);
$bezeichnung1		=  security_string_input($_GET['bezeichnung1']);
$details			=  security_string_input($_POST['details']);
$zusatzinfo			=  security_string_input($_POST['zusatzinfo']);
$besitzer			=  security_string_input($_POST['besitzer']);
$hersteller			=  security_string_input($_POST['hersteller']);
$lagerort			=  security_string_input($_POST['lagerort']);
$kiste				=  security_string_input($_POST['kiste']);
$ist_leihartikel	=  security_number_int_input($_POST['ist_leihartikel'],"","");
$ist_kiste			=  security_number_int_input($_POST['ist_kiste'],"","");

$cat			=  security_string_input($_POST['category']);
$cat1			=  security_string_input($_POST['category1']);
$add_cat		=  security_string_input($_GET['add_cat']);
$edit_cat		=  security_string_input($_GET['edit_cat']);
$breite 		= "100%";


$group_by = "category";
if (isset($_GET['group_by']))
{
$group_by = $_GET['group_by'];
}

$id				= $_GET['id'];
$show_cat				= $_GET['show_cat'];

 /*###########################################################################################
Admin PAGE
*/


if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{

 		if($DARF["view"])
		{ //$ADMIN

			if($_GET['hide'] != 1)
			{ // hide
			$sql_list_category = mysql_query("SELECT * FROM project_equipment GROUP BY ".$group_by." ");
	 		$sql_list_category_dlink = mysql_query("SELECT * FROM project_equipment GROUP BY ".$group_by." ");
$output .= "				<hr>
				Gruppierung:
				<a href='?group_by=kiste'>nach Beh&auml;lter</a> | <a href='?group_by=category'>nach Kategorie</a> | <a href='?group_by=besitzer'>nach Besitzer</a> | <a href='?group_by=lagerort'>nach Lagerort</a>
				<br>
				<br>
				<b>DirektLink:</b>

			";

			while($out_list_category_dlink = mysql_fetch_array($sql_list_category_dlink))
					{// begin while

				$output .= "
				<a href='#".$out_list_category_dlink[$group_by]."'>".$out_list_category_dlink[$group_by]."</a> &nbsp;&nbsp;";
					}


		while($out_list_category = mysql_fetch_array($sql_list_category))
					{// begin while
					
				
					$output .= "
	
				<table width='100%' cellspacing='1' cellpadding='2' border='0' style='border-bottom-color:#99CC00;border-bottom-style:solid;border-bottom-width:2px;'>
					<tbody>
						<tr>
							<td>
								<a style='font-family: Arial,Helvetica,sans-serif,Verdana;font-size: 18px;font-weight: normal; line-height: 20px;' name='".$out_list_category[$group_by]."'>
									<b>
										".$out_list_category[$group_by]."
									</b>
								</a> 
							
								 - <a href='#top'>top</a> |
							
								<a title='Kategorie ".$out_list_category['category']." editieren/&auml;ndern' href='?hide=1&action=edit_cat&edit_cat=".$out_list_category[$group_by]."'> edit </a>
							</td>
						</tr>
					</tbody>
				</table>
				";

					$sql_list_bezeichnung = mysql_query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$out_list_category[$group_by]."'  GROUP BY bezeichnung");


					$output .= "

								<table  class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
									<tbody>
										<tr>
											<td width='50'  class='msghead'>
												Anzahl
											</td>
											<td width='350'  class='msghead'>
												Details
											</td>
											<td width='10' class='msghead'>";
											if($DARF["add"] )
												{
										$output .= "
												<a href='?hide=1&action=add&add_cat=".$out_list_category[$group_by]."' >
													<img src='../images/16/db_add.png' title='Artikel in der Kategorie ".$out_list_category[$group_by]." anlegen' >
												</a>
												<a href='barcode.php?category=".$out_list_category[$group_by]."' target='_NEW'>
															<img src='../images/16/printmgr.png' title='Barcode in der Kategorie ".$out_list_category[$group_by]." drucken!'>
														</a>
												";
												}

												$output .= "</td>

										</tr>
								";

				//$num_rows = mysql_num_rows($sql_list_anzahl);
				while($out_list_bezeichnung = mysql_fetch_array($sql_list_bezeichnung))
					{// begin while
					$num_rows = mysql_num_rows( $sql_list_anzahl = mysql_query("SELECT bezeichnung FROM project_equipment WHERE bezeichnung = '".$out_list_bezeichnung['bezeichnung']."' AND ".$group_by." = '".$out_list_category[$group_by]."'") );
					$kiste = list_equipment_single($out_list_bezeichnung['kiste']);
					if($iCounter % 2 == 0)
						{
							$currentRowClass = "msgrow1";
							$farbe = "#ffffff";

						}
						else
						{
							$currentRowClass = "msgrow2";
							$farbe = "#e6e6e6";
						}


								$output .= "

								<tr ";
								$output .= ' onclick="document.location = \'?hide=1&action=show&bezeichnung1='.$out_list_bezeichnung['bezeichnung'].'&show_cat='.$out_list_bezeichnung[$group_by].'&group_by='.$group_by.' \' ";  
											';
								$output .= ' onmouseover="this.style.background=\'#c33333\'; this.style.cursor=\'pointer\';" ';
								$output .= ' onmouseout="this.style.background=\''.$farbe.'\'" ';
								$output .= ' title="Klicken um alle Artikel '.$out_list_bezeichnung['bezeichnung'].' anzuzeigen" class="'.$currentRowClass.'">';
									$output .= "
									<td >
												".$num_rows."
									</td>
									<td>
									<table width='100%' border='0' >
											<tbody>
												<tr>
													<td colspan='2'>
														<b>".$out_list_bezeichnung['bezeichnung']."</b>
													</td>
												</tr>
												<tr>
													<td  width='60'><b>Hersteller</b></td>
													<td>".$out_list_bezeichnung['hersteller']."</td>
												</tr>";
									if($out_list_bezeichnung['ist_leihartikel'] == 1)
									{
										$output .= "
												<tr align='right'>
													<td colspan='2' >
														<b><font style='color:RED;'> Leihartikel vorhanden!</font></b> 
													</td>												
												</tr>";	
									}
$output .= "							</tbody>
									</table>
									</td>
									
									<td >
												
									</td>
								</tr>
								";

				$iCounter ++;
				}
				$output .= "			</tbody>
							</table>
							<br />";
				
				} // end while





			}  // hide ende


}
if($_GET['hide'] == "1")
{

//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// DEL BEGIN
	if($_GET['action'] == 'del'  )
	{
		if (!$DARF["del"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

			if($_GET['comand'] == 'senden')

		{
			$del=mysql_query("DELETE FROM project_equipment WHERE id = '".$_GET['id']."'");
			$meldung = "Daten gelöscht";
			$PAGE->redirect($dir."?group_by=".$group_by."&view=equipment#".$category,$PAGE->sitetitle,$meldung);
		}


		$new_id = $_GET['id'];
		$out_list_name = mysql_fetch_array(mysql_query("SELECT bezeichnung FROM project_equipment WHERE id = '".$new_id."' LIMIT 1"));

	$output .= "

				<h2 style='color:RED;'>Achtung!!!!<h2>
				<br />

				<p>Sind Sie sich sicher das
				<font style='color:RED;'>".$out_list_name['bezeichnung']."</fon> gel&ouml;scht werden soll?</p>
				<br />
				<a href='?hide=1&action=del&comand=senden&id=".$new_id."&group_by=".$group_by."' target='_parent'>
				<input value='l&ouml;schen' type='button'></a>
				 \t
				<a href='?view=equipment&group_by=".$group_by."' target='_parent'>
				<input value='Zur&uuml;ck' type='button'></a>




			";




	}
// DEL ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ADD / EDIT BEGIN

	if( $_GET['action'] == 'add' || $_GET['action'] == 'edit' )
	{
		if (!$DARF["add"] || !$DARF["edit"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
	
		if($_GET['action'] == 'edit')
		{
			$out_edit_epuipment =  mysql_fetch_array( mysql_query("SELECT * FROM project_equipment WHERE id = ".$id."") );
		}
		if($_GET['action'] == 'add' && $_GET['comand'] == 'senden')
		{
			$meldung 	= equipment_add($_POST);
			$PAGE->redirect($dir."?group_by=".$group_by."&view=equipment#".$category,$PAGE->sitetitle,$meldung);
			//$output .= "<meta http-equiv='refresh' content='0; URL=?group_by=".$group_by."&view=equipment#".$category."'>";
			
		}

		if( $_GET['action'] == 'edit' && $_GET['comand'] == 'senden')
		{
			$meldung 	= equipment_edit($_POST,$id);
			$PAGE->redirect($dir."?view=equipment#".$category,$PAGE->sitetitle,$meldung);
		}
		

$output .= "
							<form name='".$_GET['action']."equip' action='?hide=1&action=".$_GET['action']."&comand=senden&id=".$id."' method='POST'>
							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td colspan='2' class='msghead'>
											Artikel Daten
										</td>
									</tr>";
									if($_GET['action'] != "add")
									{
$output .="									
									<tr class='shortbarrow'>
										<td class='msgrow1'><b>Inventar-Nr.*</b></td>
										<td class='msgrow1'>
											eq".sprintf("%06d",$out_edit_epuipment['id'])."  <!-- Eine vom System vergebene Nr. -->
										</td>
									</tr>";
									}
$output .="																
									<tr>
										<td class='msgrow1'><b>Artikelbezeichnung*</b></td>
										<td class='msgrow1'>
											<select name='bezeichnung'>
										<option value=''>w&auml;hlen</option>";

										$sql_list_bezeichnung = mysql_query("SELECT bezeichnung FROM project_equipment GROUP BY bezeichnung ASC");
										while($out_list_bezeichnung = mysql_fetch_array($sql_list_bezeichnung))
										{// begin while
													if($out_list_bezeichnung['bezeichnung'] == $out_edit_epuipment['bezeichnung'] || $out_list_bezeichnung['bezeichnung'] == $_GET['bezeichnung1'])
													{
												$output .= "<option value='".$out_list_bezeichnung['bezeichnung']."' selected>".$out_list_bezeichnung['bezeichnung']."</option>";
													}
													else
													{
												$output .= "<option value='".$out_list_bezeichnung['bezeichnung']."'>".$out_list_bezeichnung['bezeichnung']."</option>";
													}
										}

							$output .= "
										</select>
										oder neu eintragen
										<input name='bezeichnung1' value='' size='25' type='text' maxlength='50'>
										</td>
									</tr>
									<tr>
										<td class='msgrow1'><b>Hersteller</b></td>
										<td class='msgrow1'><input name='hersteller' value='".$out_edit_epuipment['hersteller']."' size='25' type='text' maxlength='50'></td>
									</tr>
									<tr>
										<td><b>Kategorie</b></td>
										<td class='msgrow1'>

										<select name='category'>
										<option value=''>w&auml;hlen</option>";

										$sql_list_category = mysql_query("SELECT category FROM project_equipment GROUP BY category ASC");
										while($out_list_category = mysql_fetch_array($sql_list_category))
										{// begin while
													if($out_list_category['category'] == $out_edit_epuipment['category'] || $out_list_category['category'] == $_GET['add_cat'])
													{
												$output .= "<option value='".$out_list_category['category']."' selected>".$out_list_category['category']."</option>";
													}
													else
													{
												$output .= "<option value='".$out_list_category['category']."'>".$out_list_category['category']."</option>";
													}
										}

							$output .= "
										</select>
										oder neu eintragen
										<input name='category1' value='' size='15' type='text' maxlength='25'>

										</td>
									</tr>
									<tr>
										<td valign='top'><b>Details</b></td>
										<td  class='msgrow1' >
										   <textarea name='details' wrap='hard' cols='40' rows='10'>".$out_edit_epuipment['details']."</textarea>
										</td>
									</tr>
									<tr>
										<td><b>Zusatzinfo*</b></td>
										<td class='msgrow1' >
											<input name='zusatzinfo' value='".$out_edit_epuipment['zusatzinfo']."' size='50' type='text' maxlength='50'>
										</td>
									</tr>
									<tr>
										<td><b>Besitzer</b></td>
										<td class='msgrow1' >
											<input name='besitzer' value='".$out_edit_epuipment['besitzer']."' size='20' type='text' maxlength='50'>
										</td>
									</tr>
									<tr >
										<td colspan='2' class='msghead'>
											Lagerung
										</td>
									</tr>
									<tr>
										<td><b>Lagerort*</b></td>
										<td class='msgrow1' >
											<select name='lagerort'>
										<option value=''>w&auml;hlen</option>";

										$sql_list_lagerort = mysql_query("SELECT * FROM project_equipment_lagerort");
										while($out_list_lagerort = mysql_fetch_array($sql_list_lagerort))
										{// begin while
													if($out_list_lagerort['id'] == $out_edit_epuipment['lagerort'])
													{
												$output .= "<option value='".$out_list_lagerort['id']."' selected>".$out_list_lagerort['bezeichnung']."</option>";
													}
													else
													{
												$output .= "<option value='".$out_list_lagerort['id']."'>".$out_list_lagerort['bezeichnung']."</option>";
													}
										}

							$output .= "
										</select>
										</td>
									</tr>
									<tr>
										<td class='msgrow1'><b>Beh&auml;lter*</b></td>
										<td class='msgrow1'>".$out_edit_epuipment['kiste']."
											<select name='kiste'>
											<option value=''>w&auml;hlen</option>";

											$sql_list_kiste = mysql_query("SELECT * FROM project_equipment WHERE ist_kiste = 1 "); //GROUP BY kiste ASC
											while($out_list_kiste = mysql_fetch_array($sql_list_kiste))
											{// begin while
														if($out_list_kiste['id'] == $out_edit_epuipment['kiste'])
														{
													$output .= "<option value='".$out_list_kiste['id']."' selected>".$out_list_kiste['bezeichnung']."</option>";
														}
														else
														{
													$output .= "<option value='".$out_list_kiste['id']."'>".$out_list_kiste['bezeichnung']."</option>";
														}
											}

								$output .= "
											</select>
										</td>
									</tr>
									<tr>
										<td><b>Ist Beh&auml;lter?</b></td>
										<td class='msgrow1' >";
										if($out_edit_epuipment['ist_kiste'] == 1)
										{
$output .="									<input title='Beh&auml;lter?' name='ist_kiste' value='1' type='checkbox' checked='checked' >";
										}
										else
										{
$output .="									<input title='Beh&auml;lter?' name='ist_kiste' value='1' type='checkbox' >";
										}
										$output .="Ist der Haken gesetzt, so wird der Beh&auml;lter in der Auswahl als Beh&auml;lter hinzugef&uuml;gt.";
$output .="								</td>
									</tr>
									<tr >
										<td colspan='2' class='msghead'>
											Kopplung Leihsystem
										</td>
									</tr>
									<tr>
										<td><b>Ist: Leihartikel?</b></td>
										<td class='msgrow1' >";
										if($out_edit_epuipment['ist_leihartikel'] == 1)
										{
$output .="									<input title='Leihartikel?' name='ist_leihartikel' value='1' type='checkbox' checked='checked' >";
										}
										else
										{
$output .="									<input title='Leihartikel?' name='ist_leihartikel' value='1' type='checkbox' >";
										}
										$output .="Ist der Haken gesetzt, so wird das Equipment im Leihsystem angezeigt";
$output .="								</td>
									</tr>
								</tbody>
							</table>
							<br>
							<input name='senden' value='Daten senden' type='submit'> 
							<b>*</b>werden auf den Barcode gedruckt!
						</form>
						<br>
						<a href='/admin/projekt/equipment/?group_by=".$group_by."#".$out_edit_epuipment['category']."'>Zur&uuml;ck zu ".$out_edit_epuipment['category']." </a>
								";
		
	}
// EDIT ENDE
// ADD / EDIT BEGIN

	if( $_GET['action'] == 'anzeigen')
	{
		
			$out_edit_epuipment =  mysql_fetch_array( mysql_query("SELECT * FROM project_equipment WHERE id = ".$id."") );
			$kiste = list_equipment_single($out_edit_epuipment['kiste']);			

$output .= "
							
							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='150' class='msghead'>
											Artikel Daten
										</td>
										<td class='msghead' align='right'>
											&nbsp;
											";
											
											if($DARF["edit"] )
										{
											$output .= "

											<a href='?hide=1&action=edit&id=".$out_edit_epuipment['id']."' target='_parent'>
												<img src='../images/16/edit.png' title='Details &auml;ndern' ></a>
												";
										}
$output .= "										
										</td>
									</tr>";
									
$output .="									
									<tr class='shortbarrow'>
										<td class='msgrow1'><b>Inventar-Nr.*</b></td>
										<td class='msgrow1'>
											eq".sprintf("%06d",$out_edit_epuipment['id'])."  <!-- Eine vom System vergebene Nr. -->
										</td>
									</tr>";
									
$output .="																
									<tr>
										<td class='msgrow1'><b>Artikelbezeichnung*</b></td>
										<td class='msgrow1'>
										";
													
										$output .="	".$out_edit_epuipment['bezeichnung'];
										
							$output .= "
										
										</td>
									</tr>
									<tr>
										<td class='msgrow1'><b>Hersteller</b></td>
										<td class='msgrow1'>
											".$out_edit_epuipment['hersteller']."
										</td>
									</tr>
									<tr>
										<td><b>Kategorie</b></td>
										<td class='msgrow1'>
										";
										$output .= "".$out_edit_epuipment['category']."";
										

							$output .= "
										</td>
									</tr>
									<tr>
										<td valign='top'><b>Details</b></td>
										<td  class='msgrow1' >
										  ".$out_edit_epuipment['details']."
										</td>
									</tr>
									<tr>
										<td><b>Zusatzinfo*</b></td>
										<td class='msgrow1' >
											".$out_edit_epuipment['zusatzinfo']."
										</td>
									</tr>
									<tr>
										<td><b>Besitzer</b></td>
										<td class='msgrow1' >
											".$out_edit_epuipment['besitzer']."
										</td>
									</tr>
									<tr >
										<td colspan='2' class='msghead'>
											Lagerung
										</td>
									</tr>
									<tr>
										<td><b>Lagerort*</b></td>
										<td class='msgrow1' >
										";

										$sql_list_lagerort = mysql_query("SELECT * FROM project_equipment_lagerort WHERE id = '".$out_edit_epuipment['lagerort']."'");
										while($out_list_lagerort = mysql_fetch_array($sql_list_lagerort))
										{// begin while
											$output .= "".$out_list_lagerort['bezeichnung']."";
										}

							$output .= "
										</td>
									</tr>
									<tr>
										<td class='msgrow1'><b>Beh&auml;lter*</b></td>
										<td class='msgrow1'>".$kiste['bezeichnung']."
											";
								$output .= "
											
										</td>
									</tr>
									<tr>
										<td><b>Ist Beh&auml;lter?</b></td>
										<td class='msgrow1' >";
										if($out_edit_epuipment['ist_kiste'] == 1)
										{
$output .="									JA";
										}
										else
										{
$output .="									NEIN";
										}										
$output .="								</td>
									</tr>
									<tr >
										<td colspan='2' class='msghead'>
											Kopplung Leihsystem
										</td>
									</tr>
									<tr>
										<td><b>Ist: Leihartikel?</b></td>
										<td class='msgrow1' >";
										if($out_edit_epuipment['ist_leihartikel'] == 1)
										{
$output .="									JA";
										}
										else
										{
$output .="									NEIN ";
										}
$output .="								</td>
									</tr>
								</tbody>
							</table>
							<br>
							<b>*</b>werden auf den Barcode gedruckt!
						</form>
						<br>
						<a href='/admin/projekt/equipment/?group_by=".$group_by."#".$out_edit_epuipment['category']."'>Zur&uuml;ck zu ".$out_edit_epuipment['category']." </a>
								";
		
	}
// Anzeigen ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// EDIT Kategorie BEGIN

	if($_GET['action'] == 'edit_cat')
	{
		if (!$DARF["edit"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

		$sql_edit_epuipment_category = mysql_query("SELECT * FROM project_equipment WHERE category = '".$edit_cat."' GROUP BY category");

	if($_GET['comand'] == 'senden')
	{	
		if($_POST['category1'] != "" )
		{
			$category = $_POST['category1'];
		}else
		{
			$category = $_POST['category'];
		}
		//$sql_edit_epuipment_category = mysql_query("SELECT * FROM project_equipment WHERE category = '".$edit_cat."'");
		//while($out_edit_epuipment_category = mysql_fetch_array($sql_edit_epuipment_category))
		//{// begin while
		$sql_update_cat = "UPDATE project_equipment SET `category` = '".$category."' WHERE `category` LIKE '".$edit_cat."';";

		$update=mysql_query($sql_update_cat);
		//}
		//$output .= $sql_update_cat."<meta http-equiv='refresh' content='0; URL=?group_by=".$group_by."&view=equipment#".$category."'>";
		$meldung = "Daten aktualisiert";
		$PAGE->redirect($dir."?group_by=".$group_by."&view=equipment#".$category,$PAGE->sitetitle,$meldung);

	}
while($out_edit_epuipment_category = mysql_fetch_array($sql_edit_epuipment_category))
		{// begin while

$output .= "
							<form name='editequipcat' action='?hide=1&action=edit_cat&edit_cat=".$out_edit_epuipment_category['category']."&comand=senden' method='POST'>
							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='200'  class='msghead'>
											Kategorie alt
										</td>
										<td width='200'  class='msghead'>
											Kategorie neu
										</td>
									</tr>
									<tr class='shortbarrow'>
										<td width='250' class='msgrow1' >
											".$out_edit_epuipment_category['category']."

										</td>
										<td  colspan='4' class='msgrow1'>

									<select name='category'>
									<option value='1'>w&auml;hlen</option>";

									$sql_list_category_1 = mysql_query("SELECT category FROM project_equipment GROUP BY category ASC");
						while($out_list_category_1 = mysql_fetch_array($sql_list_category_1))
					{// begin while
									$output .= "

									<option value='".$out_list_category_1['category']."'>".$out_list_category_1['category']."</option>";
					}

						$output .= "
									</select>
									oder neu eintragen
									<input name='category1' value='' size='15' type='text' maxlength='25'>

									</td>
									</tr>
									</tbody>
								</table>
								<input name='senden' value='Daten senden' type='submit'>

								</form>
										<a href='/admin/projekt/equipment/?group_by=".$group_by."#".$out_edit_epuipment_category['category']."'>Zur&uuml;ck zu ".$out_edit_epuipment_category['category']." </a>
								";
		}
	}
// EDIT Kategorie ENDE
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SHOW BEGIN
if($_GET['action'] == 'show')
	{
		
		$output .= show($group_by,$show_cat,$bezeichnung1,$DARF);


	}
// SHOW ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Lagerorte BEGIN

	if($_GET['action'] == 'lagerort')
	{
			$output .= "
				<table  cellspacing='1' cellpadding='2' border='0' class='shortbar'>
					 <tbody>
						<tr class='shortbarrow'>
							<td width='150' class='shortbarbit'>
								<a href='index.php?hide=1&hide1=1&action=lagerort&do=add' class='shortbarbitlink'>
									Lagerort anlegen
								</a>
							</td>
						<tr>
					</tbody>
				</table>
				<br>
			";
			if($_GET['hide1'] != 1)
			{
		
$output .= "
							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr>
										<td colspan='2' class='msghead'>
											Lagerorte
										</td>
									</tr>";
									
						$iCounter = 0;
						$sql_list_lagerort= mysql_query("SELECT * FROM project_equipment_lagerort");
						while($out_list_lagerort = mysql_fetch_array($sql_list_lagerort))
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
										<td width='95%'>
											".$out_list_lagerort['bezeichnung']."
										</td>
										<td>
									";
							if($DARF["edit"] || $DARF["del"] )
											{
												

												if($DARF["edit"] )
												{
													$output .= "

													<a href='?hide=1&hide1=1&action=lagerort&do=edit&id=".$out_list_lagerort['id']."' target='_parent'>
														<img src='../images/16/edit.png' title='Details anzeigen/&auml;ndern' ></a>
														";
												}
												if($DARF["del"] )
												{
													$output .= "
													<a href='?hide=1&hide1=1&action=lagerort&do=del&id=".$out_list_lagerort['id']."' target='_parent'>
														<img src='../images/16/editdelete.png' title='".$out_list_lagerort['bezeichnung']." l&ouml;schen'>
													</a>
													";
												}
												if($DARF["bla"] )
												{
													$output .= "
													<a href='barcode.php?lagerort=".$out_list_lagerort['id']."' target='_NEW'>
														<img src='../images/16/printmgr.png' title='Barcode der Artikel am Lagerort ".$out_list_lagerort['bezeichnung']." Drucken!'>
													</a>
													";
												}
												
											}
						$output .= "</td>
									</tr>";
									$iCounter ++;
					}
					$output .= "
									</tbody>
								</table>
								<br>
								<a href='".$dir."?hide=1&action=lagerort'>Zur&uuml;ck</a>
								";
				}
	
			if( $_GET['action'] == "lagerort" && ($_GET['do'] == 'add' || $_GET['do'] == 'edit' ) )
			{
				if (!$DARF["add"] || !$DARF["edit"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
			
				if($_GET['do'] == 'edit')
				{
					$out_edit_epuipment =  mysql_fetch_array( mysql_query("SELECT * FROM project_equipment_lagerort WHERE id = ".$id."") );
				}
				if($_GET['do'] == 'add' && $_GET['comand'] == 'senden')
				{
					$meldung 	= equipment_add_lagerort($_POST);
					$PAGE->redirect($dir."?hide=1&action=lagerort".$category,$PAGE->sitetitle,$meldung);
					//$output .= "<meta http-equiv='refresh' content='0; URL=?group_by=".$group_by."&view=equipment#".$category."'>";
					
				}

				if( $_GET['do'] == 'edit' && $_GET['comand'] == 'senden')
				{
						$meldung 	= equipment_edit_lagerort($_POST,$id);
						//$sql_edit = "UPDATE project_equipment SET  `invnr` = '".$invnr."', `bezeichnung` = '".$bezeichnung."', `besitzer` = '".$besitzer."', `details` = '".$details."', `hersteller` = '".$hersteller."', `category` = '".$category."', `lagerort` = '".$lagerort."', `kiste` = '".$kiste."', `ist_leihartikel` = '".$ist_leihartikel."' WHERE `id` = ".$id."";
						//$update=mysql_query($sql_edit);
						$PAGE->redirect($dir."?hide=1&action=lagerort".$category,$PAGE->sitetitle,$meldung);
						//$output .=  "<meta http-equiv='refresh' content='0; URL=?view=equipment#".$category."'>";

				}
				

		$output .= "
									<form name='".$_GET['action']."lagerort' action='?hide=1&hide1=1&action=".$_GET['action']."&do=".$_GET['do']."&comand=senden&id=".$id."' method='POST'>
									<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
										<tbody>
											<tr >
												<td colspan='2' class='msghead'>
													Lagerort
												</td>
											</tr>
											<tr class='shortbarrow'>
												<td width='80' class='msgrow1'><b>Lagerort</b></td>
												<td class='msgrow1'>
													<input name='bezeichnung' value='".$out_edit_epuipment['bezeichnung']."' size='50' type='text' maxlength='100'>
												</td>
											</tr>
										</tbody>
									</table>
									<br>
									<input name='senden' value='Daten senden' type='submit'>
								</form>
								<br>
								<a href='".$dir."?hide=1&action=lagerort'>Zur&uuml;ck</a>
										";
				
				}
	
	}
// Lagerorte ENDE
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Kisten BEGIN
if($_GET['action'] == 'kisten')
{	
	
	if($_GET['hide1'] != 1)
	{
	
	$output .= "<table>
					<tbody>
						<tr>
							<td colspan='2' class='msghead'>
							Beh&auml;lter
							</td>
						</tr>
	";					
						$sql_kisten = equipment_show_kisten();
						while($out = mysql_fetch_array($sql_kisten))
						{
	$output .= "		<tr>
							<td>
								".$out['bezeichnung']."
							</td>
							<td>";
							if($DARF["edit"] )
							{
								$output .= "<a href='index.php?hide=1&hide1=1&action=kisten&do=list_kiste&kiste=".$out['id']."' target='_parent'>";
									$output .= "<img src='../images/16/edit.png' title='inhalt der Beh&auml;lter anzeigen'>";
								$output .= "</a>";
								$output .= "<a href='barcode_kiste.php?id=".$out['id']."' target='_NEW'>";
									$output .= "<img src='../images/16/printmgr.png' title='Barcode der Artikel in der Beh&auml;lter ".$out['bezeichnung']." Drucken!'>";
								$output .= "</a>";
								$output .= "<a href='liste_kiste.php?kiste=".$out['id']."' target='_NEW'>";
									$output .= "<img src='../images/16/printmgr.png' title='Liste der Artikel in der Beh&auml;lter ".$out['bezeichnung']." Drucken!'>";
								$output .= "</a>";
							}
							
$output .= "				</td>
						</tr>
	";					}
						
						
	$output .= "	</tbody>
				</table>
	";
	}
	
	if($_GET['do'] == 'list_kiste')
	{
		$output .= show_kiste($_GET['kiste'],$DARF);
	}
	

	
}
// Kisten ENDE
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


if($_GET['action'] == 'suche')
	{
		$id_kiste = preg_replace('![^0-9]!', '', $_POST['kiste']);
		$out = list_equipment_single($id_kiste);
		if($out['ist_kiste'] == 1 && $out['id'])
		{
			$output .= show_kiste($id_kiste,$DARF);
		}
		elseif($out['ist_kiste'] == 0 && $out['id'])
		{
			$output .= show_equipment($id_kiste,$DARF);
		}
		if(!$out['id'])
		{
			$output .= "<h3 style='color:RED;'> Keine Daten der Nummer: eq".sprintf("%06d",$id_kiste)." gefunden!</h3>";
		}
		
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


if($_GET['action'] == 'eqtokiste')
	{
		$output .= show_kiste_inhalt($_GET['kiste']);
		
		$output .= '
				<script>
					window.onload=function()
					{ document.addeq2kiste.eqid.focus(); }
				</script>
	';
	$output .= "<form id='addeq2kiste' action='?hide=1&action=addeq2kiste&kiste=".$_GET['kiste']."' method='POST'>
				<table>
					<tbody>
						<tr>
							<td class='msghead'>
								Barcode des Artikels scannen
							</td>
						</tr>
						<tr>
							<td>
								<input name='eqid' value='' size='25' type='text' maxlength='25'>
							</td>
						</tr>
	";					
						
	$output .= "						
					</tbody>
				</table>
				</form>
	";
	
		
	}
	if($_GET['action'] == "addeq2kiste")
	{
		$id_equip = preg_replace('![^0-9]!', '', $_POST['eqid']);
		$equip = list_equipment_single($id_equip);
		if($equip['ist_kiste'] != 1)
		{
			$meldung .= addeq2kiste($id_equip,$_GET['kiste']);
			$PAGE->redirect($dir."?hide=1&hide1=1&action=eqtokiste&kiste=".$_GET['kiste'],$PAGE->sitetitle,$meldung);
		}
		if($equip['ist_kiste'] == 1)
		{
			$output .= "<h3 style='color:RED'> Eine Beh&auml;lter kann keinen Beh&auml;lter hinzugef&uuml;gt werden!";
		}
	}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////	
}

/*###########################################################################################
ENDE Admin PAGE
*/

}
$PAGE->render($output);
?>