<?
########################################################################
# Equipment Verwaltungs Modul for dotlan                               #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/equipment/index.php - Version 1.0                              #
########################################################################


include_once("../../../global.php");
include("../functions.php");
$event_id = $EVENT->next;
$data = $DB->query("SELECT * FROM `catering_order_part` WHERE DATE_FORMAT( `time_added`, '%j' ) BETWEEN DATE_FORMAT( NOW() , '%j' )  AND DATE_FORMAT( DATE_ADD( NOW(), INTERVAL 1 HOUR), '%j') AND `status` = '4' ORDER BY time_added DESC");

 /*###########################################################################################
Admin PAGE
*/

echo  "

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='de' lang='de'>
<head>
  <title>Pizza</title>
  <link rel='stylesheet' type='text/css' href='/../../styles/dotlan-net3.css' >
  <link rel='stylesheet' href='/../../styles/css/slide.css' type='text/css' media='screen' />
  <meta name='author' content='egge' />
  <meta name='keywords'                 content='dotlan, .lan, lanparty, lan-party, intranet, turnier, turniersystem, eventplanung, sitzplan, event, netzwerk, php' />
  <meta http-equiv='content-type'         content='text/html; charset=ISO-8859-1' />
  <meta http-equiv='Content-Style-Type'        content='text/css' />
</head>
<body>
<div align='center'> <h1> !! Die Bestellung ist da !! </h1> </div>
<br>
<table cellspacing='1' cellpadding='2' border='0' width='950'>
  <tbody><tr valign='middle'>

    <td class='msghead' nowrap='' width='30' align='center' title='Bestell Nr (Catering)'>BNr</td>
    <td class='msghead' nowrap='' width='30' align='center' title='Produkt Bestell Nr'>Nr</td>
    <td class='msghead' width='250'>Produktname</td>
    <td class='msghead' width='10%' nowrap=''>Benutzer</td>
    <td class='msghead' width='40' nowrap=''>Sitz</td>
    <td class='msghead' width='25' nowrap=''>Preis</td>
    <td class='msghead' width='100'nowrap=''>Status</td>
  </tr>";

 $iCount = 0;

 while($out_data = $DB->fetch_array($data))
					{// begin while
  /* Jede zweite Zeile anders darstellen */
  $out_ticket_zoom_user_sitz =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													`event_teilnehmer`
												WHERE
													( user_id = ".$out_data['user_id']." AND event_id = ".$event_id.")
												LIMIT 1
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

					echo  "
					  <tr valign='middle' class=\"$currentRowClass\"' style='cursor: pointer;'>

						<td nowrap='' align='center'><b>&lt;".$out_data['order_id']."&gt;</b></td>
						<td  nowrap='' align='center'>".$out_data['order_nr']." </td>
						<td ><b>".$out_data['name']."</b> </td>";

						$out_user_data  = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$out_data['user_id']."'  LIMIT 1"));

						echo  "
						<td  nowrap=''><a target='_blank' href='/user/?id=".$out_user_data['id']."'>".$out_user_data['nick']."</a></td>";


						echo  "
						<td  nowrap=''>".$out_ticket_zoom_user_sitz['sitz_nr']."</td>
						<td  nowrap='' align='right'><b>".$out_data['price']." &euro;</b></td>
						<td  nowrap=''>Abholbereit</td>
					  </tr>";
  			$iCount++;
					}
echo  "

			</tbody></table>

</body>
</html>

";

?>
