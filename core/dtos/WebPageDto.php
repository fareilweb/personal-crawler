<?php

class WebPageDto extends BaseDto {

    /** #var string*/
    public $content_type;

    /** @var int */
    public $id;

    /** @var string */
    public $language;

    /** @var string */
    public $title;

    /** @var array */
    public $headers;

    /** @var array */
    public $meta_data;

    /** @var array */
    public $top_words;

    /** @var DateTime */
    public $insert_date;

    /** @var DateTime */
    public $update_date;

    /** @var DateTime */
    public $url_insert_date;

    /** @var DateTime */
    public $url_update_date;

    /** @var CurlRequestInfoDto */
    public $info;

    public function __construct(CurlRequestInfoDto $info = NULL) {
        if(isset($info)) {
            $this->info = $info;
            $this->content_type = $info->content_type;
        }
    }

    public function __destruct() {
        parent::__destruct();
    }

}
