<?
########################################################################
# ratingn Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/rating/index.php - Version 1.0                                #
########################################################################


include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Rating Administration");

$data = $DB->query_first("SELECT * FROM user WHERE id = '".$user_id."'  LIMIT 1");

$frage		= security_string_input($_POST['frage']);
$position	= security_number_int_input($_POST['position'],"","");
$cat		= security_string_input($_POST['category']);
$cat1		= security_string_input($_POST['category1']);

if($_POST['category'] == "NOT")
{
	$category = $cat1;

}
else
{
	$category = $cat;

}

$id				= $_GET['id'];
$breite = "150";

 /*###########################################################################################
Admin PAGE
*/


if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

else
{
		$a = 'shortbarbit';
		$a1 = 'shortbarlink';

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

					$output .= "<a name='top' >
						<a href='/admin/projekt/'>Administration</a>
						&raquo;
						<a href='/admin/projekt/rating'>Rating</a>
						&raquo; ".$_GET['action']."
						<hr class='newsline' width='100%' noshade=''>
						<br />


			<table width='100%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
			  <tbody>
					<tr class='shortbarrow'>";
					if($DARF_PROJEKT_ADD)
					{
						$output .= "
						<td width='".$breite."' class='".$a."'><a href='?hide=1&action=add' class='".$a1."'>XX Anlegen</a></td>";
					}
						$output .="</tr>
				</tbody>
			</table>
					<hr>

							";


						if($_GET['hide'] != 1)
						{ // hide
				 $sql_list_category = $DB->query("SELECT vw_sektionen_id FROM rating_fragen GROUP BY vw_sektionen_id");
				 $sql_list_category_dlink = $DB->query("SELECT * FROM rating_sektionen");

						$output .= "

							<b>DirektLink:</b>";



						while($out_list_category_dlink = $DB->fetch_array($sql_list_category_dlink))
								{// begin while
									//$out_list_sektion_dlink = $DB->fetch_array( $DB->query("SELECT * FROM rating_sektionen WHERE id = id = '".$sql_list_category['vw_sektionen_id']."'") );

							$output .= "
							<a href='#".$out_list_category_dlink['sektion']."'>".$out_list_category_dlink['sektion']."</a>&nbsp;";
								}





								while($out_list_category = $DB->fetch_array($sql_list_category))
								{// begin while




										$out_category  = $DB->fetch_array($DB->query("SELECT * FROM rating_sektionen WHERE id = '".$out_list_category['vw_sektionen_id']."'"));

										$output .= "

									<h1 style='margin: 5px 0px 5px;'>
										<a name='".$out_category['sektion']."'><b>".$out_category['sektion']."</b></a> - <a href='#top'>top</a>
									</h1>";

									$sql_list_ip = $DB->query("SELECT * FROM rating_fragen WHERE vw_sektionen_id = '".$out_list_category['vw_sektionen_id']."' ORDER BY position");

									$output .= "
									<table width='100%' cellspacing='1' cellpadding='2' border='0'>
												<tbody>
													<tr >
														<td  class='msghead'>
															Frage
														</td>
														";
											if($DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL )
											{  // Admin
												$output .="
													<td width='45' class='msghead'>
														admin
													</td>";
											}
											$output .="
												</tr>";



									$iCount = 0;
									while($out_list_ip = $DB->fetch_array($sql_list_ip))
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

													<tr class='".$currentRowClass."'>
															<td class='shortbarbit_left'>
																".$out_list_ip['frage']."
															</td>
															";
												if($DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL )
												{ //  Admin
													$output .="
															<td class='shortbarbit_left'>";

													if($DARF_PROJEKT_EDIT )
													{ //  Admin
														$output .="
																<a href='?hide=1&action=edit&id=".$out_list_ip['Id']."' target='_parent'>
																<img src='/images/projekt/16/edit.png' title='Frage anzeigen/&auml;ndern' ></a>";
													}
													if($DARF_PROJEKT_DEL )
													{ //  Admin
													$output .="
																<a href='?hide=1&action=del&id=".$out_list_ip['Id']."' target='_parent'>
																<img src='/images/projekt/16/editdelete.png' title='Frage l&ouml;schen'></a>
															";
													}
													$output .="
															</td>";
												}
													$output .="</tr>";

											$iCount++;
											} // end while


											$output .= "
										</tbody>
											</table>
											<br>";


								} // end while


			}  // hide ende

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// DEL
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			if($_GET['hide'] == "1")
			{
				if($_GET['action'] == 'del')
				{
					if (!$DARF_PROJEKT_DEL) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

						if($_GET['comand'] == 'senden')

					{
						$del=$DB->query("DELETE FROM rating_fragen WHERE id = '".$_GET['id']."'");
						$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rating/#".$category."'>";
					}

					 $new_id = $_GET['id'];
					 $out_list_name = $DB->fetch_array($DB->query("SELECT * FROM rating_fragen WHERE Id = '".$new_id."' LIMIT 1"));

				$output .="

							<h2 style='color:RED;'>Achtung!!!!<h2>
							<br />

							<p>Sind Sie sich sicher das
							<font style='color:RED;'>".$out_list_name['frage']."</font> gel&ouml;scht werden soll?</p>
							<br />
							<a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>
							<input value='l&ouml;schen' type='button'></a>
							 \t
							<a href='/admin/projekt/rating/#".$category."' target='_parent'>
							<input value='Zur&uuml;ck' type='button'></a>




						";



				}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// DEL ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ADD
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				if($_GET['action'] == 'add')
				{
					if (!$DARF_PROJEKT_ADD) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

					if($_GET['comand'] == 'senden')

					{
						$sql_check_ip = $DB->fetch_array($DB->query("SELECT * FROM rating_fragen WHERE frage = '".$frage."'"));

						if ($sql_check_ip['frage'] == $frage)
						{
							$output .= "
										<br />
										<b><font size='+1' style='color:RED;'>! Achtung die frage ".$frage." existiert schon !!</font></b>
										<br />
										<br />
									   ";

							$output .= "<meta http-equiv='refresh' content='4; URL=/admin/projekt/rating/#".$category."'>";

						}
						else
						{		$läufer = 0;
								if($cat == "NOT")
								{
									if ($läufer == 0)
									{
										$insert=$DB->query("INSERT INTO `rating_sektionen` (`Id`, `sektion`) VALUES (NULL, '".$cat1."')");
										$output .= "SEL: ".$cat1."<br><br>";
										$output .= "Neue Selektion eingetragen<br>";
										$läufer ++;
									}
									if ($läufer == 1)
									{
										$sektion_new = $DB->fetch_array($DB->query("SELECT * FROM rating_sektionen WHERE sektion = '".$cat1."'"));
										$sektion_new_id = $sektion_new['id'];
										$output .= "Neue Selektion ID geladen<br>";
										$läufer ++;
									}
									if ($läufer == 2)
									{
										$insert=$DB->query("INSERT INTO `rating_fragen` (Id, frage, vw_sektionen_id, position) VALUES (NULL, '".$frage."', ".$sektion_new_id.", '".$position."');");
										$output .= "Daten wurden gesendet<br>";
										//$output .= "Frage: ".$frage."<br>CAT: ".$category."<br>POS: ".$position."<br> CAT_POST: ".$cat."<br> CAT_POST1: ".$cat1;
										$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rating/#".$cat1."'>";
									}
								}
								else
								{
									$insert=$DB->query("INSERT INTO `rating_fragen` (Id, frage, vw_sektionen_id, position) VALUES (NULL, '".$frage."', ".$category.", '".$position."');");
									$output .= "Daten wurden gesendet<br>";
									//$output .= "Frage: ".$frage."<br>CAT: ".$category."<br>POS: ".$position."<br> CAT_POST: ".$cat."<br> CAT_POST1: ".$cat1;
									$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rating/#".$category."'>";
								}

						}
					}


					$output .= "
										<form name='addfrage' action='?hide=1&action=add&comand=senden' method='POST''>
										<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
										<tbody>
											<tr>
												<td class='msghead'>
													Frage
												</td>
												<td class='msghead'>
													Position
												</td>
												<td class='msghead'>
													Sektionen
												</td>
											</tr>
											<tr class='msgrow1'>
												<td >
													<input name='frage' value='".$_GET['frage']."' size='60' type='text' maxlength='200'>
												</td>
												<td >
													<input name='position' value='".$_GET['position']."' size='5' type='text' maxlength='200'>
												</td>
												<td>
												<select name='category'>
													<option value='NOT'>w&auml;hlen</option>";

												$sql_list_category = $DB->query("SELECT * FROM rating_sektionen");
												while($out_list_category = $DB->fetch_array($sql_list_category))
													{// begin while
														$output .="
														<option value='".$out_list_category['Id']."'>".$out_list_category['sektion']."</option>";
													}

											$output .="
												</select>
												<br>
												oder neu anlegen
												<input name='category1' value='' size='30' type='text' maxlength='200'>
												</td>

											</tr>
										</tbody>
									</table>

											<input name='senden' value='Daten senden' type='submit'> \t
											<br /><br /><a href='/admin/projekt/rating/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
											</form>";
				}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ADD ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// EDIT
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				if($_GET['action'] == 'edit' )
				{
					if (!$DARF_PROJEKT_EDIT) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

					$sql_edit_frage = $DB->query("SELECT * FROM rating_fragen WHERE Id = ".$id."");

					if($_GET['comand'] == 'senden')

					{
								$update=$DB->query(	"UPDATE rating_fragen SET `frage` = '".$frage."' WHERE `Id` = ".$id.";");

								$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rating/#".$category."'>
						";
							//}

					}

					while($out_edit_frage = $DB->fetch_array($sql_edit_frage))
					{// begin while

					$output .= "
										<form name='editfrage' action='?hide=1&action=edit&comand=senden&id=".$id."' method='POST'>
										<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
										<tbody>
											<tr >
												<td class='msghead'>
													Frage
												</td>
												<td class='msghead'>
													Position
												</td>
												<td class='msghead'>
													Sektionen
												</td>
											</tr>
											<tr class='msgrow1'>
												<td >
													<input name='frage' value='".$out_edit_frage['frage']."' size='60' type='text' maxlength='200'>
												</td>
												<td >
													<input name='position' value='".$out_edit_frage['position']."' size='5' type='text' maxlength='200'>
												</td>
												<td>
												<select name='category'>";

												$out_list_category_now = $DB->fetch_array( $DB->query("SELECT * FROM rating_sektionen WHERE Id = ".$out_edit_frage['vw_sektionen_id']." ") );
												$output .="
												<option value='".$out_edit_frage['vw_sektionen_id']."'>".$out_list_category_now['sektion']."_Aktuell</option>";

												$sql_list_category = $DB->query("SELECT * FROM rating_sektionen");
									while($out_list_category = $DB->fetch_array($sql_list_category))
								{// begin while
												$output .="

												<option value='".$out_list_category['Id']."'>".$out_list_category['sektion']."</option>";
								}

									$output .="	</select>
												</td>

											</tr>
										</tbody>
									</table>

											<input name='senden' value='Daten senden' type='submit'> \t
											<br /><br /><a href='/admin/projekt/rating/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
											</form>";
					} // output Fragen Edit ENDE


				}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// EDIT ENDE
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


			} // hide ENDE


	$output .= "<div align='center'><br><a href='/admin/projekt/' target='_parent'>Zur&uuml;ck zur Administration</a></div>";

/*###########################################################################################
ENDE Admin PAGE
*/

}
$PAGE->render( utf8_decode($output) );
?>