<?php

include 'config.php';
include 'libraries/bookScan.php';

$bs = new bookScan();
$bs->syncBooks('repository');
