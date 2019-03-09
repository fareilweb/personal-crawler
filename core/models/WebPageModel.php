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

    /** @var int */
    public $UrlList_url_id;

    public function __construct(string $table_name, int $UrlList_url_id = NULL) {
        $this->$UrlList_url_id = $UrlList_url_id;
        parent::__construct($table_name);
    }

    public function SetFromArray(array $data_array) {
        foreach ($data_array as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Get the stored url ID
     * @return int
     */
    public function GetUrlId() : int {
        return $this->UrlList_url_id;
    }

    /**
     * Set an stored url ID
     * @param int
     */
    public function SetUrlId(int $UrlList_url_id) {
        $this->UrlList_url_id = $UrlList_url_id;
    }

    public function __destruct() {
        parent::__destruct();
    }

}
