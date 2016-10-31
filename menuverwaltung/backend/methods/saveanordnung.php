<?php

if((isset($_GET['method'])) && ($rechtemanagment->CheckRecht('menuverwaltung', 'save')))
{
	$array =  file_get_contents('php://input');
	$array = json_decode($array,true);
	$array = $array['user'];
	
	foreach($array as $menueintraege)
	{
		$eintrage_array = array();
		$eintrage_array = $menueintraege['eintrage'];
		
		
		$counter = 0;
	
		$sql = ("UPDATE `project_menu` 
					SET 
						`order_int` = :order_int
				WHERE `id` = :id ");

		$stmt = $db->prepare($sql);
		
		foreach($eintrage_array as $menuitems)
		{		
			$counter++;
			//print_r($menuitems);
			$dbid = $menuitems['dbid'];
			
			$stmt->bindValue(':order_int', $counter);
			$stmt->bindValue(':id', $dbid);
			$stmt->execute();
		}
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