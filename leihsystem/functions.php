<?php
## leih ## 
function list_leih()
{
	$sql = "SELECT * FROM `project_leih`";
	$out =  mysql_query($sql);
	return $out;
}
function list_leih_leihartikel()
{
	$sql = "SELECT * FROM `project_equipment` WHERE ist_leihartikel = 1 AND ausleihe = 0";
	$out =  mysql_query($sql);
	return $out;
}
function list_leih_leihartikel_not_maxlan()
{
	$sql = "SELECT * FROM `project_equipment` WHERE ist_leihartikel = 1 AND ausleihe = 0 AND besitzer NOT LIKE 'maxlan'";
	$out =  mysql_query($sql);
	return $out;
}
function list_leih_single($id)
{
	$sql = "SELECT * FROM `project_leih` WHERE id = '".$id."' ";
	$out =  mysql_fetch_array(mysql_query($sql));
	return $out;
}

function leih_check_rueck_or_new($id,$event_id)
{
	$sql = mysql_query("SELECT * FROM  project_leih_leihe AS l  WHERE l.event_id = '".$event_id."' AND l.id_leih_user = '".$id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
	
	if(mysql_num_rows($sql) != 0)
	{
		//$output .="2.1  Auflisten der Artikel/Artikelgruppen des Users <BR>";
		$output .= leih_list_verliehene_artikel_by_user($id,$event_id);
	}
	else
	{
		//$output .="2.2 Neue Leihe beginnen <BR>";
		$output .= leih_show_user_data($id);
	}
	return $output;
}

function leih_list_verliehene_artikel_by_user($id,$event_id)
{
	$sql_list_rueck = mysql_query("SELECT * FROM  project_leih_leihe AS l  WHERE l.event_id = '".$event_id."' AND l.id_leih_user = '".$id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
	$out_list_rueck = mysql_fetch_array($sql_list_rueck);
			//$output .= "2.1.1 Auflisten der geliehenen Artikel";
		


				$out_username  = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = '".$out_list_rueck['id_leih_user']."'"));

//					$count = count($out_list_rueck);

			$output .= "<h1>".$out_username['nick']."</h1>";

			$sql_list_article = mysql_query("SELECT * FROM  project_leih_leihe AS l INNER JOIN project_equipment AS e ON l.id_leih_artikel = e.id WHERE l.id_leih_user = '".$id."' AND l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
			$sql_list_group = mysql_query("SELECT * FROM  project_leih_leihe AS l  INNER JOIN project_equipment_groups AS g ON l.id_leih_gruppe = g.id  WHERE l.id_leih_user = '".$id."' AND l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00'");
			
			$output .= "
			<form name='".$out_username['nick']."' action='?hide=1&action=rueckgabe&comand=senden&id=".$out_username['id']."' method='POST'>
			<table class='msg' width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
							<tr>
								<td width='375' class='msghead'>
									Bezeichnung
								</td>
								<td class='msghead'>
									Kategorie
								</td>
								<td width='100' class='msghead'>
									Zur&uuml;ck
								</td>
							</tr>";

					while($out_list_article = mysql_fetch_array($sql_list_article))
					{// begin while
			$output .= "

							<tr class=\"msgrow".(($i%2)?1:2)."\">
								<td class='shortbarbit_left'>
									".$out_list_article['bezeichnung']."
								</td>
								<td class='shortbarbit_left'>
									".$out_list_article['category']."
								</td>
								<td class='shortbarbit_left'>
									<input type='checkbox' name='rueck_a_ids[]' value='".$out_list_article['id_leih_artikel']."'>
								</td>
							</tr>";
					$i++;

					} // end while
					
			while($out_list_group = mysql_fetch_array($sql_list_group))
					{// begin while
			$output .= "

							<tr class=\"msgrow".(($i%2)?1:2)."\">
								<td class='shortbarbit_left'>
									".$out_list_group['bezeichnung']."
								</td>
								<td class='shortbarbit_left'>
									&nbsp;
								</td>
								<td class='shortbarbit_left'>
									<input type='checkbox' name='rueck_g_ids[]' value='".$out_list_group['id_leih_gruppe']."'>
								</td>
							</tr>";
					$i++;

					} // end while

					$output .= "
				</tbody>
					</table>
					<div align='right'>
					<input name='senden' value='Zur&uuml;ckgeben' type='submit' >
					</div>
					</form>
					<br />
					<br />";
		return $output;
}

function leih_show_user_data($id)
{
	if($id != 0)
	{
		//$output .="2.2.1 Daten des Users Checken! <BR>";
		$sql = mysql_query("SELECT * FROM `user` WHERE id = '".$id."'");

		while($out = mysql_fetch_array($sql))
	   {
			//$id_user = $out_sql_user['id'];;	//echo "<br> User ID: ".$id_user;
	   $output .= "
	   <br>
		<form name='USERDATA' action='".$dir."?hide=1&action=leihe&add=0&user_id=".$out['id']."' method='POST'>
			<table cellspacing='1' cellpadding='1' width='100%' border='0'>
				<tr>
					<td><b>Nick:</b></td>
					<td>".$out['nick']."</td>
					<td><b>UserID:</b></td>
					<td>".$out['id']."</td>
					<input type='hidden' name='user_id' value='".$id."'>
				</tr>
				<tr>
					<td><b>Vorname:</b></td>
					<td><input type='text' name='vorname' value='".$out['vorname']."' size='40'></td>
					<td><b>Nachname:</b></td>
					<td><input type='text' name='nachname' value='".$out['nachname']."' size='40'></td>
				</tr>
				<tr>
					<td><b>Strasse Haus Nr.:</b></td>
					<td><input type='text' name='strasse' value='".$out['strasse']."' size='40'></td>
					<td><b>PLZ:</b></td>
					<td><input type='text' name='plz' value='".$out['plz']."' size='40'></td>
				</tr>
				<tr>
					<td><b>Ort:</b></td>
					<td><input type='text' name='wohnort' value='".$out['wohnort']."' size='40'></td>
					<td><b>Geb.Dat:</b></td>
					<td><input type='text' name='geb' value='".$out['geb']."' size='40'> Fortmat: <b>JJJ-MM-TT</b></td>
				</tr>
			</table>
			<br>
			<input name='senden' value='Daten korrekt ?' type='submit'>
		<form>
		";
	 }
	}
	else
	{
		$output .= "<h2 align='center' style='color:RED;'> Bitte UserID Scannen!</h2>";
	}

  
	return $output;
}
function leih_save_user_data($daten)
{
	$sql = "UPDATE user SET  `vorname` = '".$daten['vorname']."', `nachname` = '".$daten['nachname']."', `strasse` = '".$daten['strasse']."', `plz` = '".$daten['plz']."', `wohnort` = '".$daten['wohnort']."', `geb` = '".$daten['geb']."' WHERE `id` = ".$daten['user_id']." ";
	$out =  mysql_query( $sql); 	
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
function leih_list_verliehene_artikel($event_id)
{
	$sql = mysql_query("SELECT * FROM  project_leih_leihe AS l INNER JOIN project_equipment AS e ON l.id_leih_artikel = e.id WHERE l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
	
	if(mysql_num_rows($sql) != 0)
	{
		while($out = mysql_fetch_array($sql))
		{// begin while
		$out_user = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = ".$out['id_leih_user'].""));
$output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
				<td width='50%'>
					".$out['bezeichnung']." 
				</td>
				<td >
					an ".$out_user['nick']."
				</td>
			</tr>";
		$i++;
		} // end while
	}
	
	return $output;
}
function leih_list_verliehene_gruppen($event_id)
{
	$sql = mysql_query("SELECT * FROM project_leih_leihe AS l  INNER JOIN project_equipment_groups AS g ON l.id_leih_gruppe = g.id  WHERE l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00'");
	
	if(mysql_num_rows($sql) != 0)
	{
$output .= "<tr>
				<td colspan='2'>
					&nbsp;
				</td>
			</tr>
			<tr>
			<td colspan='2' class='msghead'>
				Verliehene Artikelgruppen
			</td>";
		while($out = mysql_fetch_array($sql))
		{// begin while
		$out_user = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = ".$out['id_leih_user'].""));
$output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
				<td width='50%'>
					".$out['bezeichnung']."
				</td>
				<td>
					an ".$out_user['nick']."
				</td>
			</tr>";
		$i++;
		} // end while
	}
	
	return $output;
}
function leih_list_artikel()
{
	$sql = mysql_query("SELECT * FROM project_equipment WHERE ist_leihartikel = '1' AND ausleihe != '1' ORDER BY  `category` ,  `bezeichnung`  ASC");
	
	if(mysql_num_rows($sql) != 0)
	{
		while($out = mysql_fetch_array($sql))
		{// begin while
$output .= "<tr>
				<td  class=\"msgrow".(($i%2)?1:2)."\">
					".$out['bezeichnung']."
				</td>
			</tr>";
		$i++;
		} // end while
	}
	
	return $output;
}

function leih_list_gruppen()
{
	$sql = mysql_query("SELECT eg.bezeichnung AS eg_group_bezeichnung, eg.id AS eg_group_id FROM  project_equipment AS e INNER JOIN project_equipment_equip_group AS g ON g.id_equipment = e.id, project_equipment_groups AS eg WHERE e.ist_leihartikel = '1' AND eg.ausleihe = '0' GROUP BY eg_group_id");
	
	if(mysql_num_rows($sql) != 0)
	{
$output .= "<tr>
				<td >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td  class='msghead'>
					Vorhandene Artikelgruppen
				</td>";
		while($out = mysql_fetch_array($sql))
		{// begin while
$output .= "<tr>
				<td  class=\"msgrow".(($i%2)?1:2)."\">
					".$out['eg_group_bezeichnung']."
				</td>
			</tr>";
		$i++;
		} // end while
	}
	
	return $output;
}

## ADD ##
function leih_new_leihe($user_id,$event_id,$dir)
{
	
	$output .="<h3 style='color:RED;'>Erst alle zu verleihenden Artikel nacheinander einscannen, danach die Liste mit klick auf FERTIG f&uuml;r die Leihe abschicken!</h3><br>";
	$output .= leih_lsit_temp_leihe_by_user($user_id,$event_id);
	$output .="	<p align='right'> <a a href='".$dir."?hide=1&action=leihe&save=1&user_id=".$user_id."'><input value='FERTIG? - Jetzt Artikel als verliehen buchen' type='BUTTON'></a></p>
				<br>	
			";
	$output .="
	<form name='LeihDATA' action='".$dir."?hide=1&action=leihe&add=1&user_id=".$user_id."' method='POST'>
		<table>
			<tbody>
				<tr>
					<td>
						Scannen oder Eingebe der Equipnummer!
					</td>
				</tr>
				<tr>
					<td class='msghead'>
						Artikel
					</td>
				</tr>
				<tr>
					<td>
						<input name='id_artikel' value='' type='text'>
						<select name='drop_id_artikel'>
							<option value=''>oder hier nicht Maxlan eigene Artikel w&auml;hlen!</option>";
								
								$sql_ist_leih_artikel = list_leih_leihartikel_not_maxlan();
								while($out_list_gruppen = mysql_fetch_array($sql_ist_leih_artikel))
								{// begin While Historie
									$output .= "<option value='".$out_list_gruppen['id']."'>".$out_list_gruppen['bezeichnung']."</option>";
								}
	$output .= "
						</select>
					</td>
				</tr>
				<tr>
					<td class='msghead'>
						Gruppen
					</td>
				</tr>
				<tr>
					<td>
						<select name='id_gruppe'>
							<option value=''>w&auml;hle eine Gruppe !</option>";
							$sql_list_gruppen = mysql_query("SELECT * FROM project_equipment_groups WHERE ausleihe = '0' ");
								while($out_list_gruppen = mysql_fetch_array($sql_list_gruppen))
								{// begin While Historie
									$output .= "<option value='".$out_list_gruppen['id']."'>".$out_list_gruppen['bezeichnung']."</option>";
								}
	$output .= "
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<br>
		<input name='user_id' value='".$user_id."' type='hidden'>
		<input name='senden' value='Artikel / Gruppe zur Leihliste hinzuf&uuml;gen' type='submit'>
	</form>
	
	";
	return $output;
}

function leih_save_leih_data($data,$orga_id,$event_id,$datum)
{
	$leihzusatz = substr(time(),-3);
	$leihID = $data['user_id'].$leihzusatz;
	ECHO $id = preg_replace('![^0-9]!', '', $data['id_artikel']);
	if($data['id_artikel'])
	{	$id = preg_replace('![^0-9]!', '', $data['id_artikel']);

		
		//$sql = mysql_query(	"UPDATE `project_equipment` SET `ausleihe` = 1  WHERE `id` = ".$id.";" );
		
		// Artikel  --> Keine Gruppe!
		$sql = "INSERT INTO project_leih_leihe_temp (`id`, `id_leih_user`, `id_leih_user_verleiher`, `id_leih_artikel`, `id_leih_gruppe`, `event_id`, `leih_datum`)
					VALUES ('".$leihID."', '".$data['user_id']."', '".$orga_id."', '".$id."', '0', '".$event_id."', '".$datum."');
				";
		$out =  mysql_query( $sql);
	}
	if($data['drop_id_artikel'])
	{	$id = preg_replace('![^0-9]!', '', $data['drop_id_artikel']);
		
		//$sql = mysql_query(	"UPDATE `project_equipment` SET `ausleihe` = 1  WHERE `id` = ".$id.";" );
		
		// Artikel  --> Keine Gruppe!
		$sql = "INSERT INTO project_leih_leihe_temp (`id`, `id_leih_user`, `id_leih_user_verleiher`, `id_leih_artikel`, `id_leih_gruppe`, `event_id`, `leih_datum`)
					VALUES ('".$leihID."', '".$data['user_id']."', '".$orga_id."', '".$id."', '0', '".$event_id."', '".$datum."');
				";
		$out =  mysql_query( $sql);
	}
	if($data['id_gruppe'])
	{
		$id = preg_replace('![^0-9]!', '', $data['id_gruppe']);
		$sql = "INSERT INTO project_leih_leihe_temp (`id`, `id_leih_user`, `id_leih_user_verleiher`, `id_leih_artikel`, `id_leih_gruppe`, `event_id`, `leih_datum`)
					VALUES ('".$leihID."', '".$data['user_id']."', '".$orga_id."', '0', '".$id."', '".$event_id."', '".$datum."');
				";
		$out =  mysql_query( $sql);
		
	}
	
	$meldung = "Artikel wurde der Leihliste hinzugef&uuml;gt!";
	return $meldung;
}
function leih_save_leih_data_final($id,$orga_id,$event_id,$datum)
{
	$leihzusatz = substr(time(),-3);
	$leihID = $id.$leihzusatz;
	
	$list_rueck = "SELECT * FROM  project_leih_leihe_temp AS l  WHERE l.event_id = '".$event_id."' AND l.id_leih_user = '".$id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ";
	$sql_list_rueck = mysql_query($list_rueck);
	while($data = mysql_fetch_array($sql_list_rueck))
	{
		if($data['id_leih_artikel'])
		{
		ECHO "ART: ".	$sql = mysql_query( "INSERT INTO project_leih_leihe (`id`, `id_leih_user`, `id_leih_user_verleiher`, `id_leih_artikel`, `id_leih_gruppe`, `event_id`, `leih_datum`)
					VALUES ('".$leihID."', '".$id."', '".$orga_id."', '".$data['id_leih_artikel']."', '".$data['id_leih_gruppe']."', '".$event_id."', '".$datum."')
			");
		mysql_query("UPDATE project_equipment SET  `ausleihe` = '1' WHERE `id` = ".$data['id_leih_artikel']." ");
			
		}
		if($data['id_leih_gruppe'])
		{
		ECHO "<br>GRP: ".	$grp = mysql_query("INSERT INTO project_leih_leihe (`id`, `id_leih_user`, `id_leih_user_verleiher`, `id_leih_artikel`, `id_leih_gruppe`, `event_id`, `leih_datum`)
					VALUES ('".$leihID."', '".$id."', '".$orga_id."', '".$data['id_leih_artikel']."', '".$data['id_leih_gruppe']."', '".$event_id."', '".$datum."')");
					mysql_query("UPDATE project_equipment_groups SET  `ausleihe` = '1' WHERE `id` = ".$data['id_leih_gruppe']."");
			
		}
	}
	
	
	$sql_del = "DELETE FROM  project_leih_leihe_temp WHERE `project_leih_leihe_temp`.`event_id` = ".$event_id." AND `project_leih_leihe_temp`.`id_leih_user` = ".$id." AND `project_leih_leihe_temp`.`rueckgabe_datum` = '0000-00-00 00:00:00' ";
	$del_list_rueck = mysql_query($sql_del);
		mysql_query("OPTIMIZE TABLE  `project_leih_leihe_temp`");
			
	$meldung = "Die Leihe wurde erfolgreich eingetragen.";
	return $meldung;
}
function leih_lsit_temp_leihe_by_user($id,$event_id)
{
	$sql_list_rueck = mysql_query("SELECT * FROM  project_leih_leihe_temp AS l  WHERE l.event_id = '".$event_id."' AND l.id_leih_user = '".$id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
	$out_list_rueck = mysql_fetch_array($sql_list_rueck);
			//$output .= "3.1.1 Auflisten der geliehenen Artikel";
		


				$out_username  = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = '".$id."'"));

//					$count = count($out_list_rueck);

			$output .= "<br>
						<h1>Leihliste f&uuml;r ".$out_username['nick']."</h1>";

			$sql_list_article = mysql_query("SELECT * FROM  project_leih_leihe_temp AS l INNER JOIN project_equipment AS e ON l.id_leih_artikel = e.id WHERE l.id_leih_user = '".$id."' AND l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
			$sql_list_group = mysql_query("SELECT * FROM  project_leih_leihe_temp AS l  INNER JOIN project_equipment_groups AS g ON l.id_leih_gruppe = g.id  WHERE l.id_leih_user = '".$id."' AND l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00'");
		
		if(	mysql_num_rows($sql_list_article) 	>0 
			OR
			mysql_num_rows($sql_list_group)		>0 )
		{
		
			$output .= "
			<table class='msg' width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>
							<tr>
								<td width='375' class='msghead'>
									Bezeichnung
								</td>
								<td class='msghead'>
									Kategorie
								</td>
								<td width='16' class='msghead'>
									
								</td>

							</tr>";

					while($out_list_article = mysql_fetch_array($sql_list_article))
					{// begin while
			$output .= "

							<tr class=\"msgrow".(($i%2)?1:2)."\">
								<td class='shortbarbit_left'>
									".$out_list_article['bezeichnung']."
								</td>
								<td class='shortbarbit_left'>
									".$out_list_article['category']."
								</td>
								<td>
									<a href='".$dir."?hide=1&action=del&comand=temp_leihe&id_artikel=".$out_list_article['id']."&user_id=".$out_list_article['id_leih_user']."'> <img src='../images/16/editdelete.png' title='l&ouml;schen'/>
								</td>
							</tr>";
					$i++;

					} // end while
					
			while($out_list_group = mysql_fetch_array($sql_list_group))
					{// begin while
			$output .= "

							<tr class=\"msgrow".(($i%2)?1:2)."\">
								<td class='shortbarbit_left'>
									<b>Gruppe:</b> ".$out_list_group['bezeichnung']."
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<a href='".$dir."?hide=1&action=del&comand=temp_leihe&id_gruppe=".$out_list_group['id']."&user_id=".$out_list_group['id_leih_user']."'> <img src='../images/16/editdelete.png' title='l&ouml;schen'/>
								</td>
							</tr>";
					$i++;

					} // end while

					$output .= "
				</tbody>
					</table>";
		}
		else{
				$output .= "<h2 align='center' style='color:RED;'>Noch keine Daten vorhanden!</h2>";
		}
		
		$output .= "
					<br />
					<br />";
		return $output;
}
## /ADD ##

function check_is_leihartikel($data)
{
	if($data['id_artikel'] != "" )
	{
		$id 		= preg_replace('![^0-9]!', '', $data['id_artikel']);
		$user_id 	= $data['user_id'];
		$out_vorhanden =  mysql_num_rows( mysql_query("SELECT * FROM project_leih_leihe_temp WHERE id_leih_user = ".$user_id." AND  id_leih_artikel = ".$id." ") );
		
		$sql = "SELECT * FROM project_equipment WHERE id = '".$id."' ";
		$out =  mysql_fetch_array( mysql_query($sql) );
		if($out['ist_leihartikel'] == 1 && $out['ausleihe'] == 0 && $out_vorhanden == 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	elseif($data['drop_id_artikel'] != "" )
	{
		$id = preg_replace('![^0-9]!', '', $data['drop_id_artikel']);
		$user_id 	= $data['user_id'];
		$out_vorhanden =  mysql_num_rows( mysql_query("SELECT * FROM project_leih_leihe_temp WHERE id_leih_user = ".$user_id." AND id_leih_artikel = ".$id." ") );
		$sql = "SELECT * FROM project_equipment WHERE id = '".$id."' ";
		$out =  mysql_fetch_array( mysql_query($sql) );
		
		if($out['ist_leihartikel'] == 1 && $out['ausleihe'] == 0 && $out_vorhanden == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	else
		{
			return FALSE;
		}
}
function check_is_gruppenartikel($data)
{
	$id = preg_replace('![^0-9]!', '', $data['id_gruppe']);
	$user_id 	= $data['user_id'];
	$out_vorhanden =  mysql_num_rows( mysql_query("SELECT * FROM project_leih_leihe_temp WHERE id_leih_user = ".$user_id." AND id_gruppe = ".$id." ") );
	$sql = "SELECT * FROM project_equipment_groups WHERE id = '".$id."' ";
	$out =  mysql_fetch_array( mysql_query($sql) );
	
	if($out['ausleihe'] == 0)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
?>