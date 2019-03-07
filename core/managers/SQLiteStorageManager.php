<?php

class SQLiteStorageManager extends BaseManager implements IStorageManager
{
    private $sqlite;
    
    function __construct()
    {                       
        $db_file = PATH_SQLITE . DIRECTORY_SEPARATOR . SQLITE_DB_NAME;
        $this->sqlite = new SQLite3( $db_file );
    }
    
    function InsertOrUpdateRequestResponse( RequestResponseModel $model ) : int
    {
        return 0;
    }

    function InsertOrUpdateRequestResponses( array $models ) : int
    {
        return 0;
    }

    function DeleteRequestResponseById( int $id ) : bool
    {
        return false;
    }

    function DeleteRequestResponseByUrl( string $url ) : bool
    {
        return false;
    }

    function GetRequestResultById( int $id ) : RequestResponseModel
    {
        return new RequestResponseModel();
    }

    function GetRequestResultByUrl( string $url ) : RequestResponseModel
    {
        return new RequestResponseModel();
    }

    function GetRequestResultsByInsertDateRange( DateTime $start_date, DateTime $end_date ) : array
    {
        return [];
    }

    function GetRequestResultsByUpdateDateRange( DateTime $start_date, DateTime $end_date ) : array
    {
        return [];
    }
    
    function __destruct() {
        $this->sqlite->close(); // Close DB connection        
        parent::__destruct();
    }
}