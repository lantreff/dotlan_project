<?
########################################################################
# Notiz Modul for dotlan             			                   	   #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/notiz/index.php - Version 1.0                                  #
########################################################################


include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Notizen");

$event_id = $EVENT->next;
$EVENT->getevent($event_id);

$bezeichnung	= security_string_input($_POST['bezeichnung']);
$text1	 		= $_POST['text'];
$text	 		= $text1['content'];
$kat 			= security_string_input($_POST['kategorie']);
$kat1 			= security_string_input($_POST['kategorie1']);
$id 			= $_GET['id'];
$user_historie = $CURRENT_USER->vorname." (".$CURRENT_USER->nick.") ".$CURRENT_USER->nachname;
$note_global	= $_POST['note_global'];

if($_POST['kategorie'] == 1)
{
	$kategorie = $kat1;

}
else
{
	$kategorie = $kat;

}
$date = date("Y-m-d H:i:s");

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
$selectet_event_id = $EVENT->next;
}

 /*###########################################################################################
Admin PAGE
*/


if(!$DARF_PROJEKT_VIEW ) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));
else
{// !$module_admin_check
		$a = 'shortbarbit';
		$a1 = 'shortbarlink';



 		if($DARF_PROJEKT_VIEW)
		{ //$ADMIN

			$output .= "<a name='top' >

				<a href='/admin/projekt/'>Projekt</a>
				&raquo;
				<a href='/admin/projekt/notiz'>Notizen</a>
				&raquo; ".$_GET['action']."
				<hr class='newsline' width='100%' noshade=''>
				<br />
<table width='100%' cellspacing='1' cellpadding='2' border='0'>
	<tr>
		<td>

				<table width='25%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
				  <tbody>
						<tr class='shortbarrow'>
							<td width='25%' class='".$a."'><a href='?hide=1&action=add' class='".$a1."'>Notiz Anlegen</a></td>
						<!--	<td width='2%' class='shortbarbitselect'>&nbsp;</td>
							<td width='24%' class='shortbarbit'><a href='export.php' target='_new' class='shortbarlink'>export</a></td>
						-->
						
						</tr>
					</tbody>
				</table>
			</td>
			<td>
				
				<table cellspacing='1' cellpadding='2' border='0' align='right'>
				<tr>
				<td align='right'>";

				$sql_event_ids 				= $DB->query("SELECT * FROM events ORDER BY begin DESC");
				$out_historie_event			= $DB->fetch_array($DB->query("SELECT * FROM events WHERE id = ".$selectet_event_id.""));						

				$output .= "<form name='change_event' action='' method='POST'>				
								<select name='event' onChange='document.change_event.submit()''>
									<option value='1'>w&auml;hle das Event !</option>";
								while($out_event_ids = $DB->fetch_array($sql_event_ids))
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



			if($_GET['hide'] != 1)
			{ // hide

				$sql_list_kategorie 		= $DB->query("SELECT kategorie FROM project_notizen WHERE event_id = ".$selectet_event_id." OR global = 1  GROUP BY kategorie ");
	 			$sql_list_kategorie_dlink 	= $DB->query("SELECT kategorie FROM project_notizen WHERE event_id = ".$selectet_event_id." OR global = 1 GROUP BY kategorie");

			$output .= "

				<b>DirektLink:</b>";



			while($out_list_kategorie_dlink = $DB->fetch_array($sql_list_kategorie_dlink))
					{// begin while

				$output .= "
				<a href='#".$out_list_kategorie_dlink['kategorie']."'>".$out_list_kategorie_dlink['kategorie']."</a> &nbsp;&nbsp;";
					}

					while($out_list_kategorie = $DB->fetch_array($sql_list_kategorie))
					{// begin while




				//$out_category  = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE id = '".$out_list_uID['u_id']."'"));

				$output .= "

			<h1 style='margin: 5px 0px 5px;'>
				<a name='".$out_list_kategorie['kategorie']."'><b>".$out_list_kategorie['kategorie']."</b></a> - <a href='#top'>top</a>
			</h1>";

			$sql_list_note = $DB->query("SELECT * FROM project_notizen WHERE kategorie = '".$out_list_kategorie['kategorie']."' ORDER BY bezeichnung ASC");

			$output .= "
			<table width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
							<tr>
								<td width='250' class='msghead'>
									Bezeichnung
								</td>
								<td width='150' class='msghead'>
									Erstellt
								</td>
								<td width='150' class='msghead'>
									Ge&auml;ndert
								</td>";
						if($DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL)
						{ // Global Admin
							$output .="
								<td width='70' class='msghead'>
									admin
								</td>";
						}
						$output .="
							</tr>";




			while($out_list_note = $DB->fetch_array($sql_list_note))
					{// begin while
			$output .= "

								<tr class='shortbarrow'>
									<td class='shortbarbit_left'>
										<a href='?hide=1&action=show&id=".$out_list_note['id']."&event=".$selectet_event_id."' target='_parent'>
											".$out_list_note['bezeichnung']."
										</a>
									</td>
									<td class='shortbarbit_left'>
										".$out_list_note['date']."
									</td>
									<td class='shortbarbit_left'>
										".$out_list_note['last_work']."
									</td>";
							if($DARF_PROJEKT_EDIT || $DARF_PROJEKT_DELL)
							{ // Global Admin
								$output .="
										<td class='shortbarbit_left'>";

									if($DARF_PROJEKT_EDIT )
									{ //  Admin
										$output .="

											<a href='?hide=1&action=edit&id=".$out_list_note['id']."&event=".$selectet_event_id."&event=".$selectet_event_id."' target='_parent'>
											<img src='/images/projekt/16/edit.png' title='Deteils anzeigen / &auml;ndern' ></a>
									";
									}
									if($DARF_PROJEKT_DEL )
									{ //  Admin
										$output .="
											<a href='?hide=1&action=del&id=".$out_list_note['id']."' target='_parent'>
											<img src='/images/projekt/16/editdelete.png' title='Notiz l&ouml;schen'></a>";
									}
									$output .="
											<a href='export.php?id=".$out_list_note['id']."' target='_blank'>
											<img src='/images/projekt/16/download.png' title='export / download'></a>
										</td>";
							}
								$output .="
								</tr>";

						} // end while


					$output .= "
				</tbody>
					</table>
					<br>
					";
					} // end while


			}  // hide ende




if($_GET['hide'] == "1")
{
	if($_GET['action'] == 'del')
	{
		if (!$DARF_PROJEKT_DEL) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

			if($_GET['comand'] == 'senden')

		{ 	$del=$DB->query("DELETE FROM project_notizen WHERE id = '".$_GET['id']."'");

			$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/notiz/'>";
		}

		 $new_id = $_GET['id'];
		 $out_list_name = $DB->fetch_array($DB->query("SELECT bezeichnung FROM project_notizen WHERE id = '".$new_id."' LIMIT 1"));

	$output .="

				<h2 style='color:RED;'>Achtung!!!!<h2>
				<br />

				<p>Sind Sie sich sicher das <font style='color:RED;'>".$out_list_name['bezeichnung']."</font> gel&ouml;scht werden soll?</p>
				<br />
				<a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>
				<input value='l&ouml;schen' type='button'></a>
				 \t
				<a href='/admin/projekt/notiz/' target='_parent'>
				<input value='Zur&uuml;ck' type='button'></a>




			";



	}



	if($_GET['action'] == 'add')
	{
		if (!$DARF_PROJEKT_ADD) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

		if($_GET['comand'] == 'senden')

		{ // if($_GET['comand']

			$insert=$DB->query("INSERT INTO `project_notizen` (id, event_id, bezeichnung, text, kategorie, date, last_work, global) VALUES (NULL, '".$selectet_event_id."', '".$bezeichnung."','".$text."', '".$kategorie."', '".$date."', '".$date."', '".$note_global."');");

		$output .= "Daten wurden gesendet.";
		$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/notiz/'>";


		} // if($_GET['comand'] ende

$output .= "
							<form name='addnote' action='?hide=1&action=add&comand=senden' method='POST' onsubmit='return addip()'>
							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
							<tbody>
								<tr >
									<td width='40%'  class='msghead'>
										Bezeichnung
									</td>
									<td width='40%'  class='msghead'>
										Kategorie
									</td>
									<td width='20%'  class='msghead'>
										Event &uuml;bergreifend
									</td>
								</tr>
								<tr >
									<td class='msgrow1'>
										<input name='bezeichnung' value='' size='60' type='text' maxlength='50'>
									</td>
									<td class='msgrow1'>
									<select name='kategorie'>
									<option value='1'>w&auml;hlen</option>";

									$sql_list_kategorie = $DB->query("SELECT kategorie FROM project_notizen WHERE event_id = ".$selectet_event_id." GROUP BY kategorie ASC");
						while($out_list_kategorie = $DB->fetch_array($sql_list_kategorie))
					{// begin while
									$output .="

									<option value='".$out_list_kategorie['kategorie']."'>".$out_list_kategorie['kategorie']."</option>";
					}

						$output .="
									</select>
										oder neu eintragen
										<input name='kategorie1' value='' size='20' type='text' maxlength='25'>
									</td>
									<td class='msgrow1'>";
					
						$output .=" <input type='checkbox' name='note_global' value='1'> Global sichtbar!<br>";
									
						$output .="									
									</td>
								</tr>
								<tr>
									<td colspan='3' class='msgrow2'>
										Notiz
									</td>
								</tr>
								<tr>
									<td colspan='3' class='msgrow2'>
									<!-- ################################################ -->        
									<div>
									  <input type='hidden' id='text[content]' name='text[content]' value='' />
									  <input type='hidden' id='text[content]___Config' value='SkinPath={BASEDIR}html/fck/editor/skins/silver/' />
									  <iframe id='text[content]___Frame' src='{BASEDIR}html/fck/editor/fckeditor.html?InstanceName=text[content]&amp;Toolbar=dotlan' width='100%' height='450' frameborder='no' scrolling='no'></iframe>
									</div>
									<input type='hidden' name='text[html]' value='1' />

									<!-- ################################################ -->
									<!-- <textarea class='ckeditor' cols='80'  name='text' rows='10'></textarea> -->
									</td>
								</tr>


						</tbody>
							</table>

								<input name='senden' value='Daten senden' type='submit'><br />
								<a href='/admin/projekt/notiz/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
								</form>";
	}


	if($_GET['action'] == 'edit')
	{
		if (!$DARF_PROJEKT_EDIT) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));

		$sql_edit_note = $DB->query("SELECT * FROM project_notizen WHERE id = ".$id."");
		//$sql_note_historie = $DB->query("SELECT * FROM project_notizen_historie WHERE notiz_id = ".$id." ORDER BY datum DESC");
		$query = $DB->query("SELECT * FROM project_notizen_historie WHERE notiz_id = ".$id." ORDER BY datum DESC");
		
	if($_GET['comand'] == 'senden')

	{
	if(isset($_POST['note_global']))
	{
		$insert_note = $_POST['note_global'];
	}
	else
	{
		$insert_note = 0;
	}
	
	$sql_insert_note = $DB->query("SELECT * FROM project_notizen WHERE id = ".$id."");
	$sql_tmp_note = $DB->fetch_array($sql_insert_note);
	
	$insert=$DB->query("INSERT INTO `project_notizen_historie` (`notiz_id`, `action`, `user`, `datum`, `tmp` , `tmp_bezeichnung`, `tmp_kategorie`) VALUES ('".$id."', '".$_GET['action']."', '".$user_historie."', '".$datum."', '".$sql_tmp_note['text']."', '".$sql_tmp_note['bezeichnung']."', '".$sql_tmp_note['kategorie']."'); ");
	$update=$DB->query(	"UPDATE project_notizen SET `event_id` = '".$selectet_event_id."', `bezeichnung` = '".$bezeichnung."', `text` = '".$text."', `kategorie` = '".$kategorie."', `last_work` = '".$date."', `global` = '".$insert_note."' WHERE `id` = ".$id.";");	
	
	$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/notiz/'>";

	}
while($out_edit_note = $DB->fetch_array($sql_edit_note))
		{// begin while

$output .= "
							<form name='editnote' action='?hide=1&action=edit&comand=senden&id=".$id."' method='POST' onsubmit='return editip()'>
							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
							<tbody>
								<tr >
									<td width='40%'  class='msghead'>
										Bezeichnung
									</td>
									<td width='40%'  class='msghead'>
										Kategorie
									</td>
									<td width='20%'  class='msghead'>
										Event &uuml;bergreifend
									</td>
								</tr>
								<tr >
									<td class='msgrow1'>
										<input name='bezeichnung' value='".$out_edit_note['bezeichnung']."' size='15' type='text' maxlength='50'>
									</td>
									<td class='msgrow1'>
									<select name='kategorie'>
									<option value='1'>w&auml;hlen</option>";

									$sql_list_kategorie = $DB->query("SELECT kategorie FROM project_notizen WHERE event_id = ".$selectet_event_id." GROUP BY kategorie ASC");
						while($out_list_kategorie = $DB->fetch_array($sql_list_kategorie))
					{// begin while
									$output .="

									<option value='".$out_list_kategorie['kategorie']."'>".$out_list_kategorie['kategorie']."</option>";
					}

						$output .="
									</select>
										oder neu eintragen
										<input name='kategorie1' value='".$out_edit_note['kategorie']."' size='15' type='text' maxlength='25'>
									</td>
									<td class='msgrow1'>";
					
					if($out_edit_note['global'] == 1)
					{
						$output .=" <input type='checkbox' name='note_global' value='1' checked> Global sichtbar!<br>";
					}
					else
					{
						$output .=" <input type='checkbox' name='note_global' value='1'> Global sichtbar!<br>";
					}	
						$output .="									
									</td>
								</tr>
								<tr>
									<td colspan='3' class='msghead'>
										Notiz
									</td>
								</tr>
								<tr >
									<td colspan='3' class='msgrow2'>
									<!-- ################################################ -->        
									<div>
									  <input type='hidden' id='text[content]' name='text[content]' value='".$out_edit_note['text']."' />
									  <input type='hidden' id='text[content]___Config' value='SkinPath={BASEDIR}html/fck/editor/skins/silver/' />
									  <iframe id='text[content]___Frame' src='{BASEDIR}html/fck/editor/fckeditor.html?InstanceName=text[content]&amp;Toolbar=dotlan' width='100%' height='450' frameborder='no' scrolling='no'></iframe>
									</div>
									<input type='hidden' name='text[html]' value='1' />

									<!-- ################################################ -->
									<!-- <textarea class='ckeditor' cols='80'  name='text' rows='10'>".$out_edit_note['text']."</textarea> -->
									</td>
								</tr>



						</tbody>
							</table>

								<input name='senden' value='Daten senden' type='submit'><a href='/admin/projekt/notiz/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
								</form>";
		}
		
	$output .="<script>";
    $output .="  function switch_row(id){";
    $output .="    var row = document.getElementById(id);";
    $output .="    if(row.style.display == 'none'){";
    $output .="      row.style.display = 'table-row';";
    $output .="    }else{";
    $output .="      row.style.display = 'none';";
    $output .="    }";
    $output .="  }";
    $output .="</script>";
	
    $output .="<table class='shortbar' width='100%'>";
    $output .="<tr>";
    $output .="  <td class=\"msghead\" nowrap=\"nowrap\" colspan='2'><b>History</b>&nbsp;</td>";
    $output .="</tr>";
    while($row =$DB->fetch_array($query)){
		
      $output .="<tr class=\"msgrow".(($i%2)?1:2)."\" >";
      $output .="  <td  nowrap=\"nowrap\">
						<a href='#' onClick='switch_row(\"row_".$row["id"]."\");'>
							".date("d.m.Y H:i:s", strtotime($row['datum']))." Uhr&nbsp;
						</a>
					</td>";
      $output .="  <td  nowrap=\"nowrap\">".$row["user"]."&nbsp;</td>";
      $output .="</tr>";
      $output .="<tr id='row_".$row["id"]."' style='display: none;'>";
      $output .="  	<td class=\"msgrow2\" nowrap=\"nowrap\" colspan=2>
					Bezeichnung: ".$row["tmp_bezeichnung"]." 
					<br>
					Kategorie: ".$row["tmp_kategorie"]."
					<br>
					<pre>".$row["tmp"]."</pre>
					</td>";
      $output .="</tr>";
	 
	 $i ++;
    }
    $output .="</table>";






}

	if($_GET['action'] == 'show' && $DARF_PROJEKT_VIEW )
	{
		$sql_show_note = $DB->query("SELECT * FROM project_notizen WHERE id = ".$id."");


while($out_show_note = $DB->fetch_array($sql_show_note))
		{// begin while

$output .= "

							<table class='shortbar' width='100%' cellspacing='1' cellpadding='2' border='0'>
							<tbody>
								<tr>
									<td width='100' valign='top' class='msghead'>
									Bezeichnung
									
";										
if($DARF_PROJEKT_EDIT )
	{
$output .= "										
											<a href='?hide=1&action=edit&id=".$out_show_note['id']."&event=".$selectet_event_id."' target='_parent'>
												<img align='right' src='/images/projekt/16/edit.png' title='Deteils anzeigen / &auml;ndern' >
											</a>
										
";			
	}				
$output .= "										
									</td>
								</tr>
								<tr >
									<td class='msgrow2'>
										".$out_show_note['bezeichnung']."
									</td>
								</tr>
								<tr>
									<td colspan='2' class='msghead'>
										Notiz
									</td>
								</tr>
								<tr>
									<td  class='msgrow2'>
									<br>
										".$out_show_note['text']."
									<br>
									<br>
									</td>
								</tr>



						</tbody>
							</table>

								<a href='/admin/projekt/notiz/' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
								";
		}






}


}




} //!$module_admin_check ende

/*###########################################################################################
ENDE Admin PAGE
*/

}
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>