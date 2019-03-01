<?php

interface IStorageManager 
{
    function InsertOrUpdateRequestResponse( RequestResponseModel $model );

    /**
     * Insert a list of RequestResponse at once
     *
     * @param RequestResponseModel[] $models
     * @return int - the id of the just inserted row or 0 if fails
     */
    function InsertOrUpdateRequestResponses( array $models ) : int;

    function DeleteRequestResponseById( int $id ) : bool;
    function DeleteRequestResponseByUrl( string $url ) : bool;

    function GetRequestResultById( int $id ) : RequestResponseModel;
    function GetRequestResultByUrl( string $url ) : RequestResponseModel;

    function GetRequestResultsByInsertDateRange( DateTime $start_date, DateTime $end_date ) : array;
    function GetRequestResultsByUpdateDateRange( DateTime $start_date, DateTime $end_date ) : array;    
}