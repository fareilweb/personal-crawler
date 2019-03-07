<?php

class WebPageDataResultsDto extends DataResultsDto {

    /** @var int */
    public $id;

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

    public function __construct(CurlRequestInfoDto $info = NULL) {
        if(isset($info)) {
            $this->info = $info;
        }
    }

    public function __destruct() {
        parent::__destruct();
    }

}
