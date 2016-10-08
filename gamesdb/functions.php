<?

function list_games()
{
	$sql = "SELECT * FROM `project_gamesdb` WHERE activ = 1 ORDER BY name ASC";
	$out =  mysql_query($sql);
	return $out;
}
function out_game_stats()
{
	$sql = "SELECT g.*, count(u.game_id) as stimmen
			FROM `project_gamesdb` g left outer join
				 `project_gamesdb_user2game` u
				ON g.id = u.game_id
			GROUP BY g.id
			ORDER BY g.name ASC;";
	$out =  mysql_query($sql);
	return $out;
}

function list_single_game($id)
{
	$sql = "SELECT * FROM `project_gamesdb` WHERE id = ".$id."";
	$out =  mysql_fetch_array( mysql_query($sql) );
	return $out;
}
function list_user_games($user_id,$event_id)
{
	$sql = "SELECT * FROM `project_gamesdb_user2game` AS u2g JOIN `project_gamesdb` AS g ON u2g.game_id = g.id WHERE u2g.event_id = ".$event_id." AND u2g.user_id = ".$user_id." AND g.activ = 1 ORDER BY g.name ASC; ";
	$out =  mysql_query($sql);
	return $out;
}
function list_user_duration($user_id,$game_id,$event_id)
{
	$sql = "SELECT * FROM `project_gamesdb_user2game` AS u2g WHERE u2g.event_id = ".$event_id." AND u2g.user_id = ".$user_id." AND u2g.game_id = '".$game_id."'; ";
	$out =  mysql_fetch_array(mysql_query($sql));
	return $out;
}
function list_images($image)
{
global  $global;
$ordner = $global['script_root']."/images/turnier_logo"; //auch komplette Pfade m&uuml;glich ($ordner = "download/files";)
 
// Ordner auslesen und Array in Variable speichern
$allebilder = scandir($ordner); // Sortierung A-Z
// Sortierung Z-A mit scandir($ordner, 1)               				
 
// Schleife um Array "$alledateien" aus scandir Funktion auszugeben
// Einzeldateien werden dabei in der Variabel $datei abgelegt
$output .= '
			<select name="logo">
				<option value=""># Kein  Logo</option>';
			
foreach ($allebilder as $bild) {
 
	// Zusammentragen der Dateiinfo
	$bildinfo = pathinfo($ordner."/".$bild); 
	//Folgende Variablen stehen nach pathinfo zur Verfügung
	// $ ['filename'] =Dateiname ohne Dateiendung  *erst mit PHP 5.2
	// $dateiinfo['dirname'] = Verzeichnisname
	// $dateiinfo['extension'] = Dateityp -/endung
	// $dateiinfo['basename'] = voller Dateiname mit Dateiendung
 
	// Gr&uuml;ße ermitteln für Ausgabe
	$size = ceil(filesize($ordner."/".$bild)/1048576); 
	//1024 = kb | 1048576 = MB | 1073741824 = GB
 
	// scandir liest alle Dateien im Ordner aus, zus&auml;tzlich noch "." , ".." als Ordner
	// Nur echte Dateien anzeigen lassen und keine "Punkt" Ordner
	// _notes ist eine Erg&auml;nzung für Dreamweaver Nutzer, denn DW legt zur besseren Synchronisation diese Datei in den Orndern ab
	// Thumbs.db ist eine Erg&auml;nzung unsichtbare Dateierg&auml;nzung die von ACDSee kommt
	// um weitere ungewollte Dateien von der Anzeige auszuschließen kann man die if Funktion einfach entsprechend erweitern
	$select_bild  =str_replace( $ordner."/", "", $image);
	$suche = substr($select_bild, 0, -3);
	
	//ECHO  "<br> SELECT: ".$select_bild."<br>";
	//ECHO  "<br> BILD: ".$bild."<br>";
	//ECHO  "<br> IMAGE: ".$image."<br>";
	
	if ($bild == $select_bild &&  ( $bild != "." && $bild != ".."  && $bild != "_notes" && $bildinfo['basename'] != "Thumbs.db" ) ) { 
$output .= '<option selected="selected" value="'.$bildinfo['basename'].'">'.$bildinfo['filename'].'.'.$bildinfo['extension'].'</option>';
	}
	elseif($bild != "." && $bild != ".."  && $bild != "_notes" && $bildinfo['basename'] != "Thumbs.db" ) {
		
$output .= '<option value="'.$bildinfo['basename'].'">'.$bildinfo['filename'].'.'.$bildinfo['extension'].'</option>';
	}	
	;
 };
 $output .= '
			</select>';
return $output;
}


function add_game($DATA)
{
$DATA1	 		= $DATA['news'];
$beschreibung	= $DATA1['content'];
$logo			= $DATA['logo'];	
//$name			= preg_replace ( '/[^a-z0-9 ]/i', '', $DATA['name'] );
$name			= mysql_real_escape_string($DATA['name']);
$kuerzel		= $DATA['kuerzel'];

$insert = mysql_query("
						INSERT INTO
									`project_gamesdb`
									(
										`id`,
										`bild`,
										`name`,
										`beschreibung`,
										`kuerzel`,
										`activ`
									)
									VALUES 
										(
											NULL,
											'".$logo."',
											'".$name."',
											'".$beschreibung."',
											'".$kuerzel."',
											'1'
										)
						");
}

function edit_game($DATA)
{
$DATA1	 		= $DATA['news'];
$beschreibung	= $DATA1['content'];
$logo			= $DATA['logo'];	
//$name			= preg_replace ( '/[^a-z0-9 ]/i', '', $DATA['name'] );
$name			= mysql_real_escape_string($DATA['name']);
$kuerzel		= $DATA['kuerzel'];
$activ			= $DATA['activ'];

$insert = mysql_query("	UPDATE  `project_gamesdb` 
						SET  
							`bild` 			=  '".$logo."',
							`name` 			=  '".$name."',
							`beschreibung` 	=  '".$beschreibung."',
							`kuerzel` 		=  '".$kuerzel."',
							`activ` 		=  '".$activ."'
							
						WHERE
							`id` = ".$DATA['id'].";
						");
	
	
}

function del_game($DATA)
{
	$sql = "DELETE FROM `project_gamesdb` WHERE id = ".$DATA['id'].";";
	$out =  mysql_query($sql);
	return $out;
}

function del_game_from_user($DATA)
{
	$sql = "DELETE FROM `project_gamesdb_user2game` WHERE game_id = ".$DATA['id']." AND user_id = ".$DATA['user_id']." AND event_id = ".$DATA['event_id']." ;";
	$out =  mysql_query($sql);
	return $out;
}
function check_user_set_game($DATA)
{
	$sql = "SELECT * FROM `project_gamesdb_user2game` WHERE game_id = ".$DATA['id']." AND user_id = ".$DATA['user_id']." AND event_id = ".$DATA['event_id']." ;";
	//$out =  mysql_fetch_array( mysql_query($sql) );
	
	if(mysql_num_rows($sql))
	{
		return FALSE;
		
	}
	else
	{
		return TRUE;
	}
}
function add_game_2_user($DATA,$user_id,$event_id)
{
global $PAGE;	
	 $anwesend 				= check_user_bezahlt($user_id,$event_id);
	// $game_bereits_gewaehlt = check_user_set_game($DATA);
	 // error_game_bereits_gewaehlt
	
	//if($game_bereits_gewaehlt)
	//{	
	 if($anwesend)
		{
			if($_GET['action'] <> "add_duration")
			{
			$all_ids = $_POST['game_id'];
			foreach($all_ids as $game_id)
			{
				$sql		= "INSERT INTO `project_gamesdb_user2game` (`id`, `user_id`, `game_id`, `event_id`) VALUES (NULL, '".$user_id."', '".$game_id."', '".$event_id."')";
				$insert = mysql_query($sql);
			}
			}
			else{
				$game_id	= $DATA['game_id'];
				$sql		= "INSERT INTO `project_gamesdb_user2game` (`id`, `user_id`, `game_id`, `event_id`) VALUES (NULL, '".$user_id."', '".$game_id."', '".$event_id."')";
				$insert = mysql_query($sql);
			}
			//$sql		= "INSERT INTO `project_gamesdb_user2game` (`id`, `user_id`, `game_id`, `event_id`) VALUES (NULL, '".$user_id."', '".$id."', '".$event_id."')";
			//$insert = mysql_query($sql);
		}
		else
		{
			$PAGE->error_die(html::template("error_nicht_auf_bezahlt"));
		}
	//}
	//else
	//{
//		$PAGE->error_die(html::template("error_game_bereits_gewaehlt"));
//	}
}
function add_gesuche_2_user($DATA,$user_id,$event_id)
{
global $PAGE;	
	 $anwesend 				= check_user_bezahlt($user_id,$event_id);
	// $game_bereits_gewaehlt = check_user_set_game($DATA);
	 // error_game_bereits_gewaehlt
	
	//if($game_bereits_gewaehlt)
	//{	
	 if($anwesend)
		{
			$sql	= "UPDATE  `project_gamesdb_user2game` SET `add_time` = '".$DATA['add_time']."' WHERE  `game_id` ='".$DATA['game_id']."' AND user_id = '".$user_id."'  AND event_id = '".$event_id."';";
			//	"INSERT INTO `project_gamesdb_user2game` (`id`, `user_id`, `game_id`, `event_id`) VALUES (NULL, '".$user_id."', '".$game_id."', '".$event_id."')";
			$insert = mysql_query($sql);
			
			//$id			= $DATA['game_id'];
			//$sql		= "INSERT INTO `project_gamesdb_user2game` (`id`, `user_id`, `game_id`, `event_id`) VALUES (NULL, '".$user_id."', '".$id."', '".$event_id."')";
			//$insert = mysql_query($sql);
		}
		else
		{
			$PAGE->error_die(html::template("error_nicht_auf_bezahlt"));
		}
	//}
	//else
	//{
//		$PAGE->error_die(html::template("error_game_bereits_gewaehlt"));
//	}
}
function count_game($id,$event_id)
{	// SELECT COUNT(g.user_id) AS count FROM project_gamesdb_user2game  AS g  LEFT JOIN event_teilnehmer AS t ON g.user_id = t.user_id WHERE ( g.game_id = 2 AND g.event_id = 9 ) AND t.zahl_typ > 1 AND t.event_id = 9
	// SELECT COUNT(user_id) AS count FROM project_gamesdb_user2game WHERE game_id = ".$id." AND event_id = ".$event_id."
	$out = mysql_fetch_array(mysql_query(" SELECT COUNT(g.user_id) AS count FROM project_gamesdb_user2game  AS g  LEFT JOIN event_teilnehmer AS t ON g.user_id = t.user_id WHERE ( g.game_id = ".$id." AND g.event_id = ".$event_id." ) AND t.zahl_typ > 1 AND t.event_id = ".$event_id." "));
	return $out['count'];
}

function count_suche($id,$event_id)
{	
	global $datum;
	// SELECT COUNT(g.user_id) AS count FROM project_gamesdb_user2game  AS g  LEFT JOIN event_teilnehmer AS t ON g.user_id = t.user_id WHERE ( g.game_id = 2 AND g.event_id = 9 ) AND t.zahl_typ > 1 AND t.event_id = 9
	// SELECT COUNT(user_id) AS count FROM project_gamesdb_user2game WHERE game_id = ".$id." AND event_id = ".$event_id."
	$query = mysql_query("SELECT g.* FROM project_gamesdb_user2game AS g  WHERE  g.game_id = '".$id."' AND g.event_id = '".$event_id."' AND g.add_time <> '0000-00-00 00:00:00';");
	$count_user = 0;
	while($out_duration_validate =  mysql_fetch_array($query))
	{
		$test .= "<br>".date($out_duration_validate['add_time'])." ".date($datum);
		
		if( date($out_duration_validate['add_time']) > date($datum)) {
			$count_user ++;
		}
	}
	return $count_user;	
}

function out_table($SQL,$DARF,$GET,$user,$event_id)
{
	if($user == 1) { $bildpfad = "../images/turnier_logo/";}
	else { $bildpfad = "../../../images/turnier_logo/";}
$output .= '<table cellpadding="5" cellspacing="0" border="0" width="100%">
					<tr>
						<td class="msghead3" width="10%">
						Logo
						</td>
						<td class="msghead3" width="15%">
						Name
						</td>
						<td class="msghead3" width="580">
						Beschreibung
						</td>
						';
$output .= '						
						<td class="msghead3">
							Action
						</td>
						';

$output .= '						
					</tr>
					';
	while($out = mysql_fetch_array($SQL))
	{
	$output .= '	<tr class="msgrow'.(($i%2)?2:1).'">
						<td>
						<img src="'.$bildpfad.$out['bild'].'" style="padding-right:5px;">
						</td>
						<td style="padding-right:5px; padding-left:5px;" valign="top">
						<b>'.$out['name'].'</b>
						<br>
						<br>
						<a href="?hide=1&action=show&id='.$out['id'].'">
						Anzahl Teilnehmer:
						';
						$output .= count_game($out['id'],$event_id).'
						</a>';
												
						if($out['kuerzel'] && file_exists("functions.php"))	// && file_exists("functions.php")
						{
							$output .= '<br> <b>Abk&uuml;rzung/en:</b><br />';
							$output .= str_replace(",","<br />",$out['kuerzel']);
						}
						
	$output .= '
						</td>
						<td  style="padding-right:5px; padding-left:5px;">
						';
			if($GET['page'] == "suche")
			{
				$output .= 	wordwrap($out['beschreibung'], 130, " <br />", false); 
			}
			else
			{
				$output .= nl2br($out['beschreibung']);
			}
			$output .= '
						</td>
						';
						
						if($GET['page'] == "suche")
						{
$output .= '			<td>';
//$output .= '							<input type="radio" id="game_id" name="game_id" value="'.$out['id'].'">';
$output .= '							<input type="checkbox" id="game_id_'.$out['id'].'" name="game_id[]" value="'.$out['id'].'">';
$output .= '			</td>';
						}
	
$output .= '<td>';
	if(file_exists("functions.php"))
	{
		if($DARF['edit'])
		{
		$output .= '		<a href="?hide=1&page=admin&action=edit&id='.$out['id'].'" target="_parent">
								<img src="/images/icons/pencil.png" title="Deteils anzeigen/&auml;ndern"/>
							</a>
					';			
		}
	}
	if($DARF['del'] && $_GET['page'] != "suche"  && $_GET['page'] != "games")
	{
	$output .= '		
						<a href="?hide=1&page=admin&action=del&id='.$out['id'].'" target="_parent">
							<img src="/images/icons/delete.png" title=" l&uuml;schen"/>
						</a>
				';			
	}
	elseif( $_GET['page'] != "suche")
	{
	$output .= '		
						<a href="?hide=1&action=del&id='.$out['id'].'" target="_parent">
							<img src="/images/icons/delete.png" title=" l&uuml;schen"/>
						</a>
				';			
	}
	else
	{
		
	}

$output .= '</td>';
	
$output .= '		</tr>
					';
	$i++;
	}
					
$output .= '</table>';
return $output;	
}

function out_form($id,$ACTION)
{
	if($id)
	{
		$out = list_single_game($id);
		
	}
	if($ACTION)
	{
		$befehl = "&befehl=senden";
	}
		
	$output .= '
	<form method="post" id="add" action="?hide=1&action='.$ACTION['action'].$befehl.'">
		<table cellpadding="6" cellspacing="1" border="0" width="100%" class="msg"> 
		  <tr>
			<td class="msghead3" colspan="2" style="padding: 2px 6px;">Game</td>
		  </tr>
		  <tr>
			<td class="msghead3" width="100">	
			Logo
			</td>
			<td class="msghead3" width="500">	
			Name
			</td>
		  </tr>
			<tr>
			<td class="msgrow1">	
				';
				$output .= list_images($out['bild']);
				
			$output .='
			</td>
			<td class="msgrow1" >	
				<input type="text" name="name" size="100" value="'.$out['name'].'" >
				<br>
				<br>
				<input type="text" name="kuerzel" size="100" value="'.$out['kuerzel'].'" >
				<br>
				Hier k&ouml;nnen K&uuml;rzel kommagetrennt eingegeben werden, diese k&ouml;nnen dann &uuml;ber die Suche eingegeben werden.
			</td>
			</tr>
			<tr>
			<td class="msghead3" colspan="2">
				Beschreibung
			</td>
			<tr>
			<td class="msgrow1" colspan="2">	
				<!-- ################################################ -->

												<textarea name="news[content]" class="ckeditor" cols="35" rows="20" height="150" style="width:50%;">'.$out['beschreibung'].'</textarea>
												<input type="hidden" name="news[html]" value="1" />
												<script type="text/javascript" src="{BASEDIR}html/ckeditor/ckeditor.js"></script>

												<!-- ################################################ -->
			</td>
		  </tr>
		  <tr>
			<td class="msgrow2" colspan="2" align="right">
				<input accesskey="s"  name="senden" type="submit" value="'.$ACTION['action'].'" />
				<input type="hidden" name="id" value="'.$out['id'].'" />
				<input type="hidden" name="activ" value="'.$out['activ'].'" />
			</td>
		  </tr>
		</table>
	</form>
	';
	
	return $output;
}
function list_game_stats($event_id)
{
	global $styles, $global, $datum, $user_id;
	$style = $global['defaultstyle'];
	$sql = out_game_stats();
	//$sql = list_games();
	$output .='<table cellpadding="6" cellspacing="1" border="0" width="100%" class="msg">
				<tr>
					<td class="msghead3" width="60%">Game</td>
					';
	$output .='		<td class="msghead3">Spielersuche eintragen</td>';
	$output .='		<td class="msghead3">User auf Spielersuche</td>
					<td class="msghead3">User haben Interesse</td>
				</tr>	
	';

	while($out_data = mysql_fetch_array($sql)){
		$out 				= list_single_game($out_data['id']);
		$count_game 		= count_game($out_data['id'],$event_id);
		$count_suche 		= count_suche($out_data['id'],$event_id);
		$out_user_duration 	= list_user_duration($user_id,$out_data['id'],$event_id);
		if($count_game > 0)
		{
		$output .= '<tr  class="msgrow'.(($i%2)?1:2).'"" 
		';
			$output .= ' onmouseover="this.style.background=\''.$styles[$style]['msg_over'].'\'; this.style.cursor=\'pointer\';" ';
			$output .= ' onmouseout="this.style.background=\''.$farbe.'\'" >';
			
			$output .= '<td class="gemesdb" ';
		$output .= ' onclick=" document.location = \'?hide=1&action=show&id='.$out['id'].' \' ";  
					';
			$output .= ' title="Game anzeigen" >';
			$output .= 		$out['name'].'
							<span align="right">
								<img align="right" src="../images/turnier_logo/'.$out['bild'].'"/>
							</span>						
						</td>
						<td >';
						if(date($out_user_duration['add_time']) > date($datum))
						{
							$startzeit = strtotime(date($datum));
							$endzeit = strtotime($out_user_duration['add_time']);
							 $dauerInMinuten = round(($endzeit - $startzeit)/60 );
			$output .= 'Du bist noch '.$dauerInMinuten.' Minuten in der Suche!';
						}
						else{
			$output .= '	<form name="add-duration" action="?hide=1&action=add_duration&comand=senden" method="POST" >
								<select name="add_time">
									<option value="0">Bitte dauer der Suche w&auml;hlen</option>
									<option value="'.date("Y-m-d H:i:s", strtotime($date . "+30 minutes")).'">30 Min</option>
									<option value="'.date("Y-m-d H:i:s", strtotime($date . "+60 minutes")).'">60 Min</option>
									<option value="'.date("Y-m-d H:i:s", strtotime($date . "+120 minutes")).'">120 Min</option>
								</select>
								<input name="game_id" value="'.$out['id'].'" type="hidden">
								<input name="senden" value="Suche eintragen" type="submit">
							</form>
						</td>';
						}
			$output .= '<td>';
						if(	$count_suche > 0)
						{
			$output .= '<b>'.$count_suche.'</b>';
						}
						else
						{
			$output .= ''.$count_suche.'';
						}
			$output .= '</td>
						<td >'.$count_game.'</td>
					</tr>
				';//$out_data['stimmen']
		$i++;
		}
	}
	$output .= '</table>';
	return $output;
}

function list_single_game_stats($id,$event_id,$user_id)
{
	$game = list_single_game($id);
	//$sql = mysql_query("SELECT * FROM `project_gamesdb_user2game` AS g LEFT JOIN user AS u ON g.user_id = u.id WHERE  `game_id` = ".$id." AND g.event_id = ".$event_id."  ");
	$sql = mysql_query("SELECT * FROM `project_gamesdb_user2game` AS g 
						LEFT JOIN user AS u  ON ( g.user_id = u.id )
						LEFT JOIN event_teilnehmer AS t  ON ( u.id = t.user_id )
						WHERE  ( `game_id` = ".$id." AND g.event_id = ".$event_id." ) AND (t.event_id = ".$event_id." AND t.Zahl_typ > 1)
						
						");
	
	$output .='
			<table cellpadding="6" cellspacing="1" border="0" width="80%" class="msg">
				<tr>
					<td colspan="2">
						<h2>'.$game['name'].'</h2>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<img src="../images/turnier_logo/'.$game['bild'].'" style="padding-right: 5px;"/>
						'.nl2br($game['beschreibung']).'
					</td>
				</tr>';
				if(check_user_games($game['id'],$user_id,$event_id) && check_user_angemeldet($user_id,$event_id) && $_GET['action'] != "show_beamer")				
				{
$output .= '				
				<tr>
					<td colspan="2" align="right">
						<form method="post" action="?hide=1&page=suche&action=add">
							<input type="hidden" name="game_id[]" value="'.$game['id'].'">
							<input type="submit"  value="Zu meinen Games hinzuf&uuml;gen">
						</form>
					</td>					
				</tr>';
				}
				elseif(!check_user_games($game['id'],$user_id,$event_id) && $_GET['action'] != "show_beamer")				
				{
$output .= '				
				<tr>
					<td colspan="2" align="right">
						<form method="post" action="?hide=1&action=del&id='.$game['id'].'">
							<input type="submit"  value="Aus meinen Games entfernen">
						</form>
					</td>					
				</tr>';
				}
				else{}
$output .= '				
				<tr>
					<td colspan="2">
						<h3> Teilnehmer die '.$game['name'].' spielen wollen:</h3>
					</td>
				</tr>
				<tr>
					<td class="msghead3" width="50%">Teilnehmer</td>
					<td class="msghead3" width="50%">Sitzplatz</td>
				</tr>	
	';
	while($out = mysql_fetch_array($sql))
	{	
		$sql_sitz 		= mysql_query( "SELECT * FROM event_teilnehmer WHERE user_id = '".$out['user_id']."' AND event_id = '".$event_id."' " );
		$out_sitz		= mysql_fetch_array( $sql_sitz);
		
		$sql_sitzplan 	= mysql_query("SELECT * FROM event_sitzplan WHERE active = 1 AND event_id = '".$event_id."' " );
		$out_sitzplan 	= mysql_fetch_array( $sql_sitzplan);
		
		$output .= '
				<tr class="msgrow'.(($i%2)?2:1).'">
				
					<td  class="info" >
						<a href="/user/?id='.$out['user_id'].'">'.$out['vorname'].' \''.$out['nick'].'\' '.$out['nachname'].'</a>';
						
						if(file_exists('../images/avatar/tn_'.$out['user_id'].'.jpg'))
						{
		$output .= '
						<span align="right">
								<img style="margin-right:auto;" align="right" src="../images/avatar/tn_'.$out['user_id'].'.jpg""/>
						</span>';
						
						}
		$output .= '				
					</td>
					<td >';
					//if(mysql_num_rows($sql_sitz) != 0)
					if($out_sitz['sitz_nr'])
					{
						$output .= '<a href="/party/?do=sitzplan&id='.$out_sitzplan['id'].'&highlight='.$out_sitz['sitz_id'].'">'.$out_sitz['sitz_nr'].'</a>';
					}
					else
					{
						$output .=  "Noch keinen Sitzplatz eingenommen";
					}
					
		$output .= '</td>
					
				</tr>';

	$i++;				
	}
	
	
	$output .= '</table>';
	return $output;	
}
function check_user_games($game_id,$user_id,$event_id)
{
	$sql = "SELECT * FROM `project_gamesdb_user2game` WHERE user_id = ".$user_id." AND game_id = ".$game_id." AND event_id = ".$event_id."";
	$out =  mysql_query($sql);
	
	if(mysql_num_rows($out) == 0)
	{
		return TRUE;
		
	}
	else
	{
		return FALSE;
	}
	
}
?>