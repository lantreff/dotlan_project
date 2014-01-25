<?
########################################################################
# Equipment Verwaltungs Modul for dotlan                               #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/equipment/index.php - Version 1.0                              #
########################################################################


$MODUL_NAME = "equipment";
include_once("../../../global.php");
include("../functions.php");

$iCounter = 0;

$PAGE->sitetitle = $PAGE->htmltitle = _("Equipment");

$event_id = $EVENT->next;
$EVENT->getevent($event_id);

$data = $DB->query_first("SELECT * FROM user WHERE id = '".$user_id."'  LIMIT 1");

$anzahl 		=  security_number_int_input($_POST['anzahl'],"","");
$bezeichnung	=  security_string_input($_POST['bezeichnung']);
$bezeichnung1	=  security_string_input($_GET['bezeichnung1']);
$invnr			=   security_string_input($_POST['invnr']);
$details		=  security_string_input($_POST['details']);
$besitzer		=  security_string_input($_POST['besitzer']);
$artnr			=  security_string_input($_POST['artnr']);
$hersteller		=  security_string_input($_POST['hersteller']);
$lagerort		=  security_string_input($_POST['lagerort']);
$kiste			=  security_string_input($_POST['kiste']);

$cat			=  security_string_input($_POST['category']);
$cat1			=  security_string_input($_POST['category1']);
$add_cat		=  security_string_input($_GET['add_cat']);
$edit_cat		=  security_string_input($_GET['edit_cat']);
$breite 		= "100%";

if($_POST['category1'] <> "" )
{
	$category = $cat1;

}
else
{
	$category = $cat;

}
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
$output .= "
				<a name='top' >
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='/admin/projekt/'>Projekt</a>
							&raquo;
						<a href='/admin/projekt/equipment/?group_by=".$group_by."'>Equipment</a>
						&raquo; ".$_GET['action']."
						<hr class='newsline' width='100%' noshade=''>
					</table>
					<br />";


			if($_GET['hide'] != 1)
			{ // hide

			 $sql_list_category = $DB->query("SELECT * FROM project_equipment GROUP BY ".$group_by." ");
	 		 $sql_list_category_dlink = $DB->query("SELECT * FROM project_equipment GROUP BY ".$group_by." ");
			 

				$output .= "


				<table width='50%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
					 <tbody>
						<tr class='shortbarrow'>";

						if($DARF["add"] )
							{$breite = "24%";
							$output .= "
							<td width='".$breite."' class='shortbarbit'><a href='?hide=1&action=add' class='shortbarlink'>Neu Anlegen</a></td>
							<td width='2%' class='shortbarbitselect'>&nbsp;</td>";

							}

						$output .= "


						</tr>
					</tbody>
				</table>
				<hr>
				Gruppierung:
				<a href='?group_by=kiste'>nach Kisten</a> | <a href='?group_by=category'>nach Kategorie</a> | <a href='?group_by=besitzer'>nach Besitzer</a> | <a href='?group_by=lagerort'>nach Lagerort</a>
				<br>
				<br>
				<b>DirektLink:</b>

			";

			while($out_list_category_dlink = $DB->fetch_array($sql_list_category_dlink))
					{// begin while

				$output .= "
				<a href='#".$out_list_category_dlink[$group_by]."'>".$out_list_category_dlink[$group_by]."</a> &nbsp;&nbsp;";
					}


		while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
					

						$output .= "

					<h1 style='margin: 5px 0px 5px;'>
						<a name='".$out_list_category[$group_by]."'><b>".$out_list_category[$group_by]."</b></a> - <a href='#top'>top</a>
						<a title='Kategorie ".$out_list_category['category']." editieren/&auml;ndern' href='?hide=1&action=edit_cat&edit_cat=".$out_list_category[$group_by]."'> edit </a>
					</h1>";

					$sql_list_bezeichnung = $DB->query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$out_list_category[$group_by]."'  GROUP BY bezeichnung");


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
												<img src='/images/projekt/16/db_add.png' title='Artikel in der Kategorie ".$out_list_category[$group_by]." anlegen' ></a>";
												}

												$output .= "</td>

										</tr>
								";

				//$num_rows = $DB->num_rows($sql_list_anzahl);
				while($out_list_bezeichnung = $DB->fetch_array($sql_list_bezeichnung))
					{// begin while
					$num_rows = $DB->num_rows( $sql_list_anzahl = $DB->query("SELECT bezeichnung FROM project_equipment WHERE bezeichnung = '".$out_list_bezeichnung['bezeichnung']."' ") );

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
												".$num_rows."
									</td>
									<td>
									<table >
											<tbody>
												<tr>

													<td colspan='2'>
													<b>".$out_list_bezeichnung['bezeichnung']."</b>
													</td>
													</tr>
													<tr>
													<td width='60'><b>Art. Nr.:</b></td>
													<td>".$out_list_bezeichnung['artnr']."</td>
													</tr>
													<tr>
													<td  width='60'><b>Herst.:</b></td>
													<td>".$out_list_bezeichnung['hersteller']."</td>
													</tr>
													<td  width='60'><b>Kiste:</b></td>
													<td>".$out_list_bezeichnung['kiste']."</td>
													</tr>
													</tbody>
												</table>
									</td>
									<td >
										<a href='?hide=1&action=show&bezeichnung1=".$out_list_bezeichnung['bezeichnung']."&show_cat=".$out_list_bezeichnung[$group_by]."&group_by=".$group_by."' target='_parent'><!-- KA  -->
											<img src='/images/projekt/16/lists.png' title='Alle [".$out_list_bezeichnung['bezeichnung']."] anzeigen!' ></a>
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
			$del=$DB->query("DELETE FROM project_equipment WHERE id = '".$_GET['id']."'");
			$output .= "<meta http-equiv='refresh' content='0; URL=?group_by=".$group_by."&view=equipment#".$category."'>";
		}


		$new_id = $_GET['id'];
		$out_list_name = $DB->fetch_array($DB->query("SELECT bezeichnung FROM project_equipment WHERE id = '".$new_id."' LIMIT 1"));

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
// ADD BEGIN

	if($_GET['action'] == 'add' )
	{
		if (!$DARF["add"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

		if($_GET['action'] == 'add' && $_GET['comand'] == 'senden')

		{

	$insert=$DB->query("INSERT INTO `project_equipment` (id, invnr, bezeichnung, besitzer, details, category, artnr, hersteller, lagerort, kiste) VALUES (NULL, '".$invnr."', '".$bezeichnung."', '".$besitzer."', '".$details."', '".$category."', '".$artnr."', '".$hersteller."', '".$lagerort."', '".$kiste."');");



		$output .= "Daten wurden gesendet";
		$output .= "<meta http-equiv='refresh' content='0; URL=?group_by=".$group_by."&view=equipment#".$category."'>";
		
		}




		$output .= "
							<form name='addequip' action='?hide=1&action=add&comand=senden' method='POST'>
							<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='250'  class='msghead'>
											Artikel Daten
										</td>
										<td width='200'  class='msghead'>
											Details
										</td>

										<td  class='msghead'>
											Besitzer
										</td>
										<td  class='msghead'>
											Lagerort
										</td>

									</tr>
									<tr >
										<td width='250'  valign='top'  >
											<table >
												<tr>
													<td><b>Inventar-Nr.</b></td>
													<td><input name='invnr' value='' size='25' type='text' maxlength='50'></td>
												</tr>
												<tr>
													<td><b>Artikelbezeichnung:</b></td>
													<td><input name='bezeichnung' value='' size='25' type='text' maxlength='50'></td>
												</tr>
												<tr>
													<td><b>Art. Nr.:</b></td>
													<td><input name='artnr' value='' size='25' type='text' maxlength='50'></td>
												</tr>
												<tr>
													<td><b>Herst.:</b></td>
													<td><input name='hersteller' value='' size='25' type='text' maxlength='50'></td>
												</tr>
												<tr>
													<td><b>Kiste:</b></td>
													<td><input name='kiste' value='' size='6' type='text' maxlength='6'></td>
												</tr>
											</table>
										</td>
										<td   >
										   <textarea name='details' wrap='hard' cols='40' rows='10'></textarea>
										</td>

										<td  valign='top' >
											<input name='besitzer' value='' size='20' type='text' maxlength='50'>
										</td>
										<td  valign='top' >
											<input name='lagerort' value='' size='20' type='text' maxlength='50'>
										</td>

									</tr>
									<tr>
									<td  colspan='4'>

									<select name='category'>
									<option value='1'>w&auml;hlen</option>";

									$sql_list_category = $DB->query("SELECT category FROM project_equipment GROUP BY category ASC");
						while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
									$output .= "

									<option value='".$out_list_category['category']."'>".$out_list_category['category']."</option>";
					}

						$output .= "
									</select>
									oder neu eintragen
									<input name='category1' value='".$add_cat."' size='15' type='text' maxlength='25'>

									</td>
									</tr>
									</tbody>
								</table>
								<input name='senden' value='Daten senden' type='submit'>
								</form>
								<a href='?view=equipment&group_by=".$group_by."' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
								";



	}
// ADD ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// EDIT BEGIN

	if($_GET['action'] == 'edit' )
	{
		if (!$DARF["edit"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

		$sql_edit_epuipment = $DB->query("SELECT * FROM project_equipment WHERE id = ".$id."");

	if($_GET['comand'] == 'senden' && $_GET['action'] == 'edit')

	{
		$sql_edit = "UPDATE project_equipment SET  `invnr` = '".$invnr."', `bezeichnung` = '".$bezeichnung."', `besitzer` = '".$besitzer."', `details` = '".$details."', `artnr` = '".$artnr."', `hersteller` = '".$hersteller."', `category` = '".$category."', `lagerort` = '".$lagerort."', `kiste` = '".$kiste."' WHERE `id` = ".$id."";

	$update=$DB->query($sql_edit);

		$output .=  "<meta http-equiv='refresh' content='0; URL=?view=equipment#".$category."'>
		";

	}
while($out_edit_epuipment = $DB->fetch_array($sql_edit_epuipment))
		{// begin while

$output .= "
							<form name='editequip' action='?hide=1&action=edit&comand=senden&id=".$id."' method='POST'>
							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='200'  class='msghead'>
											Artikel Daten
										</td>
										<td width='200'  class='msghead'>
											Details
										</td>

										<td  class='msghead'>
											Besitzer
										</td>
										<td  class='msghead'>
											Lagerort
										</td>

									</tr>
									<tr class='shortbarrow'>
										<td width='250' class='msgrow1' >
											<table>
													<tr>
														<td class='msgrow1'>Inventar-Nr.</td>
														<td class='msgrow1'><input name='invnr' value='".$out_edit_epuipment['invnr']."' size='25' type='text' maxlength='50'></td>
													</tr>
													<tr>
														<td class='msgrow1'>Artikelbezeichnung:</td>
														<td class='msgrow1'><input name='bezeichnung' value='".$out_edit_epuipment['bezeichnung']."' size='25' type='text' maxlength='50'></td>
													</tr>
													<tr>
														<td class='msgrow1'><b>Art. Nr.:</b></td>
														<td class='msgrow1'><input name='artnr' value='".$out_edit_epuipment['artnr']."' size='25' type='text' maxlength='50'></td>
													</tr>
													<tr>
														<td class='msgrow1'><b>Herst.:</b></td>
														<td class='msgrow1'><input name='hersteller' value='".$out_edit_epuipment['hersteller']."' size='25' type='text' maxlength='50'></td>
													</tr>
													<tr>
														<td class='msgrow1'><b>Kiste:</b></td>
														<td class='msgrow1'><input name='kiste' value='".$out_edit_epuipment['kiste']."' size='6' type='text' maxlength='6'></td>
													</tr>
											</table>
										</td>
										<td  class='msgrow1' >
										   <textarea name='details' wrap='hard' cols='40' rows='10'>".$out_edit_epuipment['details']."</textarea>
										</td>

										<td class='msgrow1' >
											<input name='besitzer' value='".$out_edit_epuipment['besitzer']."' size='20' type='text' maxlength='50'>
										</td>
										<td class='msgrow1' >
											<input name='lagerort' value='".$out_edit_epuipment['lagerort']."' size='20' type='text' maxlength='50'>
										</td>


									</tr>
									<tr>
									<td  colspan='4' class='msgrow1'>

									<select name='category'>
									<option value='1'>w&auml;hlen</option>";

									$sql_list_category = $DB->query("SELECT category FROM project_equipment GROUP BY category ASC");
						while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
									if($out_list_category['category'] == $out_edit_epuipment['category'])
									{
										$output .= "

										<option value='".$out_list_category['category']."' selected>".$out_list_category['category']."</option>";
									}
									else
									{
										$output .= "

									<option value='".$out_list_category['category']."'>".$out_list_category['category']."</option>";
									}
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
										<a href='/admin/projekt/equipment/?group_by=".$group_by."#".$out_edit_epuipment['category']."'>Zur&uuml;ck zu ".$out_edit_epuipment['category']." </a>
								";
		}
	}
// EDIT ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// EDIT Kategorie BEGIN

	if($_GET['action'] == 'edit_cat')
	{
		if (!$DARF["edit"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

		$sql_edit_epuipment_category = $DB->query("SELECT * FROM project_equipment WHERE category = '".$edit_cat."' GROUP BY category");

	if($_GET['comand'] == 'senden')

	{
		//$sql_edit_epuipment_category = $DB->query("SELECT * FROM project_equipment WHERE category = '".$edit_cat."'");
		//while($out_edit_epuipment_category = $DB->fetch_array($sql_edit_epuipment_category))
		//{// begin while
		$sql_update_cat = "UPDATE project_equipment SET `category` = '".$category."' WHERE `category` LIKE '".$edit_cat."';";

		$update=$DB->query($sql_update_cat);
		//}
		$output .= $sql_update_cat."<meta http-equiv='refresh' content='0; URL=?group_by=".$group_by."&view=equipment#".$category."'>
		";

	}
while($out_edit_epuipment_category = $DB->fetch_array($sql_edit_epuipment_category))
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

									$sql_list_category_1 = $DB->query("SELECT category FROM project_equipment GROUP BY category ASC");
						while($out_list_category_1 = $DB->fetch_array($sql_list_category_1))
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
		$sql_show_article = $DB->query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$show_cat."' AND bezeichnung = '".$bezeichnung1."' ORDER BY invnr ASC");
		$show_article =  $DB->fetch_array( $DB->query("SELECT * FROM project_equipment WHERE ".$group_by." = '".$show_cat."' AND bezeichnung = '".$bezeichnung1."' ORDER BY invnr ASC"));


						$output .= "

					<h1 style='margin: 5px 0px 5px;'>
						<a name='".$show_article[$group_by]."'><b>".$show_article[$group_by]."</b></a> - <a href='#top'>top</a>
					</h1>";




					$output .= "

								<table  class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
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
											</td>";
											if($DARF["edit"] || $DARF["del"] )
											{
												$output .= "
													<td width='45' class='msghead'>	";

													if($DARF["add"] )
														{
												$output .= "
														<a href='?hide=1&action=add&add_cat=".$show_article[$group_by]."' >
														<img src='/images/projekt/16/db_add.png' title='Artikel in der Kategorie ".$show_article[$group_by]." anlegen' ></a>";
														}
														$output .= "
														</td>";
											}
									$output .="
										</tr>
								";
					$iCounter = 0;
				//$num_rows = $DB->num_rows($out_list_bezeichnung);
				while($out_show_article = $DB->fetch_array($sql_show_article))
					{// begin while

						if($iCount % 2 == 0)
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
														<td>".$out_show_article['invnr']."</td>
													</tr>
													<tr>
														<td><b>Art. Nr.:</b></td>
														<td>".$out_show_article['artnr']."</td>
													</tr>
													<tr>
														<td><b>Herst.:</b></td>
														<td>".$out_show_article['hersteller']."</td>
													</tr>
													<tr>
														<td><b>Kiste:</b></td>
														<td>".$out_show_article['kiste']."</td>
													</tr>
												</tbody>
										</table>
									</td>

									<td>
										".$out_show_article['besitzer']."
									</td>
									<td>
										".$out_show_article['lagerort']."
									</td>";

									if($DARF["edit"] || $DARF["del"] )
									{
										$output .= "

										<td >";

										if($DARF["edit"] )
										{
											$output .= "

											<a href='?hide=1&action=edit&id=".$out_show_article['id']."' target='_parent'>
												<img src='/images/projekt/16/edit.png' title='Deteils anzeigen/&auml;ndern' ></a>
												";
										}
										if($DARF["del"] )
										{
											$output .= "
											<a href='?hide=1&action=del&id=".$out_show_article['id']."' target='_parent'>
												<img src='/images/projekt/16/editdelete.png' title='".$out_show_article['invnr']." l&ouml;schen'>
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



	}
// SHOW ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


}

/*###########################################################################################
ENDE Admin PAGE
*/

}
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
