<?php
error_reporting(E_ALL);
$dbconn = new mysqli("localhost", "emcchain","ilikeemcchain", "emcchain");
// Check connection
if ($dbconn->connect_error) {
    die("Connection failed: " . $dbconn->connect_error);
}

$dbconn2 = new mysqli("localhost", "emcchain","ilikeemcchain", "emcchain");
// Check connection
if ($dbconn2->connect_error) {
    die("Connection failed: " . $dbconn2->connect_error);
}

require_once '/var/www/neko-blockchain-explorer/tools/include/jsonRPCClient.php';
$neko = new jsonRPCClient('http://nekorpc:DRBKt7KStSXLYKjVCKUwTRJe7hhsppQiHgKMs2pWYADL@127.0.0.1:6262/');
?>
