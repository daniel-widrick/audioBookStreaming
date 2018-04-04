<?php

define('DIRECTORY_SEPERATOR','/');

class bookScan
{

	public $bookPathList;
	public $bookTitles;

	public function scanFiles($repository = "repository")
	{
		$currentDirectory = scandir($repository);
		$currentDirectory = array_diff($currentDirectory, array('..','.'));
		foreach($currentDirectory as $subPath)
		{
			$testPath = $repository.DIRECTORY_SEPERATOR.$subPath;
			if(!is_dir($testPath) and strpos(strtoupper($subPath),'.MP3') !== false)
			{
				echo $testPath." is a book file! ".$repository."\n";
				$this->bookPathList[] = $repository;
				$pathParts = explode(DIRECTORY_SEPERATOR,$repository);
				$this->bookTitles[] = $pathParts[count($pathParts)-1];
			}
			else if(is_dir($testPath))
			{
				echo "Scan sub sirectory: $testPath\n";
				$this->scanFiles($testPath);
			}
		}
	}
}

$bookScan = new bookScan();
$bookScan->scanFiles();
$bookScan->bookPathList = array_unique($bookScan->bookPathList);
$bookScan->bookTitles = array_unique($bookScan->bookTitles);
var_dump($bookScan->bookPathList);
var_dump($bookScan->bookTitles);
