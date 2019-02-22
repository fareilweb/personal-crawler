<?php

/**
 * Generic Result Class
 */
class GenericResult {
    public $status;
    public $message;
    public function __construct($status = FALSE, $message = "") {
        $this->status   = $status;
        $this->message  = $message;
    }
}

/**
 * RequestResult class
 * Result data container for HttpHelper methods
 */
class RequestResult {
    public $status;
    public $info;
    public $content;
    public function __construct ($status = NULL, $info = NULL, $content = NULL) {
        $this->status   = $status;
        $this->info     = $info;
        $this->content  = $content;
    }
}

/**
 * HttpHelper class
 * Helper class to make http requests
 */
class HttpHelper
{
    //private $allowed_protocols = array('http', 'https');
    public $user_agent_chrome  = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36';
    public $user_agent_firefox = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0';
    public $url = "";
    public $result = NULL;

    /**
     * Test a Url for Validity
     * @param string $uri
     * @return stdClass { boolean status, string message }
     */
    public function isValidUrl($uri = "") {

        if(empty($uri)) {
            return new GenericResult (FALSE, "EMPTY");
        }

        $uri_parts = parse_url($uri);
        if(!isset($uri_parts['scheme'])) {
            return new GenericResult (FALSE, "NO_SCHEME");
        }
    }

    /**
     * Set the url to use for the next request
     * @param string $url - The url for the next request
     * @return bool
     */
    public function setUrl($url = "") {
        if(!empty($url)) {
            $this->url = $this->escapeUrl($url);
            return TRUE;
        }
        return FALSE;
    }


    public function escapeUrl($uri) {
	$escUrl = "";
	$urlParts = parse_url($uri);
	if (isset($urlParts['scheme'])) {
	    $escUrl .= $urlParts['scheme']."://";
	}
	if(isset($urlParts['host'])) {
	    $escUrl .= $urlParts['host'];
	}
	if(isset($urlParts['path'])) {
	    $escUrl .= str_replace("%2F", "/", urlencode($urlParts['path']));
	}
	if(isset($urlParts['query'])) {
	    $escUrl .= "?" . str_replace("%3D", "=", str_replace("%26", "&", urlencode($urlParts['query'])));
	}
	if(isset($urlParts['fragment'])) {
	    $escUrl .= "#" . urlencode($urlParts['fragment']);
	}
	return $escUrl;
    }


    /**
     * Set the user agent to use for the next request
     * @param string $ua_string - User Agent String
     * @return bool
     */
    public function setUserAgent($ua_string = "") {
        if( !empty($ua_string) ) {
            $this->user_agent = $ua_string;
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Start request with current settings by cURL
     * @param bool $follow_redirect - Follow redirect response or not, default is FALSE
     * @return RequestResult instance that contains: $status, $info, $content
     */
    public function makeRequestCurl($follow_redirect = false) {
        if( empty($this->url) ) {
            return new RequestResult(false, "EMPTY_OR_INVALID_URL");
        } else {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agent_chrome);
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $follow_redirect);
            $curl_response = curl_exec($curl);
            $curl_info = curl_getinfo($curl);
            $this->result = new RequestResult (
                TRUE,
                $curl_info,
                $curl_response
            );
            curl_close($curl);
            return $this->result;
        }
    }

    /**
     * Start request with current settings by file_get_contents()
     * @return RequestResult instance that contains: $status, $info, $content
     */
    public function makeRequestFGC() {
        if( empty($this->url) ) {
            return new RequestResult(FALSE, "EMPTY_OR_INVALID_URL");
        } else {
            $file_get_content_result = file_get_contents($this->url);
            $response_header = $http_response_header;
            $this->result = new RequestResult (
                TRUE,
                $response_header,
                $file_get_content_result
            );
            return $this->result;
        }
    }

}
