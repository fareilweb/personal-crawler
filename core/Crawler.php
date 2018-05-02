<?php
/**
 * Personal Crawler Main Class
 */
class Crawler {

    public $input_uriset = array();
    public $working_uriset = array();
    
    private $httpHelper;
    private $domParser;
    private $storage;
    
    public function __construct($_httpHelper, $_domParser, $_storage) { 
        $this->httpHelper       = $_httpHelper;
        $this->domParser        = $_domParser;
        $this->storage          = $_storage;   
    }
    
    public function printMessage($text = "") {
        if(empty($text)) {
            return FALSE;
        } else {            
            echo 
            NEW_LINE . NEW_LINE . 
            "####################" . NEW_LINE . 
            "# Crawler Message: #" . NEW_LINE .
            "####################" . NEW_LINE .
            ">>> " . $text . NEW_LINE . NEW_LINE;            
            return TRUE;
        }
    }


    /**
     * Initialize/Start Program
     * @param [array] $uri_list
     * @return [void]
     */
    public function init($uri_list = array()) {
        if (!empty($uri_list)) {
            $this->input_uriset = $uri_list;
            $this->working_uriset = array_merge($this->working_uriset, $uri_list);
            // Start Crawling Loop
            while (count($this->working_uriset)) {
                $uri = array_shift($this->working_uriset);
		
		
		
                $this->elaborateWebDocument($uri);

                if( count($this->working_uriset) == 0 ) { 
                    $this->reInit(); 
                }
            }
        }
    }


    /**
     * Reload Uri List From Storage
     * @return [void]
     */
    public function reInit() {
        $this->init($this->input_uriset);
    }

    
    /**
     * Elaborate Web Document Content
     * @param [string] $uri
     * @return [void]
     */
    public function elaborateWebDocument($uri) {
        // Get Web Page/Dom
        $this->httpHelper->setUrl($uri);
        $httpRequestResult = $this->httpHelper->makeRequestCurl(true);
        $content_type = $httpRequestResult->info['content_type'];
        if(strpos($content_type, 'text/html') !== false) {
            $this->elaborateWebPage($httpRequestResult);
        } else {
            //... skip ...
        }
    }


    /**
     * Elaborate Web Page (text/html)
     * @return [void]
     */
    public function elaborateWebPage($httpRequestResult) {
        $uri = $httpRequestResult->info['url'];
        $pageDom = $this->domParser->stringToDom($httpRequestResult->content);

        // Get Uris From Page
        $uriList = $this->domParser->getUriListFromDom($pageDom, $uri);        
        if ($uriList !== FALSE) {
            $this->working_uriset = array_merge($this->working_uriset, $uriList);
        }
 
        //$images = $this->domParser->getImagesFromDom($pageDom, $uri);

        // Populate OnLine WebPageModel
        $WebPage_online = $this->domParser->domToPageModel($pageDom);
        $WebPage_online->uri = $uri;
        $WebPage_online->response_code = $httpRequestResult->info['http_code'];
        $WebPage_online->timestamp = time();
       
        // Get / Update / Insert
        $WebPage_stored = $this->storage->findWebPageByUri($WebPage_online);                
        if($WebPage_stored) {        
            $diff = time() - Config::$uri_max_life_time;

            if (empty($WebPage_stored->timestamp) || $WebPage_stored->timestamp < $diff) {
                
                $WebPage_online->id = $WebPage_stored->id;
                $updateResult = $this->storage->updateWebPage($WebPage_online);

                if($updateResult) {
                    $this->printMessage(Lang::$updated . " >>> $uri");                    
                } else {
                    $this->printMessage(Lang::$update_failed . " >>> $uri");                    
                }

            } else {
                $this->printMessage(Lang::$skipped . " >>> $uri");
            }       

        } else {
            $insertResult = $this->storage->insertWebPage($WebPage_online);
            if($insertResult) {
                $this->printMessage(Lang::$inserted . " >>> With ID: $insertResult >>> $uri");                    
            } else {
                $this->printMessage(Lang::$insert_failed . " >>> $uri");                    
            }
        }
    }


    /**
     * Elaborate File
     * @param [string] $uri
     * @return [void]
     */
    function elaborateFile($uri) {
        
    }
        
        
    /**
     * Elaborate EMail
     * @param [string] $uri
     * @return [void]
     */
    function elaborateMail($uri) {

    }


    /**
     * Elaborate Phone
     * @param [string] $uri
     * @return [void]
     */
    function elaboratePhone($uri) {

    }

        
}

