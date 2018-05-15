<?php

session_start();
include 'apiconfig.php';

if(!isset($_GET['mode']))
{
	header("HTTP/1.1 400 Invalid Mode");
	die();
}
switch($_GET['mode'])
{
	case "logout":
		logout();
		break;
	case "getUsername":
		getUsername();
		break;
	case "login":
	default:
		login();
		break;
}

function getUsername()
{
	var_dump($_SESSION);
	if(isset($_SESSION['username']) && $_SESSION['username'] != '')
	{
		header('HTTP/1.1 200 OK');
		echo '{"username": "' . $_SESSION['username'] . '"}';
	} else {
		header('HTTP/1.1 401');
	}
}
function logout()
{
	unset($_SESSION['username']);
	header("HTTP/1.1 200 OK");
}
function login()
{
	try {
		$pdo = new PDO(PDOSTRING);
	} catch ( PDOException $ex)
	{
		header('HTTP/1.1 501 Database Error');
		die($ex);
	}
	if( !isset($_POST['password']) || !isset($_POST['username']) )
	{
		header('HTTP/1.1 400 Invalid username/password');
		die();
	}
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
		header("HTTP/1.1 403 Unauthorized");
		return;
        }
        $_SESSION['username'] = $username;
	header("HTTP/1.1 200 " . $username);
}
