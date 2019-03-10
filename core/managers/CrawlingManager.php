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

    /** @var string[] */
    private $jail_urlset;



    /**
     * Constructor of the class
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
     * @return void
     */
    public function Start(array $params) {
        // Store urlset from parameters
        if (!empty($params['urlset'])) {
            $this->urlset = $params['urlset'];
        }

        // Store gived url jail_urlset urls if jail mode is requested
        if($params['jail_mode'] === TRUE) {
            $this->jail_urlset = $params['urlset'];
        }

        // Store all parameters internally for future references
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
     * @param string $url
     */
    public function CrawlUrl(string $url) {
        $urlModel = $this->storageManager->GetUrlModelByUrl($url);
        if(empty($urlModel)) {
            $urlModel = new UrlModel(TablesEnum::UrlListTableName, $url);
        }

        if ($this->DoUrlNeedToBeCrawled($urlModel, $url) === FALSE) {
            return NULL; // Skip
        }

        $schemeHandlerResult = $this->ChooseAndRunSchemeHandler($url);
        $contentTypeHandlerResult = $this->ChooseAndRunContentTypeHandler($urlModel, $schemeHandlerResult);
        return $contentTypeHandlerResult;
    }



    /**
     * Try to retrieve url from database, and check if need to be crawled
     * @param type UrlModel|bool
     * @return bool - TRUE if crawling is needed FALSE otherwise
     */
    public function DoUrlNeedToBeCrawled(UrlModel $urlModel, string $url) : bool {

        // Check if the url is in current url "jail_urlset"
        if($this->params['jail_mode'] === TRUE && !empty($this->jail_urlset)) {
            $result = UrlHelper::IsUrlDomainInUrlUrlsetDomains($url, $this->jail_urlset);
            if(empty($result)) {
                return FALSE;
            }
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

        // Get data about the last update of the record. If it's an old record. Crawl!
        if (IGNORE_REFRESH_RATE === TRUE) {
            return TRUE; // Configuration say to ignore. Skip any datetime controls and Crawl!
        } else {
            $last_update_datetime = (new DateTime())->setTimestamp($urlModel->update_timestamp);
            $now_datetime = new DateTime();
            $difference = $last_update_datetime->diff($now_datetime);
            if((int)$difference->format('days') >= CONTENT_REFRESH_RATE) {
                return TRUE;
            }
        }

        return FALSE;
    }


    /**
     * Try to get content type
     * @param array
     * @return string
     */
    public function TryToGetContentType(array $schemeHandlerResult) : string {

        $schemeHandlerResult['scheme'];
        $schemeHandlerResult['info'];
        $schemeHandlerResult['content'];

        return "";
    }



#region - Content Types Handlers

    /**
     * This method try to get the content type of the request results and choose the method that can handle it
     * @return WebPageModel|bool
     **/
     private function ChooseAndRunContentTypeHandler(UrlModel $urlModel, array $schemeHandlerResult) {

        // Populate with incoming info
        $urlModel->SetDataFromArray($schemeHandlerResult['info']);

        $content_type = NULL;
        if (!empty($urlModel->curl_content_type)) {
            $content_type = $urlModel->curl_content_type;
            $urlModel->inferred_content_type = $urlModel->curl_content_type;
        } else if (!empty($urlModel->inferred_content_type)) {
            $content_type = $urlModel->inferred_content_type;
        } else if(empty($content_type)) {
            $content_type = $this->TryToGetContentType($schemeHandlerResult);
        }

        if(stripos($content_type, ContentTypes::Html) !== FALSE) {
            return $this->HandleHtmlContent($urlModel, $schemeHandlerResult['content']);
        } else {
            return $this->HandleHtmlContent($urlModel, $schemeHandlerResult['content']);
        }
    }

    /**
     * Handle Result from a request to a text/html content
     * @param UrlModel
     * @param string $content
     * @return bool
     */
    private function HandleHtmlContent(UrlModel $urlModel, $content = NULL): bool {
        // Extract data form all we have now
        $webPageModel = $this->domManager->ExtractDataToWebPageModel($content, $urlModel->id);

        // Finalize to set last data on UrlModel
        if( !empty($webPageModel)) {
            $urlModel->has_content = TRUE;
        } else {
            $urlModel->has_content = FALSE;
        }

        // Insert/Update URL
        $urlModel->content_table_name = TablesEnum::WebPageListTableName;
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

        return TRUE;
    }

#endregion - Content Types Handlers







#region - Scheme Handlers

    /**
     * This method try to get scheme of the url and choose the method that can handle
     * @param string $url
     * @return array
     */
    private function ChooseAndRunSchemeHandler(string $url): array {
        $url_scheme = UrlHelper::GetUrlScheme($url);
        switch ($url_scheme) {
            case UrlSchemes::Http:      return $this->HandleHttpSchemeUrl($url);
            case UrlSchemes::Https:     return $this->HandleHttpsSchemeUrl($url);
            case UrlSchemes::Ftp:       return $this->HandleFtpSchemeUrl($url);
            case UrlSchemes::Ftps:      return $this->HandleFtpsSchemeUrl($url);
            case UrlSchemes::Sftp:      return $this->HandleSftpSchemeUrl($url);
            case UrlSchemes::Mailto:    return $this->HandleMailtoSchemeUrl($url);
            case UrlSchemes::Tel:       return $this->HandleTelSchemeUrl($url);
            case UrlSchemes::Skype:     return $this->HandleSkypeSchemeUrl($url);
            case UrlSchemes::WhatsApp:  return $this->HandleWhatsAppSchemeUrl($url);
            default:
                return $this->HandleHttpSchemeUrl($url);
        }
    }
    /**
     * Handle Http Scheme URL
     * @param string
     * @return array
     */
    private function HandleHttpSchemeUrl(string $url): array {
        $curlRequestResults = $this->httpManager->MakeCurlHttpRequest($url, $this->params['ignore_redirect']);
        return [
            'scheme' => UrlSchemes::Http,
            'info' => $curlRequestResults['curl_getinfo_result'],
            'content' => $curlRequestResults['curl_exec_result']
        ];
    }
     /**
     * Handle Https Scheme URL
     * @param string
     * @return array
     */
    private function HandleHttpsSchemeUrl(string $url): array {
        $curlRequestResults = $this->httpManager->MakeCurlHttpRequest($url, $this->params['ignore_redirect']);
        return [
            'scheme' => UrlSchemes::Https,
            'info' => $curlRequestResults['curl_getinfo_result'],
            'content' => $curlRequestResults['curl_exec_result']
        ];
    }
     /**
     * Handle Ftp Scheme URL
     * @param string
     * @return array
     */
    private function HandleFtpSchemeUrl(string $url): array {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", [UrlSchemes::Ftp]);
        return [
            'scheme' => UrlSchemes::Ftp,
            'info' => NULL,
            'content' => NULL
        ];
    }
     /**
     * Handle Ftps Scheme URL
     * @param string
     * @return array
     */
    private function HandleFtpsSchemeUrl(string $url): array {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", [UrlSchemes::Ftps]);
        return [
            'scheme' => UrlSchemes::Ftps,
            'info' => NULL,
            'content' => NULL
        ];
    }
     /**
     * Handle Sftp Scheme URL
     * @param string
     * @return array
     */
    private function HandleSftpSchemeUrl(string $url): array {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", [UrlSchemes::Sftp]);
        return [
            'scheme' => UrlSchemes::Sftp,
            'info' => NULL,
            'content' => NULL
        ];
    }
     /**
     * Handle Mailto Scheme URL
     * @param string
     * @return array
     */
    private function HandleMailtoSchemeUrl(string $url): array {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", [UrlSchemes::Mailto]);
        return [
            'scheme' => UrlSchemes::Mailto,
            'info' => NULL,
            'content' => NULL
        ];
    }
     /**
     * Handle Tel Scheme URL
     * @param string
     * @return array
     */
    private function HandleTelSchemeUrl(string $url): array {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", [UrlSchemes::Tel]);
        return [
            'scheme' => UrlSchemes::Tel,
            'info' => NULL,
            'content' => NULL
        ];
    }
     /**
     * Handle Skype Scheme URL
     * @param string
     * @return array
     */
    private function HandleSkypeSchemeUrl(string $url): array {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", [UrlSchemes::Skype]);
        return [
            'scheme' => UrlSchemes::Skype,
            'info' => NULL,
            'content' => NULL
        ];
    }
     /**
     * Handle WhatsApp Scheme URL
     * @param string
     * @return array
     */
    private function HandleWhatsAppSchemeUrl(string $url): array {
        echo $this->localizationManager->GetString("current_url") . ": {$url}" . PHP_EOL;
        echo $this->localizationManager->GetStringWith("protocol_not_handled_yet_warning", [UrlSchemes::WhatsApp]);
        return [
            'scheme' => UrlSchemes::WhatsApp,
            'info' => NULL,
            'content' => NULL
        ];
    }

#endregion - Scheme Handlers
}
