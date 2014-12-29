<?php
########################################################################
# Verleih Modul for dotlan                                 			   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/leisystem/export.php - Version 1.0                             #
########################################################################

$MODUL_NAME = "leihsystem";
include_once("../../../global.php");
include("../functions.php");
require('../fpdf16/fpdf.php');
global $global;

$URL = "http://".$_SERVER["SERVER_NAME"].$global['project_path']."leihsystem";
//echo $URL;

$event_id = $EVENT->next;
$out_event  = $DB->fetch_array($DB->query("SELECT * FROM events WHERE id = '".$event_id."'  LIMIT 1"));
$event_name = $out_event['name'];

$id_user 	= $_POST['userid']; // id des Gesuchten users aus der DB
$user_id = $CURRENT_USER->id;
$v_id 	= $user_id;
$leihzusatz = substr(time(),-3);
$leihID = $id_user.$leihzusatz;
$leihID_long = sprintf("%07d",$leihID);

// <daten des Gesuchten Users>
	//$u_id 		= $_POST['team'];
	$nick  		= $_POST['nick'];
	$vorname  	= $_POST['vorname'];
	$nachname  	= $_POST['nachname'];
	$strasse  	= $_POST['strasse'];
	$plz  		= $_POST['plz'];
	$ort  		= $_POST['ort'];
	$geb  		= $_POST['geb'];
	$leih_ids 	= $_POST['leih_ids'];
	$group_ids 	= $_POST['group_ids'];
	$anz_leih_ids = count($leih_ids);


	$update=$DB->query(	"UPDATE user SET `vorname` = '".$vorname."', `nachname` = '".$nachname."', `strasse` = '".$strasse."', `plz` = '".$plz."', `wohnort` = '".$ort."', `geb` = '".$geb."' WHERE `id` = ".$id_user.";");

if($_POST["leih_ids"]){
	 foreach($leih_ids as $lid ){

		$key_leih .= " ('".$leihID."', '".$id_user."', '".$CURRENT_USER->id."', '".$lid."', '0', '".$event_id."', '".$datum."' ),";

		$DB->query(	"UPDATE `project_equipment` SET `ausleihe` = 1  WHERE `id` = ".$lid.";" );

		$sql_group_data = $DB-> query("SELECT * FROM project_equipment_equip_group WHERE id_equipment = '".$lid."'");
		while($out_group_data = $DB->fetch_array($sql_group_data))
		{// begin while
			$DB->query(	"UPDATE `project_equipment_groups` SET `ausleihe` = 1  WHERE `id` = ".$out_group_data['id_group'].";" );
		}
		}
	$key_leih = substr($key_leih,0,-1);

		$DB->query("INSERT INTO project_leih_leihe (`id`, `id_leih_user`, `id_leih_user_verleiher`, `id_leih_artikel`, `id_leih_gruppe`, `event_id`, `leih_datum`) VALUES $key_leih;");
}
if($_POST["group_ids"]){
	 foreach($group_ids as $gid ){

		$key_grp .= " ('".$leihID."', '".$id_user."', '".$CURRENT_USER->id."', '0', '".$gid."', '".$event_id."', '".$datum."' ),";


		$DB->query(	"UPDATE `project_equipment_groups` SET `ausleihe` = 1  WHERE `id` = ".$gid.";" );

		$sql_artikel_data = $DB-> query("SELECT * FROM project_equipment_equip_group WHERE id_group = '".$gid."'");
		while($out_artikel_data = $DB->fetch_array($sql_artikel_data))
		{// begin while
			$DB->query(	"UPDATE `project_equipment` SET `ausleihe` = 1  WHERE `id` = ".$out_artikel_data['id_equipment'].";" );
		}
		}

		//$out_artikel_data = $DB->fetch_array( $DB-> query("SELECT * FROM project_equipment_equip_group WHERE id_group = '".$gid."'") );

	$key_grp = substr($key_grp,0,-1);

		$DB->query("INSERT INTO project_leih_leihe (`id`, `id_leih_user`, `id_leih_user_verleiher`, `id_leih_artikel`, `id_leih_gruppe`, `event_id`, `leih_datum`) VALUES $key_grp;");
}
	/*	 foreach($leih_ids as &$a ) {
		//$out_artikel_data = $DB->fetch_array( $DB-> query("SELECT * FROM project_equipment WHERE id = '".$leih_ids[$b]."'") );
		$DB->query("	INSERT INTO
									`project_leih_leihe`(
															`id`,
															`id_leih_user`,
															`id_leih_user_verleiher`,
															`id_leih_artikel`,
															`event_id`,
															`leih_datum`
														)
														VALUES	(
																	'".$leihID."',
																	'".$id_user."',
																	'".$user_id."',
																	'".$a."',
																	'".$event_id."',
																	'".$datum."'
																);
										");

				$update=$DB->query(	"UPDATE `project_equipment` SET `ausleihe` = 1  WHERE `id` = ".$a.";" );

		}
	*/

$sql_verleiher = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$CURRENT_USER->id."'  LIMIT 1"));

$sql_entleiher  = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$id_user."'  LIMIT 1"));


$sql_sitz  = $DB->fetch_array($DB->query("SELECT * FROM event_teilnehmer WHERE event_id = '".$event_id."' AND user_id = '".$id_user."' LIMIT 1"));
$text ="Haftung
Die Benutzer/-innen sind verpflichtet, die entliehenen Artikel mit Sorgfalt zu behandeln und sie vor Veränderung, Verschmutzung und Beschädigung zu bewahren. Wer Artikel ausleiht, hat sich deshalb beim Support zu überzeugen,dass sie keine Schäden oder Mängel aufweisen. Melden sie einen Schaden nicht an, erkennen sie an, dass sie die Artikel in ordnungsgemäßem Zustand erhalten haben. Die Benutzer/-innen haften für die auf seinen/ihren Namen entliehenen Artikel.

Die Weitergabe von geliehenen Artikeln an Dritte geschieht auf eigene Gefahr. Bei Verlust oder Beschädigung kann Ersatz bis zur Höhe des jeweiligen Ladenpreises verlangt werden.
Das Team ".$global['sitename']." entscheidet hier nach eigenem Ermessen.

Mit Ihrer Unterschrift erkennen Sie diese Bedingungen an.";

$text_wichtig = (utf8_decode($text));

$text_1 = "Unterschrift Support Rückgabe";
$unterschriftruek = (utf8_decode($text_1));
$text_2 = "Unterschrift Entleiher Rückgabe";
$unterschriftruek1 = (utf8_decode($text_2));


  $pdf=new FPDF();
  $pdf->AddPage();
  # Ueberschrift
  $pdf->SetFont('Arial','B',26);
  $pdf->Cell(0,4,$global['sitename']." Leihbeleg",0,0,C);
  $pdf->Ln();
  $pdf->SetFont('Arial','',14);
  $pdf->Cell(0,10,$event_name,0,0,C);
  $pdf->Ln();
  # Leerraum
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  $pdf->Cell(0,5,'');
  $pdf->Ln();
 # Verleiher Block
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Verleiher");
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"UserID: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_verleiher['id']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Nick: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_verleiher['nick']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Vorname:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(20,4,$sql_verleiher['vorname']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Nachname:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_verleiher['nachname']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Strasse:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_verleiher['strasse']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"PLZ:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(12,4,$sql_verleiher['plz']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Ort:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(22,4,$sql_verleiher['wohnort']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Geb.Dat: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_verleiher['geb']);
  $pdf->Ln();
  # Leerraum
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  # Barcode

  $pdf->Image($URL."/barcode/image.php?code=".$leihID_long."&tmp=.png",140,30,50);
  # Entleiher Block
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Entleiher");
  $pdf->Ln();
   $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Sitzplatz: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_sitz['sitz_nr']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"UserID: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_entleiher['id']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Nick: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_entleiher['nick']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Vorname:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(20,4,$sql_entleiher['vorname']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Nachname:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_entleiher['nachname']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Strasse:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_entleiher['strasse']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"PLZ:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(12,4,$sql_entleiher['plz']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Ort:");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(22,4,$sql_entleiher['wohnort']);
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(22,4,"Geb.Dat: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,4,$sql_entleiher['geb']);
  $pdf->Ln();
  # Leerraum
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  #Gelihene Artikel
  $pdf->SetFont('Arial','B',14);
  $pdf->Cell(30,8,"Geliehene Artikel:");
  $pdf->Ln();
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(2,2,"______________________________________________________________________________________________");
  $pdf->Ln();
    # Leerraum
  $pdf->Cell(0,4,'');
  $pdf->Ln();
  #Auflistung Artikel

	$sql_article  = $DB->query("SELECT * FROM  project_leih_leihe AS l INNER JOIN project_equipment AS e ON l.id_leih_artikel = e.id WHERE l.id_leih_user = '".$id_user."' AND l.id = '".$leihID."' "); //AND l.rueckgabe_datum = '0000-00-00 00:00:00'
if(mysql_num_rows($sql_article) != 0){
	while($out_list_article =   $DB->fetch_array($sql_article))
	{
	  $pdf->SetFont('Arial','',10);
	  $pdf->Cell(30,4,$out_list_article['bezeichnung']);
	  $pdf->Ln();
	}
}

	$sql_leih_groups  = $DB->query("SELECT *,l.id_leih_gruppe as  lg FROM  project_leih_leihe AS l INNER JOIN project_equipment_groups AS e ON l.id_leih_gruppe = e.id  WHERE l.id = '".$leihID."' AND l.rueckgabe_datum = '0000-00-00 00:00:00'");
	//$sql_leih_groups  = $DB->query("SELECT *, eg.bezeichnung AS eg_group_bezeichnung, eg.id eg_group_id FROM  project_equipment AS e INNER JOIN project_equipment_equip_group AS g ON g.id_equipment = e.id, project_equipment_groups AS eg, project_leih_leihe AS l WHERE  l.id = '".$leihID."'  GROUP BY eg.id"); // WHERE l.id_leih_user = '".$id_user."' AND  l.rueckgabe_datum = '0000-00-00 00:00:00'
if(mysql_num_rows($sql_leih_groups) != 0){
	while($out_leih_groups =   $DB->fetch_array($sql_leih_groups))
	{
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(30,4,$out_leih_groups['bezeichnung']);
		$pdf->Ln();

	$sql_leih_groups_artikel  = $DB->query("SELECT * FROM project_equipment_equip_group WHERE id_group = '".$out_leih_groups['lg']."'");
	while($out_leih_groups_artikel =   $DB->fetch_array($sql_leih_groups_artikel))
	{
		$out_leih_groups_artikel_bezeichnung  = $DB->fetch_array( $DB->query("SELECT * FROM project_equipment WHERE id = '".$out_leih_groups_artikel['id_equipment']."'") );
	  $pdf->SetFont('Arial','',10);
	  $pdf->Cell(30,4,"- ".$out_leih_groups_artikel_bezeichnung['bezeichnung']);
	  $pdf->Ln();
	}
}
}
  # Leerraum
  $pdf->Cell(0,1,'');
  $pdf->Ln();
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(2,2,"______________________________________________________________________________________________");
  $pdf->Ln();
   # Leerraum
  $pdf->Cell(0,4,'');
  $pdf->Ln();
  $pdf->Cell(0,4,'');
  $pdf->Ln();
  # Haftungstext
  $pdf->MultiCell(0,4,$text_wichtig);
  $pdf->Ln();
 # Leerraum
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  # Unterschrift
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(15,4,"Datum: ");
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(50,4,$datum.", ".$zeit);
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(60,4,	"____________________________");
  $pdf->Cell(0,4,	"____________________________");
  $pdf->Ln();
  $pdf->Cell(65,4,"");
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(60,4,"Unterschrift Support");
  $pdf->Cell(0,4,"Unterschrift Entleiher");
  $pdf->Ln();
   # Leerraum
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  $pdf->Cell(0,5,'');
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(15,4,"Datum: ");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(50,4,"_________ , _________");
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(60,4,	"____________________________");
  $pdf->Cell(0,4,	"____________________________");
  $pdf->Ln();
  $pdf->Cell(65,4,"");
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(60,4,$unterschriftruek);
  $pdf->Cell(0,4,$unterschriftruek1);
  $pdf->Ln();
  $pdf->Output();

?>
