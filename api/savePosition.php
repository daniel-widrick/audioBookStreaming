<?php

session_start();

if(!isset($_SESSION['username']))
{
	header('HTTP/1.0 403 Forbidden');
	echo "Unauthorized Request";
	die();
}
if(!isset($_GET['book']) || !isset($_GET['file']) || !isset($_GET['time']))
{
	header('HTTP/1.0 400 Bad Request');
	echo "Missing Parameter";
	die();
}
chdir('../');
var_dump(getcwd());

include 'config.php';
include 'libraries/bookScan.php';


$bookScan = new bookScan();
$result = $bookScan->saveTime($_GET['book'],$_SESSION['username'],$_GET['file'],$_GET['time']);
var_dump($result);
