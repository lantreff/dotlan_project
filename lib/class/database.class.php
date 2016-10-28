<?php
class DataBase {
	private $host;
	private $user;
	private $pass;
	private $data;
	
	private $pdo = null;
	public function __construct($host,$user,$pass,$data)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->data = $data;
		
		$this->connectToServer();
	}
	
	private function connectToServer()
	{
		$dsn = sprintf('mysql:host=%s;dbname=%s',$this->host,$this->data);
		try {
			$this->pdo = new PDO($dsn,$this->user,$this->pass);
		} 
		catch(PDOException $ex)
		{
			print "<h1>Error: ".$ex->getMessage()."</h1>".$dsn;
			die();
		}
	}
	
	public function getPDO()
	{
		return $this->pdo;
	}
	
}