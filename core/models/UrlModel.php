<?php
class UrlModel extends BaseModel {

#region cURL Data
    /** @var string */
    public $url;

    /** @var string */
    public $curl_url;

    /** @var string */
    public $curl_content_type;

    /** @var integer */
    public $curl_http_code;

    /** @var integer */
    public $curl_redirect_count;

    /** @var string */
    public $curl_redirect_url;

    /** @var string */
    public $curl_primary_ip;

    /** @var integer */
    public $curl_primary_port;

#endregion cURL Data

    /** @var boolean */
    public $has_content;

    /** @var string */
    public $content_table_name;

    /** @var DateTime */
    public $insert_timestamp;

    /** @var DateTime */
    public $update_timestamp;


    public function __construct(string $table_name, $url = NULL) {
        $this->url = $url;
        parent::__construct($table_name);
    }

    public function SetDataFromArray(array $data_array) {
        foreach ($data_array as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function SetDataFromCurlRequestInfoDto(CurlRequestInfoDto $curlRequestInfoDto) {
        $curl_prefix = "curl_";
        foreach ($curlRequestInfoDto as $key => $value) {
            $property_name = $curl_prefix . $key;
            if(property_exists($this, $property_name)) {
                $this->{$property_name} = $value;
            }
        }

//        $this->curl_url = $curlRequestInfoDto->url;
//        $this->curl_content_type = $curlRequestInfoDto->content_type;
//        $this->curl_http_code = $curlRequestInfoDto->http_code;
//        $this->curl_redirect_count = $curlRequestInfoDto->redirect_count;
//        $this->curl_redirect_url = $curlRequestInfoDto->redirect_url;
//        $this->curl_primary_ip = $curlRequestInfoDto->primary_ip;
//        $this->curl_primary_port = $curlRequestInfoDto->primary_port;
    }

    public function __destruct() {
        parent::__destruct();
    }
}
