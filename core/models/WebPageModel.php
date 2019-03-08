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

    /** @var array */
    public $meta_keywords;

    /** @var string */
    public $meta_description;

    /** @var array */
    public $top_words;

    /** @var DateTime */
    public $insert_date;

    /** @var DateTime */
    public $update_date;

    /** @var CurlRequestInfoDto */
    public $info;

    public function __construct() {
        $this->table = BaseModel::$Tables['web_pages'];
    }

    public function FromDto() {

    }

    public function __destruct() {
        parent::__destruct();
    }

}
