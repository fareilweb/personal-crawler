<?php

require_once (__DIR__ . '/core/AppAutoloader.php');

function GetUrlAndStoreToFolder($document_url, $file_name) {
    $httpManager = new HttpManager();
    $domManager = new DomManager();
    $request_result = $httpManager->MakeCurlHttpRequest($document_url, FALSE);
    $file_path = __DIR__ . DIRECTORY_SEPARATOR . 'test_files' . DIRECTORY_SEPARATOR . $file_name;
    file_put_contents($file_path, $request_result->content);
}

$jail_set = ["https://www.fareilweb.com/blabla/ciao", "https://www.google.com", "www.miodominio.com", "miodominio.com"];
$host_to_test = "www.miodominio.com";

function IsHostInUrlDomains($host_to_test, $url_set) {
    foreach ($url_set as $jail_url) {
        $jail_url_parsed = parse_url($jail_url);
        if ($host_to_test === $jail_url_parsed['host'] || $host_to_test === $jail_url_parsed['path']) {
            return TRUE;
        }
    }
    return FALSE;
}

var_dump($res);

exit;





