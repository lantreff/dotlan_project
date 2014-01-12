<?
########################################################################
# Iplisten Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/ipliste/export_dns.php - Version 1.0                           #
########################################################################


include_once("../../../global.php");
include("../functions.php");

$event_id = $EVENT->next;
$datei_lan = "lan.txt";
$datei_rev = "rev.txt";
$TTL = "\$TTL";
if (file_exists($datei_lan))

{

unlink($datei_lan);
echo "Die Datei ".$datei_lan." wurde gel&ouml;scht <br /> <br />";
unlink($datei_rev);
echo "Die Datei ".$datei_rev." wurde gel&ouml;scht <br /> <br />";
}

//Normal muss die Variable da nicht angegeben werden, da sie
//am besten durch ein Formular übergeben wird
//du willst ja, dass man den dateinamen aussuchen kann
//ich hab sie hier nur hingetan, damit das script auch komplett ist



$error = false;

if (empty($datei_lan) && empty($datei_rev) ) {
  echo "";
  $error = true;
}



if ($error) {
  echo "Ein fehler ist aufgetreten!";
}else{

if (file_exists($datei_lan) && file_exists($datei_rev))

{

echo "Eine datei mit dem selben Namen ist bereits vorhanden";

}else{

$fp = fopen($datei_lan,"w");
chmod ($datei_lan, 0777);
fclose($fp);
echo "Die Datei ".$datei_lan." wurde erstellt <br /> <br />";

$fp = fopen($datei_rev,"w");
chmod ($datei_rev, 0777);
fclose($fp);

echo "Die Datei ".$datei_rev." wurde erstellt";

}

}


$zahl1 = date("YmdHis");
$zahl2 = date("YmdHis");
$head_lan = ";
;
; BIND data file for yourdomain.com
;
@       IN      SOA    dns. root.localhost. (
                ".$zahl1."     			; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
;
@	IN	NS	dns.
	IN	A	10.10.1.253
";

$footer_lan = ";Manuell eingetragen
snx	IN	CNAME	dns
ts3	IN	CNAME	dns
irc	IN	CNAME	dns
www	IN	CNAME	dns";



$head_rev =";
;
; BIND reverse data file for 10.110.0.0
;
@       IN      SOA     dns. root.localhost. (
               ".$zahl2."         		; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
;
@	IN	NS	dns.lan.
";




							// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei_lan , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , $head_lan);
							fClose($handler); // Datei schließen

$sql_list_category = $DB->query("SELECT category FROM project_ipliste GROUP BY category  ORDER BY inet_aton(ip)");


  while($out_list_category = $DB->fetch_array($sql_list_category))
					{// begin while
					// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei_lan , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , ";".$out_list_category['category']."\n");
							fClose($handler); // Datei schließen

			$sql_list_ip = $DB->query("SELECT * FROM project_ipliste WHERE category = '".$out_list_category['category']."' AND ip LIKE  '%10.10%' ORDER BY inet_aton(ip)");
#Table
			while($out_list_ip = $DB->fetch_array($sql_list_ip))
						{// begin while

							$text = $out_list_ip['dns']."\tIN\tA\t".$out_list_ip['ip']."\n";


							// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei_lan , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , $text);
							fClose($handler); // Datei schließen

						}

					}
				// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei_lan , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , $footer_lan);
							fClose($handler); // Datei schließen

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// REV DATEI //


							// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei_rev , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , $head_rev);
							fClose($handler); // Datei schließen

$sql_list_category_rev = $DB->query("SELECT category FROM project_ipliste GROUP BY category  ORDER BY inet_aton(ip)");


  while($out_list_category_rev = $DB->fetch_array($sql_list_category_rev))
					{// begin while
					// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei_rev , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , ";".$out_list_category_rev['category']."\n");
							fClose($handler); // Datei schließen

			$sql_list_ip_rev = $DB->query("SELECT * FROM project_ipliste WHERE category = '".$out_list_category_rev['category']."' AND ip LIKE  '%10.10%' ORDER BY inet_aton(ip)");
#Table
			while($out_list_ip_rev = $DB->fetch_array($sql_list_ip_rev))
						{// begin while
	  						$ip = substr($out_list_ip_rev['ip'], 8);
							$ip1 = substr($out_list_ip_rev['ip'], 6,1);
							$text = $ip.".".$ip1."\tIN\tPTR\t".$out_list_ip_rev['dns'].".".$out_list_ip_rev['lan'].".\n";


							// Datei öffnen,
							// wenn nicht vorhanden dann wird die Datei erstellt.
							$handler = fopen($datei_rev , "a+");
							// Dateiinhalt in die Datei schreiben
							fWrite($handler , $text);
							fClose($handler); // Datei schließen

						}

					}




echo "<meta http-equiv='refresh' content='0; URL=/admin/projekt/ipliste/'>";
?>