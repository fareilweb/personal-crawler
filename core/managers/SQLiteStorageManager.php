<?php

class SQLiteStorageManager extends BaseManager implements IStorageManager
{
    private $sqlite;

    function __construct()
    {
        $db_file = PATH_SQLITE . DIRECTORY_SEPARATOR . SQLITE_DB_NAME;
        $this->sqlite = new SQLite3( $db_file );
    }

    public function GetTableByUrl(string $url): string {
        $table_name = "";
        
        return $table_name;
    }

    function InsertOrUpdateWebPage( WebPageModel $model ) : int {
        return 0;
    }

    function InsertOrUpdateWebPages( array $models ) : array {
        return [];
    }

    function GetWebPageById( int $id ) : WebPageModel {
        return new WebPageModel();
    }

    function GetWebPageByUrl( string $url ) : WebPageModel {
        return new WebPageModel();
    }

    function GetWebPagesByInsertDateRange( DateTime $start_date, DateTime $end_date ) : array {
        return [new WebPageModel()];
    }

    function GetWebPagesByUpdateDateRange( DateTime $start_date, DateTime $end_date ) : array {
        return [new WebPageModel()];
    }

    function DeleteWebPageById( int $id ) : int {
        return 0;
    }

    function DeleteWebPageByUrl( string $url ) : int {
        return 0;
    }



    function __destruct() {
        $this->sqlite->close(); // Close DB connection
        parent::__destruct();
    }
}