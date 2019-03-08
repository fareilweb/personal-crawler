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
    public $insert_date;

    /** @var DateTime */
    public $update_date;

    /** Curl request Info */

    /** @var string */
    public $info_url;

    /** @var integer */
    public $info_http_code;

    /** @var string */
    public $info_content_type;

    /** @var integer */
    public $info_redirect_count;

    /** @var string */
    public $info_redirect_url;

    /** @var string */
    public $info_primary_ip;

    /** @var integer */
    public $info_primary_port;


    public function __construct(string $table_name) {
        parent::__construct($table_name);
    }

    public function SetInfoFromCurlRequestInfoDto(CurlRequestInfoDto $curlRequestInfoDto) {
        $this->info_url = $curlRequestInfoDto->url;
        $this->info_content_type = $curlRequestInfoDto->content_type;
        $this->info_http_code = $curlRequestInfoDto->http_code;
        $this->info_redirect_count = $curlRequestInfoDto->redirect_count;
        $this->info_redirect_url = $curlRequestInfoDto->redirect_url;
        $this->info_primary_ip = $curlRequestInfoDto->primary_ip;
        $this->info_primary_port = $curlRequestInfoDto->primary_port;
    }

    public function __destruct() {
        parent::__destruct();
    }

}
