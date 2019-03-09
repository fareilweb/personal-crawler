<?php
class SchemeHandlerResultDto extends BaseDto {

    /** @var CurlRequestInfoDto */
    public $curl_info;

    /** @var string */
    public $content;

    public function __construct(CurlRequestInfoDto $curl_info = NULL, string $content = NULL) {
        $this->curl_info = $curl_info;
        $this->content = $content;
    }

    public function __destruct() {
        parent::__destruct();
    }
}
