<?

$MODUL_NAME = "sts";
include_once("../../../../global.php");
include("../../functions.php");

$ticketid 	= $_GET['ticketid'];
$iCount		= 0;
$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System - Rechteverwaltung");
	$part	= array(_("STS - Administration"), "team");
	$output = "<a href=\"{BASEDIR}admin/projekt".$S->url."\">"._("Projekt")."</a> &raquo; <a href=\"{BASEDIR}admin/projekt/sts/admin/".$S->urlu."\">".htmlentities($part[0])."</a><hr width=\"100%\" noshade class=newsline><br />\n\n".$output;

								
//////////
$user		= $user_id;
$text 		= nl2br($_POST['user_eingabe']);
$tucket_id 	= $_POST['ticket_id'];
$prio 		= $_POST['prio'];


$sql_list_orga = $DB->query("
											SELECT
												*
											FROM
												user_orga
									");
								

/*###########################################################################################
Admin PAGE
*/

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{

	if($action == 'speichern' && isset($_POST['submit']))
	{
	$drop_all = $DB->query("TRUNCATE TABLE project_ticket_agent_queue");
					foreach($team AS $orgaid => $data) {
					$rights = 0;
				
				if(is_array($data['rights']))
					{
						foreach($data['rights'] AS $r)
						{												
							//Echo "Userid= ".$orgaid."<br>";Echo "R= ".$r."<br>";
							
							$info = $DB->fetch_array( $DB->query("SELECT * FROM project_ticket_queue WHERE id = '".$r."'") );
							
							$output .= "Das Recht ".$info['name']." zu sehen  wurde aktiviert!<bR>" ;
						
								$insert = $DB->query("
													INSERT INTO
														`project_ticket_agent_queue`
															(
																`id`,
																`user_id`,
																`queueid`
															)
													VALUES
														(
															NULL,
															'".$orgaid."',
															'".$r."'
														)
												");
											
							
							
						}
					
					}				
			else 
			{
					$rights=0;
					// Echo "ELSE <br>";
			}
			//Echo "Hallo ".$orgaid." ".$rights."<br>";
			//$DB->query("UPDATE user_orga SET rights_project=".intval($rights)." WHERE id=".intval($orgaid));
			//$PAGE->redirect("{BASEDIR}admin/projekt/sts/admin/rights.php","Rechteverwaltung","die Userrechte wurden gespeichert");
		}

						
					
				
		$PAGE->redirect("{BASEDIR}admin/projekt/sts/admin","Rechteverwaltung","die Userrechte wurden gespeichert");
		//$output .= "<meta http-equiv='refresh' content='0; URL=test.php'>";
	}
	else
	{


		
		$res = $DB->query("SELECT 
									u.id AS id,
									u.nick,
									u.vorname,
									u.nachname,
									u.email,

									o.funktion,
									o.group_id,
									
									o.rights,
									o.id AS orga_id
									
								FROM 
									user_orga AS o
								
								LEFT JOIN
									user AS u
								ON 
								
								o.user_id=u.id
								
							
						");
		$output .= "
				<h1 style='margin: 5px 0px 5px;'>
					Zuordnung der User zu den einzelnen Bereichen im Ticket System
				</h1>";
		$output .= "<form action=\"?action=speichern\" method=\"post\">
		<table width='100%' cellspacing='0' cellpadding='2' border='0'>";
		$output	.= "<tr valign=\"bottom\" align=\"center\">";
		$output	.= "<td class=\"msghead3\" width=\"100%\" align=\"left\"><b>Nick</b></td>";
		$sql_orga_queue = $DB->query("
											SELECT
												*
											FROM
												`project_ticket_queue`
										");
			while($out_orga_queue = $DB->fetch_array($sql_orga_queue))
			{// begin while
				$output	.= "<td class=\"msghead3\" style=\"border-left: 1px solid #FFFFFF;\">
								<b> ";
									
									$laenge = strlen($out_orga_queue['name']);
									for($x=0;$x<=$laenge;$x++) {
									 $wort[$x] = substr($out_orga_queue['name'], $x, 1);
									$output	.=  $wort[$x]."<br>";
									}  									
									$output	.= "
								</b>
							</td>";
			}
		
		
		$output .= "</tr>";
		

		while($info = $DB->fetch_array($res)) {
			
			$id_user 	= htmlentities($info['id']);
			$vorname 	= htmlentities($info['vorname']);
			$nachname 	= htmlentities($info['nachname']);
			$nick 		= htmlentities($info['nick']);
			$funktion	= htmlentities($info['funktion']);
			
			
			$output	.= "<tr>";
			
			$output	.= "<td class=\"msgrow".(($i%2)?1:2)."\" width=\"100%\" nowrap>".$vorname." '".$nick."' ".$nachname."</td>";
			
			$sql_orga_queue = $DB->query("
											SELECT
												*
											FROM
												`project_ticket_queue`
										");
			
			while($out_orga_queue = $DB->fetch_array($sql_orga_queue))
			{// begin while
				
				$out_orga_queue_checked = 
											$DB->fetch_array(
																$DB->query("
																				SELECT
																					*
																				FROM
																					`project_ticket_agent_queue`
																				WHERE
																				(	queueid = '".$out_orga_queue['id']."'
																				AND
																					user_id = '".$id_user."'
																				)
																			")
															);
					
						
						
							$output	.= "<td style=\"border-left: 1px solid #FFFFFF;\" class=\"msgrow".(($i%2)?1:2)."\"><input type=checkbox name=\"team[".$id_user."][rights][]\" value=".$out_orga_queue['id']." ".		(($out_orga_queue_checked['queueid'] & $out_orga_queue['id'])?"checked":"").	"></td>\n";
						
						
							//$output	.= "<td style=\"border-left: 1px solid #FFFFFF;\" class=\"msgrow".(($i%2)?1:2)."\"><input type='checkbox' title='User ".$vorname." ".$nachname." darf den Bereich ".$out_orga_queue['name']." sehen!'  value='".$out_orga_queue['id'].";".$id."' name=\team[".$id."][".$out_orga_queue['id']."][]\"  ></td>\n";					
						
			}
			
			$output .= "</tr>";
			
			$i++;
		}
		$output .= "</table><br /><input name=\"submit\" value=\"Rechte Speichern\" type=submit></form>";
		
		
	}
	
	$output .= "<br><hr><br>";
	$output .= "
				<h1 style='margin: 5px 0px 5px;'>
					Mail oder Pm im Ticket System
				</h1>";
	$output .= "
					<form mame='MAILorPM'  action=\"?action=speichern\" method=\"post\">
				";
				if($_GET['action'] == 'speichern')
				{
						$out_pm_mail_checked = 
											$DB->fetch_array(
																$DB->query("
																				SELECT
																					*
																				FROM
																					`project_ticket_globals`
																				WHERE
																					type = 'mail_pm'
																				
																			")
															);
						
							if($out_pm_mail_checked['wert'] == 1 )
							{									
								if(isset($_POST['MAILorPM']))
								{
									$DB->query("UPDATE project_ticket_globals SET wert= '".$_POST['MAILorPM']."' WHERE type= 'mail_pm' ");
								}
								else
								{
									$DB->query("UPDATE project_ticket_globals SET wert= '0' WHERE type= 'mail_pm' ");
								}
							}
							else
							{
								if(isset($_POST['MAILorPM']))
								{
									$DB->query("UPDATE project_ticket_globals SET wert= '".$_POST['MAILorPM']."' WHERE type= 'mail_pm' ");
								}
								else
								{
									$DB->query("UPDATE project_ticket_globals SET wert= '1' WHERE type= 'mail_pm' ");
								}
							}
						
				$PAGE->redirect("{BASEDIR}admin/projekt/sts/admin","Rechteverwaltung","Es wurd auf Mail oder PM umgestellt!");		
				}
				$out_pm_mail_checked = 
											$DB->fetch_array(
																$DB->query("
																				SELECT
																					*
																				FROM
																					`project_ticket_globals`
																				WHERE
																					type = 'mail_pm'
																				
																			")
															);
				if($out_pm_mail_checked['wert'] == 1 )
				{ $name = "Es werden Mails versendet!";
					$output .= "Es werden <img src='../images/16/mail_send.png'> versendet!
					
					";
				}
				else
				{$name = "Es werden Privatnachrichten versendet!";
					$output .= "Es werden <img src='../images/16/irc_protocol.png'> versendet!
					
					";
				}
				
	$output .= "			
					<input type='submit' value='".$name."'>
					
					</form>
	";

	$output .= "<br><hr><br>";
	$output .= "
				<h1 style='margin: 5px 0px 5px;'>
					Globales Mailsystem ein oder ausschalten!
				</h1>";
		$output .= "<form mame='MAILorPM'  action=\"?action=speichern_mail\" method=\"post\">";
		
		
	$out_pm_mail_checked = 
											$DB->fetch_array(
																$DB->query("
																				SELECT
																					*
																				FROM
																					`project_ticket_globals`
																				WHERE
																					type = 'global_mail'
																				
																			")
															);
				if($out_pm_mail_checked['wert'] == 1 )
				{ $name = " GLOBAL Mail ON";
					$output .= "<font style='color:#00FF00;'> GLOBAL Mail ON!</font>";
				}
				else
				{ $name = " GLOBAL Mail OFF";
					$output .= "<font style='color:#FF0000;'> GLOBAL Mail OFF!</font>";
				}
				
				
		
$output .= "		
						<input type='submit' value='".$name."'> <font style='color:RED'> !!! Die Mailfunktion in der config.php &auml;ndern !!!</font>
					</form>
		";
if($_GET['action'] == 'speichern_mail')
{
	$out_global_mail_on_off = 
											$DB->fetch_array(
																$DB->query("
																				SELECT
																					*
																				FROM
																					`project_ticket_globals`
																				WHERE
																					type = 'global_mail'
																				
																			")
															);
// Auslesen
$alt = "['use_email']=TRUE;";
$neu = "['use_email']=FALSE;";
$file = "../../../../config.php";

$content = file_get_contents($file);				
				
/*				
				if($out_global_mail_on_off['wert'] == 1 )
				{
					$content = str_replace($alt, $neu, $content);
					$DB->query("UPDATE project_ticket_globals SET wert= 0 WHERE type= 'global_mail' ");
					
				}
				else
				{
					$content = str_replace($neu, $alt, $content);
					$DB->query("UPDATE project_ticket_globals SET wert= 1 WHERE type= 'global_mail' ");
				}
		
$fh = fopen($file, "w");
$content = fputs($fh, $content);
fclose($fh);
*/
$PAGE->redirect("{BASEDIR}admin/projekt/sts/admin","Rechteverwaltung","Es wurd auf Mail oder PM umgestellt!");		
} 
/// ENDE Antworten
}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>
