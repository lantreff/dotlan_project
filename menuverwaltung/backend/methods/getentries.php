<?php
$db = $datenbank->getPDO();

if(isset($_GET['menu']))
{
	$menu = $_GET['menu'];
	
	$stmt = $db->prepare("SELECT * FROM project_menu WHERE menu=:menu");
	$stmt->bindValue(':menu', $menu);
	
	$stmt->execute();
	
	
	$d = $stmt->fetchAll();
}
else 
{
	$returnarr = array();
	$stmt = $db->prepare("SELECT DISTINCT(menu) as menu FROM project_menu");
	$stmt->execute();
	
	//$introw = $stmt->fetchAll();
	foreach($stmt->fetchAll() as $row)
	{
		$menueintrag = utf8_encode($row['menu']);
		$returnarr[$menueintrag]['label']=$menueintrag;
		$subquery = $db->prepare("SELECT titel,id,param1,param2,param3 FROM project_menu WHERE menu=:menueintrag");
		$subquery->bindValue(':menueintrag', $menueintrag);
		$subquery->execute();
		foreach($subquery->fetchAll() as $nrow)
		{
			
			$titel = $nrow['titel'];
			$dbid  = $nrow['id'];
			$param1 = boolval($nrow['param1']);
			$param2 = boolval($nrow['param2']);
			$param3 = boolval($nrow['param3']);
			$eintrag = array();
			$eintrag['name'] = utf8_encode($titel);
			$eintrag['dbid'] = utf8_encode($dbid);
			$eintrag['param1'] = $param1;
			$eintrag['param2'] = $param2;
			$eintrag['param3'] = $param3;
			$returnarr[$menueintrag]['eintrage'][] = $eintrag;
		}
		
	}
	echo json_encode($returnarr);
	
}
//print_r($d);

//echo json_encode($d);