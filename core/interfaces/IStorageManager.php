<?php
interface IStorageManager
{
     /**
     * Get the table where the url content was stored (if exists)
     *
     * @param string $url
     * @return string - the table name if success, an empty string if url was not found, FALSE if fail
     */
    public function GetContentTableNameByUrl(string $url): string;

    /**
     * Search for an url record by ID
     * @param int $id
     * @return array
     */
    public function GetUrlModelById(int $id) : UrlModel;

    /**
     * Search for an url record by URL
     * @param string $url
     * @return array
     */
    public function GetUrlModelByUrl(string $url) : UrlModel;

    /**
     * Insert Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList "lastInsertRowID" if success FALSE otherwise
     */
    public function InsertUrl(UrlModel $model) : int;

    /**
     * Update Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList updated ID if success FALSE otherwise
     */
    public function UpdateUrl(UrlModel $model) : int;

    /**
     * Get a WebPageModel from database
     * @param int $id
     * @return WebPageModel
     */
    public function GetWebPageModelById(int $id) : WebPageModel;

    /**
     * Get a WebPageModel from database by URL
     * @param int $id
     * @return WebPageModel
     */
    public function GetWebPageModelByUrl(string $url) : WebPageModel;

    /**
     * Insert WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage "lastInsertRowID" if success FALSE otherwise
     */
    public function InsertWebPage(WebPageModel $model, int $UrlList_url_id) : int;

     /**
     * Update WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage ID if success FALSE otherwise
     */
    function UpdateWebPage(WebPageModel $model, int $UrlList_url_id) : int;

}