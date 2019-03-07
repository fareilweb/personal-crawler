<?php

class CurlRequestInfoDto extends BaseDto {

    /** @var string */
    public $url;

    /** @var string */
    public $content_type;

    /** @var integer */
    public $http_code;

    /** @var integer */
    public $header_size;

    /** @var integer */
    public $request_size;

    /** @var integer */
    public $filetime;

    /** @var integer */
    public $ssl_verify_result;

    /** @var integer */
    public $redirect_count;

    /** @var float */
    public $total_time;

    /** @var float */
    public $namelookup_time;

    /** @var float */
    public $connect_time;

    /** @var float */
    public $pretransfer_time;

    /** @var float */
    public $size_upload;

    /** @var float */
    public $size_download;

    /** @var float */
    public $speed_download;

    /** @var float */
    public $speed_upload;

    /** @var float */
    public $download_content_length;

    /** @var float */
    public $upload_content_length;

    /** @var float */
    public $starttransfer_time;

    /** @var float */
    public $redirect_time;

    /** @var string */
    public $redirect_url;

    /** @var string */
    public $primary_ip;

    /** @var array */
    public $certinfo;

    /** @var integer */
    public $primary_port;

    /** @var string */
    public $local_ip;

    /** @var integer */
    public $local_port;

    public function __construct(array $curl_getinfo_result = NULL) {
        if (isset($curl_getinfo_result) && is_array($curl_getinfo_result)) {
            $this->FromCurlGetInfoResult($curl_getinfo_result);
        }
    }

    public function FromCurlGetInfoResult(array $curl_getinfo_result) {
        foreach ($curl_getinfo_result as $property_name => $property_value) {
            if(property_exists($this, $property_name) ) {
                $this->{$property_name} = $property_value;
            }
        }
    }

    public function __destruct() {
        parent::__destruct();
    }

}
