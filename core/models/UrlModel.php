<?php
class UrlModel extends BaseModel {

#region cURL Data
    /** @var string */
    public $url;

    /** @var string */
    public $content_type;

    /** @var integer */
    public $http_code;

    /** @var integer */
    public $redirect_count;

    /** @var string */
    public $redirect_url;

    /** @var string */
    public $primary_ip;

    /** @var integer */
    public $primary_port;

#endregion cURL Data

    /** @var boolean */
    public $has_content;

    /** @var string */
    public $content_table_name;

    /** @var DateTime */
    public $insert_timestamp;

    /** @var DateTime */
    public $update_timestamp;


    public function __construct(string $table_name) {
        parent::__construct($table_name);
    }

    public function SetDataFromCurlRequestInfoDto(CurlRequestInfoDto $curlRequestInfoDto) {
        $this->url = $curlRequestInfoDto->url;
        $this->content_type = $curlRequestInfoDto->content_type;
        $this->http_code = $curlRequestInfoDto->http_code;
        $this->redirect_count = $curlRequestInfoDto->redirect_count;
        $this->redirect_url = $curlRequestInfoDto->redirect_url;
        $this->primary_ip = $curlRequestInfoDto->primary_ip;
        $this->primary_port = $curlRequestInfoDto->primary_port;
    }

    public function SetDataFromArray(array $data_array) {
        foreach ($data_array as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function __destruct() {
        parent::__destruct();
    }
}
