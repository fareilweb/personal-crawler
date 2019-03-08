<?php

class SQLiteStorageManager extends BaseManager implements IStorageManager
{
    private $db;

    function __construct()
    {
        $db_file = PATH_SQLITE . DIRECTORY_SEPARATOR . SQLITE_DB_NAME;
        $this->db = new SQLite3( $db_file );
    }

    /**
     * Get the table where the url content was stored (if exists)
     *
     * @param string $url
     * @return string - the table name if success, an empty string if url was not found, FALSE if fail
     */
    public function GetTableByUrl(string $url): string {
        $searched_table_name = "";
        $urllist_table_name = DBTablesEnum::UrlList;

        $query = "SELECT * FROM {$urllist_table_name} WHERE info_url = '{$url}'";
        $results = $this->db->query($query);

        if($results === FALSE) { return FALSE; } // Query Failed

        $results_count = $results->numColumns();
        if($results_count > 0) {
            $first_row = $results->fetchArray();
            $searched_table_name = $first_row['content_table_name'];
        }

        return $searched_table_name;
    }

    /**
     * Insert Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList "lastInsertRowID" if success FALSE otherwise
     */
    public function InsertUrl(WebPageModel $model) : int {
        $urllist_table_name = DBTablesEnum::UrlList;

        // Insert to UrlList
        $query_insert_urllist = <<<EOT
            INSERT INTO {$urllist_table_name} (
                info_url,
                info_content_type,
                info_http_code,
                info_redirect_count,
                info_redirect_url,
                info_primary_ip,
                info_primary_port,
                content_table_name
            ) VALUES (
                '{$model->info_url}',
                '{$model->info_content_type}',
                '{$model->info_http_code}',
                '{$model->info_redirect_count}',
                '{$model->info_redirect_url}',
                '{$model->info_primary_ip}',
                '{$model->info_primary_port}',
                '{$model->table_name}'
            );
EOT;

        $insert_urllist_id = FALSE;
        if($this->db->exec($query_insert_urllist)) {
            $insert_urllist_id = $this->db->lastInsertRowID();
        }
        return $insert_urllist_id;
    }


    /**
     * Insert WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage "lastInsertRowID" if success FALSE otherwise
     */
    public function InsertWebPage(WebPageModel $model, int $UrlList_info_url_id) : int {
        $webpages_table_name = DBTablesEnum::WebPageList;


        // Insert To WebPageList
        $query_insert_webpages = <<<EOT
            INSERT INTO {$webpages_table_name} (
                language,
                title,
                h1,
                h2,
                h3,
                h4,
                h5,
                h6,
                meta_keywords,
                meta_description,
                top_words,
                insert_date,
                update_date,
                UrlList_info_url
            ) VALUES (
                '{$model->language}',
                '{$model->title}',
                '{$model->h1}',
                '{$model->h2}',
                '{$model->h3}',
                '{$model->h4}',
                '{$model->h5}',
                '{$model->h6}',
                '{$model->meta_keywords}',
                '{$model->meta_description}',
                '{$model->top_words}',
                '{$model->insert_date->date}',
                '{$model->update_date->date}',
                '{$UrlList_info_url_id}'
            );
EOT;

        $insert_webpage_id = FALSE;
        if($this->db->exec($query_insert_webpages)) {
            $insert_webpage_id = $this->db->lastInsertRowID();
        }
        return $insert_webpage_id;
    }


    function UpdateWebPage( WebPageModel $model ) : int {

        return 0;
    }

    function InsertOrUpdateWebPages( array $models ) : array {
        return [];
    }

    function GetWebPageById( int $id ) : WebPageModel {
        return new WebPageModel(DBTablesEnum::WebPageList);
    }

    function GetWebPageByUrl( string $url ) : WebPageModel {
        return new WebPageModel(DBTablesEnum::WebPageList);
    }

    function GetWebPagesByInsertDateRange( DateTime $start_date, DateTime $end_date ) : array {
        return [new WebPageModel(DBTablesEnum::WebPageList)];
    }

    function GetWebPagesByUpdateDateRange( DateTime $start_date, DateTime $end_date ) : array {
        return [new WebPageModel(DBTablesEnum::WebPageList)];
    }

    function DeleteWebPageById( int $id ) : int {
        return 0;
    }

    function DeleteWebPageByUrl( string $url ) : int {
        return 0;
    }



    function __destruct() {
        $this->db->close(); // Close DB connection
        parent::__destruct();
    }
}