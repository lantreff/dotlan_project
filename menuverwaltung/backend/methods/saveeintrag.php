<?php
//$return['response'] = true;

if((isset($_GET['method'])) && (isset($_GET['id'])) && ($rechtemanagment->CheckRecht('menuverwaltung', 'save')))
{
	$dbid = $_GET['id'];
	
	$array =  file_get_contents('php://input');
	$array = json_decode($array,true);
	$array = $array['user'];
	
	$sql = ("UPDATE `project_menu` 
				SET 
					`titel` = :name, 
					`param1`=:param1,
					`param2` =:param2,
					`param3` =:param3,
					`view_recht` =:recht,
					`url` = :url
			
			WHERE `id` = :id ");
	
	$query = $db->prepare($sql);
	$query->bindValue(':id', $dbid);
	$query->bindValue(':name', $array['titel']);
	$query->bindValue(':param1', $array['param1']);
	$query->bindValue(':param2', $array['param2']);
	$query->bindValue(':param3', $array['param3']);
	$query->bindValue(':recht', $array['recht']);
	$query->bindValue(':url', $array['url']);
	$query->execute();
	
	$returnarr['debug'] = $query->errorInfo();
	$returnarr['response'] = "Speichern erfolgreich";
	
	echo json_encode($returnarr);
	
}
