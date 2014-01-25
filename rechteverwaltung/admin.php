<?
#########################################################################
# Rechte-Verwaltungsmodul for dotlan                                	#
#                                                                      	#
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              	#
#                                                                      	#
#########################################################################


include_once("../../../global.php");
include("../functions.php");




$PAGE->sitetitle = $PAGE->htmltitle = _("Projekt Rechteverwaltung");
$event_id		= $EVENT->next;			// ID des anstehenden Event's

// auslesen der einzelnen Werte die über die Adresszeile übergeben werden
	$id				= $_GET['id'];
	$name			= security_number_int_input($_POST['name'],"","");
	$rechte			= security_string_input($_POST['rechte']);
	$cat			= security_string_input($_POST['bereich']);
	$cat1			= security_string_input($_POST['bereich1']);
	$bereich = "";
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

if($_POST['bereich1'] <> "" )
{
	$bereich = $cat1;

}
else
{
	$bereich = $cat;

}
$breite = "150";

 /*###########################################################################################
Admin PAGE
*/


if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_nopermission"));  
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

			
			
	if($DARF_PROJEKT_VIEW || $ADMIN->check(GLOBAL_ADMIN))
	{ //$ADMIN
					$output .= "
					
					

					
					
					<a name='top' >
				<a href='/admin/projekt/'>Administration</a>
				&raquo;
				<a href='/admin/projekt/rechteverwaltung'>Rechteverwaltung</a>
				&raquo; ".$_GET['action']."
				<hr class='newsline' width='100%' noshade=''>
				<br />";

			if($DARF_PROJEKT_ADD || $DARF_PROJEKT_EDIT)
			{
			$output .= "
	<table  width='10%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
	  <tbody>
			<tr class='shortbarrow'>";
			if($DARF_PROJEKT_ADD )
			{
				$output .= "
				<td width='".$breite."' class='".$a."'><a href='?hide=1&action=add' class='".$a1."'>Recht Anlegen</a></td>";
			}
		}
		$output .= "
		
			</tr>
		</tbody>
	</table>
	<br>";
	
	if($_GET['hide'] != 1)
				{ // hide
	$output .="
				<table class='msg2' width='40%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
						<tr  >
						<td class=\"msghead\">
							Bereich	
						</td>
						<td class=\"msghead\">
							Rechte
						</td>";
			if($DARF_PROJEKT_EDIT )
			{
				$output .= "						
						<td class=\"msghead\">
							admin
						</td>";
			}
$output .= "			</tr>
						";

$sql_list_bereich = $DB->query("SELECT bereich FROM project_rights_rights GROUP BY bereich");


  while($out_list_bereich = $DB->fetch_array($sql_list_bereich))
					{// begin while
						$sql_list_recht = $DB->query("SELECT * FROM project_rights_rights WHERE bereich = '".$out_list_bereich['bereich']."' ");
#Table
		$output .= "
					<tr  class=\"msgrow".(($i%2)?1:2)."\">
						<td valign='top'>
						".$out_list_bereich['bereich']."	
						</td>
						<td valign='top' align='right'>
				";
			while($out_list_recht = $DB->fetch_array($sql_list_recht))
						{// begin while
						
						$recht = explode("_", $out_list_recht['name']);
						
						$output .= $recht[2]."
							<a href='?hide=1&action=del&id=".$out_list_recht['id']."' target='_parent'>
								<img src='../images/16/editdelete.png' title='".$recht[2]." l&ouml;schen'>
							</a>
						<br>";
						}
$i ++;
					
			$output .="	</td>";
			
			if($DARF_PROJEKT_EDIT )
			{
				$sql_list_recht_id = $DB->fetch_array( $DB->query("SELECT * FROM project_rights_rights WHERE bereich = '".$out_list_bereich['bereich']."' "));
				$output .= " 
					<td width='5' valign='top' align='right'>
						<a href='?hide=1&action=edit&id=".$sql_list_recht_id['id']."' target='_parent'>
							<img src='../images/16/edit.png' title='Recht &auml;ndern' >
						</a>
											</td>";
						
			}
			}
$output .= "
					</tr>
					</tbody>
				</table>
				
				";
		
	
	} // ENDE darf den Inhalt der Seite sehen
	
	} // ENDE HIDE
	
	if($_GET['hide'] == "1")
	{
		if($_GET['action'] == 'del')
		{
			if (!$DARF_PROJEKT_DEL) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

				if($_GET['comand'] == 'senden')

			{
				$del=$DB->query("DELETE FROM project_rights_rights WHERE id = '".$_GET['id']."'");
				$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/#".$bereich."'>";
			}

			 $new_id = $_GET['id'];
			 $out_list_name = $DB->fetch_array($DB->query("SELECT * FROM project_rights_rights WHERE id = '".$new_id."' LIMIT 1"));
			 $recht = explode("_",$out_list_name['name']);
		$output .="

					<h2 style='color:RED;'>Achtung!!!!<h2>
					<br />

					<p>Sind Sie sicher?
					<br>
					Das Recht: 
					<font style='color:RED;'>".$recht[2]."</font> des Bereiches ".ucfirst($recht[1])." l&ouml;schen?</p>
					<br />
					<a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>
					<input value='l&ouml;schen' type='button'></a>
					 \t
					<a href='/admin/projekt/rechteverwaltung/#".$bereich."' target='_parent'>
					<input value='Zur&uuml;ck' type='button'></a>




				";



		}



		if($_GET['action'] == 'add')
		{
			if (!$DARF_PROJEKT_ADD) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

			if($_GET['action'] == 'add' && $_GET['comand'] == 'senden')

			{
			/*
				$sql_check_ip = $DB->fetch_array($DB->query("SELECT * FROM project_rights_rights WHERE ip = '".$name."'"));

				if ($sql_check_ip['name'] == $name)
				{
					$output .= "
								<br />
								<b><font size='+1' style='color:RED;'>!! Achtung die Recht ".$name." existiert schon !!</font></b>
								<br />
								<br />
							   ";

					$output .= "<meta http-equiv='refresh' content='4; URL=/admin/projekt/rechteverwaltung/#".$bereich."'>";

				}
				else
				{
				*/
						//$insert=$DB->query("INSERT INTO `project_rights_rights` (id, name, bereich) VALUES (NULL, '".$name."','".$bereich."')");
						//Echo "VALUES (NULL, '".$name."','".$bereich."')";
						$output .= "Daten wurden gesendet";
						$all_rights = $_POST['recht'];
						foreach($all_rights as &$a ) {
							//echo "INSERT INTO `project_rights_rights` (id, name, bereich, recht) VALUES (NULL, 'projekt_".$bereich."_".$a."', '".$bereich."', '".$a."')<br>";
							
						$insert=$DB->query("INSERT INTO `project_rights_rights` (id, name, bereich, recht) VALUES (NULL, 'projekt_".$bereich."_".$a."', '".$bereich."', '".$a."')");
							
						}
						$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/#".$bereich."'>";
				//}
			}


			$output .= "
								<form id='formular' name='addip' action='?hide=1&action=add&comand=senden' method='POST' >
								<table id='dyntable' class='msg2' width='60%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='150' class='msghead'>
											Bereich
										</td>
										<td width='150' class='msghead'>
											Recht
										</td>
										
									</tr>
									<tr class='msgrow1'>
										<td valign='top'>
										<select name='bereich'>
										<option value='1'>w&auml;hlen</option>";

										$sql_list_bereich = $DB->query("SELECT bereich FROM project_rights_rights GROUP BY bereich ASC");
							while($out_list_bereich = $DB->fetch_array($sql_list_bereich))
						{// begin while
										$output .="

										<option value='".$out_list_bereich['bereich']."'>".$out_list_bereich['bereich']."</option>";
						}

							$output .="
										</select>
										oder neu eintragen
										<input name='bereich1' value='".$_GET['add_bereich']."' size='13' type='text' maxlength='25'>
										</td>
										<td >
											<input name='recht[]' value='' type='text' maxlength='30'>
											<div id='div'></div>
											<a href='#' onClick='mehr();'><input type='button' value='Feld hinzufügen' /></a>
	
										</td>

									</tr>
							</tbody>
								</table>

									<input name='senden' value='Daten senden' type='submit'> \t
									<br /><br /><a href='/admin/projekt/rechteverwaltung/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
									</form>
									
									";
		}


		if($_GET['action'] == 'edit' )
		{
			if (!$DARF_PROJEKT_EDIT) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

			$sql_edit_rechteverwaltung = $DB->query("SELECT * FROM project_rights_rights WHERE id = ".$id."");

			if($_GET['action'] == 'edit' && $_GET['comand'] == 'senden')

			{
					/*$sql_check_ip = $DB->fetch_array($DB->query("SELECT * FROM project_rights_rights WHERE ip = '".$name."'"));

					if ($sql_check_ip['name'] == $name)
					{
						$output .= "
									<br />
									<b><font size='+1' style='color:RED;'>! Achtung die Recht ".$name." existiert schon !!</font></b>
									<br />
									<br />
								   ";

						$output .= "<meta http-equiv='refresh' content='4; URL=/admin/projekt/rechteverwaltung/#".$bereich."'>";

					}
					else
					{*/
					
						$all_rights = $_POST['rechte'];
						$all_rights1 =$all_rights;
						$all_ids = $_POST['rechte1'];
						$laufer = 0;
						
						
						foreach($all_rights as &$a) {
											
						
						echo "UPDATE project_rights_rights SET `name` = 'projekt_".$bereich."_".$a."', `bereich` = '".$bereich."' WHERE `id` = '".$all_ids[$laufer]."' <br>";
						//$update=$DB->query(	"UPDATE project_rights_rights SET `name` = 'projekt_".$bereich."_".$a."', `bereich` = '".$bereich."', `recht` = '".$a."' WHERE `id` = '".$all_ids[$laufer]."';");
						$laufer ++;
						}
						
						
						
						
						
						
						//$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/rechteverwaltung/#".$bereich."'>";
					//}

			}

			while($out_edit_rechteverwaltung = $DB->fetch_array($sql_edit_rechteverwaltung))
			{// begin while

			$output .= "
								<form name='editip' action='?hide=1&action=edit&comand=senden&id=".$id."' method='POST'>
								<table class='msg2' width='60%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='150' class='msghead'>
											Bereich
										</td>
										<td width='150' class='msghead'>
											Recht
										</td>
									</tr>
									<tr class='msgrow1'>
										<td valign='top'>

										<select name='bereich'>
										<option value='1'>w&auml;hlen</option>";

										$sql_list_bereich = $DB->query("SELECT bereich FROM project_rights_rights GROUP BY bereich ASC");
							while($out_list_bereich = $DB->fetch_array($sql_list_bereich))
						{// begin while
										$output .="

										<option value='".$out_list_bereich['bereich']."'>".$out_list_bereich['bereich']."</option>";
						}

							$output .="
										</select>
										oder neu eintragen
										<input name='bereich1' value='".$out_edit_rechteverwaltung['bereich']."' size='13' type='text' maxlength='25'>
										</td>
										<td >";

										$sql_list_rechte = $DB->query("SELECT * FROM project_rights_rights WHERE bereich = '".$out_edit_rechteverwaltung['bereich']."' ");
							while($out_list_rechte = $DB->fetch_array($sql_list_rechte))
						{// begin while
							 $rechte1 = explode("_",$out_list_rechte['name']);
										$output .="

										<input name='rechte[]' value='".$rechte1[2]."' type='text' maxlength='30'>
											
											<a href='?hide=1&action=del&id=".$out_list_rechte['id']."' target='_parent'>
												<img src='../images/16/editdelete.png' title='Recht l&ouml;schen'>
											</a>
											
											<br>
										
										<input name='rechte1[]' value='".$out_list_rechte['id']."' type='hidden' >
										";
						}

							$output .="
										<div id='div'></div>
										<a href='?hide=1&action=add&add_bereich=".$out_edit_rechteverwaltung['bereich']."'><input type='button' value='Neues Recht hinzuf&uuml;gen' /></a>
										</td>
										

									</tr>
								</tbody>
							</table>

									<input name='senden' value='Daten senden' type='submit'> \t
									<br /><br /><a href='/admin/projekt/rechteverwaltung/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
									</form>";
			}
		}


	}
	

} // ADMIN PAGE ENDE
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
