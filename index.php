<?php

session_start();

include 'themes/dg/main.html';
if(!isset($_SESSION['username']) && !isset($_POST['submit']) )
{
	include 'themes/dg/login.html';
	exit;
}


