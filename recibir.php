<?php 

$uniqid = uniqid();
file_put_contents("$uniqid.txt", print_r($_REQUEST, true));

?>