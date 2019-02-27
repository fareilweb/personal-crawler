<?php
include_once (__DIR__ . '/core/AppAutoloader.php');

$http = new HttpManager();
$dom = new DomManager();

$request_result = $http->MakeRequest("https://www.fareilweb.com", TRUE);
