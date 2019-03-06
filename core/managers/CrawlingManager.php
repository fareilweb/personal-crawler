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
        $this->params = NULL;
        $this->urlset = NULL;

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
            $url = array_splice($this->urlset, 0, 1, NULL)[0];
            if(UrlHelper::IsValidUrl($url)) {

                $handledResult = $this->ChooseAndRunSchemeHandlerMethod($url);

                $this->ChooseAndRunContentTypeHandlerMethod($handledResult);

            } else {
                //TODO
            }
        }

    }

#region - Content types handlers methods
    private function ChooseAndRunContentTypeHandlerMethod($handledResult)
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
     * @return array
     */
    private function ChooseAndRunSchemeHandlerMethod($url): SchemeHandlerResultDto {
        $url_scheme = UrlHelper::GetUrlScheme($url);
        switch ($url_scheme) {
            case UrlHelper::$UrlSchemes['http'] :   return $this->HandleHttpSchemeUrl($url);
            case UrlHelper::$UrlSchemes['https']:   return $this->HandleHttpsSchemeUrl($url);
            case UrlHelper::$UrlSchemes['ftp']:     return $this->HandleFtpSchemeUrl($url);
            case UrlHelper::$UrlSchemes['ftps']:    return $this->HandleFtpsSchemeUrl($url);
            case UrlHelper::$UrlSchemes['sftp']:    return $this->HandleSftpSchemeUrl($url);
            case UrlHelper::$UrlSchemes['mailto']:  return $this->HandleMailtoSchemeUrl($url);
            case UrlHelper::$UrlSchemes['tel']:     return $this->HandleTelSchemeUrl($url);
            case UrlHelper::$UrlSchemes['skype']:   return $this->HandleSkypeSchemeUrl($url);
            default:
                return $this->HandleHttpSchemeUrl($url);
        }
    }
    private function HandleHttpSchemeUrl($url) : SchemeHandlerResultDto {
        $requestResult = $this->httpManager->MakeCurlRequest( $url, $this->params['ignore_redirect'] );

        $info = $requestResult['curl_getinfo_result'];
        $content_type = $requestResult['curl_getinfo_result']['content_type'];
        $content = $requestResult['curl_exec_result'];

        return new SchemeHandlerResultDto($info, $content_type, $content);
    }
    private function HandleHttpsSchemeUrl($url) : SchemeHandlerResultDto {
        return $this->HandleHttpSchemeUrl($url);
    }
    private function HandleFtpSchemeUrl($url) : SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftp"]);
        return new SchemeHandlerResultDto();
    }
    private function HandleFtpsSchemeUrl($url) : SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftps"]);
        return new SchemeHandlerResultDto();
    }
    private function HandleSftpSchemeUrl($url) : SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["sftp"]);
        return new SchemeHandlerResultDto();
    }
    private function HandleMailtoSchemeUrl($url) : SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["mailto"]);
        return new SchemeHandlerResultDto();
    }
    private function HandleTelSchemeUrl($url) : SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["tel"]);
        return new SchemeHandlerResultDto();
    }
    private function HandleSkypeSchemeUrl($url) : SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["skype"]);
        return new SchemeHandlerResultDto();
    }

#endregion - END OF: Scheme handlers methods

}
