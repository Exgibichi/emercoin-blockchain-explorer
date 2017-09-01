<?php
error_reporting(E_ALL);
$dbconn = new mysqli("localhost", "emcchain","ilikeemcchain", "emcchain32");
// Check connection
if ($dbconn->connect_error) {
    die("Connection failed: " . $dbconn->connect_error);
} 
include ('/var/www/emercoin-blockchain-explorer/tools/wallet_settings.php');
require_once '/var/www/emercoin-blockchain-explorer/tools/include/jsonRPCClient.php';
$emercoin = new jsonRPCClient('http://emercoinrpc:DRBKt7KStSXLYKjVCKUwTRJe7hhsppQiHgKMs2pWYADL@127.0.0.1:6662/');
?>
