<?php

include 'config.php';

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
				$this->bookPathList[] = $repository;
				$pathParts = explode(DIRECTORY_SEPERATOR,$repository);
				$this->bookTitles[] = $pathParts[count($pathParts)-1];
			}
			else if(is_dir($testPath))
			{
				$this->scanFiles($testPath);
			}
		}
	}
	public function syncBooks($repository = "repository")
	{
		$this->scanFiles($repository);
		$this->bookPathList = array_unique($this->bookPathList);
		$this->bookTitles = array_unique($this->bookTitles);
		$googleApi = new googleApi();
		foreach($this->bookTitles as $title)
		{
			$response = $googleApi->searchByTitle($title);
			$pathKey = array_search($title,$this->bookPathList);
			var_dump($this->bookPathList[$pathKey]);
			$response->path = $this->bookPathList[$pathKey];
			return $response;
		}
	}
}

class googleApi
{
	public function searchByTitle($title)
	{
		$titleSearch = urlencode($title);
		$request = 'https://www.googleapis.com/books/v1/volumes?q=' . $titleSearch . '&key='. GOOGLE_API_KEY;
		var_dump($request);
		$response = file_get_contents($request);
		$responseObj = json_decode($response);
		$item = new stdClass();
		$item->title = $responseObj->items[0]->volumeInfo->title;
		$item->author = $responseObj->items[0]->volumeInfo->authors[0];
		$item->publishDate = $responseObj->items[0]->volumeInfo->publishedDate;
		$item->rating = $responseObj->items[0]->volumeInfo->averageRating;
		$item->ratingsCount = $responseObj->items[0]->volumeInfo->ratingsCount;
		$item->thumbnail = $responseObj->items[0]->volumeInfo->imageLinks->thumbnail;
		$item->description = $responseObj->items[0]->volumeInfo->description;
		return $item;
	}
}

$bookScan = new bookScan();
$response = $bookScan->syncBooks();
var_dump($response);
