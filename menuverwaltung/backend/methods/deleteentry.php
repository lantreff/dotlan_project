<?php
if((isset($_GET['method'])) && ($_GET['id']) && ($rechtemanagment->CheckRecht('menuverwaltung', 'save')))
{
	$id = $_GET['id'];
	
	$sql = ("DELETE FROM `project_menu` WHERE `id` = :id");
	
	$query = $db->prepare($sql);
	$query->bindValue(':id', $id);
	$query->execute();
	
	$returnarr = array();
	
	
	$returnarr['response'] = true;
	echo json_encode($returnarr);
}

?>