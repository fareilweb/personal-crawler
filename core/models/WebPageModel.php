<?php

class WebPageModel extends BaseModel {

    /** @var string */
    public $language;

    /** @var string */
    public $title;

    /** @var string */
    public $h1;

    /** @var string */
    public $h2;

    /** @var string */
    public $h3;

    /** @var string */
    public $h4;

    /** @var string */
    public $h5;

    /** @var string */
    public $h6;

    /** @var string */
    public $meta_keywords;

    /** @var string */
    public $meta_description;

    /** @var string */
    public $top_words;

    /** @var DateTime */
    public $insert_timestamp;

    /** @var DateTime */
    public $update_timestamp;

    /** @var UrlModel */
    public $url;

    public function __construct(string $table_name, UrlModel $url = NULL) {
        $this->url = $url;
        parent::__construct($table_name);
    }

    /**
     * Get the UrlModel instance stored into WebPageModel::$url property
     * @return \UrlModel
     */
    public function GetUrl() : UrlModel {
        return $this->url;
    }

    /**
     * Set an instance of UrlModel as a "navigation property" of the current WebPageModel
     * @param UrlModel $url
     */
    public function SetUrl(UrlModel $url) {
        $this->url = $url;
    }

    public function __destruct() {
        parent::__destruct();
    }

}
