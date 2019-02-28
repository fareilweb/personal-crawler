<?php
require (__DIR__ . '/dependencies.php');

// Remove first argoument (script name)
unset($argv[0]);

if (count($argv) === 0) {
    echo Lang::$crawler_start_no_args;
} else {

    // Get Crawler Instance
    $crawler = new Crawler(
    	new HttpHelper(),
    	new DomParser(),
    	new StorageMySQL()
    );

    
    $crawler->init($argv);
}
