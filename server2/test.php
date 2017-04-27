<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit','256M');

require_once('twitterInterface.php');

function test() {
    $interface = new TwitterInterface();
    $interface->getTweets("Theresa May", 120);


    echo '<pre>';
    print_r($interface->data);
    echo '</pre>';
}
//test();
?>
