<?php

require_once (__DIR__ . '/core/AppAutoloader.php');

/*------------------------------------------------------------------------------
 * App Bootstrap as Crawl
 *------------------------------------------------------------------------------*/
$pc = new PersonalCrawler
(
	new HttpManager()
);


$pc->Initialize ( $argv );
$pc->Crawl();
