<?php
interface IStorageManager
{
    /**
     * Insert a RequestResponse
     *
     * @param BaseModel
     * @return int - the id of the just inserted/updated row or 0 if fails
     */
    function InsertOrUpdateRequestResponse( BaseModel $model ) : int;

    /**
     * Insert a list of RequestResponse at once
     *
     * @param BaseModel[] $models
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
     * @return BaseModel
     */
    function GetRequestResultById( int $id ) : BaseModel;

    /**
     * Get a row by url
     *
     * @param string $url
     * @return BaseModel
     */
    function GetRequestResultByUrl( string $url ) : BaseModel;

    /**
     * Get a list of all rows inserted beetwen the gived range of dates
     *
     * @param DateTime $start_date
     * @param DateTime $end_date
     * @return BaseModel[]
     */
    function GetRequestResultsByInsertDateRange( DateTime $start_date, DateTime $end_date ) : array;

    /**
     * Get a list of all rows updated beetwen the gived range of dates
     *
     * @param DateTime $start_date
     * @param DateTime $end_date
     * @return BaseModel[]
     */
    function GetRequestResultsByUpdateDateRange( DateTime $start_date, DateTime $end_date ) : array;

}