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
     * Constructor of the class
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

        while (count($this->urlset) > 0) {
            $url = array_splice($this->urlset, 0, 1, NULL)[0];
            if (!UrlHelper::IsValidUrl($url)) {
                $url = UrlHelper::FixUrl($url);
            }

            // Play with stored date to decide if update or not the content
            //$storedWebPageModel = $this->storageManager->GetWebPageByUrl($url);
            //if($storedWebPageModel->update_date) {
            //}

            $schemeHandlerResultDto = $this->ChooseAndRunSchemeHandler($url);

            $this->ChooseAndRunContentTypeHandler($schemeHandlerResultDto);
        }
    }

    private function Test(string $t) {
        return "OK! - " . $t;
    }

#region - Content Types Handlers

    /**
     * This method try to get the content type of the request results and choose the method that can handle it
     *
     * @param SchemeHandlerResultDto $schemeHandlerResultDto
     * @return BaseDto
     */
    private function ChooseAndRunContentTypeHandler(SchemeHandlerResultDto $schemeHandlerResultDto) {
        // Get contenty_type only by removing every other information is in the same string
        $info_content_type = $schemeHandlerResultDto->info->content_type;

        // Choose the best handler
        if (strpos($info_content_type, 'text/html') !== FALSE) {
            $this->HandleHtmlContent($schemeHandlerResultDto);
        } else {
            // Can't inference content type. Do default action
            $this->HandleHtmlContent($schemeHandlerResultDto);
        }
    }

    private function HandleHtmlContent(SchemeHandlerResultDto $schemeHandlerResultDto) : bool {
        $webPageDto = $this->domManager->ExtractDataFromSchemeHandlerResultDto($schemeHandlerResultDto);
        $webPageConverter = new WebPageConverter();
        $webPageModel = $webPageConverter->ToModel($webPageDto);

        // Insert Url
        $url_id = $this->storageManager->InsertUrl($webPageModel);
        if($url_id === FALSE) { return FALSE; }

        // Insert WebPage
        $web_page_id  = $this->storageManager->InsertWebPage($webPageModel, $url_id);
        if($web_page_id === FALSE) { return FALSE; }

        return TRUE;
    }

#endregion - Content Types Handlers




#region - Scheme Handlers

    /**
     * This method try to get scheme of the url and choose the method that can handle
     *
     * @param string $url
     * @return SchemeHandlerResultDto
     */
    private function ChooseAndRunSchemeHandler(string $url) : SchemeHandlerResultDto {
        $url_scheme = UrlHelper::GetUrlScheme($url);
        switch ($url_scheme) {
            case UrlHelper::$UrlSchemes['http']: return $this->HandleHttpSchemeUrl($url);
            case UrlHelper::$UrlSchemes['https']: return $this->HandleHttpsSchemeUrl($url);
            case UrlHelper::$UrlSchemes['ftp']: return $this->HandleFtpSchemeUrl($url);
            case UrlHelper::$UrlSchemes['ftps']: return $this->HandleFtpsSchemeUrl($url);
            case UrlHelper::$UrlSchemes['sftp']: return $this->HandleSftpSchemeUrl($url);
            case UrlHelper::$UrlSchemes['mailto']: return $this->HandleMailtoSchemeUrl($url);
            case UrlHelper::$UrlSchemes['tel']: return $this->HandleTelSchemeUrl($url);
            case UrlHelper::$UrlSchemes['skype']: return $this->HandleSkypeSchemeUrl($url);
            default:
                return $this->HandleHttpSchemeUrl($url);
        }
    }

    private function HandleHttpSchemeUrl(string $url): SchemeHandlerResultDto {
        $requestResult = $this->httpManager->MakeCurlRequest($url, $this->params['ignore_redirect']);
        $info = $requestResult['curl_getinfo_result'];
        $content = $requestResult['curl_exec_result'];
        return new SchemeHandlerResultDto($info, $content);
    }

    private function HandleHttpsSchemeUrl(string $url): SchemeHandlerResultDto {
        return $this->HandleHttpSchemeUrl($url);
    }

    private function HandleFtpSchemeUrl(string $url): SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftp"]);
        return new SchemeHandlerResultDto();
    }

    private function HandleFtpsSchemeUrl(string $url): SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["ftps"]);
        return new SchemeHandlerResultDto();
    }

    private function HandleSftpSchemeUrl(string $url): SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["sftp"]);
        return new SchemeHandlerResultDto();
    }

    private function HandleMailtoSchemeUrl(string $url): SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["mailto"]);
        return new SchemeHandlerResultDto();
    }

    private function HandleTelSchemeUrl(string $url): SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["tel"]);
        return new SchemeHandlerResultDto();
    }

    private function HandleSkypeSchemeUrl(string $url): SchemeHandlerResultDto {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", ["skype"]);
        return new SchemeHandlerResultDto();
    }

#endregion - Scheme Handlers


}
