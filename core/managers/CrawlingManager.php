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

        while (count($this->urlset) > 0) 
        {
            $url = array_splice($this->urlset, 0, 1, NULL);  
            if(UrlHelper::IsValidUrl($url)) {
                $this->ChooseAndRunSchemeHandlerMethod($url);
            }            
        }
                
    }

#region - Content types handlers methods
    private function ChooseAndRunContentTypeHandlerMethod() 
    {
        
    }
    
    
    private function HandleHtmlContent() {
        //$requestResult['curl_getinfo_result'];
        //$requestResult['curl_exec_result'];                     
        //$requestInfoDto = new RequestInfoDto( $curlGetinfoResult );
        //$domDocument = $this->domManager->ConvertStringToDOMDocument( $curlExecResult );        
        //$requestResponseDto = $this->domManager->ExtractDataFromDOMDocument( $domDocument );       
    }
    
#endregion - END OF: Content types handlers methods    
#region - Scheme handlers methods
    
     /**
     * This method try to get scheme of the url and choose the method that can handle
     *
     * @param string $url
     * @return string
     */
    private function ChooseAndRunSchemeHandlerMethod($url): string {
        $url_scheme = UrlHelper::GetUrlScheme($url);
        switch ($url_scheme) {
            case 'http':    $this->HandleHttpSchemeUrl($url);
            case 'https':   $this->HandleHttpsSchemeUrl($url);
            case 'ftp':     $this->HandleFtpSchemeUrl($url);
            case 'ftps':    $this->HandleFtpsSchemeUrl($url);
            case 'sftp':    $this->HandleSftpSchemeUrl($url);
            case 'mailto':  $this->HandleMailtoSchemeUrl($url);
            case 'tel':     $this->HandleTelSchemeUrl($url);
            case 'skype':   $this->HandleSkypeSchemeUrl($url);
            default: $this->HandleHttpSchemeUrl($url);
        }
    }
    private function HandleHttpSchemeUrl($url) {
        $requestResult = $this->httpManager->MakeRequest( $url, $this->params['ignore_redirect'] );  
        return $requestResult;        
    }   
    private function HandleHttpsSchemeUrl($url) {
        $this->HandleHttpSchemeUrl($url);
    }
    private function HandleFtpSchemeUrl($url) {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftp"]);
        return;
    }
    private function HandleFtpsSchemeUrl($url) {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftps"]);
        return;
    }
    private function HandleSftpSchemeUrl($url) {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["sftp"]);
        return;
    }
    private function HandleMailtoSchemeUrl($url) {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["mailto"]);
        return;
    }
    private function HandleTelSchemeUrl($url) {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["tel"]);
        return;
    }
    private function HandleSkypeSchemeUrl($url) {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["skype"]);
        return;
    }
   
#endregion - END OF: Scheme handlers methods
    
}
