<?php

class DomManager extends BaseManager
{
    private $default_encoding = "";
    private $default_version = "";


    /**
     * Convert a String of a Web Page into a DOMDocument object
     *
     * @param string $document_content - the string with the HTML of the web page
     * @return DOMDocument $dom
     */
    public function ConvertStringToDOMDocument($document_content): DOMDocument {
        $dom = new DOMDocument();

        if (!empty(trim($document_content))) {
            libxml_use_internal_errors(true); // Ignore Doc Type (and others errors)
            // try to load dom document object from document_content string
            $dom->loadHTML($document_content);

            libxml_use_internal_errors(false); // Restore error settings
        }

        return $dom;
    }

#region # Data extract methods


    /**
     * Collect data from dom document
     *
     * @param string
     * @return WebPageDto
     */
    public function ExtractDataFromSchemeHandlerResultDto(SchemeHandlerResultDto $sourceDto) : WebPageDto {

        $resultDto = new WebPageDto($sourceDto->info);

        if(!isset($sourceDto->content) || empty($sourceDto->content)) {
            return $resultDto;
        }

        $domDocument = $this->ConvertStringToDOMDocument($sourceDto->content);
        if (!isset($domDocument) || empty($domDocument)) {
            return $resultDto;
        }

        // Language
        $resultDto->language = $this->GetWebPageLanguage($domDocument);

        // Title
        $resultDto->title = $this->GetWebPageTitle($domDocument);

        // Headers
        $resultDto->headers = $this->GetHeadersContent($domDocument);

        // Meta Data
        $resultDto->meta_data = $this->GetMetaData($domDocument);

        // Most Repeated Word
        $resultDto->top_words = $this->GetMostRepeatedWords($domDocument);



        return $resultDto;
    }

    public function FindByAttribute(DOMNodeList $domNodeList, string $attributeName, string $attributeValue = NULL) : array {
        $found_elements = [];
        foreach ($domNodeList as $item) {
            if($item->hasAttribute($attributeName)  && ($attributeValue == NULL || $attributeValue == $item->getAttribute($attributeName))) {
                array_push($found_elements, $item);
            }
        }
        return $found_elements;
    }
    public function FindFirstByAttribute(DOMNodeList $domNodeList, string $attributeName, string $attributeValue = NULL) {
        $founds = $this->FindByAttribute($domNodeList, $attributeName, $attributeValue);
        if($founds == FALSE || count($founds) == 0) {
            return FALSE;
        }
        return $founds[0];
    }

    /**
     * Get Most Repeated Word From a DOMDocument object
     *
     * @param DOMDocument $domDocument
     * @param int
     * @return string[]
     */
    public function GetMostRepeatedWords(DOMDocument $domDocument, int $howManyWords = NULL) : array {
        $topWords = [];

        // Get Body Content
        $body = $domDocument->getElementsByTagName('body');

        $bodyAsString = "";
        if ($body->length > 0) {
            $bodyAsString = $body[0]->textContent;
        }

        $allWords = array_filter(
            str_word_count($bodyAsString, 2), function($word) {
                return strlen($word) >= 4;
            }
        );

        if (count($allWords) === 0) { return FALSE; }

        $wordsCount = array_count_values($allWords);
        if (count($wordsCount) === 0) { return FALSE; }

        arsort($wordsCount);

        $howManyWords = isset($howManyWords) ? $howManyWords : 20;
        $topWords = array_slice($wordsCount, 0, $howManyWords, true);

        return $topWords;
    }



    public function GetMetaData(DOMDocument $domDocument) : array {
        $metaData = [];
        $metaTags = $domDocument->getElementsByTagName('meta');
        if ($metaTags->length > 0) {
            foreach ($metaTags as $k => $elem) {
                $name       = $elem->getAttribute('name');
                $content    = $elem->getAttribute('content');
                array_push($metaData, ['name' => $name, 'content' => $content]);
            }
        }
        return $metaData;
    }

    public function GetHeadersContent(DOMDocument $domDocument) : array {
        $hxs = [ 'h1' => [], 'h2' => [], 'h3' => [], 'h4' => [], 'h5' => [], 'h6' => [] ];
        foreach ($hxs as $key => $value) {
            $hx = $domDocument->getElementsByTagName($key);
            if (!empty($hx) && $hx->length > 0) {
                foreach ($hx as $hxValue) {
                    array_push($hxs[$key], $hxValue->textContent);
                }
            }
        }
        return $hxs;
    }

    public function GetWebPageTitle(DOMDocument $domDocument) {
        $title = "";
        $titleTags = $domDocument->getElementsByTagName('title');
        if(!empty($titleTags) && $titleTags->length > 0) {
            $title = $titleTags[0]->textContent;
        }
        return $title;
    }

    public function GetWebPageLanguage(DOMDocument $domDocument) {
        $lang = "";

        // Search language in HTML tag
        $htmlTags = $domDocument->getElementsByTagName('html');
        if ($htmlTags->length > 0 && $htmlTags->item(0)->hasAttribute('lang')) {
            $lang = $htmlTags->item(0)->getAttribute('lang');
            if(!empty($lang)) { return $lang; }
        }

        // Search language in META tags
        $metaTags = $domDocument->getElementsByTagName('meta');
        if ($metaTags->length > 0) {
            $found = $this->FindFirstByAttribute($metaTags, "http-equiv", "Content-Language");
            if($found && $found->hasAttribute("content")) {
                $lang = $found->getAttribute("content");
                if(!empty($lang)) { return $lang; }
            }
        }

        return $lang;
    }




#endregion # Data extract methods




    /**
     * Try To Extract All Images From The Page
     *
     * @param DOMDocument $dom
     * @param string $parent_url
     * @return ImagesDto[]
     */
    public function old__getImagesFromDom($dom, $parent_url = NULL) {
        $imgList = array();
        $imgElements = $dom->getElementsByTagName('img');
        foreach ($imgElements as $img) {

            $src    = $img->getAttribute('src');
            $title  = $img->getAttribute('title');
            $alt    = $img->getAttribute('alt');

            array_push($imgList, new ImageDto(
                $src,
                $title,
                $alt
            ));
        }
        return $imgList;
    }
    /**
     * Try To Extract All Uris From The Page
     *
     * @param DOMDocument $dom
     * @param string $parent_url
     * @return void
     */
    public function old__getUriListFromDom($dom, $parent_url = NULL) {
        $urlList = array(); // The container list that will be returned
        $aElements = $dom->getElementsByTagName('a'); // Get all "a" from the dom
        foreach ($aElements as $a) {
            if ($a->hasAttribute('href')) {
                $url = $a->getAttribute('href');
                $valid_url = $this->validateUri($url, $parent_url);
                if (!empty($valid_url)) {
                    array_push($urlList, $valid_url);
                }
            }
        }
        return $urlList;
    }
    public function old__getLinksModelsFromDom($dom, $parent_url = NULL) {
        $urlList = array();
        $aElements = $dom->getElementsByTagName('a');
        foreach ($aElements as $a) {
            $aObj = new LinkModel(); // Get Link Model
            $aObj->url = $a->getAttribute('href');
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

}
