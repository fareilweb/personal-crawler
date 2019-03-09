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
    public function Start(array $params) {
        // Merge urlset param into local urlset member
        if (isset($params['urlset']) && count($params['urlset']) > 0) {
            $this->urlset = $params['urlset'];
        }

        // Store params
        $this->params = $params;

        while (count($this->urlset) > 0) {

            $url = array_splice($this->urlset, 0, 1, NULL)[0];

            // Validate and fix url.
            // TODO - assume that url is valid ---
            //if (!UrlHelper::IsValidUrl($url)) {
            //    $url = UrlHelper::FixUrl($url);
            //}
            // TODO END --------------------------

            $this->CrawlUrl($url);
        }
    }

    /**
     * Take care of crawling for the gived url
     *
     * @param string $url
     */
    public function CrawlUrl(string $url) {

        $urlModel = $this->storageManager->GetUrlModelByUrl($url);
        if($this->DoUrlNeedToBeCrawled($urlModel) === FALSE) {
            return; // Skip
        }

        // Create instance of UrlModel if previus result was empty
        if(empty($urlModel)) {
            $urlModel = new UrlModel(DBTablesEnum::UrlListTableName, $url);
        }

        // Get data from scheme handler (Request) and populate/Update data of UrlModel
        $schemeHandlerResultDto = $this->ChooseAndRunSchemeHandler($url);

        // Populate UrlModel with curl request info
        $urlModel->SetDataFromCurlRequestInfoDto($schemeHandlerResultDto->curl_info);

        // Manage request based on content type
        $runResult = $this->ChooseAndRunContentTypeHandler($urlModel, $schemeHandlerResultDto->content);

        return $runResult;
    }


    /**
     * Try to retrieve url from database, and check if need to be crawled
     * @param type UrlModel|bool
     * @return bool - TRUE if crawling is needed FALSE otherwise
     */
    public function DoUrlNeedToBeCrawled($urlModel) : bool {
        // Just update contents every time
        if (IGNORE_REFRESH_RATE === TRUE) {
            return TRUE;
        }

        // Empty or inexistant url. Crawl!
        if (empty($urlModel)) {
            return TRUE;
        }

        // Empty update timestamp. Crawl!
        if (empty($urlModel->update_timestamp)) {
            return TRUE;
        }

        // Get data abount the last update of the record
        $last_update_datetime = (new DateTime())->setTimestamp($urlModel->update_timestamp);
        $now_datetime = new DateTime();
        $difference = $last_update_datetime->diff($now_datetime);

        // Old record. Crawl!
        if((int)$difference->format('days') >= CONTENT_REFRESH_RATE) {
            return TRUE;
        }

        return FALSE;
    }

#region - Content Types Handlers

    /**
     * This method try to get the content type of the request results and choose the method that can handle it
     * @return WebPageModel|bool
     **/
    private function ChooseAndRunContentTypeHandler(UrlModel $urlModel, $content) {
        // Choose the best handler
        if (strpos($urlModel->curl_content_type, 'text/html') !== FALSE) {
            return $this->HandleHtmlContent($urlModel, $content);
        } else {
            // Can't inference content type. Do default action.
            return $this->HandleHtmlContent($urlModel, $content);
        }
    }

    private function HandleHtmlContent(UrlModel $urlModel, $content = NULL): WebPageModel {
        // Extract data form all we have now
        $webPageModel = $this->domManager->ExtractDataToWebPageModel($content);

        // Finalize to set last data on UrlModel
        if( !empty($webPageModel)) {
            $urlModel->has_content = TRUE;
        } else {
            $urlModel->has_content = FALSE;
        }

        // Insert/Update URL
        $urlModel->content_table_name = DBTablesEnum::WebPageListTableName;
        $url_id = $this->storageManager->InsertOrUpdateUrl($urlModel);
        if ($url_id === FALSE) {
            return FALSE;
        }

        // Insert/Update WebPage
        if($urlModel->has_content === TRUE) {
            $web_page_id = $this->storageManager->InsertOrUpdateWebPage($webPageModel, $url_id);
            if ($web_page_id === FALSE) {
                return FALSE;
            }
        }

        return $webPageModel;
    }

#endregion - Content Types Handlers
#region - Scheme Handlers

    /**
     * This method try to get scheme of the url and choose the method that can handle
     *
     * @param string $url
     * @return SchemeHandlerResultDto
     */
    private function ChooseAndRunSchemeHandler(string $url): SchemeHandlerResultDto {
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
