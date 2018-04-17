<?php

class bookScan
{

	public $bookPathList;
	public $bookTitles;
	private $pdo;
	public function __construct()
	{
		$this->pdo = new PDO('sqlite:database/database.sqlite3');
	}


	public function getResumeList()
	{
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = 'Select title, authors.author, thumbnail, books.id from books 
				left join bookPositions on books.id = bookPositions.book 
				left join authors on books.author = authors.id
				where bookPositions.username = :username';
		$searchStmt = $this->pdo->prepare($query);
		$searchStmt->bindParam(':username',$_SESSION['username']);
		$result = $searchStmt->execute();
		$books = $searchStmt->fetchAll();
		return $books;
	}
	//$bookScan->saveTime($_GET['book'],$_SESSION['username'],$_GET['file'],$_GET['time']);
	public function getTime($book, $user)
	{
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = 'SELECT file, time, book, username from bookPositions where username = :username AND book = :book';
		$searchStmt = $this->pdo->prepare($query);
		$searchStmt->bindParam(':username',$user);
		$searchStmt->bindParam(':book',$book);
		$result = $searchStmt->execute();
		return $searchStmt->fetch();
	}
	public function saveTime($book, $user, $file, $time)
	{
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = 'REPLACE INTO bookPositions (username, book, file, time) VALUES(:username, :book, :file, :time)';
		$insertStmt = $this->pdo->prepare($query);
		$insertStmt->bindParam(':username',$user);
		$insertStmt->bindParam(':book',$book);
		$insertStmt->bindParam(':file',$file);
		$insertStmt->bindParam(':time',$time);
		return $insertStmt->execute();
	}
	public function getAuthor($author)
	{
		$pdo = $this->pdo;
		$sql = "select * from authors where author = :author";
		$searchStmt = $pdo->prepare($sql);
		$searchStmt->bindParam(':author',$author);
		$searchStmt->execute();
		return $searchStmt->fetch();
	}
	public function addAuthor($author)
	{
		$pdo = $this->pdo;
		$storedAuthor = $this->getAuthor($author);
		if($storedAuthor === false)
		{
			$sql = "INSERT INTO authors (author) VALUES(:author)";
			$insertStmt = $pdo->prepare($sql);
			$insertStmt->bindParam(':author',$author);
			$insertStmt->execute();
		}
		return $this->getAuthor($author);
	}
	public function getBookById($id)
	{
		$query = "SELECT * from books where books.id = :bookId";
		$search = $this->pdo->prepare($query);
		$search->bindParam(':bookId',$id);
		$search->execute();
		return $search->fetch();
	}
		
		
	public function getBook($book)
	{
		$pdo = $this->pdo;
		$searchQuery = "SELECT * from books where title like :title";
		$searchStmt = $pdo->prepare($searchQuery);
		$bookTitle = '%'.$book->title.'%';
		$bookAuthor = '%'.$book->author.'%';
		$searchStmt->bindParam(':title',$bookTitle);
		//$searchStmt->bindParam(':author',$bookAuthor);
		$searchStmt->execute();
		$row = $searchStmt->fetch();
		return $row;
	}
	public function getBookList()
	{
		$pdo = $this->pdo;
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "select authors.author, books.title, books.path, books.id from books left join authors on authors.id = books.author order by authors.author asc, books.path, substr(books.publishDate,1,4) asc";
		$searchStatement = $pdo->prepare($sql);
		$result = $searchStatement->execute();
		$bookList = Array();
		
		while( ($book = $searchStatement->fetch()) !== false)
		{
			$bookList[] = $book;
		}
		return $bookList;
	}
	public function storeBook($book)
	{
		$pdo = $this->pdo;
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$book->author = str_replace('. ','.',$book->author);
		echo "Store: $book->author :: $book->title \n";
		if($this->getBook($book) === false)
		{
			$insertQuery = "INSERT INTO books (title, author, publishDate, rating, ratingsCount, thumbnail, description, id, path) VALUES(:title, :author, :publishDate, :rating, :ratingsCount, :thumbnail, :description, :id, :path)";
			$insertStmt = $pdo->prepare($insertQuery);
			$insertStmt->bindParam(':title',$book->title);
			$author=$this->addAuthor($book->author)["id"];
			$insertStmt->bindParam(':author',$author);
			$insertStmt->bindParam(':publishDate',$book->publishDate);
			$insertStmt->bindParam(':rating',$book->rating);
			$insertStmt->bindParam(':ratingsCount',$book->ratingsCount);
			$insertStmt->bindParam(':thumbnail',$book->thumbnail);
			$insertStmt->bindParam(':description',$book->description);
			$insertStmt->bindParam(':id',$book->id);
			$insertStmt->bindParam(':path',$book->path);
			$insertStmt->execute();
			echo "Stored: $book->author :: $book->title \n";
		}
	}

	public function scanFiles($repository = "repository")
	{
		$currentDirectory = scandir($repository);
		$currentDirectory = array_diff($currentDirectory, array('..','.'));
		foreach($currentDirectory as $subPath)
		{
			$testPath = $repository.DIRECTORY_SEPERATOR.$subPath;
			if(!is_dir($testPath) and strpos(strtoupper($subPath),'.MP3') !== false)
			{
				echo "TEST: $testPath\n";
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
	public function getBookFiles($book)
	{
		$path = $book["path"];
		$files = scandir($path);
		$files = array_diff($files,array('..','.'));



		return $files;
	}
	public function syncBooks($repository = "repository")
	{
		$this->scanFiles($repository);
		$this->bookPathList = array_unique($this->bookPathList);
		$this->bookTitles = array_unique($this->bookTitles);
		$googleApi = new googleApi();
		foreach($this->bookTitles as $key => $title)
		{
			if(substr($title,0,1) == 0) $title = preg_replace('/[0-9]+/','',$title);
			echo "Search Title: $title \n";
			$response = $googleApi->searchByTitle($title);
			$response->path = $this->bookPathList[$key];
			$this->storeBook($response);
			sleep(1);
		}
	}
}

class googleApi
{
	public function searchByTitle($title)
	{
		$titleSearch = urlencode($title);
		$request = 'https://www.googleapis.com/books/v1/volumes?q=' . $titleSearch . '&key='. GOOGLE_API_KEY;
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
		$item->id = $responseObj->items[0]->id;
		return $item;
	}
}

