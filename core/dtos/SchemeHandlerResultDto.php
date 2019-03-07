<?php
class SchemeHandlerResultDto extends BaseDto {

    /** @var array */
    public $info;

    /** @var string */
    public $content_type;

    /** @var string */
    public $content;

    public function __construct(array $info = NULL, string $content_type = NULL, string $content = NULL) {
        $this->info         = $info;
        $this->content_type = $content_type;
        $this->content      = $content;
    }

    public function __destruct() {
        parent::__destruct();
    }
}
