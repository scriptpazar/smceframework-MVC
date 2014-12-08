<?php

/**
 *
 * @author Samed Ceylan
 * @link http://www.samedceylan.com/
 * @copyright 2015 SmceFramework
 * @github https://github.com/imadige/SMCEframework-MVC
 */


namespace Smce\Core;

class SmAssetsManager
{
	public $file=array();
	
	public $name="";
	
	public $error=array();
	
	public function __construct($name)
	{
		if($name=="")
			$this->setError("Not assets name");
		else
			$this->name=$name;
			
		if(!is_dir(BASE_PATH."/assets/".$name))
			mkdir(BASE_PATH."/assets/".$name,0777);
	}
	
	public function addFile($file)
	{
		$this->file[]=$file;
	}
	
	public function run()
	{
		foreach($this->file as $key=>$value)
		{
			if(!file_exists(BASE_PATH."/assets/".$this->name."/".basename($value)))
				copy($value,BASE_PATH."/assets/".$this->name."/".basename($value));
		}
	}
	
	public function setError($error)
	{
		$this->error[]=$error;
	}
	
	public function getError($error)
	{
		$this->error[]=$error;
	}
}