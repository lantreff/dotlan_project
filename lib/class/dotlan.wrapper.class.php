<?php
/**
 * Diese klasse stellt die möglichkeit zur Verfügung um Scripte die von mir geschrieben sind
 * ins Dotlan system einzupflegen.
 * 
 *
 * 
 * @author Damian Bodde
 *
 */
class dotlanWrapper{
	
	private $filename;
	private $content;
	private $filehandler = null;
	
	public function setFileName($filename)
	{
		$this->filename = $filename;
	}
	public function getFileName($filename)
	{
		return $this->filename;
	}
	
	public function __construct($filename)
	{
		$this->setFileName($filename);
		$this->readFile();
		
	}
	
	private function dateivorhanden()
	{
		return file_exists($this->filename);
	}
	
	public function isVorhanden()
	{
		return $this->dateivorhanden();
	}
	
	private function readFile()
	{
		if(!$this->dateivorhanden()){
			throw new Exception("Datei wurde nicht gefunden");
		}
			
		$this->filehandler = fopen($this->filename,"r");
		if(!$this->filehandler)
		{
			throw new Exception("Dateihandler is null");
		}
		else
		{
			while(($buffer = fgets($this->filehandler,4096)) !== false)
			{
				$this->content .= $buffer;
			}
			fclose($this->filehandler);
		}
		
	}

	public function GetFileContent()
	{
		return $this->content;
	}
}


?>