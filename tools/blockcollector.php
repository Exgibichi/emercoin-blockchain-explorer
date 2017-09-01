<?php
while(true) {
// sleep 10 sec and run again
sleep(10);
exec('php /var/www/emercoin-blockchain-explorer/tools/get_blocks.php');
}
?>
