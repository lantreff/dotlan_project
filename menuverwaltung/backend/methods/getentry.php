<?php
if($rechtemanagment->CheckRecht('menuverwaltung', 'show'))
{
	$menuid = 0;
	if(isset($_GET['entryid']))
		$menuid = $_GET['entryid'];
	
	if($menuid == 0)
		return;
	
	$query = $db->prepare("SELECT titel,view_recht as recht,url,param1,param2,param3,menu FROM project_menu WHERE id=:menuid");
	$query->bindValue(':menuid', $menuid);
	$query->execute();
	foreach($query->fetchAll() as $row)
	{
		$titel = $row['titel'];
		$menu = $row['menu'];
		$url  = $row['url'];
		$recht = $row['recht'];
		$param1 = boolval($row['param1']);
		$param2 = boolval($row['param2']);
		$param3 = boolval($row['param3']);
		
		$returnarray['titel'] = $titel;
		$returnarray['recht'] = $recht;
		$returnarray['menu'] = $menu;
		$returnarray['url'] = $url;
		$returnarray['param1'] = $param1;
		$returnarray['param2'] = $param2;
		$returnarray['param3'] = $param3;
		
		
	}
	
	echo json_encode($returnarray);
}

?>