<?php

session_start();

if(!isset($_SESSION['username']))
{
	header('HTTP/1.0 403 Forbidden');
	echo "Unauthorized Request";
	die();
}
if(!isset($_GET['book']))
{
	header('HTTP/1.0 400 Bad Request');
	echo "Missing Parameter";
	die();
}
chdir('../');
include 'config.php';
include 'libraries/bookScan.php';


$bookScan = new bookScan();
$result = $bookScan->getTime($_GET['book'],$_SESSION['username']);
echo json_encode($result);
