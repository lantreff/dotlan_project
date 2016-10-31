<?php
if((isset($_GET['method'])) && (isset($_GET['menu'])) && ($rechtemanagment->CheckRecht('menuverwaltung', 'save')))
{
	$menu = $_GET['menu'];
	
	$sql = ("INSERT INTO `project_menu` (`menu`) VALUES(:menu)");
	
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':menu', $menu);
	
	try {
		$db->beginTransaction();
		$stmt->execute();
		$lastid = $db->lastInsertId();
		$db->commit();
		
		$returnarray['response'] = "ok";
		$returnarray['lastid'] = $lastid;
	}
	catch(PDOException $ex)
	{
		$db->rollBack();
		$returnarray['response'] = $ex->getMessage();
	}
	
	echo json_encode($returnarray);
}