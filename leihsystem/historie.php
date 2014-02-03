<?php
#########################################################################
# Verleih Modul for dotlan                                 			   	#
#                                                                      	#
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              	#
#																		#
#########################################################################

$MODUL_NAME = "leihsystem";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Leihsystem - Historie");

if (isset($_POST['event']))
{
$event_id = $_POST['event'];
}
else
{
$event_id = $EVENT->next;
}


$seite = $_GET["seite"];  //Abfrage auf welcher Seite man ist 

//Wenn man keine Seite angegeben hat, ist man automatisch auf Seite 1 
if(!isset($seite)) 
   { 
   $seite = 1; 
   } 
   
$eintraege_pro_seite = 15; 
$start = $seite * $eintraege_pro_seite - $eintraege_pro_seite; 

$sql_historie = $DB->query("SELECT TIMESTAMPDIFF(SECOND,leih_datum,now()) AS diff_zeit, TIMEDIFF(now(),leih_datum) AS diff_leih_zeit, TIMEDIFF(rueckgabe_datum,leih_datum) AS leih_rueck_zeit, project_leih_leihe.* FROM project_leih_leihe WHERE event_id = ".$event_id." ORDER BY leih_datum DESC LIMIT ".$start.", ".$eintraege_pro_seite." ");
$sql_event_ids = $DB->query("SELECT * FROM events ORDER BY begin DESC");
$out_historie_event		= $DB->fetch_array($DB->query("SELECT * FROM events WHERE id = ".$event_id.""));




if($DARF["view"])
{ // darf sehen
$output .= "	<a name='top' >
				<a href='/admin/projekt/'>Projekt</a>
				&raquo;
				<a href='/admin/projekt/leihsystem'>Leihsystem</a>
				&raquo; ".$out_historie_event['name']."  
				<hr class='newsline' width='100%' noshade=''>
				";
				
	$output .= "
		<table width='100%' cellspacing='1' cellpadding='2' border='0' class='msg2'>
			<tbody>
				<tr class='shortbarrow'>";
				
				if($DARF["add"])
				{ //$ADMIN
				$output .= "
					<td width='20%' class='shortbarbit'><a href='./?hide=1&action=add' class='shortbarlink'>Artikel Anlegen</a></td>";
				}
				
				$output .= "
					<td width='20%' class='shortbarbit'><a href='./?hide=1&action=list_all' class='shortbarlink'>Alle Artikel</a></td>
					<td width='0' class='shortbarbitselect'>&nbsp;</td>
					<td width='20%' class='shortbarbit'><a href='./?hide=1&action=NEW_Leihe' class='shortbarlink'>Artikel verleihen</a></td>
					<td width='20%' class='shortbarbit'><a href='./?hide=1&action=rueckgabe' class='shortbarlink'>R&uuml;ckgabe</a></td>
					<td width='20%' class='shortbarbitselect'><a href='historie.php' class='shortbarlinkselect'>Historie</a></td>
				</tr>
			</tbody>
		</table>
		<br />
	";



$output .= "<form name='change_event' action='' method='POST'>				
			<select name='event' onChange='document.change_event.submit()''>
				<option value='1'>w&auml;hle das Event !</option>";
				while($out_event_ids = $DB->fetch_array($sql_event_ids))
				{// begin While Historie
					if	($out_event_ids['id'] == $event_id)
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
			</form>";
			
			
				
$output .= "
					<table width='100%' cellspacing='1' cellpadding='2' border='0' class='msg2'>
							<tbody>
								<tr>
									<td class='msghead'>
										Name (Leiher)
									</td>
									<td class='msghead'>
										Name (Verleiher)
									</td>
									<td  class='msghead'>
										geliehener Artikel
									</td>
									<td class='msghead'>
										Leihdatum
									</td>
									<td class='msghead'>
										R&uuml;ckgabedatum
									</td>
									<td class='msghead'>
										Leihdauer
									</td>
								</tr>";
						$iCount = 0;
						while($out_historie = $DB->fetch_array($sql_historie))
						{// begin While Historie
						
						if($iCount % 2 == 0)
							{
								$currentRowClass = "msgrow2";
							}
							else
							{
									$currentRowClass = "msgrow1";
								
							}
							

							if ($out_historie['rueckgabe_datum'] == '0000-00-00 00:00:00')
								{
									$currentRowClass = "msgrowORANGE";
								}
							if ($out_historie['rueckgabe_datum'] == '0000-00-00 00:00:00' && $out_historie['diff_zeit']  > 3600 )
								{
									$currentRowClass = "msgrowRED";
								}
						
						$out_historie_user_leiher 		= $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_historie['id_leih_user'].""));
						$out_historie_user_verleiher	= $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_historie['id_leih_user_verleiher'].""));
						$out_historie_artikel			= $DB->fetch_array($DB->query("SELECT * FROM project_leih_article WHERE id = ".$out_historie['id_leih_artikel'].""));
						

$output .= "					
								<tr class='".$currentRowClass."'>
									<td class='shortbarbit_left'>
									<a href ='/user/?id=".$out_historie['id_leih_user']."'>	".$out_historie_user_leiher['nick']."</a>
									</td>
									<td class='shortbarbit_left'>
									<a href ='/user/?id=".$out_historie['id_leih_user_verleiher']."'>	".$out_historie_user_verleiher['nick']."</a>
									</td>
									<td class='shortbarbit_left' 
									title='Kategorie - ".$out_historie_artikel['kategorie']." ".$out_historie_artikel['bezeichnug']." --> Besitzer: ".$out_historie_artikel['v_id']."'>
										".$out_historie_artikel['bezeichnung']."
									</td>
									<td class='shortbarbit_left'>
										".date($out_historie['leih_datum'], strtotime($out_historie['leih_datum']))."
									</td>
									<td class='shortbarbit_left'>
										".date($out_historie['rueckgabe_datum'], strtotime($out_historie['rueckgabe_datum']))."
									</td>
									<td class='shortbarbit_left'>
";
								if ($out_historie['rueckgabe_datum'] == '0000-00-00 00:00:00')
								{
									$output .= " ".$out_historie['diff_leih_zeit']."";
								}
								else
								{
									$output .= " ".$out_historie['leih_rueck_zeit']."";
								}
$output .= "						</td>
								</tr>";
								
								$iCount++;
						} // While Historie ENDE

								
$output .= "
					</tbody>
						</table>";

$result = mysql_query("SELECT id FROM project_leih_leihe WHERE event_id = ".$event_id." "); 
$menge = mysql_num_rows($result); 
$wieviel_seiten = $menge / $eintraege_pro_seite; 
//Ausgabe der Seitenlinks: 
$output .= "<div align=\"center\">"; 
$output .= "<b>Seite:</b> "; 


//Ausgabe der Links zu den Seiten 
for($a=0; $a < $wieviel_seiten; $a++) 
   { 
   $b = $a + 1; 

   //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
   if($seite == $b) 
      { 
      $output .= "  <b>$b</b> "; 
      } 

   //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
   else 
      { 
      $output .= "  <a href=\"?seite=$b\">$b</a> "; 
      } 


   } 
$output .= "</div>"; 


} // ende darf sehen
else // darf nicht view
{

$PAGE->error_die($HTML->gettemplate("error_nopermission"));

} // ende darf nicht View

$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
