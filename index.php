<?php

session_start();

include 'themes/dg/main.html';
if(!isset($_SESSION['username']) && (!isset($_POST['username']) || !isset($_POST['password'])) )
{
	include 'themes/dg/login.html';
	exit;
}else if( isset($_POST['username']) && isset($_POST['password']) ) 
{
	//Check login credentials
	$pdo = new PDO('sqlite:database/database.sqlite3');
	$saltQuery = "SELECT salt from users where username = :username";
	$saltStmt = $pdo->prepare($saltQuery);
	$saltStmt->bindParam(':username',$_POST['username']);
	$saltStmt->execute();
	$salt = $saltStmt->fetch()["salt"];

	$passhash = md5($_POST['password'] . $salt);
	$usernameQuery = "SELECT username from users where username = :username and password = :password";
	$usernameStmt = $pdo->prepare($usernameQuery);
	$usernameStmt->bindParam(':username',$_POST['username']);
	$usernameStmt->bindParam(':password',$passhash);
	$usernameStmt->execute();
	$username = $usernameStmt->fetch()["username"];

	if($username === null)
	{
		die("Authentication Failure. TODO: redirect");
	}
	$_SESSION['username'] = $username;
}	

//Logged in
include 'libraries/bookScan.php';
$bookScan = new bookScan();
$bookList = $bookScan->getBookList();

$authors = [];
$books = [];
foreach($bookList as $book)
{
	$author = $book["author"];
	if($author == "") $author = "Unknown";
	$authors[] = $author;
	$books[$author][] = $book;
}
echo '<ul class="authorList">';
foreach($books as $author => $book)
{
	echo '<li class="authorItem">' . $author;
	echo '<ul class="bookList">';
	foreach($book as $b)
	{
		echo '<li class="bookItem">'.$b["title"].'</li>';
	}
	echo '</ul></li>';
}
