<?php
class SchemeHandlerResultDto extends BaseDto {

    /** @var CurlRequestInfoDto */
    public $info;

    /** @var string */
    public $content;

    public function __construct(CurlRequestInfoDto $info = NULL, string $content = NULL) {
        $this->info = $info;
        $this->content = $content;
    }

    public function __destruct() {
        parent::__destruct();
    }
}
