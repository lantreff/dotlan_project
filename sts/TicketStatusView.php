<?

$MODUL_NAME = "sts";
include_once("../../../global.php");
include("../functions.php");
$iCount = 0;

$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");

// Sortierung //
// Variablen für die Sortierfunktion
	$sort			= "prio"; // Standardfeld das zum Sortieren genutzt wird
	$order			= "DESC"; // oder DESC | Sortierung aufwerts, abwerts

	if (IsSet ($_GET['sort'] ) )
	{
		$sort		= security_string_input($_GET['sort']);
	}
	if (IsSet ($_GET['order'] ) )
	{
		$order		= security_string_input($_GET['order']);
	}
////////////////////////////////////////////////



$seite = $_GET["seite"];  //Abfrage auf welcher Seite man ist 

//Wenn man keine Seite angegeben hat, ist man automatisch auf Seite 1 
if(!isset($seite)) 
   { 
   $seite = 1; 
   } 
   
$eintraege_pro_seite = 15; 

//Ausrechen welche Spalte man zuerst ausgeben muss: 

$start = $seite * $eintraege_pro_seite - $eintraege_pro_seite; 

if(isset($_GET['queueid']))
{
	$queueid = $_GET['queueid'];
}
else
{
	$queueid = 1;
}
 /*###########################################################################################
Admin PAGE
*/

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{

$sql_queue_count_my_tickets = $DB->query	("
												SELECT
													*
												FROM 
													`project_ticket_ticket`
												WHERE
													agent = ".$user_id ."
											");
	$count_my_ticket = mysql_num_rows($sql_queue_count_my_tickets);

include("header.php");
include("news.php");	
include("queuelist.php");	


$output .=
"
<table width='100%' cellspacing='0' cellpadding='3' border='0'>
	<tbody>
		<tr align='left' class='contenthead'>
                        <th width='10%'>Prio<br>
                        <a href='?queueid=".$queueid."&sort=prio&order=ASC&seite=".$seite."' name='OverviewControl'><img border='0' alt='aufw&auml;rts' src='../images/sts/up-small.png'></a> /
                        <a  href='?queueid=".$queueid."&sort=prio&order=DESC&seite=".$seite."' name='OverviewControl'><img border='0' alt='abw&auml;rts' src='../images/sts/down-small.png'></a>
                        </th>
                        <th width='15%'>Ticket#<br>
                        <a href='?queueid=".$queueid."&sort=id&order=ASC&seite=".$seite."' name='OverviewControl'><img border='0' alt='aufw&auml;rts' src='../images/sts/up-small.png'></a> /
                        <a  href='?queueid=".$queueid."&sort=id&order=DESC&seite=".$seite."' name='OverviewControl'><img border='0' alt='abw&auml;rts' src='../images/sts/down-small.png'></a>
                        </th>
                        <th width='15%'>Alter<br>
                        <a href='?queueid=".$queueid."&sort=erstellt&order=DESC&seite=".$seite."' name='OverviewControl'><img border='0' alt='aufw&auml;rts' src='../images/sts/up-small.png'></a> /
                        <a  href='?queueid=".$queueid."&sort=erstellt&order=ASC&seite=".$seite."' name='OverviewControl'><img border='0' alt='abw&auml;rts' src='../images/sts/down-small.png'></a>
                        </th>
<!--start RecordTicketTitleHeader-->
                        <th width='40%'>Von/Titel</th>
<!--stop RecordTicketTitleHeader -->
                        <th width='5%'>Status<br>
                        <a href='?queueid=".$queueid."&sort=status&order=ASC&seite=".$seite."' name='OverviewControl'><img border='0' alt='aufw&auml;rts' src='../images/sts/up-small.png'></a> /
                        <a  href='?queueid=".$queueid."&sort=status&order=DESC&seite=".$seite."' name='OverviewControl'><img border='0' alt='abw&auml;rts' src='../images/sts/down-small.png'></a>
                        </th>
                        <th width='5%'>Sperre<br>
                        <a href='?queueid=".$queueid."&sort=sperre&order=ASC&seite=".$seite."' name='OverviewControl'><img border='0' alt='aufw&auml;rts' src='../images/sts/up-small.png'></a> /
                        <a  href='?queueid=".$queueid."&sort=sperre&order=DESC&seite=".$seite."' name='OverviewControl'><img border='0' alt='abw&auml;rts' src='../images/sts/down-small.png'></a>
                        </th>
                        <th width='10%'>Queue<br>
                        <a href='?queueid=".$queueid."&sort=queue&order=ASC&seite=".$seite."' name='OverviewControl'><img border='0' alt='aufw&auml;rts' src='../images/sts/up-small.png'></a> /
                        <a  href='?queueid=".$queueid."&sort=queue&order=DESC&seite=".$seite."' name='OverviewControl'><img border='0' alt='abw&auml;rts' src='../images/sts/down-small.png'></a>
                        </th>
                        <th width='15%'>Besitzer<br>
                        <a href='?queueid=".$queueid."&sort=agent&order=ASC&seite=".$seite."' name='OverviewControl'><img border='0' alt='aufw&auml;rts' src='../images/sts/up-small.png'></a> /
                        <a  href='?queueid=".$queueid."&sort=agent&order=DESC&seite=".$seite."' name='OverviewControl'><img border='0' alt='abw&auml;rts' src='../images/sts/down-small.png'></a>
                        </th>
                    </tr>
";				
if (IsSet ($_POST['suche'] ) ) // nur wenn im fled suchen etwas eingegeben wurde wird in den eingetragenen spalten gesucht. diese können um noch weitere Erg&auml;nzt werden, dies kann einfach duch ein "OR" getrennt geschehen
			 {
				$sql_ticket_queue_list = $DB->query("
													SELECT
														*
													FROM
														project_ticket_ticket
													WHERE
														(
														`id` 			LIKE  '%".$_POST['suche']."%' OR
														`erstellt` 		LIKE  '%".$_POST['suche']."%' OR
														`user`	 		LIKE  '%".$_POST['suche']."%' OR
														`titel` 		LIKE  '%".$_POST['suche']."%' OR
														`status` 		LIKE  '%".$_POST['suche']."%' OR
														`prio`	 		LIKE  '%".$_POST['suche']."%' OR
														`sperre` 		LIKE  '%".$_POST['suche']."%' OR
														`agent` 		LIKE  '%".$_POST['suche']."%' OR
														`queue` 		LIKE  '%".$_POST['suche']."%' OR
														`text` 			LIKE  '%".$_POST['suche']."%'
														)
													AND
														( status <> 1  AND  status <> 2 )
													LIMIT
														".$start.", ".$eintraege_pro_seite."

												");
				}
				else {

				$sql_ticket_queue_list = $DB->query("
													SELECT
														*
													FROM
														project_ticket_ticket
													WHERE
														( status <> 1  AND  status <> 2 )
													AND
														sperre = 'frei'
													ORDER BY
														".$sort."
														".$order."
													LIMIT
														".$start.", ".$eintraege_pro_seite."
												");
				}					
//$sql_ticket_queue_list = $DB->query("SELECT * FROM `project_ticket_ticket` WHERE queue = ".$queueid." ");

 
$result = mysql_query("
						SELECT
							id
						FROM
							project_ticket_ticket
						WHERE
							( status <> 1  AND  status <> 2 )
					"); 
					
$menge = mysql_num_rows($result); 	
//Errechnen wieviele Seiten es geben wird 
$wieviel_seiten = $menge / $eintraege_pro_seite; 

				
while($out_ticket_queue_list = $DB->fetch_array($sql_ticket_queue_list))
{
	$out_ticket_queue_list_user = 
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM 
													`user`
												WHERE
													id = ".$out_ticket_queue_list['user']."
											")
								);
	$out_ticket_queue_list_agent = 
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM 
													`user`
												WHERE
													id = ".$out_ticket_queue_list['agent']."
											")
								);
	$out_ticket_queue_list_queue = 
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM 
													project_ticket_queue
												WHERE
													id = ".$out_ticket_queue_list['queue']."
											")
								);
	$out_ticket_zoom_status = 
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM 
													project_ticket_status
												WHERE
													id = ".$out_ticket_queue_list['status']."
											")
								);
							if($iCount % 2 == 0)
							{
								$currentRowClass = "msgrow2";

							}
							else
							{
								$currentRowClass = "msgrow1";
							}									
$output .=
"					
					<tr class='".$currentRowClass."'>
						<td width='1%' title='Priorit&auml;t: ".$out_ticket_queue_list['prio']."' class='PriorityID-".$out_ticket_queue_list['prio']."'>&nbsp;&nbsp;</td>
                        <td>
                            <table width='100%' height='100%' cellspacing='0' cellpadding='0' border='0'>
                                <tbody><tr>
<!--                                    <td class='PriorityID-".$out_ticket_queue_list['prio']."' title='Priorit&auml;t: ".$out_ticket_queue_list['prio']."' width='1%'>&nbsp;&nbsp;</td> -->
                                    <td>
                                        <a title='".$out_ticket_queue_list['titel']."' href='TicketZoom.php?ticketid=".$out_ticket_queue_list['id']."&queueid=".$out_ticket_queue_list['queue']."'> Ticket#: ".$out_ticket_queue_list['id']."</a>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                        <td>
";

$oldDate	= strtotime($out_ticket_queue_list['erstellt']);
$actDate = strtotime(date('Y-m-d H:i:s'));              // aktuelles Datum
$diffDate = ($actDate-$oldDate);         // Differenz berechnen (in Sekunden)

$days = floor($diffDate / 24 / 60 / 60 );   // Anzahl Tage = Sekunden /24/60/60
$diffDate = $diffDate - ($days*24*60*60);   // den verbleibenden Rest berechnen = Stunden
$hours = floor($diffDate / 60 / 60);      // den Stundenanteil herausrechnen
$diffDate = ($diffDate - ($hours*60*60));
$minutes = floor($diffDate/60);            // den Minutenanteil
$diffDate = $diffDate - ($minutes*60);
$seconds = floor($diffDate);             // die verbleibenden Sekunden
if($days > 0)
{
$output .=
"	                          
".$days." Tage 

";
}
if($hours > 0)
{
$output .=
"	                          
".$hours." Stunden 

";
}
if($minutes > 0 && $days == 0)
{
$output .=
"	                          
".$minutes." Minuten

";
}
$output .=
"
                        </td>


                        <td>
                            
                            <div title='&quot;".$out_ticket_queue_list_user['vorname']." ".$out_ticket_queue_list_user['nachname']."&quot; &lt;".$out_ticket_queue_list_user['email']."&gt;'>'".$out_ticket_queue_list_user['vorname']." ".$out_ticket_queue_list_user['nachname']."' &lt;".$out_ticket_queue_list_user['email']."&gt;</div>


<!--start RecordTicketTitle-->
                            <div title='".$out_ticket_queue_list['titel']."'>".$out_ticket_queue_list['titel']."</div>
<!--stop RecordTicketTitle -->
                            
                        </td>
                        <td>
                            <div title='".$out_ticket_zoom_status['name']."'>".$out_ticket_zoom_status['name']."</div>
                        </td>
                        <td>
                            <div title='".$out_ticket_queue_list['sperre']."'>".$out_ticket_queue_list['sperre']."</div>
                        </td>
                        <td>
                            <div title='".$out_ticket_queue_list_queue['name']."'>".$out_ticket_queue_list_queue['name']."</div>
                        </td>
                        <td>
                            <div title='".$out_ticket_queue_list_agent['vorname']." ".$out_ticket_queue_list_agent['nachname']."'</div>
                            <div title='(".$out_ticket_queue_list_agent['vorname']." ".$out_ticket_queue_list_agent['nachname'].")' >(".$out_ticket_queue_list_agent['vorname']." ".$out_ticket_queue_list_agent['nachname'].")</div>
                        </td>
                    </tr>
";

$iCount ++;
}

$output .=
"					

	</tbody>
</table>

";
//Ausgabe der Seitenlinks: 
 $output .= "<br>
			 <div align=\"center\">"; 
 $output .= "<b>Seite:</b> "; 

//Ausgabe der Links zu den Seiten 
for($a=0; $a < $wieviel_seiten; $a++) 
   { 
   $b = $a + 1; 

   //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
   if($seite == $b) 
      { 
      $output .= "  <b>".$b."</b> "; 
      } 

   //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
   else 
      { 
      $output .= "  <a href='?queueid=".$queueid."&seite=".$b."'>".$b."</a> "; 
      } 


   } 
$output .= "</div>"; 

}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>
