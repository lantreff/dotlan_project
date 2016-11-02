<?php
if($rechtemanagment->CheckRecht('menuverwaltung', 'show'))
{
	$returnarr = array();
	$stmt = $db->prepare("SELECT DISTINCT(menu) as menu FROM project_menu");
	$stmt->execute();
	
	//$introw = $stmt->fetchAll();
	foreach($stmt->fetchAll() as $row)
	{
		$menueintrag = utf8_encode($row['menu']);
		$returnarr[$menueintrag]['label']=$menueintrag;
		$subquery = $db->prepare("SELECT titel,id,param1,param2,param3 FROM project_menu WHERE menu=:menueintrag ORDER BY order_int");
		$subquery->bindValue(':menueintrag', $menueintrag);
		$subquery->execute();
		foreach($subquery->fetchAll() as $nrow)
		{
			$titel = $nrow['titel'];
			$dbid  = $nrow['id'];
			$eintrag = array();
			$eintrag['name'] = $titel;
			$eintrag['dbid'] = utf8_encode($dbid);
			$returnarr[$menueintrag]['eintrage'][] = $eintrag;
		}
		
	}
	echo json_encode($returnarr);
}