<?php
if($rechtemanagment->CheckRecht('menuverwaltung', 'show'))
{
	$returnarr = array();
	
	if(!(isset($_GET['menueeintrag'])))
	{
		$stmt = $db->prepare("SELECT DISTINCT(menu) as menu FROM project_menu");
		$stmt->execute();
		
		foreach($stmt->fetchAll( ) as $row)
		{
			$menueintrag = utf8_encode($row['menu']);
			$returnarr[$menueintrag]['label'] = $menueintrag;
		}
	}
	else
	{
		$menueintrag = $_GET['menueeintrag'];
		
		$stmt = $db->prepare("SELECT titel,id,param1,param2,param3 FROM project_menu WHERE menu=:menueintrag ORDER BY order_int");
		$stmt->bindValue(':menueintrag', $menueintrag);
		$stmt->execute();
		
		foreach($stmt->fetchAll() as $row)
		{
			$titel = $row['titel'];
			$dbid = $row['id'];
			$eintrag = array();
			$eintrag['name'] = $titel;
			$eintrag['dbid'] = $dbid;
			$returnarr['eintrage'][] = $eintrag;
		}
	}
	echo json_encode($returnarr);
}