<?php
interface IStorageManager
{

#region # Web Pages

    /**
     * Insert a Web Page
     *
     * @param WebPageModel
     * @return int - the id of the just inserted/updated row or 0 if fails
     */
    function InsertOrUpdateWebPage( WebPageModel $model ) : int;

    /**
     * Insert a list of Web Pages at once
     *
     * @param WebPageModel[] $models
     * @return int[] - the ids of the just inserted/updated rows or an empty array if fails
     */
    function InsertOrUpdateWebPages( array $models ) : array;

    /**
     * Get a Web Page by id
     *
     * @param integer $id
     * @return WebPageModel
     */
    function GetWebPageById( int $id ) : WebPageModel;

    /**
     * Get a Web Page by url
     *
     * @param string $url
     * @return WebPageModel
     */
    function GetWebPageByUrl( string $url ) : WebPageModel;

    /**
     * Get a list of all Web Pages inserted beetwen the gived range of dates
     *
     * @param DateTime $start_date
     * @param DateTime $end_date
     * @return WebPageModel[]
     */
    function GetWebPagesByInsertDateRange( DateTime $start_date, DateTime $end_date ) : array;

    /**
     * Get a list of all Web Pages updated beetwen the gived range of dates
     *
     * @param DateTime $start_date
     * @param DateTime $end_date
     * @return WebPageModel[]
     */
    function GetWebPagesByUpdateDateRange( DateTime $start_date, DateTime $end_date ) : array;
    
    /**
     * Delete a Web Page by id
     *
     * @param int $id
     * @return int - the id of the just deleted row or FALSE if fails
     */
    function DeleteWebPageById( int $id ) : int;

    /**
     * Delete a Web Page by url
     *
     * @param string $url
     * @return int - the id of the just deleted row or FALSE if fails
     */
    function DeleteWebPageByUrl( string $url ) : int;

#endregion # Web Pages

}