<?


include_once("../../../global.php");
include("../functions.php");

$iCount = 0;
$iCount1 = 0;
$iCount2 = 0;
$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");

// Sortierung //
// Variablen für die Sortierfunktion
	$sort			= "erstellt"; // Standardfeld das zum Sortieren genutzt wird
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
$eintraege_pro_seite = 15; 
$O_seite = $_GET["O_seite"];  //Abfrage auf welcher Seite man ist 

//Wenn man keine Seite angegeben hat, ist man automatisch auf Seite 1 
if(!isset($O_seite)) 
   { 
   $O_seite = 1; 
   } 

//Ausrechen welche Spalte man zuerst ausgeben muss: 

$O_start = $O_seite * $eintraege_pro_seite - $eintraege_pro_seite; 


$E_seite = $_GET["E_seite"];  //Abfrage auf welcher Seite man ist 

//Wenn man keine Seite angegeben hat, ist man automatisch auf Seite 1 
if(!isset($E_seite)) 
   { 
   $E_seite = 1; 
   } 
//Ausrechen welche Spalte man zuerst ausgeben muss: 

$E_start = $E_seite * $eintraege_pro_seite - $eintraege_pro_seite; 

   
$G_seite = $_GET["G_seite"];  //Abfrage auf welcher Seite man ist 

//Wenn man keine Seite angegeben hat, ist man automatisch auf Seite 1 
if(!isset($G_seite)) 
   { 
   $G_seite = 1; 
   } 
//Ausrechen welche Spalte man zuerst ausgeben muss: 

$G_start = $G_seite * $eintraege_pro_seite - $eintraege_pro_seite; 



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

if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
$sql_tickets_erinnerung = $DB->query("
										SELECT
											*
										FROM
											project_ticket_ticket
										WHERE
											status = 4
										ORDER BY
											".$sort."
											".$order."
										LIMIT
											".$E_start.", ".$eintraege_pro_seite."
									"); 
									
$sql_tickets_offen = $DB->query("
										SELECT
											*
										FROM
											project_ticket_ticket
										WHERE
											status = 3
										ORDER BY
											".$sort."
											".$order."
										LIMIT
											".$O_start.", ".$eintraege_pro_seite."
									");

$sql_tickets_geschlossen = $DB->query("
										SELECT
											*
										FROM
											project_ticket_ticket
										WHERE
											status = 1 
										OR
											status = 2
										ORDER BY
											".$sort."
											".$order."
										LIMIT
											".$G_start.", ".$eintraege_pro_seite."
									");  									

include("header.php");
include("news.php");		

if (mysql_num_rows($sql_tickets_erinnerung) > 0)
{
$output .=
"
<table width='100%' cellspacing='2' cellpadding='0' border='0' align='center'>
                            <tbody><tr>
                                <td>

                                    <table width='100%' cellspacing='0' cellpadding='4' border='0' align='center'>
                                        <tbody><tr>
                                            <td title='Alle Tickets die etwas &auml;lter sind!' class='contenthead'>Erinnerungs Tickets</td>
                                            <td align='right' class='contenthead'>
											</td>
                                        </tr>
                                        <tr>
                                            <td class='contentbody' colspan='2'>
                                                <div >
                                                    
<table width='100%' cellspacing='0' cellpadding='2' border='0'>

<!--start ContentLargeTicketGenericFilter-->
  <tbody><tr>
    <td width='70%' class='small'>
		
	</td>
    <td align='right' class='small'>

<!--start ContentLargeTicketGenericFilterNavBar-->
"; 
 $output .= "<b>Seite:</b> "; 

$sql_tickets_erinnerung_seiten = $DB->query("
										SELECT
											*
										FROM
											project_ticket_ticket
										WHERE
											( status <> 1  AND  status <> 2 AND  status <> 3 )
									"); 
$menge_erinnerung = mysql_num_rows($sql_tickets_erinnerung_seiten); 	
//Errechnen wieviele Seiten es geben wird 
$wieviel_seiten_erinnerung = $menge_erinnerung / $eintraege_pro_seite; 
//Ausgabe der Links zu den Seiten 
for($a=0; $a < $wieviel_seiten_erinnerung; $a++) 
   { 
   $b = $a + 1; 

   //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
   if($E_seite == $b) 
      { 
      $output .= "  <b>".$b."</b> "; 
      } 

   //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
   else 
      { 
      $output .= "  <a href='?queueid=".$queueid."&E_seite=".$b."'>".$b."</a> "; 
      } 


   } 

$output .= 
"
<!--stop ContentLargeTicketGenericFilterNavBar -->
    </td>
  </tr>
<!--stop ContentLargeTicketGenericFilter -->
  <tr>
    <td colspan='2'>
      <table   width='100%' cellspacing='0' cellpadding='2' border='0'>

<!--start ContentLargeTicketGenericRow-->
        
        <tbody>
";

while($out_tickets_erinnerung = $DB->fetch_array($sql_tickets_erinnerung))
{

$out_tickets_erinnerung_user = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												`user`
											WHERE
												id = ".$out_tickets_erinnerung['user']."
										")
							);
$out_tickets_erinnerung_queue = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												project_ticket_queue
											WHERE
												id = ".$out_tickets_erinnerung['queue']."
										")
							);
$out_tickets_erinnerung_prio = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												project_ticket_prio
											WHERE
												id = ".$out_tickets_erinnerung['prio']."
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
			<tr class'".$currentRowClass."' >
					<td width='3' title='Priorität: ".$out_tickets_erinnerung_prio['name']."' class='PriorityID-".$out_tickets_erinnerung_prio['id']."' >&nbsp;</td>
					
";
			if($out_tickets_erinnerung['sperre'] == "gesperrt")
			{
				$output .= 
						"<td width='32' align='center'>
						<a title='".$out_tickets_erinnerung['titel']."' href='TicketZoom.php?ticketid=".$out_tickets_erinnerung['id']."'>
							<img src='/images/projekt/16/lock.png'>
						</a>
						";
			}
			else
			{
			
				$output .= 
						"<td width='100'  align='center'>
						&nbsp;<a title='".$out_tickets_erinnerung['titel']."' href='TicketZoom.php?ticketid=".$out_tickets_erinnerung['id']."'> Ticket#: ".$out_tickets_erinnerung['id']."</a>
						";
			
			
			}
$output .= 
"

			  </td>          
			  <td width='400'>
				<div title='".$out_tickets_erinnerung_user['vorname']." ".$out_tickets_erinnerung_user['nachname']."'>".$out_tickets_erinnerung_user['vorname']." ".$out_tickets_erinnerung_user['nachname']."</div>

				<div title='".$out_tickets_erinnerung['titel']."'>".$out_tickets_erinnerung['titel']."</div>
			  </td>
			  <td width='115'>
			  
			  ";

$oldDate	= strtotime($out_tickets_erinnerung['erstellt']);
$actDate = strtotime($datum);              // aktuelles Datum
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
			  <td align='right' width='65'>
				<div title='".$out_tickets_erinnerung_queue['name']."'><small>".$out_tickets_erinnerung_queue['name']."</small></div>
			  </td>
			</tr>
";
$iCount ++;
}

$output .= 
"			
		</tbody>
	</table>
    </td>
  </tr>
</tbody></table>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='contentfooter' colspan='2'>
                                                &nbsp;

                                            </td>
                                        </tr>
                                    </tbody></table>

                                </td>
                            </tr>
                        </tbody></table>

";
}

if (mysql_num_rows($sql_tickets_offen) > 0)
{
$output .=
"
<table width='100%' cellspacing='2' cellpadding='0' border='0' align='center'>
                            <tbody><tr>
                                <td>

                                    <table width='100%' cellspacing='0' cellpadding='4' border='0' align='center'>
                                        <tbody><tr>
                                            <td title='Alle Tickets die neu sind!' class='contenthead'>Offene Tickets</td>
                                            <td align='right' class='contenthead'>
											</td>
                                        </tr>
                                        <tr>
                                            <td class='contentbody' colspan='2'>
                                                <div >
                                                    
<table width='100%' cellspacing='0' cellpadding='2' border='0'>

<!--start ContentLargeTicketGenericFilter-->
  <tbody><tr>
    <td width='70%' class='small'>
		
	</td>
    <td align='right' class='small'>

<!--start ContentLargeTicketGenericFilterNavBar-->
"; 
 $output .= "<b>Seite:</b> "; 

$sql_tickets_offen_seiten = $DB->query("
										SELECT
											*
										FROM
											project_ticket_ticket
										WHERE
											status = 3
									"); 
$menge_offen = mysql_num_rows($sql_tickets_offen_seiten); 	
//Errechnen wieviele Seiten es geben wird 
$wieviel_seiten_offen = $menge_offen / $eintraege_pro_seite; 
//Ausgabe der Links zu den Seiten 
for($z=0; $z < $wieviel_seiten_offen; $z++) 
   { 
   $y = $z + 1; 

   //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
   if($O_seite == $y) 
      { 
      $output .= "  <b>".$y."</b> "; 
      } 

   //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
   else 
      { 
      $output .= "  <a href='?queueid=".$queueid."&O_seite=".$y."'>".$y."</a> "; 
      } 


   } 

$output .= 
"
<!--stop ContentLargeTicketGenericFilterNavBar -->
    </td>
  </tr>
<!--stop ContentLargeTicketGenericFilter -->
  <tr>
    <td colspan='2'>
      <table width='100%' cellspacing='0' cellpadding='1' border='0'>

<!--start ContentLargeTicketGenericRow-->
        
        <tbody>
";

while($out_tickets_offen = $DB->fetch_array($sql_tickets_offen))
{
$out_tickets_offen_user = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												`user`
											WHERE
												id = ".$out_tickets_offen['user']."
										")
							);
$out_tickets_offen_queue = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												project_ticket_queue
											WHERE
												id = ".$out_tickets_offen['queue']."
										")
							);
$out_tickets_offen_prio = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												project_ticket_prio
											WHERE
												id = ".$out_tickets_offen['prio']."
										")
							);	
if($iCount1 % 2 == 0)
							{
								$currentRowClass1 = "msgrow2";

							}
							else
							{
								$currentRowClass1 = "msgrow1";
							}	
							
$output .= 
"			
			<tr class='".$currentRowClass1."'>
					<td width='3' title='Priorität: ".$out_tickets_offen_prio['name']."' class='PriorityID-".$out_tickets_offen_prio['id']."' >&nbsp;</td>
					
";
			if($out_tickets_offen['sperre'] == "gesperrt")
			{
				$output .= 
						"<td  width='32' align='center'>
						<a title='".$out_tickets_offen['titel']."' href='TicketZoom.php?ticketid=".$out_tickets_offen['id']."'>
							<img  align='center' src='/images/projekt/16/lock.png'>
						</a>
						";
			}
			else
			{
			
				$output .= 
						"<td  width='100'  align='center'>
						&nbsp;<a title='".$out_tickets_offen['titel']."' href='TicketZoom.php?ticketid=".$out_tickets_offen['id']."'> Ticket#: ".$out_tickets_offen['id']."</a>
						";
			
			
			}
$output .= 
"

			  </td>          
			  <td width='400'>
				<div title='".$out_tickets_offen_user['vorname']." ".$out_tickets_offen_user['nachname']."'>".$out_tickets_offen_user['vorname']." ".$out_tickets_offen_user['nachname']."</div>
				
				<div title='".$out_tickets_offen['titel']."'>".$out_tickets_offen['titel']."</div>
			  </td>
			  <td width='115'>
			  
			  ";

$oldDate	= strtotime($out_tickets_offen['erstellt']);
$actDate = strtotime($datum);             // aktuelles Datum
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
			  <td align='right' width='65'>
				<div title='".$out_tickets_offen_queue['name']."'><small>".$out_tickets_offen_queue['name']."</small></div>
			  </td>
			</tr>
";
$iCount1 ++;
}

$output .= 
"			
		</tbody>
	</table>
    </td>
  </tr>
</tbody></table>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='contentfooter' colspan='2'>
                                                &nbsp;

                                            </td>
                                        </tr>
                                    </tbody></table>

                                </td>
                            </tr>
                        </tbody></table>

";

}

if (mysql_num_rows($sql_tickets_geschlossen) > 0)
{
$output .=
"
<table width='100%' cellspacing='2' cellpadding='0' border='0' align='center'>
                            <tbody><tr>
                                <td>

                                    <table width='100%' cellspacing='0' cellpadding='4' border='0' align='center'>
                                        <tbody><tr>
                                            <td title='Alle Tickets die neu sind!' class='contenthead'>Geschlossene Tickets</td>
                                            <td align='right' class='contenthead'>
											</td>
                                        </tr>
                                        <tr>
                                            <td class='contentbody' colspan='2'>
                                                <div >
                                                    
<table width='100%' cellspacing='0' cellpadding='2' border='0'>

<!--start ContentLargeTicketGenericFilter-->
  <tbody><tr>
    <td width='70%' class='small'>
		
	</td>
    <td align='right' class='small'>

<!--start ContentLargeTicketGenericFilterNavBar-->
"; 
 $output .= "<b>Seite:</b> "; 

$sql_tickets_geschlossen_seiten = $DB->query("
										SELECT
											*
										FROM
											project_ticket_ticket
										WHERE
											( status = 1 OR status = 2 )
									"); 
$menge_geschlossen = mysql_num_rows($sql_tickets_geschlossen_seiten); 	
//Errechnen wieviele Seiten es geben wird 
$wieviel_seiten_geschlossen = $menge_geschlossen / $eintraege_pro_seite; 
//Ausgabe der Links zu den Seiten 
for($c=0; $c < $wieviel_seiten_geschlossen; $c++) 
   { 
   $v = $c + 1; 

   //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
   if($O_seite == $v) 
      { 
      $output .= "  <b>".$v."</b> "; 
      } 

   //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
   else 
      { 
      $output .= "  <a href='?queueid=".$queueid."&O_seite=".$v."'>".$v."</a> "; 
      } 


   } 

$output .= 
"
<!--stop ContentLargeTicketGenericFilterNavBar -->
    </td>
  </tr>
<!--stop ContentLargeTicketGenericFilter -->
  <tr>
    <td colspan='2'>
      <table width='100%' cellspacing='0' cellpadding='2' border='0'>

<!--start ContentLargeTicketGenericRow-->
        
        <tbody>
";

while($out_tickets_geschlossen = $DB->fetch_array($sql_tickets_geschlossen))
{
$out_tickets_geschlossen_user = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												`user`
											WHERE
												id = ".$out_tickets_geschlossen['user']."
										")
							);
$out_tickets_geschlossen_queue = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												project_ticket_queue
											WHERE
												id = ".$out_tickets_geschlossen['queue']."
										")
							);
$out_tickets_geschlossen_prio = 
			$DB->fetch_array(
								$DB->query	("
											SELECT
												*
											FROM 
												project_ticket_prio
											WHERE
												id = ".$out_tickets_geschlossen['prio']."
										")
							);	
if($iCount2 % 2 == 0)
							{
								$currentRowClass2 = "msgrow2";

							}
							else
							{
								$currentRowClass2 = "msgrow1";
							}									
							
$output .= 
"			
			<tr class='".$currentRowClass2."'>
			  
					<td width='3' title='Priorität: ".$out_tickets_geschlossen_prio['name']."' class='PriorityID-".$out_tickets_geschlossen_prio['id']."' >&nbsp;</td>
					
";
			if($out_tickets_geschlossen['sperre'] == "gesperrt")
			{
				$output .= 
						"<td  width='32'  align='center'>
						<a title='".$out_tickets_geschlossen['titel']."' href='TicketZoom.php?ticketid=".$out_tickets_geschlossen['id']."'>
							<img  src='/images/projekt/16/lock.png'>
						</a>
						";
			}
			else
			{
			
				$output .= 
						"<td  width='100'  align='center'>
						&nbsp;<a title='".$out_tickets_geschlossen['titel']."' href='TicketZoom.php?ticketid=".$out_tickets_geschlossen['id']."'> Ticket#: ".$out_tickets_geschlossen['id']."</a>
						";
			
			
			}
$output .= 
"

			  </td>          
			  <td width='400'>
				<div title='".$out_tickets_geschlossen_user['vorname']." ".$out_tickets_geschlossen_user['nachname']."'>".$out_tickets_geschlossen_user['vorname']." ".$out_tickets_geschlossen_user['nachname']."</div>

				<div title='".$out_tickets_geschlossen['titel']."'>".$out_tickets_geschlossen['titel']."</div>
			  </td>
			  <td width='115'>
			  
			  ";

$oldDate	= strtotime($out_tickets_geschlossen['erstellt']);
$actDate = strtotime($datum);               // aktuelles Datum
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
			  
			  </td >
			  <td align='right' width='65' >
				<div title='".$out_tickets_geschlossen_queue['name']."'><small>".$out_tickets_geschlossen_queue['name']."</small></div>
			  </td>
			</tr>
";
$iCount2 ++;
}

$output .= 
"			
		</tbody>
	</table>
    </td>
  </tr>
</tbody></table>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='contentfooter' colspan='2'>
                                                &nbsp;

                                            </td>
                                        </tr>
                                    </tbody></table>

                                </td>
                            </tr>
                        </tbody></table>

";
}
}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>