<?php
//Protect api pages with boiler plate code:
if(!isset($_SESSION['username']) && $_SESSION['username'] != "")
{
        header('HTTP/1.1 401');
        die();
}

if( !isset($_GET['mode']) || $_GET['mode'] == "")
{
        header('HTTP/1.1 400');
        die();
}
