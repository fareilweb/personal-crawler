<?php

class RequestInfoDto
{

    /** @var string */ public $url;
    /** @var string */ public $content_type;
    /** @var int */ public $http_code;
    /** @var int */ public $header_size;
    /** @var int */ public $request_size;
    /** @var int */ public $filetime;
    /** @var int */ public $ssl_verify_result;
    /** @var int */ public $redirect_count;
    /** @var int */ public $total_time;
    /** @var int */ public $namelookup_time;
    /** @var int */ public $connect_time;
    /** @var int */ public $pretransfer_time;
    /** @var int */ public $size_upload;
    /** @var int */ public $size_download;
    /** @var int */ public $speed_download;
    /** @var int */ public $speed_upload;
    /** @var int */ public $download_content_length;
    /** @var int */ public $upload_content_length;
    /** @var int */ public $starttransfer_time;
    /** @var int */ public $redirect_time;
    /** @var string */ public $redirect_url;
    /** @var string */ public $primary_ip;
    /** @var array */ public $certinfo;
    /** @var int */ public $primary_port;
    /** @var string */ public $local_ip;
    /** @var int */ public $local_port;

    /**
     * Constructor of the class RequestInfoDto. Take as input the array returned by function: "curl_getinfo()" during a curl execution and store data
     *
     * @param array $curl_getinfo_result - the curl_getinfo() returned value
     */
    public function __construct(array $curl_getinfo_result)
    {
        if (is_array($curl_getinfo_result) && count($curl_getinfo_result) > 0) {
            foreach ($curl_getinfo_result as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }


    public function __destruct()
    {
        foreach (get_object_vars($this) as $key => $val) {
            unset($this->{$key});
        }
    }
}
