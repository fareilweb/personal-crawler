<?php

class WebPageModel {

    public $id;
    public $uri;
    public $lang;
    public $title;
    public $h1;
    public $h2;
    public $metakeywords;
    public $metadescription;
    public $top_word;
    public $response_code;
    public $timestamp;

    public function __construct($uri = "") {
        if (!empty($uri)) {
            $this->uri = $uri;
        }
    }

    public function loadFromObject($web_page_obj) {
        foreach ($web_page_obj as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
        return TRUE;
    }

}
