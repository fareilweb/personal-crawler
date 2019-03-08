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

    public function __construct(string $table_name) {
        parent::__construct($table_name);
    }

    public function __destruct() {
        parent::__destruct();
    }

}
