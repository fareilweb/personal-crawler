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


    public function __construct($table_name) {
        parent::__construct($table_name);
    }

    public function SetInfoFromCurlRequestInfoDto(CurlRequestInfoDto $curlRequestInfoDto) {
        $this->url = $curlRequestInfoDto->url;
        $this->content_type = $curlRequestInfoDto->content_type;
        $this->http_code = $curlRequestInfoDto->http_code;
        $this->redirect_count = $curlRequestInfoDto->redirect_count;
        $this->redirect_url = $curlRequestInfoDto->redirect_url;
        $this->primary_ip = $curlRequestInfoDto->primary_ip;
        $this->primary_port = $curlRequestInfoDto->primary_port;
    }

    public function SetDataFromArray(array $data_array) {
        /* cURL Data */
        $this->id = $data_array['id'];
        $this->url = $data_array['url'];
        $this->content_type = $data_array['content_type'];
        $this->http_code = $data_array['http_code'];
        $this->redirect_count = $data_array['redirect_count'];
        $this->redirect_url = $data_array['redirect_url'];
        $this->primary_ip = $data_array['primary_ip'];
        $this->primary_port = $data_array['primary_port'];
        /* cURL Data END */

        $this->has_content = $data_array['has_content'];
        $this->content_table_name = $data_array['content_table_name'];
        $this->insert_timestamp = $data_array['insert_timestamp'];
        $this->update_timestamp = $data_array['update_timestamp'];
    }

    public function __destruct() {
        parent::__destruct();
    }
}
