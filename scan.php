<?php

include 'config.php';
include 'libraries/bookScan.php';

$bs = new bookScan();
$bs->syncBooks('repository/R. A. Salvatore Audiobook Collection 1 to 28');
