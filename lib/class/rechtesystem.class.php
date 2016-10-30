<?php
class rechtesystem {
	private $session = null;
	
	public function __construct($session)
	{
		$this->session = $session;
		print_r($session);
	}
}