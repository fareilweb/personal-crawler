<?php

class DomManager extends BaseManager
{
    private $default_encoding = "";
    private $default_version = "";


    /**
     * Collect data from dom document
     *
     * @param string
     * @return WebPageDataDto
     */
    public function ExtractDataFromHtml( string $html, CurlRequestInfoDto $info ) : WebPageDataDto {
        $resultsDataDto = new WebPageDataDto($info);

        $domDocument = $this->ConvertStringToDOMDocument($html);

        // Language
        $tagsHtml = $domDocument->getElementsByTagName('html');
        if($tagsHtml->length > 0 && $tagsHtml->item(0)->hasAttribute('lang')) {
            $resultsDataDto->language = $tagsHtml->item(0)->getAttribute('lang');
        }

        return $resultsDataDto;

        // // Title
        // $title = $dom->getElementsByTagName('title');
        // if ($title->length > 0) {
        //     $page->title = $title[0]->textContent;
        // }

        // // H1
        // $h1 = $dom->getElementsByTagName('h1');
        // if ($h1->length > 0) {
        //     $page->h1 = $h1[0]->textContent;
        // }

        // // H2
        // $h2 = $dom->getElementsByTagName('h2');
        // if ($h2->length > 0) {
        //     $page->h2 = $h2[0]->textContent;
        // }

        // // Meta Data
        // $metas = $dom->getElementsByTagName('meta');
        // if ($metas->length > 0) {
        //     foreach ($metas as $k => $elem) {
        //         $name = $elem->getAttribute('name');

        //         // Meta Description
        //         if ($name == 'description')
        //             $page->metadescription = $elem->getAttribute('document_content');

        //         // Meta Keywords
        //         if ($name == 'keywords')
        //             $page->metakeywords = $elem->getAttribute('document_content');
        //     }
        // }

        // // Most Repeated Word
        // $top_word = $this->getDomTopWord($dom);
        // if ($top_word !== FALSE) {
        //     $page->top_word = $top_word;
        // }

    }


    /**
     * Convert a String of a Web Page into a DOMDocument object
     *
     * @param string $document_content - the string with the HTML of the web page
     * @return DOMDocument $dom
     */
    public function ConvertStringToDOMDocument( $document_content ) : DOMDocument
    {
        $dom = new DOMDocument();

        if ( !empty(trim($document_content)) )
        {
            libxml_use_internal_errors(true); // Ignore Doc Type (and others errors)


            // try to load dom document object from document_content string
            $dom->loadHTML($document_content);

            libxml_use_internal_errors(false); // Restore error settings
        }

        return $dom;
    }



    /**
     * Try To Extract All Images From The Page
     *
     * @param DOMDocument $dom
     * @param string $parent_url
     * @return ImagesDto[]
     */
    public function GetImagesFromDom($dom, $parent_url = NULL) {
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
    public function getUriListFromDom($dom, $parent_url = NULL) {
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

    public function getLinksModelsFromDom($dom, $parent_url = NULL) {
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

    /**
     * Get Most Repeated Word From a DomDocument
     *
     * @param DOMDocument $dom
     * @return string $top_word
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



}
