<?php

if((isset($_GET['method'])) && ($rechtemanagment->CheckRecht('menuverwaltung', 'save')))
{
	$array =  file_get_contents('php://input');
	$array = json_decode($array,true);
	$array = $array['user'];
	
	$array = $array['eintrage'];
	
	$counter = 0;
	
	$sql = ("UPDATE `project_menu`
					SET
						`order_int` = :order_int
				WHERE `id` = :id ");
	
	$stmt = $db->prepare($sql);
	
	foreach($array as $menueintraege)
	{
		$counter++;
		$dbid = $menueintraege['dbid'];
		$stmt->bindValue(':order_int', $counter);
		$stmt->bindValue(':id', $dbid);
		$stmt->execute();
	}
	$action['erfolg'] = true;
	$action['response'] = "Menu gespeichert";
	
}
else
{
	$action['erfolg'] = false;
	$action['response'] = "Sie haben keine Berechtigung zum Speichern";	
}
echo json_encode($action);