<?php 



require ('dependencies.php');


function escapeUrl($uri) {
	$escUrl = "";	
	$urlParts = parse_url($uri);	
	if (isset($urlParts['scheme'])) {
	    $escUrl .= $urlParts['scheme']."://";
	}	
	if(isset($urlParts['host'])) {
	    $escUrl .= $urlParts['host'];
	}
	if(isset($urlParts['path'])) {
	    $escUrl .= str_replace("%2F", "/", urlencode($urlParts['path']));
	}
	if(isset($urlParts['query'])) {
	    $escUrl .= "?" . str_replace("%3D", "=", str_replace("%26", "&", urlencode($urlParts['query'])));
	}
	if(isset($urlParts['fragment'])) {	
	    $escUrl .= "#" . urlencode($urlParts['fragment']);
	}
	return $escUrl;
    }

    
$url  = "http://www.fareilweb.com/ciao/sono/una directory/unfile.php?query1=ciao&query2=ariciao";
echo escapeUrl($url);