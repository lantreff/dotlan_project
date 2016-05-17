<?php
########################################################################
# Iplisten Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/ipliste/export_dns.php - Version 1.0                           #
########################################################################

## Auskommentiert, da in functions.php vorhanden.
# function umlaute_ersetzen($text){
# $such_array  = array ('ä', 'ö', 'ü', 'ß');
# $ersetzen_array = array ('ae', 'oe', 'ue', 'ss');
# $neuer_text  = str_replace($such_array, $ersetzen_array, $text);
# return $neuer_text;
# }


include_once("../../../global.php");
include("../functions.php");

#$event_id = $EVENT->next;
$datei = "hosts";
$TTL = "\$TTL";
if (file_exists($datei))
{
	unlink($datei);
	echo "Die Datei ".$datei." wurde gel&ouml;scht <br /> <br />";
}

//Normal muss die Variable da nicht angegeben werden, da sie
//am besten durch ein Formular übergeben wird
//du willst ja, dass man den dateinamen aussuchen kann
//ich hab sie hier nur hingetan, damit das script auch komplett ist



$error = false;

if (empty($datei)) {
  echo "";
  $error = true;
}



if ($error) {
  echo "Ein fehler ist aufgetreten!";
}else{

if (file_exists($datei))

{

echo "Eine datei mit dem selben Namen ist bereits vorhanden";

}else{

$fp = fopen($datei,"w");
chmod ($datei, 0777);
fclose($fp);
echo "Die Datei ".$datei." wurde erstellt <br /> <br />";
}

}


$head = "## Lokales Netzwerk
#  = IP =     =  Domainname =    = Rechnername =
";



							// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei, "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , $head);
							fClose($handler); // Datei schließen

$sql_list_category = $DB->query("SELECT category FROM project_ipliste WHERE ip LIKE  '%10.10%' GROUP BY category  ORDER BY inet_aton(ip)");


  while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
					// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , "#".$out_list_category['category']."\n");
							fClose($handler); // Datei schließen

			$sql_list_ip = $DB->query("SELECT * FROM project_ipliste WHERE category = '".$out_list_category['category']."' AND ip LIKE  '%10.10%' AND dns != '' ORDER BY inet_aton(ip)");
#Table
			while($out_list_ip = $DB->fetch_array($sql_list_ip))
						{// begin while

							$teile = explode(",", str_replace(' ','',$out_list_ip['dns']));

							foreach($teile as $list_dns)
								{
									$text = $out_list_ip['ip']."\t\t".$list_dns.".".$out_list_ip['lan']."\t\t#".umlaute_ersetzen($out_list_ip['bezeichnung'])."\n";

									// Datei öffnen,
									// wenn nicht vorhanden dann wird die Datei erstellt.
									$handler = fopen($datei , "a+");
									// Dateiinhalt in die Datei schreiben
									fWrite($handler , $text);
								}
							fClose($handler); // Datei schließen

						}

					}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<meta http-equiv='refresh' content='0; URL=".$dir."'>";
?>
