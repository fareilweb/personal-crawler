<?php
interface IStorageManager
{
     /**
     * Get the table where the url content was stored (if exists)
     *
     * @param string $url
     * @return string - the table name if success, an empty string if url was not found, FALSE if fail
     */
    function GetContentTableNameByUrl(string $url): string;

    /**
     * Search for an url record by ID
     * @param int $id
     * @return UrlModel|bool
     */
    function GetUrlModelById(int $id);

    /**
     * Search for an url record by URL
     * @param string
     * @return UrlModel|bool
     */
    function GetUrlModelByUrl(string $url);

    /**
     * Insert Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList "lastInsertRowID" if success FALSE otherwise
     */
    function InsertUrl(UrlModel $model) : int;

    /**
     * Update Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList updated ID if success FALSE otherwise
     */
    function UpdateUrl(UrlModel $model) : int;

    /**
     * Choose if call Insert or Update Method for the gived UrlModel
     * @param UrlModel $urlModel
     * @return int|bool return id of the last operation row or FALSE if fail
     */
    function InsertOrUpdateUrl(UrlModel $urlModel) : int;

    /**
     * Get a WebPageModel from database
     * @param int $id
     * @return WebPageModel
     */
    function GetWebPageModelById(int $id) : WebPageModel;

    /**
     * Get a WebPageModel from database by URL Id
     * @param int
     * @return WebPageModel
     */
    function GetWebPageModelByUrlId(int $UrlList_url_id): WebPageModel;

    /**
     * Insert WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage "lastInsertRowID" if success FALSE otherwise
     */
    function InsertWebPage(WebPageModel $model, int $UrlList_url_id) : int;

    /**
     * Update WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage ID if success FALSE otherwise
     */
    function UpdateWebPage(WebPageModel $model, int $UrlList_url_id) : int;

    /**
     * Choose if call Insert or Update Method for the gived WebPageModel
     * @param WebPageModel
     * @return int|bool return id of the last operation row or FALSE if fail
     */
    function InsertOrUpdateWebPage(WebPageModel $webPageModel, int $UrlList_url_id): int;
}