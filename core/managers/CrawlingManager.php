<?php

class CrawlingManager extends BaseManager {

    /** @var IStorageManager */
    private $storageManager;

    /** @var HttpManager */
    private $httpManager;

    /** @var LocalizationManager */
    private $localizationManager;

    /** @var DomManager */
    private $domManager;

    /** @var array */
    private $params;

    /** @var string[] */
    private $urlset;

    /**
     * COnstructor of the class
     *
     * @param IStorageManager
     * @param HttpManager
     * @param LocalizationManager
     * @param DomManager
     */
    public function __construct(IStorageManager $storage_manager, HttpManager $http_manager, LocalizationManager $localization_manager, DomManager $dom_manager) {
        // Dependecies
        $this->storageManager = $storage_manager;
        $this->httpManager = $http_manager;
        $this->localizationManager = $localization_manager;
        $this->domManager = $dom_manager;

        // Initializations
        $this->params = [];
        $this->urlset = [];

        parent::__construct();
    }

    /**
     * Start crawling the web
     *
     * @return void
     */
    public function StartCrawling(array $params) {
        // Merge urlset param into local urlset member
        if (isset($params['urlset']) && count($params['urlset']) > 0) {
            $this->urlset = $params['urlset'];
        }

        // Store params
        $this->params = $params;

        while (count($this->urlset) > 0) {
            $url = array_splice($this->urlset, 0, 1, NULL);
            $this->GetAndStoreUrlContent($url[0]);
        }
    }

    private function GetAndStoreUrlContent($url) {
        $handler_method = $this->ChooseHandlerMethod($url);
        if ($handler_method !== NULL) {
            $this->{$handler_method}($url);
        }
    }

    private function HandleHttpUrl($url) {
        $requestResult = $this->httpManager->MakeRequest( $url, $this->params['ignore_redirect'] );

        if( !isset($requestResult) || empty($requestResult) ) 
        {
            // Request fails
            // TODO - Store the results for the url to database

            return;
        }
        
        // Request Success
        $curlGetinfoResult 	= $requestResult['curl_getinfo_result'];        
        $curlExecResult	= $requestResult['curl_exec_result'];
        
        $requestInfoDto = new RequestInfoDto( $curlGetinfoResult );
        $domDocument = $this->domManager->ConvertStringToDOMDocument( $curlExecResult );        
        $requestResponseDto = $this->domManager->ExtractDataFromDOMDocument( $domDocument );
        
        


    }

    private function HandleHttpsUrl($url) {
        $this->HandleHttpUrl($url);
    }

    private function HandleFtpUrl($url) {
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftp"]);
        return;
    }

    private function HandleFtpsUrl($url) {
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftps"]);
        return;
    }

    private function HandleSftpUrl($url) {
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["sftp"]);
        return;
    }

    private function HandleMailtoUrl($url) {
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["mailto"]);
        return;
    }

    private function HandleTelUrl($url) {
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["tel"]);
        return;
    }

    private function HandleSkypeUrl($url) {
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["skype"]);
        return;
    }

    /**
     * This method try to get scheme of the url and choose the method that can handle
     *
     * @param string $url
     * @return string
     */
    private function ChooseHandlerMethod($url): string {
        $url_scheme = UrlHelper::GetUrlScheme($url);
        switch ($url_scheme) {
            case 'http': return "HandleHttpUrl";
            case 'https': return "HandleHttpsUrl";
            case 'ftp': return "HandleFtpUrl";
            case 'ftps': return "HandleFtpsUrl";
            case 'sftp': return "HandleSftpUrl";
            case 'mailto': return "HandleMailtoUrl";
            case 'tel': return "HandleTelUrl";
            case 'skype': return "HandleSkypeUrl";
            default: return "HandleHttpUrl";
        }
    }

}
