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
include 'libraries/display.php';
$bookScan = new bookScan();

//Navigation
if(isset($_GET['book']))
{
	//Play Audio Book
	$book = $bookScan->getBookById($_GET['book']);

	$files = $bookScan->getBookFiles($book);
	var_dump(DISPLAY::showBookPlayer($book,$files));
}
else
{
	echo DISPLAY::showAuthorList();
}



