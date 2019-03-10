<?php
class UrlModel extends BaseModel {

    /** @var string */
    public $url;

    /** @var boolean */
    public $has_content;

    /** @var string */
    public $content_table_name;

    /** @var string */
    public $inferred_content_type;

    /** @var DateTime */
    public $insert_timestamp;

    /** @var DateTime */
    public $update_timestamp;

#region cURL Data

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


    public function __construct(string $table_name, $url = NULL) {
        $this->url = $url;
        parent::__construct($table_name);
    }

    public function SetInferredContentType(string $content_type){
        $this->inferred_content_type = $content_type;
    }

    public function SetDataFromArray(array $data_array) {
        foreach ($data_array as $key => $value) {
            // The control for exclude "url" is for avoid conflict
            // with curl "url" VS the one set by constructor
            if($key !== "url" && property_exists($this, $key)) {
                $this->{$key} = $value; // General Data
            } else if(property_exists($this, "curl_".$key)) {
                $this->{"curl_".$key} = $value; // cURL data is stored with a special prefix
            }
        }
    }

    public function __destruct() {
        parent::__destruct();
    }
}
