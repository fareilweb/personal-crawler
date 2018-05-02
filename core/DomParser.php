<?php

class DomParser {

    private $app_uri_schemes = [
	'mailto', 'tel', 'skype'
    ];

    /**
     * Convert a String of a Web Page into a DOMDocument object
     *
     * @param [string] $string
     * @return [DOMDocument] $dom
     */
    public function stringToDom($string) {
	$dom = new DOMDocument();
	if (!empty($string)) {
	    libxml_use_internal_errors(true); // Ignore Doc Type (and others errors)
	    $dom->loadHTML($string);
	    libxml_use_internal_errors(false); // Restore error settings
	}
	return $dom;
    }

    /**
     * Analize and try to Build a Valid Uri
     *
     * @param [string] $uri
     * @param [string] $parent_uri
     * @return [string] $valid_uri
     */
    public function validateUri($uri, $parent_uri) {
	$valid_uri = "";
	$parent_parts = parse_url($parent_uri);
	$parts = parse_url($uri);

	// Exclude Schemes for Emails, Phones, Ecc...
	if (isset($parts['scheme']) && isset($parts['path']) && in_array($parts['scheme'], $this->app_uri_schemes)) {
	    return $uri;
	}

	// Should Be a Web Uri to a Document
	$valid_uri .= isset($parts['scheme']) ? $parts['scheme'] . "://" : (isset($parent_parts['scheme']) ? $parent_parts['scheme'] . "://" : "");
	$valid_uri .= isset($parts['host']) ? $parts['host'] : (isset($parent_parts['host']) ? $parent_parts['host'] : "");
	$valid_uri .= isset($parts['path']) ? $parts['path'] : (isset($parent_parts['path']) ? $parent_parts['path'] : "");
	$valid_uri .= isset($parts['query']) ? "?" . $parts['query'] : ""; // ?
	$valid_uri .= isset($parts['fragment']) ? "#" . $parts['fragment'] : ""; // #

	return $valid_uri;
    }

    /**
     * Try To Extract All Images From The Page
     *
     * @param [type] $dom
     * @param [type] $parent_uri
     * @return [array[ImagesModel]]
     */
    public function getImagesFromDom($dom, $parent_uri = NULL) {
	$imgList = array();
	$imgElements = $dom->getElementsByTagName('img');
	foreach ($imgElements as $img) {
	    $imgObj = new ImageModel();

	    $imgObj->src = $this->validateUri($img->getAttribute('src'), $parent_uri);
	    $imgObj->title = $img->getAttribute('title');
	    $imgObj->alt = $img->getAttribute('alt');

	    array_push($imgList, $imgObj);
	}
	return $imgList;
    }

    /**
     * Try To Extract All Uris From The Page
     *
     * @param [type] $dom
     * @param [type] $parent_uri
     * @return void
     */
    public function getUriListFromDom($dom, $parent_uri = NULL) {
	$urlList = array(); // The container list that will be returned
	$aElements = $dom->getElementsByTagName('a'); // Get all "a" from the dom
	foreach ($aElements as $a) {
	    if ($a->hasAttribute('href')) {
		$uri = $a->getAttribute('href');
		$valid_uri = $this->validateUri($uri, $parent_uri);
		if (!empty($valid_uri)) {
		    array_push($urlList, $valid_uri);
		}
	    }
	}
	return $urlList;
    }

    public function getLinksModelsFromDom($dom, $parent_uri = NULL) {
	$urlList = array();
	$aElements = $dom->getElementsByTagName('a');
	foreach ($aElements as $a) {
	    $aObj = new LinkModel(); // Get Link Model
	    $aObj->uri = $a->getAttribute('href');
	    $aObj->nodeValue = $a->nodeValue;
	    $aObj->textContent = $a->textContent;

	    $aObj->attributes = array();
	    foreach ($a->attributes as $attr) {
		$attrObj = new LinkAttributeModel(); // Get Link Attributes Model
		$attrObj->name = $attr->name;
		$attrObj->value = $attr->value;
		array_push($aObj->attributes, $attrObj);
	    }
	    array_push($urlList, $aObj);
	}
	return $urlList;
    }

    /**
     * Get Most Repeated Word From a DomDocument
     *
     * @param [DOMDocument] $dom
     * @return [string] $top_word
     */
    public function getDomTopWord($dom = NULL) {
	if (empty($dom))
	    return FALSE;

	// Get Body Content
	$body = $dom->getElementsByTagName('body');
	$body_string = "";
	if ($body->length > 0) {
	    $body_string = $body[0]->textContent;
	}

	$words = array_filter(
		str_word_count($body_string, 2), function($word) {
	    return strlen($word) >= 4;
	}
	);

	if (count($words) === 0)
	    return FALSE;

	$words_count = array_count_values($words);
	if (count($words_count) === 0)
	    return FALSE;

	$max_value = max($words_count);
	if ($max_value === FALSE)
	    return FALSE;

	$top_word = array_search($max_value, $words_count);
	if ($top_word === FALSE)
	    return FALSE;

	return $top_word;
    }

    /**
     * Collect Data From Dom Document and Populate WebPageModel
     *
     * @param [DOMDocument] $dom
     * @return [WebPageModel] $page
     */
    public function domToPageModel($dom) {
	// Get model instance
	$page = new WebPageModel();

	// Language
	$html = $dom->getElementsByTagName('html');
	if ($html->length > 0 && $html->item(0)->hasAttribute('lang')) {
	    $page->lang = $html->item(0)->getAttribute('lang');
	}

	// Title
	$title = $dom->getElementsByTagName('title');
	if ($title->length > 0) {
	    $page->title = $title[0]->textContent;
	}

	// H1
	$h1 = $dom->getElementsByTagName('h1');
	if ($h1->length > 0) {
	    $page->h1 = $h1[0]->textContent;
	}

	// H2
	$h2 = $dom->getElementsByTagName('h2');
	if ($h2->length > 0) {
	    $page->h2 = $h2[0]->textContent;
	}

	// Meta Data
	$metas = $dom->getElementsByTagName('meta');
	if ($metas->length > 0) {
	    foreach ($metas as $k => $elem) {
		$name = $elem->getAttribute('name');

		// Meta Description
		if ($name == 'description')
		    $page->metadescription = $elem->getAttribute('content');

		// Meta Keywords
		if ($name == 'keywords')
		    $page->metakeywords = $elem->getAttribute('content');
	    }
	}

	// Most Repeated Word
	$top_word = $this->getDomTopWord($dom);
	if ($top_word !== FALSE) {
	    $page->top_word = $top_word;
	}

	return $page;
    }

}
