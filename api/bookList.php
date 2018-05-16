<?php

session_start();
include 'apiconfig.php';
include 'apiprotect.php';


switch($_GET['mode'])
{
	case "getResumeList":
		getResumeList();
		break;

	default:
		header('HTTP/1.1 400');
		die();
}

function getResumeList()
{
	$pdo = new PDO(PDOSTRING);
	$query = 'SELECT title, authors.author, thumbnail, books.id
			from books left join bookPositions on books.id = bookPositions.book
			left join authors on books.author = authors.id
			where bookPositions.username = :username';
	$searchStmt = $pdo->prepare($query);
	$searchStmt->bindParam(':username',$_SESSION['username']);
        $result = $searchStmt->execute();
        $books = $searchStmt->fetchAll();

	echo json_encode($books);
}
