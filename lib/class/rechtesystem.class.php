<?php
class rechtesystem {
	private $session = null;
	
	private $rechte;
	private $pdo = null;
	
	private $userid;
	
	public function __construct($datenbankpdo,$session)
	{
		$this->session = $session;
		$this->pdo = $datenbankpdo;

		$this->getUserStruct();
		$this->getProjectRechte();
	}
	
	private function getUserStruct()
	{
		
		$this->userid = $this->session['session']['user_id'];
		
	}
	
	private function getProjectRechte()
	{
		$sql =("SELECT prr.bereich,prr.recht FROM `project_rights_user_rights` AS prur, `project_rights_rights` AS prr WHERE prur.user_id=:userid");
		
		$stmt = $this->pdo->prepare($sql);
		
		$stmt->bindValue(':userid',$this->userid);
		
		$stmt->execute();
		
		foreach($stmt->fetchAll() as $row)
		{
			$rechtename = $row['bereich'];
			$recht = $row['recht'];
			//echo $recht;
			$this->rechte[$rechtename][$recht] = true; 
		}
	}
	
	public function CheckRecht($method,$action)
	{
		if(!array_key_exists($method, $this->rechte))
			return false;
		else 
		{
			//$tmp_array = $this->rechte[$method];
			return array_key_exists($action, $this->rechte[$method]);
		}
		
	}
}