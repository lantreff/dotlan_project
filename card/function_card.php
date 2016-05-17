<?php

## Funktionsdatei für Lanparty Cards

## Zuerst einige Daten für die Konfiguration
## Dotlan Globale Funktionen importieren
include_once("../../../global.php");
## Funktionen Project importieren
include("../functions.php");

## Pfad FPDF
define("FPDFPATH", "../fpdf16/fpdf.php");

## Pfad Bilder User
define("PICTUREPATH", "./userpics/");
define("PICTUREPATH_FULL", "/var/www/maxlan/admin/projekt/card/userpics/");
//
## Pfad Barcode
define("BARCODEPATH", "../barcode/");

## Pfad Dokumente mit Dateien
define("DOCPATH", "./docs/");


## Daten für SQL als Merker - wenn fertig, bitte entfernen
# table project_card
# card_ID
# user_ID
# pic_hash
# creation_date
# card_status: 0=bestellt,1=in Produktion, 2=abholbereit (manuell festlegen), 3=abgeholt

# table project_card_queue
# queue_ID
# card_ID

## Seiten
# index.php // Übersicht über Maxlan Karten, Suchfunktion nach User, Klick Buttons für: einzeln Drucken, In Druckqueue einfügen, Anzeige Druckqueue für A4 (anz bis Seite komplett), ggf. Card löschen?, Anzeige Guthaben, Card Freischalten
# new_card.php // Benutzer suchen & Auswählen (Dotlan FKT), Bild für Upload
# change_card.php // Neues Bild für Upload
# export.php // Erzeugen PDF für Druck, je nachdem ob Queue oder Einzelbild

# User Seiten
# /card/my_card.php - Übersicht über die Karte, Guthaben, Bild, Bild Prüfergebnis.
# /card/upload_pic.php - Bild hochladen/ändern & prüfen

## Funktion zum Hochladen und Verkleinern der Bilder
function picupload(){
}

## Funktion zum Anlegen einer Karte
function add_card(){
}

## Funktion zum Ändern einer Karte
function change_card(){
}

## Funktion zum Anzeigen der Kartenbestellungen
function show_cards($status,$allow_print){
	$card_query = mysql_query("SELECT project_card.card_ID, project_card.user_ID, project_card.pic_hash, project_card.card_status, project_card.last_order_date, project_card.last_creation_date, user.nick, user.vorname, user.nachname, user.geb FROM `project_card` LEFT JOIN user ON project_card.user_ID = user.id WHERE project_card.card_status = '{$status}' ORDER BY project_card.user_ID ASC") or die(mysql_error());
	switch($status){
		case 0:
			$cards .= '<h3>Neue Bestellungen</h3>';
			break;
		case 1:
			$cards .= '<br><br><h3>Karten in Produktion</h3>';
			break;
		case 2:
			$cards .= '<br><br><h3>Karten wartend auf Abholung</h3>';
			break;
		case 3:
			$cards .= '<br><br><h3>Karten bereits abgeholt</h3>';
			break;
		case 98:
			$cards .= '<br><br><h3>Karten angenommen</h3>';
			break;
		case 99:
			$cards .= '<br><br><h3>Karten abgelehnt</h3>';
			break;
	}

    $cards .=  '<table class="maincontent"><tr><td class="msghead" nowrap="nowrap" width="60"><b>UserID</b></td><td width="250" class="msghead" nowrap="nowrap"><b>User&nbsp;</b></td><td width="150" class="msghead"><b>Bestelldatum&nbsp;</b></td><td width="150" class="msghead"><b>Letzter Druck&nbsp;</b></td>';
	if($allow_print){
		// $cards .=  '<td width="50" class="msghead" nowrap="nowrap"></td>';
		// $cards .=  '<td width="50" class="msghead" nowrap="nowrap"></td>';
	}
	$cards .=  '</tr>';
	$i=0;
	while ($cdata = mysql_fetch_array($card_query)){
		if ($cdata['last_order_date']){$last_order_date = throwDateTime($cdata['last_order_date']);}
		if ($cdata['last_creation_date']){$last_creation_date = throwDateTime($cdata['last_creation_date']);}
		$cards .= '<tr class="msgrow'.(($i%2)?1:2).'">';
		$cards .= '<td>'.sprintf("%04d",$cdata['user_ID']).'</td>';
		$cards .= '<td>'.$cdata['vorname'].' <i>"'.$cdata['nick'].'<i>" '.$cdata['nachname'].'</td>';
		$cards .= '<td>'.$last_order_date.'</td>';
		$cards .= '<td>'.$last_creation_date.'</td>';
		if($allow_print){
			$cards .= '<td><a href="index.php?user='.$cdata['user_ID'].'&action=singleprint"';
			$cards .= " onClick=\"return confirm ('Willst du wirklich die Maxlan Card drucken? Die Karte wird dann auf &quot; In Produktion &quot; gesetzt und steht dann unter &quot; Karten drucken &quot; zum Download bereit?')\">";
			$cards .= 'Einzeldruck</a></td>';
			if ($cdata['card_status'] == 1){
				$cards .= '<td><a href="index.php?user='.$cdata['user_ID'].'&card='.$cdata['card_ID'].'&action=abholbereit"';
				$cards .= " onClick=\"return confirm ('Ist die Maxlan Card gedruckt, ausgeschnitten UND einlaminiert? Dann darfs Du jetzt auf auf OK klicken.')\">";
				$cards .= 'Abholbereit</a></td>';
			}
			if ($cdata['card_status'] == 2){
				$cards .= '<td><a href="index.php?user='.$cdata['user_ID'].'&card='.$cdata['card_ID'].'&action=abgeholt"';
				$cards .= " onClick=\"return confirm ('Wurde diese Karte wirklich ehrlich echt jetzt abgeholt?')\">";
				$cards .= 'Abgeholt</a></td>';
            }
			if ($cdata['card_status'] == 3){
				$cards .=  '<td></td>';
			}

		}
		if ($cdata['card_status'] == 0 || $cdata['card_status'] == 1 || $cdata['card_status'] == 98 || $cdata['card_status'] == 99){
			$cards .= '<td><a href="datacheck.php?user='.$cdata['user_ID'].'">Datencheck</a></td>';
		}
		$cards .= '</tr>';
		$i++;
	}
	$cards .= '<table>';
	return $cards;
}

## Funktion zum Anzeigen
function show_card_documents(){
	$doc_query = mysql_query("SELECT * FROM project_card_document ORDER BY date_generated");
	$doclist .= '<h3>Verfügbare Kartendokumente</h3>';
	$doclist .=  '<table class="maincontent"><tr><td class="msghead" nowrap="nowrap" width="60"><b>DocID</b></td><td width="450" class="msghead" nowrap="nowrap"><b>Titel&nbsp;</b></td><td width="150" class="msghead"><b>Erstellt am&nbsp;</b></td><td width="150" class="msghead"></td></tr>';
	while ($ddata = mysql_fetch_array($doc_query)){
		$doclist .= '<tr class="msgrow'.(($i%2)?1:2).'">';
		$doclist .= '<td>'.$ddata['doc_ID'].'</td>';
		$doclist .= '<td>'.$ddata['doc_title'].'</td>';
		$doclist .= '<td>'.throwDateTime($ddata['date_generated']).'</td>';
		$doclist .= '<td><a href="'.DOCPATH.$ddata['doc_hash'].'" target="_blank">Download</a></td>';
		$doclist .= '</tr>';
	}
	$doclist .= '</table>';
	return $doclist;
}

## Funktion zum Enfernen einer Karte aus der Druckqueue
function delete_card_document(){
}



## Funktion zum Generieren der Karten auf DIN A4 PDF aus der Druckqeue
function generate_cards_pdf_queue(){
  require("../fpdf16/fpdf.php");
  $ProjektURL = "http://".$_SERVER["SERVER_NAME"]."/admin/projekt/";
  $pdf=new FPDF();
  $count = 0;

  $card_background_user = "./mx_card_background.png";
  $card_background_orga = "./mx_card_background_orga.png";
  $card_watermark = "./maxlan_watermark.png";
  $card_query = mysql_query("SELECT project_card.card_ID, project_card.user_ID, project_card.pic_hash, user.nick, user.vorname, user.nachname, user.geb FROM `project_card` LEFT JOIN user ON project_card.user_ID = user.id WHERE project_card.card_status = 98 ORDER BY project_card.user_ID ASC") or die(mysql_error());
  $ycoord = 0;
  $xright = 0;

  while ($cdata = mysql_fetch_array($card_query)){
  	$admin_check = mysql_query("SELECT * FROM user_orga WHERE user_id = '{$cdata['user_ID']}'");
  	if (mysql_num_rows($admin_check) > 0){
  		$card_background = $card_background_orga;
	} else {
	  	$card_background = $card_background_user;
	}

  	$count = $count + 1;
  	$birthday = birthday2german($cdata['geb']);
  	$long_ID = sprintf("%04d",$cdata['user_ID']);
  	$user_pic = PICTUREPATH.$cdata['pic_hash'];

  // Neue Seite wenn mehr als 10 Karten pro Seite
  	if (fmod($count,10) == 1){ $pdf->AddPage(); $ycoord = 0; $xright = 0;}

  	$pdf->Image(substr($_SERVER["SCRIPT_URI"],0,0).$card_background,(10+$xright),(10+$ycoord),78);
  	$pdf->Image(substr($_SERVER["SCRIPT_URI"],0,0).$user_pic,(14+$xright),(22.5+$ycoord),22);
  	$pdf->SetFont('Arial','',11);
  	$pdf->SetXY((37.5+$xright),(30.5+$ycoord));
  	$pdf->Cell(48,0,$cdata['vorname'].' '.$cdata['nachname'],0,0,'L',0);
  	$pdf->SetFont('Arial','',9);
  	$pdf->SetXY((37.5+$xright),(38.5+$ycoord));
  	$pdf->Cell(48,0,$long_ID." - ".$cdata['nick'],0,0,'L',0);
  	$pdf->SetFont('Arial','',8);
  	$pdf->SetXY((37.5+$xright),(54+$ycoord));
  	$pdf->Cell(48,0,$birthday,0,0,'L',0);
  	$pdf->Image("http://lan.maxlan.de/admin/projekt/card/barcode/image.php?code=".$long_ID."&tmp=.png",(59+$xright),(46+$ycoord),27);
  	// Neue Zeile alle 2 Karten
  	if (fmod($count,2) == 0){ $ycoord = $ycoord + 55; }
  	// jede 2. Karte nach rechts verschieben
  	if ($xright == 0){ $xright = 90; } else { $xright = 0; }
	$creation_time = time();
	$message_time = date("Y-m-d H:i:s");
	$card_user_update = mysql_query("UPDATE project_card SET last_creation_date = '{$creation_time}', card_status = 1 WHERE card_ID = '{$cdata['card_ID']}'");
	// $message = mysql_query("INSERT INTO private_message SET folder = 'INBOX', userid = '{$cdata['user_ID']}', touserid = '{$cdata['user_ID']}', fromuserid = '0', subject = 'Deine Maxlan Card ist in Produktion', message = 'Deine Maxlan Card wird gerade hergestellt.', dateline = '{$message_time}', messageread = 0, confirmread = 0");
	// $PRVMSG->generate_message($cdata['user_ID'],"INBOX",$cdata['user_ID'],0,"Deine Maxlan Card ist in Produktion",'Deine Maxlan Card wird gerade hergestellt.');
}

  $doks = mysql_query("SELECT * FROM project_card_document");
  $dok_ID = mysql_num_rows($doks) + 1;

  //$pdf->Output('mx_card.pdf', 'I');
  $pdf->Output('/var/www/maxlan/admin/projekt/card/docs/mx_card_'.$dok_ID.'.pdf', 'F');
  $dok_titel = 'Alle Maxlan Card Bestellungen am - '.throwDateTime(time());
  $dok_url = 'mx_card_'.$dok_ID.'.pdf';
  $time = time();
  $add_cards = mysql_query("INSERT INTO project_card_document SET doc_title = \"$dok_titel\", doc_hash = \"$dok_url\", date_generated = \"$time\", print_status = 0") or die(mysql_error());
}

## Funktion zum Generieren einzelner Karte auf DIN A4
function generate_card_pdf_single($UserID){
  require("../fpdf16/fpdf.php");
  $ProjektURL = "http://".$_SERVER["SERVER_NAME"]."/admin/projekt/";
  $pdf=new FPDF();
  $count = 0;

  $card_background_user = "./mx_card_background.png";
  $card_background_orga = "./mx_card_background_orga.png";
  $card_watermark = "./maxlan_watermark.png";
  $card_query = mysql_query("SELECT project_card.card_ID, project_card.user_ID, project_card.pic_hash, user.nick, user.vorname, user.nachname, user.geb FROM `project_card` LEFT JOIN user ON project_card.user_ID = user.id WHERE project_card.user_ID = '{$UserID}'") or die(mysql_error());
  $ycoord = 0;
  $xright = 0;

  while ($cdata = mysql_fetch_array($card_query)){
  	$admin_check = mysql_query("SELECT * FROM user_orga WHERE user_id = '{$cdata['user_ID']}'");
  	if (mysql_num_rows($admin_check) > 0){
  		$card_background = $card_background_orga;
	} else {
	  	$card_background = $card_background_user;
	}

  	$count = $count + 1;
  	$birthday = birthday2german($cdata['geb']);
  	$long_ID = sprintf("%04d",$cdata['user_ID']);
  	$user_pic = PICTUREPATH.$cdata['pic_hash'];
	$username = $cdata['vorname'].'<i>&quot;'.$cdata['nick'].'&quot;</i> '.$cdata['nachname'];
  // Neue Seite wenn mehr als 10 Karten pro Seite
  	if (fmod($count,10) == 1){ $pdf->AddPage(); }

  	$pdf->Image(substr($_SERVER["SCRIPT_URI"],0,0).$card_background,(10+$xright),(10+$ycoord),78);
  	$pdf->Image(substr($_SERVER["SCRIPT_URI"],0,0).$user_pic,(14+$xright),(22.5+$ycoord),22);
  	$pdf->SetFont('Arial','',11);
  	$pdf->SetXY((37.5+$xright),(30.5+$ycoord));
  	$pdf->Cell(48,0,$cdata['vorname'].' '.$cdata['nachname'],0,0,'L',0);
  	$pdf->SetFont('Arial','',9);
  	$pdf->SetXY((37.5+$xright),(38.5+$ycoord));
  	$pdf->Cell(48,0,$long_ID." - ".$cdata['nick'],0,0,'L',0);
  	$pdf->SetFont('Arial','',8);
  	$pdf->SetXY((37.5+$xright),(54+$ycoord));
  	$pdf->Cell(48,0,$birthday,0,0,'L',0);
  	$pdf->Image("http://lan.maxlan.de/admin/projekt/card/barcode/image.php?code=".$long_ID."&tmp=.png",(59+$xright),(46+$ycoord),27);
  	// Neue Zeile alle 2 Karten
  	if (fmod($count,2) == 0){ $ycoord = $ycoord + 55; }
  	// jede 2. Karte nach rechts verschieben
  	if ($xright == 0){ $xright = 90; } else { $xright = 0; }
	$creation_time = time();
	$card_user_update = mysql_query("UPDATE project_card SET last_creation_date = '{$creation_time}', card_status = 1 WHERE card_ID = '{$cdata['card_ID']}'");
	}

  // $pdf->Output('mx_card_user_'.$long_ID.'.pdf', 'I');

  $doks = mysql_query("SELECT * FROM project_card_document");
  $dok_ID = mysql_num_rows($doks) + 1;

  //$pdf->Output('mx_card.pdf', 'I');
  $pdf->Output('/var/www/maxlan/admin/projekt/card/docs/mx_card_single_user_'.$long_ID.'.pdf', 'F');
  $dok_titel = 'Maxlan Card User '.$long_ID.' '.$username;
  $dok_url = 'mx_card_single_user_'.$long_ID.'.pdf';
  $time = time();
  $add_cards = mysql_query("INSERT INTO project_card_document SET doc_title = \"$dok_titel\", doc_hash = \"$dok_url\", date_generated = \"$time\", print_status = 0") or die(mysql_error());

}

function upload_userpic($u_id){

	$File = $_FILES['upload']['tmp_name'];
	$File_name = 'temppic'.time().'.jpg';
	// $File_name = stripfilename($_FILES['upload']['name']);
	$Path = PICTUREPATH_FULL.$File_name;
	copy($File,$Path);
	$image_big = imagecreatefromjpeg($File);
	$image_small = imagecreatetruecolor(257,386);
	$img_size = getimagesize($Path);
	$height = $img_size[1];
	$width = $img_size[0];
	imagecopyresampled ($image_small, $image_big, 0, 0, $startx, $starty, 75, 75, $window, $window);
	imagecopyresized($image_small, $image_big, 0, 0, 0, 0, 257, 386, $width, $height);
	UnsharpMask($image_small, 10, 0.8, 0);
	$new_file_name = hash("sha256",'user_'.$u_id.time()).'.jpg';
	imagejpeg($image_small, PICTUREPATH.$new_file_name, 75);
	imagedestroy($image_big);
	imagedestroy($image_small);
	unlink($Path);
	$time = time();
	return $new_file_name;
}


function stripfilename($datei_name){
	$umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
	$replace = Array("ae","oe","ue","Ae","Oe","Ue","ss");
    // Umlaute entfernen
    $datei_name = preg_replace($umlaute, $replace, $datei_name);
	// sonstige sonderzeichen entfernen
	$datei_name = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $datei_name);
	return $datei_name;
}

function UnsharpMask($img, $amount, $radius, $threshold)    {

////////////////////////////////////////////////////////////////////////////////////////////////
////
////                  Unsharp Mask for PHP - version 2.1.1
////
////    Unsharp mask algorithm by Torstein Hønsi 2003-07.
////             thoensi_at_netcom_dot_no.
////               Please leave this notice.
////
///////////////////////////////////////////////////////////////////////////////////////////////



    // $img is an image that is already created within php using
    // imgcreatetruecolor. No url! $img must be a truecolor image.

    // Attempt to calibrate the parameters to Photoshop:
    if ($amount > 500)    $amount = 500;
    $amount = $amount * 0.016;
    if ($radius > 50)    $radius = 50;
    $radius = $radius * 2;
    if ($threshold > 255)    $threshold = 255;

    $radius = abs(round($radius));     // Only integers make sense.
    if ($radius == 0) {
        return $img; imagedestroy($img); break;        }
    $w = imagesx($img); $h = imagesy($img);
    $imgCanvas = imagecreatetruecolor($w, $h);
    $imgBlur = imagecreatetruecolor($w, $h);


    // Gaussian blur matrix:
    //
    //    1    2    1
    //    2    4    2
    //    1    2    1
    //
    //////////////////////////////////////////////////


    if (function_exists('imageconvolution')) { // PHP >= 5.1
            $matrix = array(
            array( 1, 2, 1 ),
            array( 2, 4, 2 ),
            array( 1, 2, 1 )
        );
        imagecopy ($imgBlur, $img, 0, 0, 0, 0, $w, $h);
        imageconvolution($imgBlur, $matrix, 16, 0);
    }
    else {

    // Move copies of the image around one pixel at the time and merge them with weight
    // according to the matrix. The same matrix is simply repeated for higher radii.
        for ($i = 0; $i < $radius; $i++)    {
            imagecopy ($imgBlur, $img, 0, 0, 1, 0, $w - 1, $h); // left
            imagecopymerge ($imgBlur, $img, 1, 0, 0, 0, $w, $h, 50); // right
            imagecopymerge ($imgBlur, $img, 0, 0, 0, 0, $w, $h, 50); // center
            imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);

            imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 33.33333 ); // up
            imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 25); // down
        }
    }

    if($threshold>0){
        // Calculate the difference between the blurred pixels and the original
        // and set the pixels
        for ($x = 0; $x < $w-1; $x++)    { // each row
            for ($y = 0; $y < $h; $y++)    { // each pixel

                $rgbOrig = ImageColorAt($img, $x, $y);
                $rOrig = (($rgbOrig >> 16) & 0xFF);
                $gOrig = (($rgbOrig >> 8) & 0xFF);
                $bOrig = ($rgbOrig & 0xFF);

                $rgbBlur = ImageColorAt($imgBlur, $x, $y);

                $rBlur = (($rgbBlur >> 16) & 0xFF);
                $gBlur = (($rgbBlur >> 8) & 0xFF);
                $bBlur = ($rgbBlur & 0xFF);

                // When the masked pixels differ less from the original
                // than the threshold specifies, they are set to their original value.
                $rNew = (abs($rOrig - $rBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))
                    : $rOrig;
                $gNew = (abs($gOrig - $gBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))
                    : $gOrig;
                $bNew = (abs($bOrig - $bBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))
                    : $bOrig;



                if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
                        $pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
                        ImageSetPixel($img, $x, $y, $pixCol);
                    }
            }
        }
    }
    else{
        for ($x = 0; $x < $w; $x++)    { // each row
            for ($y = 0; $y < $h; $y++)    { // each pixel
                $rgbOrig = ImageColorAt($img, $x, $y);
                $rOrig = (($rgbOrig >> 16) & 0xFF);
                $gOrig = (($rgbOrig >> 8) & 0xFF);
                $bOrig = ($rgbOrig & 0xFF);

                $rgbBlur = ImageColorAt($imgBlur, $x, $y);

                $rBlur = (($rgbBlur >> 16) & 0xFF);
                $gBlur = (($rgbBlur >> 8) & 0xFF);
                $bBlur = ($rgbBlur & 0xFF);

                $rNew = ($amount * ($rOrig - $rBlur)) + $rOrig;
                    if($rNew>255){$rNew=255;}
                    elseif($rNew<0){$rNew=0;}
                $gNew = ($amount * ($gOrig - $gBlur)) + $gOrig;
                    if($gNew>255){$gNew=255;}
                    elseif($gNew<0){$gNew=0;}
                $bNew = ($amount * ($bOrig - $bBlur)) + $bOrig;
                    if($bNew>255){$bNew=255;}
                    elseif($bNew<0){$bNew=0;}
                $rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew;
                    ImageSetPixel($img, $x, $y, $rgbNew);
            }
        }
    }
    imagedestroy($imgCanvas);
    imagedestroy($imgBlur);

    return $img;

}

?>
