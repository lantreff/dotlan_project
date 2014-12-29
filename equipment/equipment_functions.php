<?php

## ADD ##
function equipment_add($daten)
{
	$sql = "INSERT INTO `project_equipment` (id, invnr, bezeichnung, hersteller, category, besitzer, details, zusatzinfo, lagerort, kiste, ist_leihartikel, ist_kiste) 
			VALUES 	(NULL, '".$daten['invnr']."', '".$daten['bezeichnung']."', '".$daten['hersteller']."', '".$daten['category']."', '".$daten['besitzer']."', '".$daten['details']."', '".$daten['zusatzinfo']."', '".$daten['lagerort']."', '".$daten['kiste']."', '".$daten['ist_leihartikel']."', '".$daten['ist_kiste']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
## /ADD ##

function equipment_edit($daten,$id)
{
	if($daten['category1'] <> "" )
	{
		$category = $daten['category1'];
	}else
	{
		$category = $daten['category'];
	}
	$sql = "UPDATE project_equipment SET  `invnr` = '".$daten['invnr']."', `bezeichnung` = '".$daten['bezeichnung']."', `besitzer` = '".$daten['besitzer']."', `details` = '".$daten['details']."', `zusatzinfo` = '".$daten['zusatzinfo']."', `hersteller` = '".$daten['hersteller']."', `category` = '".$category."', `lagerort` = '".$daten['lagerort']."', `kiste` = '".$daten['kiste']."', `ist_leihartikel` = '".$daten['ist_leihartikel']."' WHERE `id` = ".$id." ";
	$out =  mysql_query( $sql); 	
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}

## ADD ##
function equipment_add_lagerort($daten)
{
	$sql = "INSERT INTO `project_equipment_lagerort` (id, bezeichnung) 
			VALUES 	(NULL, '".$daten['bezeichnung']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
## /ADD ##

function equipment_edit_lagerort($daten,$id)
{
	$sql = "UPDATE project_equipment_lagerort SET  `bezeichnung` = '".$daten['bezeichnung']."' WHERE `id` = ".$id." ";
	$out =  mysql_query( $sql); 	
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
?>