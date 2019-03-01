<?php
interface IStorageManager 
{
    /**
     * Insert a RequestResponse
     *
     * @param RequestResponseModel $model
     * @return int - the id of the just inserted/updated row or 0 if fails
     */
    function InsertOrUpdateRequestResponse( RequestResponseModel $model ) : int;

    /**
     * Insert a list of RequestResponse at once
     *
     * @param RequestResponseModel[] $models
     * @return int - the id of the just inserted/updated rows or an empty array if fails
     */
    function InsertOrUpdateRequestResponses( array $models ) : int;

    /**
     * Delete a row by id
     *
     * @param integer $id
     * @return boolean - TRUE if success FALSE if fails
     */
    function DeleteRequestResponseById( int $id ) : bool;

    /**
     * Delete a row by url
     *
     * @param string $url
     * @return boolean - TRUE if success FALSE if fails
     */
    function DeleteRequestResponseByUrl( string $url ) : bool;

    /**
     * Get a row by id
     *
     * @param integer $id
     * @return RequestResponseModel
     */
    function GetRequestResultById( int $id ) : RequestResponseModel;

    /**
     * Get a row by url
     *
     * @param string $url
     * @return RequestResponseModel
     */
    function GetRequestResultByUrl( string $url ) : RequestResponseModel;

    /**
     * Get a list of all rows inserted beetwen the gived range of dates
     *
     * @param DateTime $start_date
     * @param DateTime $end_date
     * @return RequestResponseModel[]
     */
    function GetRequestResultsByInsertDateRange( DateTime $start_date, DateTime $end_date ) : array;

    /**
     * Get a list of all rows updated beetwen the gived range of dates
     *
     * @param DateTime $start_date
     * @param DateTime $end_date
     * @return RequestResponseModel[]
     */
    function GetRequestResultsByUpdateDateRange( DateTime $start_date, DateTime $end_date ) : array;    

}