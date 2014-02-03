<?php
########################################################################
# Verleih Modul for dotlan                                 			   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/leisystem/export.php - Version 1.0                             #
########################################################################

include_once("../../../global.php");
include("../functions.php");
require('../fpdf16/fpdf.php');
global $global;

$event_id = $EVENT->next;
$out_event  = $DB->fetch_array($DB->query("SELECT * FROM events WHERE id = '".$event_id."'  LIMIT 1"));
$event_name = $out_event['name'];


$user_id = $CURRENT_USER->id;
$v_id 	= $_GET['v_id'];
$leihID = $_GET['leihID'];

$id_user 	= $_POST['userid']; // id des Gesuchten users aus der DB
$e_id 	= $id_user;
// <daten des Gesuchten Users>
	$u_id 		= $_POST['team'];
	$nick  		= $_POST['nick'];
	$vorname  	= $_POST['vorname'];
	$nachname  	= $_POST['nachname'];
	$strasse  	= $_POST['strasse'];
	$plz  		= $_POST['plz'];
	$ort  		= $_POST['ort'];
	$geb  		= $_POST['geb'];


$sql = "SELECT id FROM project_leih_user WHERE id = '".$leihID."'";

$result  = $DB->query($sql);
if(mysql_num_rows($result)==0)
 {
    	//echo "Nicht gefunden";

		$insert_NEW_Leihe = $DB->query("INSERT INTO `project_leih_user` (id, nick, vorname, nachname, strasse, plz, wohnort, geb)VALUES ('".$id_user."', '".$nick."', '".$vorname."', '".$nachname."', '".$strasse."', '".$plz."', '".$ort."', '".$geb."')");


		//echo "<br> User 11 ID: ".$id_user;
		$output .= "Die Leihe wurde eingetragen ";

		$anz_leih_ids = count($leih_ids);

		for($y=0;$y<$anz_leih_ids;$y++)
		{

		$insert_NEW_Leihe = $DB-> query("
										INSERT INTO
											`project_leih_leihe`
												(
													`id`,
													`id_leih_user`,
													`id_leih_user_verleiher`,
													`id_leih_artikel`,
													`event_id`,
													`leih_datum`
												)
													VALUES
														(
															NULL,
															'".$id_user."',
															'".$user_id."',
															'".$leih_ids[$y]."',
															'".$event_id."',
															'".$datum."'
														)
										");

		$update=$DB->query(	"UPDATE project_leih_article SET `ausleihe` = '1', `u_id` = '".$id_user."' WHERE `id` = ".$leih_ids[$y].";");


		}
 }
else
 {
       // echo "gefunden";

		$update=$DB->query(	"UPDATE project_leih_user SET `id` = '".$id_user."', `nick` = '".$nick."', `vorname` = '".$vorname."', `nachname` = '".$nachname."', `strasse` = '".$strasse."', `plz` = '".$plz."', `wohnort` = '".$ort."', `geb` = '".$geb."' WHERE `id` = ".$id_user.";");


			$output .= "Die Leihe wurde eingetragen ";

		$anz_leih_ids = count($leih_ids);

		for($b=0;$b<$anz_leih_ids;$b++)
		{
		$out_artikel_data = $DB->fetch_array( $DB-> query("SELECT * FROM project_leih_article WHERE id = '".$leih_ids[$b]."'") );
		$insert_NEW_Leihe = $DB-> query("
										INSERT INTO
											`project_leih_leihe`
												(
													`id`,
													`id_leih_user`,
													`id_leih_user_verleiher`,
													`id_leih_artikel`,
													`event_id`,
													`leih_datum`
												)
												VALUES
													(
														NULL,
														'".$id_user."',
														'".$user_id."',
														'".$leih_ids[$b]."',
														'".$event_id."',
														'".$datum."'
													)
										");
		$update=$DB->query(	"UPDATE project_leih_article SET `ausleihe` = '1', `u_id` = '".$id_user."' WHERE `id` = ".$leih_ids[$b].";");


		}

}


$sql_verleiher = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$v_id."'  LIMIT 1"));

$sql_entleiher  = $DB->fetch_array($DB->query("SELECT * FROM project_leih_user WHERE id = '".$e_id."'  LIMIT 1"));


$sql_sitz  = $DB->fetch_array($DB->query("SELECT * FROM event_teilnehmer WHERE event_id = '".$event_id."' AND user_id = '".$e_id."' LIMIT 1"));
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
  $sql_article  = $DB->query("SELECT * FROM project_leih_article WHERE u_id = '".$e_id."'");

  while($out_list_article =   $DB->fetch_array($sql_article))
{
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(30,4,$out_list_article['bezeichnung']);
  $pdf->Ln();
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